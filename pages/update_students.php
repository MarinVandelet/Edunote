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
    $sql_eleves = "SELECT ID_eleve, nom, prenom FROM ELEVE WHERE ID_classe = :id_classe";
    $stmt_eleves = $pdo->prepare($sql_eleves);
    $stmt_eleves->execute(['id_classe' => $id_classe]);
    $eleves = $stmt_eleves->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour récupérer les notes et appréciations des élèves pour une épreuve spécifique
    $sql_epreuve = "SELECT ID_eleve, note, appreciation FROM EPREUVE WHERE ID_classe = :id_classe";
    $stmt_epreuve = $pdo->prepare($sql_epreuve);
    $stmt_epreuve->execute(['id_classe' => $id_classe]);
    $notes_appreciations = $stmt_epreuve->fetchAll(PDO::FETCH_ASSOC);

    // Combiner les informations des élèves avec les notes et appréciations récupérées
    foreach ($eleves as &$eleve) {
        foreach ($notes_appreciations as $na) {
            if ($na['ID_eleve'] == $eleve['ID_eleve']) {
                $eleve['note'] = $na['note'];
                $eleve['appreciation'] = $na['appreciation'];
                break;
            }
        }
    }

    // Retourner les élèves avec leurs notes et appréciations au format JSON
    header('Content-Type: application/json');
    echo json_encode($eleves);

} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur lors de la récupération des élèves : " . $e->getMessage();
    exit;
}
?>
