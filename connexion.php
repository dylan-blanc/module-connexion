<?php
require_once 'config/database.php';
require_once 'includes/session.php';

$pageTitle = 'Connexion - Module Connexion';
$message = '';
$messageType = '';

// Redirection si déjà connecté
if (isLoggedIn()) {
    header('Location: accueil.php');
    exit();
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation des champs
    if (empty($login) || empty($password)) {
        $message = 'Veuillez remplir tous les champs.';
        $messageType = 'error';
    } else {
        try {
            // Recherche de l'utilisateur
            $stmt = $pdo->prepare("SELECT id, login, prenom, nom, password FROM utilisateurs WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie - création de la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_login'] = $user['login'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_nom'] = $user['nom'];

                $message = 'Connexion réussie ! Redirection en cours...';
                $messageType = 'success';
                
                // Redirection après 1 seconde
                header("refresh:1;url=accueil.php");
            } else {
                $message = 'Login ou mot de passe incorrect.';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Erreur de base de données : ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Connexion</h1>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required 
                   value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>"
                   placeholder="Votre nom d'utilisateur">
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required
                   placeholder="Votre mot de passe">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Se connecter</button>
            <a href="inscription.php" class="btn btn-success">Pas encore de compte ? S'inscrire</a>
        </div>
    </form>
    
    <div class="card" style="margin-top: 2rem;">
        <h3>Compte de test</h3>
        <p>Pour tester l'interface d'administration, vous pouvez utiliser :</p>
        <ul>
            <li><strong>Login :</strong> admin</li>
            <li><strong>Mot de passe :</strong> admin123</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>