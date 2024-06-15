<?php
include '../functions/config.php';
$pdo = connexionDB();

if (isset($_GET['id_ens'])) {
    $id_ens = $_GET['id_ens'];
    $response = [];

    try {
        $sql = "SELECT * FROM ENSEIGNEMENT WHERE ID_Ens = :id_ens";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_ens' => $id_ens]);
        $enseignement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($enseignement) {
            $response = $enseignement;

            // Récupérer les options UE
            $ueOptions = '';
            $sql = "SELECT ID_UE, Competence FROM UE";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ueOptions .= '<option value="' . htmlspecialchars($row['ID_UE']) . '"';
                if ($row['ID_UE'] == $enseignement['ID_UE']) {
                    $ueOptions .= ' selected';
                }
                $ueOptions .= '>' . htmlspecialchars($row['Competence']) . '</option>';
            }
            $response['ueOptions'] = $ueOptions;

            // Récupérer les options Type
            $typeOptions = '';
            $sql = "SELECT type FROM TYPE_ENSEIGNEMENT";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $typeOptions .= '<option value="' . htmlspecialchars($row['type']) . '"';
                if ($row['type'] == $enseignement['type']) {
                    $typeOptions .= ' selected';
                }
                $typeOptions .= '>' . htmlspecialchars($row['type']) . '</option>';
            }
            $response['typeOptions'] = $typeOptions;

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Enseignement non trouvé.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de l\'enseignement manquant.']);
}
?>
