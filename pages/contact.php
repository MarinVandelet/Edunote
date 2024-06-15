<?php
session_start();
include "../includes/header.php";
?>

<body>
    <main>
        <h2>Contactez-nous</h2>
        <div class="container-contact">
            <!-- Formulaire avec un conteneur pour afficher les messages -->
            <form id="contactForm" enctype="multipart/form-data">
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
                <input type="submit" id="submitButton" value="Envoyer">
            </form>
            <!-- Conteneur pour afficher les messages -->
            <div id="messageContainer"></div>
        </div>
    </main>

    <!-- Script JavaScript pour gérer l'envoi du formulaire via AJAX -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contactForm');
        const messageContainer = document.getElementById('messageContainer');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Empêcher le formulaire de se soumettre normalement

            const formData = new FormData(form);

            // Envoyer les données du formulaire via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../functions/process_contact.php');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Afficher le message de succès
                    messageContainer.innerHTML = xhr.responseText;
                    // Désactiver le bouton d'envoi
                    document.getElementById('submitButton').disabled = true;
                    // Rafraîchir la page après 3 secondes
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                } else {
                    messageContainer.innerHTML = 'Une erreur s\'est produite lors de l\'envoi du formulaire.';
                }

            };
            xhr.onerror = function () {
                messageContainer.innerHTML = 'Une erreur s\'est produite lors de l\'envoi du formulaire.';
            };
            xhr.send(formData);
        });
    });
</script>
<?php
include "../includes/footer.php";
?>
</body>

</html>