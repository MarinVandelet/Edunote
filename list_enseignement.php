<?php
session_start();
include 'config.php';
$pdo = connexionDB();
include 'header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Requête SQL pour récupérer les enseignements et les professeurs associés
$sql = "SELECT ENSEIGNEMENT.ID_Ens AS ID, Libelle_Ens AS nom_ens, Semestre, Coefficient, UE.Competence AS ue_competence,
               GROUP_CONCAT(CONCAT(ENSEIGNANT.nom, ' ', ENSEIGNANT.prenom) SEPARATOR ', ') AS professeurs
        FROM ENSEIGNEMENT
        JOIN UE ON ENSEIGNEMENT.ID_UE = UE.ID_UE
        LEFT JOIN ENSEIGNE ON ENSEIGNEMENT.ID_Ens = ENSEIGNE.ID_Ens
        LEFT JOIN ENSEIGNANT ON ENSEIGNE.ID_prof = ENSEIGNANT.ID_prof
        WHERE Libelle_Ens LIKE :search OR Semestre LIKE :search OR Coefficient LIKE :search OR UE.Competence LIKE :search
        GROUP BY ENSEIGNEMENT.ID_Ens, Libelle_Ens, Semestre, Coefficient, UE.Competence";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Liste des enseignements</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h2>Liste des enseignements</h2>
    <div>
        <form method="get" action="" id="filterForm" class="form-container">
            <div class="search-container2">
                <label for="search">Recherche :</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>
    </div>
    <table>
        <tr>
            <th>Nom de l'enseignement</th>
            <th>Semestre</th>
            <th>Coefficient</th>
            <th>Unité d'enseignement</th>
            <th>Professeurs</th>
            <th>Options avancées</th>
        </tr>
        <?php
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => '%' . $search . '%']);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['nom_ens']) . "</td>
                        <td>" . htmlspecialchars($row['Semestre']) . "</td>
                        <td>" . htmlspecialchars($row['Coefficient']) . "</td>
                        <td>" . htmlspecialchars($row['ue_competence']) . "</td>
                        <td>" . htmlspecialchars($row['professeurs']) . "</td>
                        <td class='options-btn'>
                            <button onclick=\"editEnseignement('" . htmlspecialchars($row['ID']) . "')\">🖊 Éditer</button>
                            <button onclick=\"deleteEnseignement('" . htmlspecialchars($row['ID']) . "')\">🗑 Supprimer</button>
                        </td>
                    </tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6' style='color:red;'>Erreur: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
    <?php

    include "footer.php";

    ?>

    <script>
        function editEnseignement(id) {
            var editUrl = 'edit_enseignement.php?id=' + id;
            var editWindow = window.open(editUrl, '_blank', 'width=600,height=400');
            editWindow.focus();
        }

        function deleteEnseignement(id) {
            if (confirm('Voulez-vous vraiment supprimer cet enseignement ?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_enseignement.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('Enseignement supprimé avec succès');
                            location.reload();
                        } else {
                            alert('Erreur: ' + response.message);
                        }
                    } else {
                        alert('Erreur: La requête n\'a pas pu être complétée.');
                    }
                };
                xhr.send('id=' + encodeURIComponent(id));
            }
        }
    </script>
</body>

</html>