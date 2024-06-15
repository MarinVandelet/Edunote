<?php
session_start();

include "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <main>
        <h2>Contactez-nous</h2>
        <div class="container-contact">
            <form action="process_contact.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="email">Adresse e-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="objet">Objet:</label>
                    <input type="text" id="objet" name="objet" required>
                </div>
                <div>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                <div>
                    <label for="file">Pièces jointes:</label>
                    <input type="file" id="file" name="file[]" multiple>
                </div>
                <input type="submit" value="Envoyer">
            </form>
        </div>
    </main>

    <?php

    include "footer.php";

    ?>

</body>
</html>
