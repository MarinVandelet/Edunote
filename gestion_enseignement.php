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
        <button class="button-creer" onclick="location.href='new_enseigement.php'">Créer une ressource</button>
        <button class="button-lister" onclick="location.href='list_enseignement.php'">Lister les ressources</button>
    </div>
    <?php

    include "footer.php";

    ?>
</body>
</html>