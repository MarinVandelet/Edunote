<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = 'edunote.uge@gmail.com';
    $objet = $_POST['objet'];
    $message = nl2br($_POST['message']); // Convertir les retours à la ligne en balises <br>
    $author = $_POST['email'];
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'edunote.uge@gmail.com'; // Remplacez par votre adresse Gmail
        $mail->Password = 'wnwy jjrs wzol jpmh'; // Remplacez par votre mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataires
        $mail->setFrom('edunote.uge@gmail.com', 'Edunote'); // Remplacez par votre adresse Gmail
        $mail->addAddress($email);

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = $objet;
        $mail->Body = $message;
        $mail->addReplyTo($author);

        // Pièces jointes
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'][0])) {
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['file']['error'][$key] == UPLOAD_ERR_OK) {
                    $file_name = $_FILES['file']['name'][$key];
                    $mail->addAttachment($tmp_name, $file_name);
                }
            }
        }

        $mail->send();
        echo 'Votre message a été envoyé avec succès.';
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode de requête non supportée.";
}
?>
