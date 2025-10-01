<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['login'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}

require_once __DIR__ . "/includes/session.php";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=moduleconnexion", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<span style='padding-left: 45%;'>Connexion PDO réussie !</span><br><br>";
} catch (PDOException $e) {
    echo "Erreur lors de la connexion ";
}

// Exemple : récupérer tous les utilisateurs
$sql = "SELECT * FROM utilisateurs";
$stmt = $pdo->query($sql);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/infosession.php'; ?>


<h2 style="text-align:center;">Liste des utilisateurs</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Login</th>
        <th>Prénom</th>
        <th>Nom</th>
        <th>mot de pass</th>
    </tr>
    <?php if (isAdmin()): ?>
        <?php foreach ($utilisateurs as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['login']); ?></td>
                <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                <td><?php echo htmlspecialchars($user['nom']); ?></td>
                <td><?php echo htmlspecialchars($user['password']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: echo "<p style='text-align:center; color:white;'> Erreur : Accès réservé à l'administrateur.</p>" ?>
    <?php endif; ?>
</table>


<?php include 'includes/footer.php'; ?>