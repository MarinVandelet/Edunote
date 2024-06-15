<?php
include '../functions/config.php';
$pdo = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];  // Assurez-vous que cette clé correspond à celle envoyée par JavaScript

    $pdo->beginTransaction();

    try {
        $sql = "DELETE FROM EPREUVE WHERE ID_epreuve = :ID_epreuve";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ID_epreuve' => $id]);  // Correction de la clé dans execute

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
