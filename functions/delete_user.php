<?php
include 'config.php';
$pdo = connexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if ($type == 'Élève') {
        $sql = "DELETE FROM ELEVE WHERE ID_eleve = :id";
    } elseif ($type == 'Enseignant') {
        $sql = "DELETE FROM ENSEIGNANT WHERE ID_prof = :id";
    } elseif ($type == 'Administrateur') {
        $sql = "DELETE FROM ADMINISTRATEUR WHERE ID_admin = :id";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Type utilisateur inconnu']);
        exit;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}