<?php
// Traitement de la déconnexion AVANT tout HTML, empêche d'afficher les formulaires profil et de connexion si non connecté
if (isset($_GET['logout'])) {
    logout();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Blabla</title>
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header>
        <nav class="nav-header">
            <div class="nav-container">
                <h1><a href="index.php">Module Connexion</a></h1>
                <ul class="nav-menu">
                    <li><a href="index.php">Accueil</a></li>
                    <?php if (isLoggedIn()): ?> <!-- Si connecté afficher profil et deconnexion dans le header-->
                        <li><a href="profil.php">Profil</a></li>
                        <li><a href="?logout=1">Déconnexion</a></li>
                    <?php else: ?> <!-- Sinon afficher connexion et inscription -->
                        <li><a href="connexion.php">Connexion</a></li>
                        <li><a href="inscription.php">Inscription</a></li>
                    <?php endif; ?>
                    <?php if (isAdmin()): ?> <!-- Si admin afficher lien admin -->
                        <li><a href="admin.php">Admin</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="main-content"></main>