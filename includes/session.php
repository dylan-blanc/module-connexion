<?php
// Gestion des sessions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['user_login']) && $_SESSION['user_login'] === 'admin';
}

// Fonction pour rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: connexion.php');
        exit();
    }
}

// Fonction pour rediriger si non admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: accueil.php');
        exit();
    }
}

// Fonction pour se déconnecter
function logout() {
    session_destroy();
    header('Location: connexion.php');
    exit();
}
?>