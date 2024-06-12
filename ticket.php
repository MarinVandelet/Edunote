<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include "header.php";
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $ticket_id = $_POST['ticket_id'];
    $reply = $_POST['reply'];

    // Fetch the email of the ticket owner
    $stmt = $conn->prepare("SELECT email FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    // Send the reply via email
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
        $mail->Subject = 'Réponse à votre ticket';
        $mail->Body = $reply;

        $mail->send();
        echo 'La réponse a été envoyée avec succès.';
    } catch (Exception $e) {
        echo "La réponse n'a pas pu être envoyée. Erreur: {$mail->ErrorInfo}";
    }
}

// Fetch the ticket details
if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT email, subject, message FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $stmt->bind_result($email, $subject, $message);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Ticket ID non fourni.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?php echo $ticket_id; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ticket #<?php echo $ticket_id; ?></h1>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Objet:</strong> <?php echo $subject; ?></p>
    <p><strong>Message:</strong> <?php echo $message; ?></p>

    <h2>Répondre au ticket</h2>
    <form action="ticket.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
        <textarea name="reply" required></textarea><br>
        <button type="submit">Envoyer la réponse</button>
    </form>
    <style>

form {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
}

form label {
    display: block;
    margin-top: 10px;
}

form input, form textarea, form button {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
}

form button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

form button:hover {
    background-color: #45a049;
}

h1, h2 {
    text-align: center;
}

p {
    max-width: 600px;
    margin: auto;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f1f1f1;
}

textarea {
    height: 150px;
}
    </style>
    <footer>
        <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
        <p>5 boulevard Descartes <br>
            Champs-sur-Marne <br>
            77454 Marne-la-Vallée cedex 2 <br>
            Téléphone : +33 (0)1 60 95 75 00
        </p>

        <ul class="social-icons">
            <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img src="img/facebookicon.png" alt="icone facebook" class="icon"></a></li>
            <li><a href="https://twitter.com/UGustaveEiffel" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Logo_of_Twitter.svg/512px-Logo_of_Twitter.svg.png" alt="icone twitter" class="icon"></a></li>
            <li><a href="https://fr.linkedin.com/school/université-gustave-eiffel/" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/72px-LinkedIn_icon.svg.png" alt="icone linkedin" class="icon"></a></li>
            <li><a href="https://www.instagram.com/universitegustaveeiffel/" class="icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/600px-Instagram_icon.png" alt="icone instagram" class="icon"></a></li>
        </ul>
    </footer>
</body>
</html>
