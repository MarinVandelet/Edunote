<?php
// Inclure le fichier de configuration de la base de données
include '../functions/config.php';

// Vérifier si l'ID de classe est passé en paramètre
if (!isset($_GET['id_classe'])) {
    http_response_code(400);
    echo "ID de classe non spécifié.";
    exit;
}

$id_classe = $_GET['id_classe'];

try {
    // Connexion à la base de données
    $pdo = connexionDB();

    // Requête pour récupérer les élèves de la classe spécifiée
    $sql = "SELECT ID_eleve, nom, prenom FROM ELEVE WHERE ID_classe = :id_classe";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_classe' => $id_classe]);
    $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les élèves au format JSON
    header('Content-Type: application/json');
    echo json_encode($eleves);

} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur lors de la récupération des élèves : " . $e->getMessage();
    exit;
}
?>
