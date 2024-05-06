<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Formulaire</title>
</head>
<body>

<nav>
    <img src="EDN_Logo_bleueiffel.png" alt="EDN_Logo" class="logo">
    <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="formulaire.php">Vos infos</a></li>
    <li><a href="connexion.php">connexion</a></li>
    </ul>
</nav>
    <h2>Connexion</h2>
    <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Identifiant:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" class="submit-boutton" value="Se connecter">
        <?php if(isset($erreur)) { echo "<p style='color:red;'>$erreur</p>"; } ?>
    </form>
</div>
</body>
</html>


<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = "Marin";
    $mot_de_passe = "Marin123";

    if ($_POST['username'] == $identifiant && $_POST['password'] == $mot_de_passe) {
        $_SESSION['username'] = $_POST['username'];
        header("location: accueil.php");
        exit;    }
    else {
        $erreur = "Identifiant ou mot de passe incorrect.";
        echo $erreur;
    }
}
?>