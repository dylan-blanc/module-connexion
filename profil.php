<?php
require_once 'config/database.php';
require_once 'includes/session.php';

$pageTitle = 'Profil - Module Connexion';
$message = '';
$messageType = '';

// Vérification de la connexion
requireLogin();

// Récupération des informations actuelles de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT login, prenom, nom, created_at FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        logout();
    }
} catch (PDOException $e) {
    $message = 'Erreur lors de la récupération du profil : ' . $e->getMessage();
    $messageType = 'error';
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

    // Validation des champs obligatoires
    if (empty($prenom) || empty($nom)) {
        $message = 'Le prénom et le nom sont obligatoires.';
        $messageType = 'error';
    } else {
        $updatePassword = false;
        
        // Vérification si changement de mot de passe
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $message = 'Veuillez saisir votre mot de passe actuel pour le modifier.';
                $messageType = 'error';
            } elseif (strlen($newPassword) < 6) {
                $message = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
                $messageType = 'error';
            } elseif ($newPassword !== $confirmNewPassword) {
                $message = 'La confirmation du nouveau mot de passe ne correspond pas.';
                $messageType = 'error';
            } else {
                // Vérification du mot de passe actuel
                try {
                    $stmt = $pdo->prepare("SELECT password FROM utilisateurs WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $currentUser = $stmt->fetch();
                    
                    if (!password_verify($currentPassword, $currentUser['password'])) {
                        $message = 'Le mot de passe actuel est incorrect.';
                        $messageType = 'error';
                    } else {
                        $updatePassword = true;
                    }
                } catch (PDOException $e) {
                    $message = 'Erreur lors de la vérification du mot de passe : ' . $e->getMessage();
                    $messageType = 'error';
                }
            }
        }
        
        // Mise à jour si pas d'erreur
        if (empty($message)) {
            try {
                if ($updatePassword) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, password = ? WHERE id = ?");
                    $stmt->execute([$prenom, $nom, $hashedPassword, $_SESSION['user_id']]);
                } else {
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ? WHERE id = ?");
                    $stmt->execute([$prenom, $nom, $_SESSION['user_id']]);
                }
                
                // Mise à jour de la session
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['user_nom'] = $nom;
                
                // Rechargement des données utilisateur
                $user['prenom'] = $prenom;
                $user['nom'] = $nom;
                
                $message = 'Profil mis à jour avec succès !';
                $messageType = 'success';
                
            } catch (PDOException $e) {
                $message = 'Erreur lors de la mise à jour : ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Mon Profil</h1>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <h2>Informations du compte</h2>
        <p><strong>Login :</strong> <?php echo htmlspecialchars($user['login']); ?></p>
        <p><strong>Membre depuis :</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
    </div>
    
    <form method="POST" action="">
        <div class="card">
            <h2>Modifier mes informations</h2>
            
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required 
                       value="<?php echo htmlspecialchars($user['prenom']); ?>">
            </div>
            
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?php echo htmlspecialchars($user['nom']); ?>">
            </div>
        </div>
        
        <div class="card">
            <h2>Changer le mot de passe (optionnel)</h2>
            
            <div class="form-group">
                <label for="current_password">Mot de passe actuel :</label>
                <input type="password" id="current_password" name="current_password"
                       placeholder="Laisser vide pour ne pas modifier">
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" id="new_password" name="new_password" minlength="6"
                       placeholder="Au moins 6 caractères">
            </div>
            
            <div class="form-group">
                <label for="confirm_new_password">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password"
                       placeholder="Confirmer le nouveau mot de passe">
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="accueil.php" class="btn">Retour à l'accueil</a>
        </div>
    </form>
</div>

<script>
// Validation côté client pour les nouveaux mots de passe
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmNewPassword = document.getElementById('confirm_new_password');
    const currentPassword = document.getElementById('current_password');
    
    function validateNewPasswords() {
        if (newPassword.value && newPassword.value !== confirmNewPassword.value) {
            confirmNewPassword.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmNewPassword.setCustomValidity('');
        }
    }
    
    function checkCurrentPassword() {
        if (newPassword.value && !currentPassword.value) {
            currentPassword.setCustomValidity('Mot de passe actuel requis pour modifier');
        } else {
            currentPassword.setCustomValidity('');
        }
    }
    
    newPassword.addEventListener('input', function() {
        validateNewPasswords();
        checkCurrentPassword();
        if (this.value) {
            confirmNewPassword.required = true;
            currentPassword.required = true;
        } else {
            confirmNewPassword.required = false;
            currentPassword.required = false;
        }
    });
    
    confirmNewPassword.addEventListener('input', validateNewPasswords);
    currentPassword.addEventListener('input', checkCurrentPassword);
});
</script>

<?php include 'includes/footer.php'; ?>