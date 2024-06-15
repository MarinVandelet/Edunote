<?php
session_start();
include 'config.php';
$pdo = connexionDB();
include 'header.php';

$typesUtilisateurs = ['Administrateur', 'Élève', 'Enseignant'];

if (isset($_GET['type']) && in_array($_GET['type'], $typesUtilisateurs)) {
    $typeSelectionne = $_GET['type'];
} else {
    $typeSelectionne = null;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($typeSelectionne && $typeSelectionne !== 'Tous') {
    if ($typeSelectionne === 'Élève') {
        $sql = "SELECT 'Élève' AS type, nom, prenom, adresse_mail, ID_eleve AS ID FROM ELEVE 
                WHERE nom LIKE :search OR prenom LIKE :search OR ID_eleve LIKE :search";
    } elseif ($typeSelectionne === 'Enseignant') {
        $sql = "SELECT 'Enseignant' AS type, nom, prenom, adresse_mail, ID_prof AS ID FROM ENSEIGNANT 
                WHERE nom LIKE :search OR prenom LIKE :search OR ID_prof LIKE :search";
    } elseif ($typeSelectionne === 'Administrateur') {
        $sql = "SELECT 'Administrateur' AS type, nom, prenom, adresse_mail, ID_admin AS ID FROM ADMINISTRATEUR 
                WHERE nom LIKE :search OR prenom LIKE :search OR ID_admin LIKE :search";
    }
} else {
    $sql = "SELECT 'Administrateur' AS type, nom, prenom, adresse_mail, ID_admin AS ID FROM ADMINISTRATEUR 
            WHERE nom LIKE :search OR prenom LIKE :search OR ID_admin LIKE :search 
            UNION ALL 
            SELECT 'Élève' AS type, nom, prenom, adresse_mail, ID_eleve AS ID FROM ELEVE 
            WHERE nom LIKE :search OR prenom LIKE :search OR ID_eleve LIKE :search 
            UNION ALL 
            SELECT 'Enseignant' AS type, nom, prenom, adresse_mail, ID_prof AS ID FROM ENSEIGNANT 
            WHERE nom LIKE :search OR prenom LIKE :search OR ID_prof LIKE :search";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Liste des utilisateurs</title>
    <link rel="stylesheet" href="css/styles.css">

</head>

<body>
    <h2>Liste des utilisateurs</h2>
    <div>
        <form method="get" action="" id="filterForm" class="form-container">
            <label for="type">Filtrer par type :</label>
            <select name="type" id="type" onchange="document.getElementById('filterForm').submit()">
                <option value="" <?php if (!$typeSelectionne)
                    echo 'selected'; ?>>Tous</option>
                <?php foreach ($typesUtilisateurs as $type): ?>
                    <option value="<?php echo $type; ?>" <?php if ($typeSelectionne === $type)
                           echo 'selected'; ?>>
                        <?php echo $type; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="search-container">
                <label for="search">Recherche :</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>
    </div>
    <table>
        <tr>
            <th>Type</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Adresse mail</th>
            <th>ID</th>
            <th>Options avancées</th>
        </tr>
        <?php
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => '%' . $search . '%']);
            while ($row = $stmt->fetch()) {
                echo "<tr><td>" . htmlspecialchars($row['type']) . "</td><td>" . htmlspecialchars($row['nom']) . "</td><td>" . htmlspecialchars($row['prenom']) . "</td><td>" . htmlspecialchars($row['adresse_mail']) . "</td><td>" . htmlspecialchars($row['ID']) . "</td><td class='options-btn'><button onclick=\"editUser('" . htmlspecialchars($row['ID']) . "', '" . htmlspecialchars($row['type']) . "')\">🖊 Éditer</button><button onclick=\"deleteUser('" . htmlspecialchars($row['ID']) . "', '" . htmlspecialchars($row['type']) . "')\">🗑 Supprimer</button></td></tr>";
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
        function editUser(id, type) {
    var editUrl = 'edit_user.php?id=' + id + '&type=' + type;
    var editWindow = window.open(editUrl, '_blank', 'width=600,height=400');
    editWindow.focus();
}


        function deleteUser(id, type) {
            if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_user.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('Utilisateur supprimé avec succès');
                            location.reload();
                        } else {
                            alert('Erreur: ' + response.message);
                        }
                    } else {
                        alert('Erreur: La requête n\'a pas pu être complétée.');
                    }
                };
                xhr.send('id=' + encodeURIComponent(id) + '&type=' + encodeURIComponent(type));
            }
        }
    </script>
</body>

</html>