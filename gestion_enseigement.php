<?php
session_start();

include "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Gestion ressources</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Gestion des ressources</h2>
    <img src="img/menu.png" alt="EDN_Logo" class="menu_banner">
    <div class="container-gestion">
        <button class="button-creer" onclick="location.href='new_rss.php'">Créer une ressource</button>
        <button class="button-lister" onclick="location.href='list_enseigement.php'">Lister les ressources</button>
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