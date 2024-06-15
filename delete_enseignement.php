<?php
include 'config.php';
$pdo = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $pdo->beginTransaction();

    try {
        $sql = "DELETE FROM ENSEIGNE WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $sql = "DELETE FROM CONTIENT WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $sql = "DELETE FROM EPREUVE WHERE ID_enseignement = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $sql = "DELETE FROM ENSEIGNEMENT WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
