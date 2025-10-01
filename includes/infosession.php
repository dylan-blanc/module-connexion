
<!-- Afficher les informations de session -->
<?php if (session_status() === PHP_SESSION_ACTIVE): ?>
    <div style="position: absolute; left: 10px; top: 18px; color: white;">
        <strong>ID session :</strong> <?= session_id(); ?>
        <strong>Nom de compte :</strong> <?= $_SESSION['user_login'] ?? ''; ?>
        <strong>ID utilisateur :</strong> <?= $_SESSION['user_id'] ?? ''; ?>
        <strong>Nom :</strong> <?= $_SESSION['user']['nom'] ?? ''; ?>
        <strong>Prénom :</strong> <?= $_SESSION['user']['prenom'] ?? ''; ?>

    </div>
<?php endif; ?>