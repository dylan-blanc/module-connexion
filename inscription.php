<?php

require_once __DIR__ . "/includes/session.php";



try {
    $pdo = new PDO("mysql:host=localhost;dbname=moduleconnexion", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<span style='padding-left: 45%;'>Connexion PDO réussie !</span><br><br>";
} catch (PDOException $e) {
    echo "Erreur lors de la connexion ";
}

?>

<?php include 'includes/header.php'; ?>


<?php $formType = 'inscription';
include 'includes/forminscription.php'; ?>



<?php include 'includes/footer.php'; ?>