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

if ($typeSelectionne && $typeSelectionne !== 'Tous') {
    if ($typeSelectionne === 'Élève') {
        $sql = "SELECT 'Élève' AS type, nom, prenom, adresse_mail FROM ELEVE";
    } elseif ($typeSelectionne === 'Enseignant') {
        $sql = "SELECT 'Enseignant' AS type, nom, prenom, adresse_mail FROM ENSEIGNANT";
    } elseif ($typeSelectionne === 'Administrateur') {
        $sql = "SELECT 'Administrateur' AS type, nom, prenom, adresse_mail FROM ADMINISTRATEUR";
    }
} else {
    $sql = "SELECT 'Administrateur' AS type, nom, prenom, adresse_mail FROM ADMINISTRATEUR 
            UNION ALL 
            SELECT 'Élève' AS type, nom, prenom, adresse_mail FROM ELEVE 
            UNION ALL 
            SELECT 'Enseignant' AS type, nom, prenom, adresse_mail FROM ENSEIGNANT";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Liste des utilisateurs</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #0F1383;
        }

        th,
        td {
            border: 1px solid #0F1383;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #0F1383;
            color: white;
        }
    </style>
</head>

<body>
    <h2>Liste des utilisateurs</h2>
    <div>
        <form method="get" action="" id="filterForm">
            <label for="type">Filtrer par type :</label>
            <select name="type" id="type" onchange="document.getElementById('filterForm').submit()">
                <option value="" <?php if (!$typeSelectionne) echo 'selected'; ?>>Tous</option>
                <?php foreach ($typesUtilisateurs as $type): ?>
                    <option value="<?php echo $type; ?>" <?php if ($typeSelectionne === $type) echo 'selected'; ?>>
                        <?php echo $type; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <table>
        <tr>
            <th>Type</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Adresse mail</th>
        </tr>
        <?php
        try {
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch()) {
                echo "<tr><td>" . htmlspecialchars($row['type']) . "</td><td>" . htmlspecialchars($row['nom']) . "</td><td>" . htmlspecialchars($row['prenom']) . "</td><td>" . htmlspecialchars($row['adresse_mail']) . "</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='4' style='color:red;'>Erreur: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
    <footer>
        <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
        <p>5 boulevard Descartes <br>
            Champs-sur-Marne <br>
            77454 Marne-la-Vallée cedex 2 <br>
            Téléphone : +33 (0)1 60 95 75 00
        </p>

        <ul class="social-icons">
            <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img src="img/facebookicon.png"
                        alt="icone facebook" class="icon"></a></li>
            <li><a href="https://twitter.com/UGustaveEiffel" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Logo_of_Twitter.svg/512px-Logo_of_Twitter.svg.png"
                        alt="icone twitter" class="icon"></a></li>
            <li><a href="https://fr.linkedin.com/school/université-gustave-eiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/72px-LinkedIn_icon.svg.png"
                        alt="icone linkedin" class="icon"></a></li>
            <li><a href="https://www.instagram.com/universitegustaveeiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/600px-Instagram_icon.png"
                        alt="icone instagram" class="icon"></a></li>
        </ul>
    </footer>
</body>

</html>
