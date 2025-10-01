<?php include 'includes/infosession.php'; ?>


<section>
    <div style="text-align: center;" class="formulaire">
        <h2> <!-- titre du formulaire changeant selon le $formtype -->
            <?php
            if ($formType === 'inscription') {
                echo "Formulaire d'inscription";
            } elseif ($formType === 'connexion') {
                echo "Formulaire de connexion";
            } elseif ($formType === 'profil') {
                echo "Modifier le profil";
            }
            ?>
        </h2>
        <form method="post" action="<?php // definir la cible du formulaire selon le $formtype
                                    if ($formType === 'inscription') {
                                        echo 'inscription.php';
                                    } elseif ($formType === 'connexion') {
                                        echo 'connexion.php';
                                    } elseif ($formType === 'profil') {
                                        echo 'profil.php';
                                    }
                                    ?>" border="1">

            <?php if ($formType !== 'profil'): ?>  <!-- Si le $formtype n'es pas profil afficher login et password ((et ne pas afficher si profil)) -->
                <label for="login">Nom de compte:</label>
                <input type="text" id="login" name="login" required><br>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required><br>
            <?php endif; ?>

            <?php if ($formType === 'inscription' || $formType === 'profil'): ?>
                <label for="prenom">Prenom:</label>
                <input type="text" id="prenom" name="prenom"
                    value="<?= htmlspecialchars($_POST['prenom'] ?? ($_SESSION['user']['prenom'] ?? '')) ?>" required><br>

                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom"
                    value="<?= htmlspecialchars($_POST['nom'] ?? ($_SESSION['user']['nom'] ?? '')) ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? ($_SESSION['user']['email'] ?? '')) ?>" required><br>
            <?php endif; ?>

            <input id="submitformulaire" type="submit" value="<?php  // definir le texte du bouton submit selon le $formtype
                                                                if ($formType === 'inscription') {
                                                                    echo "S'inscrire";
                                                                } elseif ($formType === 'connexion') {
                                                                    echo "Se connecter";
                                                                } elseif ($formType === 'profil') {
                                                                    echo "Mettre à jour";
                                                                }
                                                                ?>">
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($formType === 'inscription') {  //si formulaire d'inscription
            try {  // Traitement de l'inscription
                $login = htmlspecialchars($_POST['login']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $prenom = htmlspecialchars($_POST['prenom']);
                $nom = htmlspecialchars($_POST['nom']);
                $email = htmlspecialchars($_POST['email']);

                // Vérifier l'unicité du login
                $stmt = $pdo->prepare("SELECT login FROM utilisateurs WHERE login = ?");
                $stmt->execute([$login]);
                $unique = $stmt->fetch();

                if ($unique) {
                    echo "<p style='text-align: center; color: red;'>Ce login existe déjà !</p>";
                } else {  // Insérer le nouvel utilisateur dans la Base de donnée (PDO)
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, password, prenom, nom, email) VALUES (?, ?, ?, ?, ?)");   // Insérer le nouvel utilisateur
                    $stmt->execute([$login, $password, $prenom, $nom, $email]); 
                    echo "<p style='text-align: center; color: gold;'>Inscription réussie !</p>";
                    $_SESSION['message'] = "Inscription réussie, veuillez vous connecter.";
                    header('Location: connexion.php');  // Rediriger vers la page de connexion après inscription
                    exit();
                }
            } catch (PDOException $e) {  // Gérer les erreurs de la base de données
                echo "<p style='text-align: center; color: red;'>Erreur</p>";
            }
        } elseif ($formType === 'profil') {  //si formulaire de profil
            try {
                $prenom = htmlspecialchars($_POST['prenom']);
                $nom = htmlspecialchars($_POST['nom']);
                $email = htmlspecialchars($_POST['email']);
                $user_id = $_SESSION['user']['id'];

                $stmt = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, email = ? WHERE id = ?");  // Mettre à jour les informations de l'utilisateur
                $stmt->execute([$prenom, $nom, $email, $user_id]);

                // Mettre à jour la session
                $_SESSION['user']['prenom'] = $prenom;
                $_SESSION['user']['nom'] = $nom;
                $_SESSION['user']['email'] = $email;

                echo "<p style='text-align: center; color: gold;'>Changement réussi !</p>";
            } catch (PDOException $e) {
                echo "<p style='text-align: center; color: red;'>Erreur</p>";
            }
        } elseif ($formType === 'connexion') {  //si formulaire de connexion
            try {
                $login = htmlspecialchars($_POST['login']);
                $password = htmlspecialchars($_POST['password']);

                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
                $stmt->execute([$login]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Connexion réussie, on crée la session
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'login' => $user['login'],
                        'prenom' => $user['prenom'],
                        'nom' => $user['nom'],
                        'email' => $user['email']
                    ];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_login'] = $user['login'];
                    echo "<p style='text-align: center; color: green;'>Connexion réussie !</p>";
                    $_SESSION['messageconnexion'] = "Connexion réussie, bienvenue ! " . htmlspecialchars($user['prenom']);
                    header('Location: index.php');
                } else {
                    echo "<p style='text-align: center; color: red;'>Login ou mot de passe incorrect !</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='text-align: center; color: red;'>Erreur lors de la connexion</p>";
            }
        }
    }
    ?>
</section>