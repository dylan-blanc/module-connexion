<?php

require_once __DIR__ . "/../librarieDB.php";



try {
$pdo = new PDO("mysql:host=localhost;dbname=jour09", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "<span style='margin-left: 45%;'>Connexion PDO réussie !</span><br><br>";
} catch (PDOException $e) {
echo "Erreur lors de la connexion ";
}

