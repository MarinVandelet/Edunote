<?php
session_start();
include '../functions/config.php';
include '../includes/header.php';

// Vérifiez si l'ID de l'enseignement est passé en paramètre
if (!isset($_GET['id_ens'])) {
    echo "ID de l'enseignement non spécifié.";
    exit;
}

$id_ens = $_GET['id_ens'];

try {
    $pdo = connexionDB();

    // Récupérer toutes les épreuves associées à l'enseignement spécifique
    $sql = "
        SELECT ID_epreuve, Date, Nom_epreuve, note, Coefficient
        FROM EPREUVE
        WHERE ID_ens = :id_ens
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_ens' => $id_ens]);
    $epreuves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculer les moyennes des notes pour chaque épreuve
    $epreuveMoyennes = [];

    foreach ($epreuves as $epreuve) {
        $id_epreuve = $epreuve['ID_epreuve'];
        if (!isset($epreuveMoyennes[$id_epreuve])) {
            $epreuveMoyennes[$id_epreuve] = [
                'ID_epreuve' => $epreuve['ID_epreuve'],
                'Date' => $epreuve['Date'],
                'Nom_epreuve' => $epreuve['Nom_epreuve'],
                'Coefficient' => $epreuve['Coefficient'],
                'notes' => []
            ];
        }
        if (!is_null($epreuve['note'])) {
            $epreuveMoyennes[$id_epreuve]['notes'][] = $epreuve['note'];
        }
    }

    foreach ($epreuveMoyennes as &$data) {
        $notes = $data['notes'];
        if (count($notes) > 0) {
            $data['moyenne'] = array_sum($notes) / count($notes);
        } else {
            $data['moyenne'] = 'N/A';
        }
    }
    unset($data); // Rompre la référence pour éviter les bugs potentiels

} catch (PDOException $e) {
    echo "Erreur lors de la récupération des épreuves: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Épreuves de l'enseignement</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Épreuves de l'enseignement</h2>
    <div>
        <!-- Affichage des épreuves récupérées depuis la base de données -->
        <table>
            <thead>
                <tr>
                    <th>Date de l'épreuve</th>
                    <th>Nom de l'épreuve</th>
                    <th>Moyenne de classe</th>
                    <th>Coefficient</th>
                    <th>Options Avancées</th>
                    <!-- D'autres colonnes si nécessaire -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($epreuveMoyennes as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['Date']); ?></td>
                        <td><?php echo htmlspecialchars($data['Nom_epreuve']); ?></td>
                        <td><?php echo htmlspecialchars(is_numeric($data['moyenne']) ? round($data['moyenne'], 2) : 'N/A'); ?>
                        </td>
                        <td><?php echo htmlspecialchars($data['Coefficient']); ?></td>
                        <td class='options-btn'>
                            <button
                                onclick="editEpreuve('<?php echo htmlspecialchars($data['ID_epreuve']); ?>', '<?php echo htmlspecialchars($data['Nom_epreuve']); ?>')">🖊
                                Éditer</button>
                            <button
                                onclick="deleteEpreuve('<?php echo htmlspecialchars($data['ID_epreuve']); ?>', '<?php echo htmlspecialchars($data['Nom_epreuve']); ?>')">🗑
                                Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <?php include "../includes/footer.php"; ?>
    <script>
        function editEpreuve(id, name) {
            var editUrl = 'edit_epreuve.php?id=' + id;
            var editWindow = window.open(editUrl, '_blank', 'width=fullscreen,height=fullscreen');
            editWindow.focus();
        }

        function deleteEpreuve(id, name) {
            if (confirm('Voulez-vous vraiment supprimer cette épreuve ?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../functions/delete_epreuve.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('Épreuve supprimée avec succès');
                            location.reload();
                        } else {
                            alert('Erreur: ' + response.message);
                        }
                    } else {
                        alert('Erreur: La requête n\'a pas pu être complétée.');
                    }
                };
                xhr.send('id=' + encodeURIComponent(id));  // Envoi de la clé 'id'
            }
        }

    </script>
</body>

</html>