<?php
session_start();

include "../includes/header.php";
?>

<body>
    <h2>Gestion des utilisateurs</h2>
    <img src="../img/menu.png" alt="EDN_Logo" class="menu_banner">
    <div class="container-gestion">
        <button class="button-creer" onclick="location.href='new_user.php'">Créer un utilisateur</button>
        <button class="button-lister" onclick="location.href='list_users.php'">Lister les utilisateurs</button>
    </div>
    <?php

    include "../includes/footer.php";

    ?>
</body>
</html>