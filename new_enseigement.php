<?php
session_start();
include 'config.php';
$pdo = connexionDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_ens = $_POST['nom_ens'];
    $semestre = $_POST['semestre'];
    $coefficient = $_POST['coefficient'];
    $id_ue = $_POST['ue'];
    $professeurs = $_POST['professeurs'];
    $type_ens = $_POST['type_ens']; // Ajout de la récupération du type d'enseignement

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO ENSEIGNEMENT (ID_Ens, nom_Ens, Semestre, Coefficient, ID_UE, type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Générer un ID_Ens unique
        $id_ens = uniqid();

        $stmt->execute([$id_ens, $nom_ens, $semestre, $coefficient, $id_ue, $type_ens]); // Ajout du type d'enseignement

        $sql = "INSERT INTO ENSEIGNE (ID_prof, ID_Ens) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        foreach ($professeurs as $id_prof) {
            $stmt->execute([$id_prof, $id_ens]);
        }

        $pdo->commit();

        header("Location: gestion_enseignement.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
    }
}

// Query to fetch UE options from the database
$ueOptions = '';
try {
    $sql = "SELECT ID_UE, Competence FROM UE";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ueOptions .= '<option value="' . htmlspecialchars($row['ID_UE']) . '">' . htmlspecialchars($row['Competence']) . '</option>';
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
}

// Query to fetch professor options from the database
$profOptions = '';
try {
    $sql = "SELECT ID_prof, nom, prenom FROM ENSEIGNANT";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $profOptions .= '<option value="' . htmlspecialchars($row['ID_prof']) . '">' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</option>';
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
}

include 'header.php';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Créer Enseignement</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Ajouter un Enseignement</h2>
    <div class="container-connexion">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="nom_ens">Nom de l'enseignement:</label>
                <input type="text" id="nom_ens" name="nom_ens" required>
            </div>
            <div>
                <label for="semestre">Semestre:</label>
                <input type="number" id="semestre" name="semestre" required>
            </div>
            <div>
                <label for="coefficient">Coefficient:</label>
                <input type="number" id="coefficient" name="coefficient" required>
            </div>
            <div>
                <label for="ue">Unité d'enseignement (UE):</label>
                <select name="ue" id="ue" required>
                    <?php echo $ueOptions; ?>
                </select>
            </div>
            <div>
                <label for="type_ens">Type d'enseignement:</label> <!-- Ajout de la sélection du type d'enseignement -->
                <select name="type_ens" id="type_ens" required>
                    <option value="SAE">SAE</option>
                    <option value="Ressource">Ressource</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div>
                <label for="professeurs">Professeurs concernés:</label>
                <select name="professeurs[]" id="professeurs" multiple required>
                    <?php echo $profOptions; ?>
                </select>
            </div>
            <br>
            <input type="submit" class="submit-boutton" value="Ajouter l'Enseignement">
        </form>
    </div>
</body>
</html>

<?php
include "footer.php";
?>
