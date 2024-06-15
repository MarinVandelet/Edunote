<?php
session_start();

include "../includes/header.php";
?>
    <title>EDN - A propos</title>
<style>
    #about tr td{
            width: 50%;
            text-align: center;
            font-size: 180%;
            padding : 0 2em;
            border : 0;
        }
        #about .imgabout{
            padding : 0;
        }
        #about td img{
            width : 100%;
            display: block;
        }
</style>

    <table id="about">
    
        <tr>
            <td>
                <h2>Edunote</h2>
                <p>Edunote permet aux étudiants et aux professeurs de consulter et gérer les notes en un même endroit<br><br>
                    L’objectif est de rendre plus simple la consultation et la gestion des notes</p>
            </td>
            <td class="imgabout">
                <img src="../img/about1.png" alt="image_colonnes">
            </td>
        </tr>
        <tr>
            <td class="imgabout">
                <img src="../img/about2.png" alt="image_bibliotheque">
            </td>
            <td>
                <h2>Fonctionnalités du site </h2>
                <p>Etudiants<br>
                    -    Consultation des notes<br><br>
                    Professeurs<br>
                    -    Gestion des notes<br><br>
                    Administrateurs<br>
                    -    Gestions des éléments de formations (ressources, professeurs, ...)<br>
                    -    Gestions des utilisateurs<br>
                    </p>
            </td>
        </tr>
        <tr>
            <td>
                <h2>Contexte</h2>
                <p>Edunote est le fruit du travail d’une SAE réalisé afin de répondre à la problématique suivante :<br><br>
                    Comment simplifier la consultation et la gestion des notes au sein de l’université Gustave Eiffel sur le site de Meaux
                    </p>
            </td>
            <td class="imgabout">
                <img src="../img/about3.png" alt="image_carnet">
            </td>
        </tr>
        <tr>
            <td class="imgabout">
                <img src="../img/about4.png" alt="image_groupe">
            </td>
            <td>
                <h2>Equipe de réalisation</h2>
                <p>Marin Vandelet<br>
                    Malick Thiams<br>
                    Ruben Pereira<br>
                    Lucas Wattin<br><br>
                    <b>Chisa</b><br><br>
                    Etudiant MMI 1ère année<br>
                    Université Gustave Eiffel Site De Meaux
                    
                    </p>
            </td>
        </tr>
    </table>

    <?php

    include "../includes/footer.php";

    ?>
    
</body>

</html>