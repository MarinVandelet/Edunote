<!DOCTYPE html>
<body>
<head>

<link rel="stylesheet" href="css/style.css">
<title>Résultat</title>
</head>
<nav>
    <img src="EDN_Logo_bleueiffel.png" alt="EDN_Logo" class="logo">
    <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="formulaire.php">Vos infos</a></li>
    <li><a href="connexion.php">connexion</a></li>
    </ul>
</nav>
        <h2>Affichage des données</h2>
        <?php
            echo "<p>Nom :" . ($_POST["nom"]) . "</p>";
            echo "<p>Prénom : " . ($_POST["prenom"]) . "</p>";
            echo "<p>Ville : " . ($_POST["ville"]) . "</p>";
            echo "<p>Code Postal : " . ($_POST["code_postal"]) . "</p>";
            echo "<p>Genre : " . (($_POST["genre"])) . "</p>";
            echo "<p>description : " . (($_POST["description"])) . "</p>";
        ?>

        <?php
        setcookie("langue", "fr");

        if (isset($_POST["nom"])) {
            $nom = $_POST["nom"];
            setcookie("nom", $nom, time() + (86400));
        }

        if (isset($_POST["prenom"])) {
            $prenom = $_POST["prenom"];
            setcookie("prenom", $prenom, time() + (86400));
        }

        if (isset($_POST["ville"])) {
            $ville = $_POST["ville"];
            setcookie("ville", $ville, time() + (86400));
        }

        if (isset($_POST["code_postal"])) {
            $code_postal = $_POST["code_postal"];
            setcookie("code_postal", $code_postal, time() + (86400));
        }

        if (isset($_POST["genre"])) {
            $genre = $_POST["genre"];
            setcookie("genre", $genre, time() + (86400));
        }

        setcookie("nom", "", time()-3600); 
        setcookie("prenom", "", time()-3600); 
        setcookie("ville", "", time()-3600); 
        setcookie("code_postal", "", time()-3600); 
        setcookie("genre", "", time()-3600);
        setcookie("langue", "", time()-3600); 
 ?>