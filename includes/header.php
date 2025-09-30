<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Module Connexion'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <h1><a href="accueil.php">Module Connexion</a></h1>
                <ul class="nav-menu">
                    <li><a href="accueil.php">Accueil</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="profil.php">Profil</a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin.php">Administration</a></li>
                        <?php endif; ?>
                        <li><a href="?logout=1">Déconnexion</a></li>
                        <li class="user-info">Connecté: <?php echo htmlspecialchars($_SESSION['user_prenom'] ?? ''); ?></li>
                    <?php else: ?>
                        <li><a href="connexion.php">Connexion</a></li>
                        <li><a href="inscription.php">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="main-content"><?php
// Traitement de la déconnexion
if (isset($_GET['logout'])) {
    logout();
}
?>