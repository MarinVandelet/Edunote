<?php
session_start();

include "header.php";
include "config.php";

$pdo = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_mail = $_POST['adresse_mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing du mot de passe pour plus de sécurité

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

        echo "<p style='color:green;'>Utilisateur ajouté avec succès.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
    }
}
?>

<h2>Ajouter un utilisateur</h2>
<div class="container-connexion">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="user_type">Type d'utilisateur:</label><br>
        <select name="user_type" class="usertype" required onchange="toggleFields()">
            <option value="admin">Administrateur</option>
            <option value="eleve">Élève</option>
            <option value="prof">Enseignant</option>
        </select><br>

        <div id="ID_admin_div">
            <label for="ID_admin">ID Administrateur:</label><br>
            <input type="text" id="ID_admin" name="ID_admin"><br>
        </div>

        <div id="ID_eleve_div">
            <label for="ID_eleve">ID Élève:</label><br>
            <input type="text" id="ID_eleve" name="ID_eleve"><br>
            <label for="ID_classe">ID Classe:</label><br>
            <input type="text" id="ID_classe" name="ID_classe"><br>
        </div>

        <div id="ID_prof_div">
            <label for="ID_prof">ID Enseignant:</label><br>
            <input type="text" id="ID_prof" name="ID_prof"><br>
        </div>

        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom" required><br>
        <label for="prenom">Prénom:</label><br>
        <input type="text" id="prenom" name="prenom" required><br>
        <label for="adresse_mail">Adresse mail:</label><br>
        <input type="email" id="adresse_mail" name="adresse_mail" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" class="submit-boutton" value="Ajouter l'utilisateur">
    </form>
</div>
<script>
    function toggleFields() {
        var userType = document.querySelector('select[name="user_type"]').value;
        document.getElementById('ID_admin_div').style.display = userType === 'admin' ? 'block' : 'none';
        document.getElementById('ID_eleve_div').style.display = userType === 'eleve' ? 'block' : 'none';
        document.getElementById('ID_prof_div').style.display = userType === 'prof' ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleFields);
</script>
<footer>
    <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
    <p>5 boulevard Descartes <br>
        Champs-sur-Marne <br>
        77454 Marne-la-Vallée cedex 2 <br>
        Téléphone : +33 (0)1 60 95 75 00
    </p>
    <ul class="social-icons">
        <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img src="img/facebookicon.png" alt="icone facebook" class="icon"></a></li>
        <li><a href="https://twitter.com/UGustaveEiffel" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Logo_of_Twitter.svg/512px-Logo_of_Twitter.svg.png" alt="icone twitter" class="icon"></a></li>
        <li><a href="https://fr.linkedin.com/school/université-gustave-eiffel/" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/72px-LinkedIn_icon.svg.png" alt="icone linkedin" class="icon"></a></li>
        <li><a href="https://www.instagram.com/universitegustaveeiffel/" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/600px-Instagram_icon.png" alt="icone instagram" class="icon"></a></li>
    </ul>
</footer>
</body>
</html>
