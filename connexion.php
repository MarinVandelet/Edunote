<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>EDN - Accueil</title>
    <link rel="icon" href="img/icon.png" type="image/x-icon">
    <script src="script.js"></script>
</head>

<body>
    <nav>
        <img src="img/EDN_Logo_blanc.png" alt="EDN_Logo" class="logo">
        <ul class="nav-links">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="about.php">A propos</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <a href="connexion.php" class="connexion">Connexion</a>
        <button id="theme-toggle" class="theme-toggle" onclick="toggleTheme()">
  <div class="toggle-track">
    <div class="toggle-thumb"></div>
  </div>
  <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
  <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
</button>
    </nav>
    
    <h2>Connexion</h2>
    <div class="container-connexion">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Identifiant:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" class="submit-boutton" value="Se connecter">
        <select name="user_type" required>
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
        $stmt = $pdo->prepare('SELECT * FROM ADMINISTRATEUR WHERE nom = :username OR prenom = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['ID_admin'];
            header('Location: epreuves.php');
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
            header('Location: student.php');
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
            header('Location: professor.php');
            exit;
        }
    }

    // Si aucune correspondance n'a été trouvée
    echo $error;
}
?>


