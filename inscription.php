<?php
require_once 'config/database.php';
require_once 'includes/session.php';

$pageTitle = 'Inscription - Module Connexion';
$message = '';
$messageType = '';

// Redirection si déjà connecté
if (isLoggedIn()) {
    header('Location: accueil.php');
    exit();
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation des champs
    if (empty($login) || empty($prenom) || empty($nom) || empty($password) || empty($confirmPassword)) {
        $message = 'Tous les champs sont obligatoires.';
        $messageType = 'error';
    } elseif (strlen($login) < 3) {
        $message = 'Le login doit contenir au moins 3 caractères.';
        $messageType = 'error';
    } elseif (strlen($password) < 6) {
        $message = 'Le mot de passe doit contenir au moins 6 caractères.';
        $messageType = 'error';
    } elseif ($password !== $confirmPassword) {
        $message = 'Les mots de passe ne correspondent pas.';
        $messageType = 'error';
    } else {
        try {
            // Vérifier si le login existe déjà
            $checkStmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
            $checkStmt->execute([$login]);
            
            if ($checkStmt->fetch()) {
                $message = 'Ce login est déjà utilisé. Veuillez en choisir un autre.';
                $messageType = 'error';
            } else {
                // Insertion du nouvel utilisateur
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insertStmt = $pdo->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)");
                
                if ($insertStmt->execute([$login, $prenom, $nom, $hashedPassword])) {
                    $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                    $messageType = 'success';
                    
                    // Redirection après 2 secondes
                    header("refresh:2;url=connexion.php");
                } else {
                    $message = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                    $messageType = 'error';
                }
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
    <h1>Inscription</h1>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required minlength="3" 
                   value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>"
                   placeholder="Votre nom d'utilisateur">
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required 
                   value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"
                   placeholder="Votre prénom">
        </div>
        
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required 
                   value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                   placeholder="Votre nom">
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required minlength="6"
                   placeholder="Au moins 6 caractères">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required
                   placeholder="Confirmer votre mot de passe">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">S'inscrire</button>
            <a href="connexion.php" class="btn btn-primary">Déjà un compte ? Se connecter</a>
        </div>
    </form>
</div>

<script>
// Validation côté client pour les mots de passe
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePasswords() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
});
</script>

<?php include 'includes/footer.php'; ?>