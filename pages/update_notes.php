<?php
session_start();
include '../functions/config.php';

// Afficher le contenu des données POST pour le débogage
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// Vérifiez si les données nécessaires sont présentes
$required_fields = ['id_epreuve', 'date', 'nom_epreuve', 'id_classe', 'coefficient', 'note', 'appreciation'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    $errorMessage = "Données manquantes pour enregistrer l'épreuve : certaines données nécessaires sont manquantes.<br>";
    $errorMessage .= "Les données requises incluent : " . implode(", ", $missing_fields);
    echo $errorMessage;
    exit;
}

// Filtrer et valider les données d'entrée
$id_epreuve = filter_input(INPUT_POST, 'id_epreuve', FILTER_SANITIZE_NUMBER_INT);
$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
$nom_epreuve = filter_input(INPUT_POST, 'nom_epreuve', FILTER_SANITIZE_STRING);
$id_classe = filter_input(INPUT_POST, 'id_classe', FILTER_SANITIZE_NUMBER_INT);
$coefficient = filter_input(INPUT_POST, 'coefficient', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$note = $_POST['note'];
$appreciation = $_POST['appreciation'];

try {
    $pdo = connexionDB();
    // Démarrez une transaction pour assurer l'intégrité des données
    $pdo->beginTransaction();

    // Préparer la mise à jour des notes et appréciations
    $sql_update = "UPDATE epreuve 
                   SET Date = :date, Nom_epreuve = :nom_epreuve, id_classe = :id_classe, Coefficient = :coefficient, Note = :note, Appreciation = :appreciation 
                   WHERE ID_Epreuve = :id_epreuve AND ID_Eleve = :id_eleve";
    $stmt_update = $pdo->prepare($sql_update);

    // Mettre à jour chaque note et appréciation dans la base de données
    foreach ($note as $id_eleve => $note_value) {
        $appreciation_value = isset($appreciation[$id_eleve]) ? $appreciation[$id_eleve] : '';
        $stmt_update->execute([
            'id_epreuve' => $id_epreuve,
            'date' => $date,
            'nom_epreuve' => $nom_epreuve,
            'id_classe' => $id_classe,
            'coefficient' => $coefficient,
            'id_eleve' => $id_eleve,
            'note' => $note_value,
            'appreciation' => $appreciation_value
        ]);
    }

    // Valider la transaction
    $pdo->commit();

    // Redirection vers la page de visualisation des épreuves après sauvegarde
    echo "<script>window.close();window.opener.location.reload();</script>";
    exit;
} catch (PDOException $e) {
    // En cas d'erreur, annuler la transaction et afficher un message d'erreur
    $pdo->rollBack();
    echo "Erreur lors de la mise à jour de l'épreuve: " . $e->getMessage();
}
?>
