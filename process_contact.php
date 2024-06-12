<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticketing_system";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $objet = $_POST['objet'];
    $message = $_POST['message'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM tickets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Fetch the ticket ID
        $stmt->bind_result($ticket_id);
        $stmt->fetch();
    } else {
        // Insert the ticket into the database
        $stmt = $conn->prepare("INSERT INTO tickets (email, subject, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $objet, $message);
        $stmt->execute();

        // Get the ID of the new ticket
        $ticket_id = $stmt->insert_id;
    }

    $stmt->close();

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

        // Débogage SMTP
        $mail->SMTPDebug = 0; // Désactiver le débogage
        $mail->Debugoutput = 'html';

        // Destinataires
        $mail->setFrom('edunote.uge@gmail.com', 'Edunote'); // Remplacez par votre adresse Gmail
        $mail->addAddress($email);

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Votre ticket: ' . $objet;
        $mail->Body = "Merci pour votre message. Vous pouvez suivre votre ticket ici: <a href='http://localhost/ticket.php?id=" . $ticket_id . "'>Voir votre ticket</a>";

        $mail->send();
        echo 'Votre message a été envoyé avec succès.';
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode de requête non supportée.";
}

$conn->close();
?>
