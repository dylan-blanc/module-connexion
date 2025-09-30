<?php
require_once 'config/database.php';
require_once 'includes/session.php';

$pageTitle = 'Administration - Module Connexion';
$message = '';
$messageType = '';

// Vérification de la connexion et des droits admin
requireLogin();
requireAdmin();

// Récupération de la liste des utilisateurs
try {
    $stmt = $pdo->prepare("SELECT id, login, prenom, nom, created_at FROM utilisateurs ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();
    $userCount = count($users);
} catch (PDOException $e) {
    $message = 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage();
    $messageType = 'error';
    $users = [];
    $userCount = 0;
}

// Traitement de la suppression d'utilisateur (si implémenté)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = (int)($_POST['user_id'] ?? 0);
    
    if ($userId && $userId !== $_SESSION['user_id']) {
        try {
            $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND login != 'admin'");
            $result = $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                $message = 'Utilisateur supprimé avec succès.';
                $messageType = 'success';
                
                // Recharger la liste
                $stmt = $pdo->prepare("SELECT id, login, prenom, nom, created_at FROM utilisateurs ORDER BY created_at DESC");
                $stmt->execute();
                $users = $stmt->fetchAll();
                $userCount = count($users);
            } else {
                $message = 'Impossible de supprimer cet utilisateur.';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Erreur lors de la suppression : ' . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = 'Vous ne pouvez pas supprimer votre propre compte ou le compte admin principal.';
        $messageType = 'error';
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Administration</h1>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <h2>Tableau de bord</h2>
        <div class="user-count">
            <p><strong>Nombre total d'utilisateurs :</strong> <?php echo $userCount; ?></p>
        </div>
    </div>
    
    <div class="card">
        <h2>Liste des utilisateurs</h2>
        
        <?php if (empty($users)): ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php else: ?>
            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Login</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($user['login']); ?>
                                    <?php if ($user['login'] === 'admin'): ?>
                                        <span style="color: #e74c3c; font-weight: bold;">(Admin)</span>
                                    <?php endif; ?>
                                    <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                        <span style="color: #3498db; font-weight: bold;">(Vous)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id'] && $user['login'] !== 'admin'): ?>
                                        <form method="POST" action="" style="display: inline-block;" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Supprimer
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #666; font-style: italic;">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h2>Statistiques</h2>
        <div class="features-grid">
            <div class="feature-card">
                <h3>👥 Utilisateurs</h3>
                <p><strong><?php echo $userCount; ?></strong> utilisateurs inscrits</p>
            </div>
            
            <div class="feature-card">
                <h3>📊 Activité</h3>
                <p>Page d'administration accessible</p>
                <p><small>Réservée aux administrateurs</small></p>
            </div>
            
            <div class="feature-card">
                <h3>🔒 Sécurité</h3>
                <p>Mots de passe chiffrés</p>
                <p><small>Protection via password_hash()</small></p>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 2rem;">
        <a href="accueil.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>