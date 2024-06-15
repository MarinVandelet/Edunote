<?php
session_start();

include "../includes/header.php";
?>
<body>
    <h2>Gestion des ressources</h2>
    <img src="../img/menu.png" alt="EDN_Logo" class="menu_banner">
    <div class="container-gestion">
        <button class="button-creer" onclick="location.href='new_enseigement.php'">Créer une ressource</button>
        <button class="button-lister" onclick="location.href='list_enseignement.php'">Lister les ressources</button>
    </div>
    <?php

    include "../includes/footer.php";

    ?>
</body>
</html>