<?php
session_start();
include '../functions/config.php';
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

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">

<title>EDN - Créer utilisateur</title>
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

    <?php

    include "../includes/footer.php";

    ?>
    
</body>

</html>