<?php
require_once 'config/database.php';
require_once 'includes/session.php';

$pageTitle = 'Accueil - Module Connexion';
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="welcome-section">
        <h1>Bienvenue sur le Module Connexion</h1>
        
        <?php if (isLoggedIn()): ?>
            <div class="message success">
                <p>Bonjour <?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?> !</p>
                <p>Vous êtes connecté avec succès.</p>
            </div>
        <?php else: ?>
            <p>Ce module vous permet de créer un compte, vous connecter et gérer votre profil.</p>
        <?php endif; ?>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <h3>🔐 Connexion Sécurisée</h3>
            <p>Système de connexion avec gestion de session sécurisée et mot de passe chiffré.</p>
        </div>

        <div class="feature-card">
            <h3>👤 Gestion de Profil</h3>
            <p>Créez et modifiez votre profil utilisateur facilement.</p>
        </div>

        <div class="feature-card">
            <h3>🛡️ Administration</h3>
            <p>Interface d'administration pour gérer les utilisateurs (réservée aux administrateurs).</p>
        </div>
    </div>

    <?php if (!isLoggedIn()): ?>
        <div class="card" style="text-align: center; margin-top: 2rem;">
            <h2>Commencer</h2>
            <p>Pour utiliser toutes les fonctionnalités, veuillez vous connecter ou créer un compte.</p>
            <div style="margin-top: 1rem;">
                <a href="connexion.php" class="btn btn-primary">Se connecter</a>
                <a href="inscription.php" class="btn btn-success">S'inscrire</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card" style="text-align: center; margin-top: 2rem;">
            <h2>Actions disponibles</h2>
            <div style="margin-top: 1rem;">
                <a href="profil.php" class="btn btn-primary">Voir mon profil</a>
                <?php if (isAdmin()): ?>
                    <a href="admin.php" class="btn btn-warning">Administration</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>