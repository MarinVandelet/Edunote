<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Informations de connexion Gmail
$smtpHost = 'smtp.gmail.com';
$smtpUsername = 'edunote.uge@gmail.com'; // Remplacez par votre adresse Gmail
$smtpPassword = 'wnwy jjrs wzol jpmh'; // Remplacez par votre mot de passe d'application Gmail
$smtpPort = 587;
$smtpEncryption = PHPMailer::ENCRYPTION_STARTTLS;

$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = $smtpEncryption;
    $mail->Port = $smtpPort;

    // Débogage SMTP
    // $mail->SMTPDebug = 2; // Afficher tous les messages de débogage
    // $mail->Debugoutput = 'html';

    // Destinataire (pour ce test, nous allons utiliser l'adresse d'expéditeur)
    $mail->setFrom($smtpUsername, 'Test');
    $mail->addAddress($smtpUsername);

    // Contenu de l'e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Test de Connexion SMTP';
    $mail->Body = 'Ceci est un test pour vérifier la connexion SMTP.';

    // Envoi de l'e-mail
    $mail->send();
    echo 'Le test de connexion SMTP a réussi. Le message a été envoyé.';
} catch (Exception $e) {
    echo "Le test de connexion SMTP a échoué. Erreur: {$mail->ErrorInfo}";
}
