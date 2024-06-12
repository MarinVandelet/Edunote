<?php
session_start();
include 'config.php';
$pdo = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_mail = $_POST['adresse_mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        if ($user_type == "admin") {
            $ID_user = $_POST['ID_admin'];
            $sql = "INSERT INTO ADMINISTRATEUR (ID_admin, nom, prenom, adresse_mail, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ID_user, $nom, $prenom, $adresse_mail, $password]);
        } elseif ($user_type == "eleve") {
            $ID_user = $_POST['ID_eleve'];
            $ID_classe = $_POST['ID_classe'];
            $sql = "INSERT INTO ELEVE (ID_eleve, password, nom, prenom, adresse_mail, ID_classe) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ID_user, $password, $nom, $prenom, $adresse_mail, $ID_classe]);
        } elseif ($user_type == "prof") {
            $ID_user = $_POST['ID_prof'];
            $sql = "INSERT INTO ENSEIGNANT (ID_prof, nom, prenom, adresse_mail, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ID_user, $nom, $prenom, $adresse_mail, $password]);
        }

        header("Location: gestion_utilisateurs.php");
        exit();
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
    }
}

include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Créer utilisateur</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h2>Ajouter un utilisateur</h2>
    <div class="container-connexion">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="user_type">Type d'utilisateur:</label>
                <select name="user_type" class="usertype" required onchange="toggleFields()">
                    <option value="admin">Administrateur</option>
                    <option value="eleve">Élève</option>
                    <option value="prof">Enseignant</option>
                </select>
            </div>

            <div id="ID_admin_div">
                <label for="ID_admin">ID Administrateur:</label>
                <input type="text" id="ID_admin" name="ID_admin" oninput="this.value = this.value.replace(/[^0-9]/g, '')"maxlength="6">
            </div>

            <div id="ID_eleve_div">
                <label for="ID_eleve">ID Élève:</label>
                <input type="text" id="ID_eleve" name="ID_eleve" oninput="this.value = this.value.replace(/[^0-9]/g, '')"maxlength="6">
                <label for="ID_classe">ID Classe:</label>
                <input type="text" id="ID_classe" name="ID_classe" oninput="this.value = this.value.replace(/[^0-9]/g, '')"maxlength="6">
            </div>

            <div id="ID_prof_div">
                <label for="ID_prof">ID Enseignant:</label>
                <input type="text" id="ID_prof" name="ID_prof" oninput="this.value = this.value.replace(/[^0-9]/g, '')"maxlength="6">

            </div>


            <div>
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div>
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div>
                <label for="adresse_mail">Adresse mail:</label>
                <input type="email" id="adresse_mail" name="adresse_mail" required>
            </div>

            <div>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <input type="submit" class="submit-boutton" value="Ajouter l'utilisateur">
        </form>






    </div>
    <script>
        function toggleFields() {
            var userType = document.querySelector("select[name='user_type']").value;
            document.getElementById("ID_admin_div").style.display = userType === "admin" ? "block" : "none";
            document.getElementById("ID_eleve_div").style.display = userType === "eleve" ? "block" : "none";
            document.getElementById("ID_prof_div").style.display = userType === "prof" ? "block" : "none";
        }
        document.addEventListener("DOMContentLoaded", toggleFields);
    </script>
    <footer>
        <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
        <p>5 boulevard Descartes <br>
            Champs-sur-Marne <br>
            77454 Marne-la-Vallée cedex 2 <br>
            Téléphone : +33 (0)1 60 95 75 00
        </p>

        <ul class="social-icons">
            <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img src="img/facebookicon.png"
                        alt="icone facebook" class="icon"></a></li>
            <li><a href="https://twitter.com/UGustaveEiffel" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Logo_of_Twitter.svg/512px-Logo_of_Twitter.svg.png"
                        alt="icone twitter" class="icon"></a></li>
            <li><a href="https://fr.linkedin.com/school/université-gustave-eiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/72px-LinkedIn_icon.svg.png"
                        alt="icone linkedin" class="icon"></a></li>
            <li><a href="https://www.instagram.com/universitegustaveeiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/600px-Instagram_icon.png"
                        alt="icone instagram" class="icon"></a></li>
        </ul>
    </footer>
</body>

</html>