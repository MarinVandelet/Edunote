<?php
session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/icon.png" type="image/x-icon">
    <script src="script.js"></script>
</head>

<body>
    <header>
        <nav>
            <img src="img/EDN_Logo_blanc.png" alt="EDN_Logo" class="logo">
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="pages/about.php">A propos</a></li>
                <li><a href="pages/contact.php">Contact</a></li>
                <?php
                if (isset($_SESSION['etat'])){
                    $stat = $_SESSION['etat'];
                    switch ($stat){
                        case 'admin' :
                            echo "<li><a href=\"pages/gestion_enseignement.php\">Gestion Ressources</a></li>";
                            echo "<li><a href=\"pages/gestion_utilisateurs.php\">Gestion Utilisateurs</a></li>";
                            break;
                        case 'eleve':
                            echo "<li><a href=\"pages/matiere_eleve.php\">Notes</a></li>";
                            break;
                        case 'prof':
                           echo "<li><a href=\"pages/matiere.php\">Mes matières</a></li>";
                            break;
                        default :
                            break;
                    }
                }
         ?>
            </ul>
            <?php
                if (!isset($_SESSION['etat'])){
                    echo "<a href=\"pages/connexion.php\" class=\"connexion\">Connexion</a>";
                } else {
                    echo "<a href=\"functions/deconnexion.php\" class=\"connexion\">Deconnexion</a>";
                }
            ?>
            <button id="theme-toggle" class="theme-toggle" onclick="toggleTheme()">
            <div class="toggle-track">
            <div class="toggle-thumb"></div>
            </div>
            <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
        </nav>
    </header>
<title>EDN - Accueil</title>
        
        <img src="img/imgaccueilpre.png" alt="imgaccueil" class="imghead">
        
        <div id="contenu_accueil">
            <section class="element_accueil">
                <img src="img/etudiantaccueil.png" alt="img_etudiantaccueil" class="img_accueil">
                <div>
                    <h3>Etudiants</h3>
                    <p>Consultez toutes vos notes facilement et au même endroit</p>
                </div>
            </section>
            <section class="element_accueil">
                <img src="img/enseignantaccueil.png" alt="img_etudiantaccueil" class="img_accueil">
                <div>
                    <h3>Professeurs</h3>
                    <p>Gérez les notes aisément grâce à un outil intuitif</p>
                </div>
            </section>
        </div>

    <?php

    include "includes/footer.php";

    ?>

</body>

</html>