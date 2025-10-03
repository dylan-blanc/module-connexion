<?php
// Gestion des sessions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin()
{
    return isset($_SESSION['user_login']) && $_SESSION['user_login'] === 'admin';
}



// Fonction pour se deconnecter, supprimer la session/cookie et rediriger vers le formulaire de connexion
function logout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location: connexion.php');
    exit;
}

$user_password = isset($_SESSION['user_password']) ? $_SESSION['user_password'] : null;
