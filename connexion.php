<?php
session_start();

include "header.php";
?>
    
    <h2>Connexion</h2>
    <div class="container-connexion">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Identifiant:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" class="submit-boutton" value="Se connecter">
        <select name="user_type" class="usertype" required>
            <option value="admin">Administrateur</option>
            <option value="eleve">Élève</option>
            <option value="prof">Enseignant</option>
        </select>    
        <?php if(isset($erreur)) { echo "<p style='color:red;'>$erreur</p>"; } ?>
    </form>
</div>
    <footer>
        <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
        <p>5 boulevard Descartes <br>
            Champs-sur-Marne <br>
            77454 Marne-la-Vallée cedex 2 <br>
            Téléphone : +33 (0)1 60 95 75 00
        </p>

        <ul class="social-icons">
            <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img
                        src="img/facebookicon.png"
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
<?php
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
            header('Location: accueil.php');
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
            header('Location: accueil.php');
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
            header('Location: accueil.php');
            exit;
        }
    }

    // Si aucune correspondance n'a été trouvée
    echo $error;
}
?>


