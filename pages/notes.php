<?php
session_start();
include '../functions/config.php';
include '../includes/header.php';

// Vérifiez si l'élève est connecté
if (!isset($_SESSION['eleve'])) {
    echo "Vous devez être connecté pour voir vos épreuves.";
    exit;
}

$id_eleve = $_SESSION['eleve'];

// Vérifiez si l'ID de l'enseignement est passé en paramètre
if (!isset($_GET['id_ens'])) {
    echo "ID de l'enseignement non spécifié.";
    exit;
}

$id_ens = $_GET['id_ens'];

// Variable pour stocker le nom de l'enseignement
$Nom_ens = '';

try {
    $pdo = connexionDB();

    // Récupérer le nom de l'enseignement spécifique
    $sql_nom_ens = "SELECT Nom_ens FROM ENSEIGNEMENT WHERE ID_Ens = :id_enseignement";
    $stmt_nom_ens = $pdo->prepare($sql_nom_ens);
    $stmt_nom_ens->execute(['id_enseignement' => $id_ens]);
    $Nom_ens = $stmt_nom_ens->fetchColumn();

    // Récupérer toutes les épreuves associées à l'élève et à l'enseignement spécifique
    $sql = "
        SELECT E.ID_epreuve, E.Date, E.Nom_epreuve, E.note, E.Coefficient, E.Appreciation
        FROM EPREUVE E
        WHERE E.ID_eleve = :id_eleve AND E.ID_Ens = :id_enseignement
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_eleve' => $id_eleve, 'id_enseignement' => $id_ens]);
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
                'notes' => [],
                'Appreciation' => $epreuve['Appreciation']
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
    <title>Mes Notes - <?php echo htmlspecialchars($Nom_ens); ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h2>Mes Notes - <?php echo htmlspecialchars($Nom_ens); ?></h2>
    <div>
        <!-- Affichage des épreuves récupérées depuis la base de données sous forme de tableau -->
        <table border="1">
            <thead>
                <tr>
                    <th>Nom de l'épreuve</th>
                    <th>Date de l'épreuve</th>
                    <th>Note</th>
                    <th>Coefficient</th>
                    <th>Appréciation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($epreuveMoyennes as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['Nom_epreuve']); ?></td>
                        <td><?php echo format_date_french($data['Date']); ?></td>
                        <td><?php echo htmlspecialchars(is_numeric($data['moyenne']) ? round($data['moyenne'], 2) : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($data['Coefficient']); ?></td>
                        <td><?php echo htmlspecialchars($data['Appreciation']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include "../includes/footer.php"; ?>

    <?php
    // Fonction pour formater la date en français sans IntlDateFormatter
    function format_date_french($date) {
        // Tableau de correspondance des mois en français
        $mois = array(
            '01' => 'janvier', '02' => 'février', '03' => 'mars',
            '04' => 'avril', '05' => 'mai', '06' => 'juin',
            '07' => 'juillet', '08' => 'août', '09' => 'septembre',
            '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'
        );

        // Séparation de la date en jour, mois, année
        $date_parts = explode('-', $date);
        $jour = intval($date_parts[2]);
        $mois_num = $date_parts[1];
        $annee = $date_parts[0];

        // Retourne la date formatée en français
        return $jour . ' ' . $mois[$mois_num] . ' ' . $annee;
    }
    ?>
</body>

</html>
