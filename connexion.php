<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connexionDB();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];
    $error = "Nom d'utilisateur ou mot de passe incorrect";

    if ($userType == 'admin') {
        // Vérification pour l'Administrateur
        $stmt = $pdo->prepare('SELECT * FROM ADMINISTRATEUR WHERE adresse_mail = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['ID_admin'];
            $_SESSION['etat'] = $userType;
            header('Location: index.php');
            exit;
        }
    } elseif ($userType == 'eleve') {
        // Vérification pour l'Elève
        $stmt = $pdo->prepare('SELECT * FROM ELEVE WHERE adresse_mail = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $etud = $stmt->fetch();

        if ($etud && password_verify($password, $etud['password'])) {
            $_SESSION['etudiant'] = $etud['ID_eleve'];
            $_SESSION['etat'] = $userType;
            header('Location: index.php');
            exit;
        }
    } elseif ($userType == 'prof') {
        // Vérification pour l'Enseignant
        $stmt = $pdo->prepare('SELECT * FROM ENSEIGNANT WHERE adresse_mail = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $prof = $stmt->fetch();

        if ($prof && password_verify($password, $prof['password'])) {
            $_SESSION['prof'] = $prof['ID_prof'];
            $_SESSION['etat'] = $userType;
            header('Location: index.php');
            exit;
        }
    }

    // Si aucune correspondance n'a été trouvée
    $erreur = $error;
}
?>
<title>EDN - Connexion</title>
<link rel="icon" href="img/icon.png" type="image/x-icon">
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "header.php"; ?>

    <h2>Connexion</h2>
    <div class="container-connexion">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="login-form">
            <label for="username">Identifiant:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Mot de passe:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="hidden" id="user_type" name="user_type" value="">

            <div class="user-type-buttons">
                <input type="radio" id="user_eleve" name="user_type_radio" value="eleve" style="display:none;">
                <label for="user_eleve" class="user-button" data-user-type="eleve">
                    <img src="img/student.png" alt="Élève Image">
                    <span>Élève</span>
                </label>
                <input type="radio" id="user_prof" name="user_type_radio" value="prof" style="display:none;">
                <label for="user_prof" class="user-button" data-user-type="prof">
                    <img src="img/teacher.png" alt="Enseignant Image">
                    <span>Enseignant</span>
                </label>
                <input type="radio" id="user_admin" name="user_type_radio" value="admin" style="display:none;">
                <label for="user_admin" class="user-button" data-user-type="admin">
                    <img src="img/admin.png" alt="Admin Image">
                    <span>Administrateur</span>
                </label>
            </div>

            <input type="submit" class="submit-boutton" value="Se connecter">
            <?php if (isset($erreur)) {
                echo "<p style='color:red;'>$erreur</p>";
            } ?>
        </form>
    </div>

    <script>
        document.querySelectorAll('.user-button').forEach(label => {
            label.addEventListener('click', function () {
                const userType = this.getAttribute('data-user-type');
                document.getElementById('user_type').value = userType;
                document.querySelectorAll('.user-button').forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.getElementById('login-form').addEventListener('submit', function (event) {
            if (!document.getElementById('user_type').value) {
                event.preventDefault();
                alert('Veuillez sélectionner un type d\'utilisateur.');
            }
        });
    </script>

    <?php

    include "footer.php";

    ?>

</body>

</html>