<?php
session_start();
include '../functions/config.php';
$pdo = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ens = $_POST['id_ens'];
    $nom_ens = $_POST['nom_ens'];
    $semestre = $_POST['semestre'];
    $coefficient = $_POST['coefficient'];
    $ue = $_POST['ue'];
    $type_ens = $_POST['type_ens'];

    try {
        $sql = "UPDATE ENSEIGNEMENT 
                SET nom_Ens = :nom_ens, Semestre = :semestre, Coefficient = :coefficient, ID_UE = :ue, type = :type_ens 
                WHERE ID_Ens = :id_ens";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom_ens' => $nom_ens,
            'semestre' => $semestre,
            'coefficient' => $coefficient,
            'ue' => $ue,
            'type_ens' => $type_ens,
            'id_ens' => $id_ens
        ]);

        header("Location: matiere.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>
    