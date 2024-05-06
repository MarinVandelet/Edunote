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
    
    <div class="container">
    <h2>Formulaire</h2>
    <form action="affichage.php" method="post">
        <label for="nom">Nom :</label><br>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="prenom">Prénom :</label><br>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <label for="ville">Ville :</label><br>
        <input type="text" id="ville" name="ville" required><br><br>

        <label for="code_postal">Code Postal :</label><br>
        <input type="text" id="code_postal" name="code_postal"><br><br>

        <label>Genre :</label><br>
        <input type="radio" value="homme" name="genre">
        <label for="homme">Homme</label>
        <input type="radio" value="femme" name="genre">
        <label for="femme">Femme</label><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="5" cols="50"></textarea><br><br>

        <input type="submit" value="Envoyer">
    </div>
    
    </form>
</body>
</html>
"