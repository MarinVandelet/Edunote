<?php
session_start();
include '../functions/config.php';

// Vérifiez si les données nécessaires sont présentes
if (!isset($_POST['id_ens'], $_POST['date'], $_POST['nom_epreuve'], $_POST['id_classe'],  $_POST['coefficient'], $_POST['note'], $_POST['appreciation'])) {
    $errorMessage = "Données manquantes pour enregistrer l'épreuve : certaines données nécessaires sont manquantes.<br>";
    $errorMessage .= "Les données requises incluent : ";
    $errorMessage .= isset($_POST['id_ens']) ? "" : "ID Enseignement, ";
    $errorMessage .= isset($_POST['date']) ? "" : "Date, ";
    $errorMessage .= isset($_POST['nom_epreuve']) ? "" : "Nom de l'épreuve, ";
    $errorMessage .= isset($_POST['id_classe']) ? "" : "id_classe, ";
    $errorMessage .= isset($_POST['coefficient']) ? "" : "Coefficient, ";
    $errorMessage .= isset($_POST['note']) ? "" : "note, ";
    $errorMessage .= isset($_POST['appreciation']) ? "" : "Appréciations, ";

    // Retirez la dernière virgule et l'espace
    $errorMessage = rtrim($errorMessage, ", ");

    echo $errorMessage;
    exit;
}

$id_ens = $_POST['id_ens'];
$date = $_POST['date'];
$nom_epreuve = $_POST['nom_epreuve'];
$id_classe = $_POST['id_classe'];
$coefficient = $_POST['coefficient'];
$note = $_POST['note'];
$appreciation = $_POST['appreciation'];

try {
    $pdo = connexionDB();
    
    // Démarrez une transaction pour assurer l'intégrité des données
    $pdo->beginTransaction();

    // Récupérer le plus grand ID actuel dans la table EPREUVE
    $sql_max_id = "SELECT MAX(ID_Epreuve) AS max_id FROM EPREUVE";
    $stmt_max_id = $pdo->prepare($sql_max_id);
    $stmt_max_id->execute();
    $result = $stmt_max_id->fetch(PDO::FETCH_ASSOC);
    $max_id = $result['max_id'] !== null ? $result['max_id'] : 0;
    $new_id_epreuve = $max_id + 1;

    // Insérer l'épreuve dans la table EPREUVE avec le nouvel ID
    // $sql_epreuve = "INSERT INTO EPREUVE (ID_Epreuve, Date, Nom_epreuve, id_classe, Coefficient, ID_Ens) 
    //                 VALUES (:id_epreuve, :date, :nom_epreuve, :id_classe, :coefficient, :id_ens)";
    // $stmt_epreuve = $pdo->prepare($sql_epreuve);
    // $stmt_epreuve->execute([
    //     'id_epreuve' => $new_id_epreuve,
    //     'date' => $date,
    //     'nom_epreuve' => $nom_epreuve,
    //     'id_classe' => $id_classe,
    //     'coefficient' => $coefficient,
    //     'id_ens' => $id_ens
    // ]);

    // Préparer l'insertion des note et appréciations
    $sql_note = "INSERT INTO epreuve (ID_Epreuve,Date, Nom_epreuve, id_classe, Coefficient, ID_Ens, ID_Eleve, Note, Appreciation) VALUES (:id_epreuve, :date, :nom_epreuve, :id_classe, :coefficient, :id_ens, :id_eleve, :note, :appreciation)";
    $stmt_note = $pdo->prepare($sql_note);

    // Insérer chaque note et appréciation dans la base de données
    foreach ($note as $id_eleve => $note) {
        $appreciation_value = isset($appreciation[$id_eleve]) ? $appreciation[$id_eleve] : '';
        $stmt_note->execute([
            'id_epreuve' => $new_id_epreuve,
            'date' => $date,
            'nom_epreuve' => $nom_epreuve,
            'id_classe' => $id_classe,
            'coefficient' => $coefficient,
            'id_ens' => $id_ens,
            'id_eleve' => $id_eleve,
            'note' => $note,
            'appreciation' => $appreciation_value
        ]);
    }

    // Valider la transaction
    $pdo->commit();

    // Redirection vers la page de visualisation des épreuves après sauvegarde
    header("Location: view_epreuves.php?id_ens=$id_ens");
    exit;
} catch (PDOException $e) {
    // En cas d'erreur, annuler la transaction et afficher un message d'erreur
    $pdo->rollBack();
    echo "Erreur lors de l'enregistrement de l'épreuve: " . $e->getMessage();
}
?>
