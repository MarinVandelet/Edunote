<?php
session_start();

include "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Gestion utilisateurs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Gestion des utilisateurs</h2>
    <img src="img/menu.png" alt="EDN_Logo" class="menu_banner">
    <div class="container-gestion">
        <button class="button-creer" onclick="location.href='new_user.php'">Créer un utilisateur</button>
        <button class="button-lister" onclick="location.href='list_users.php'">Lister les utilisateurs</button>
    </div>
    <?php

    include "footer.php";

    ?>
</body>
</html>