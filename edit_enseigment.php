<?php
session_start();
include 'config.php';
$pdo = connexionDB();

$error = '';
$enseignement = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nom_ens = $_POST['nom_ens'];
    $semestre = $_POST['semestre'];
    $coefficient = $_POST['coefficient'];
    $competence = $_POST['competence'];

    $sql = "UPDATE ENSEIGNEMENT SET nom_Ens = :nom_ens, Semestre = :semestre, Coefficient = :coefficient WHERE ID_Ens = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom_ens' => $nom_ens,
            'semestre' => $semestre,
            'coefficient' => $coefficient,
            'id' => $id
        ]);
        echo "<script>window.close();window.opener.location.reload();</script>";
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour de l'enseignement: " . $e->getMessage();
    }
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM ENSEIGNEMENT WHERE ID_Ens = :id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $enseignement = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Erreur lors de la récupération des données de l'enseignement: " . $e->getMessage();
        }
    } else {
        $error = "ID d'enseignement manquant";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDN - Modifier Enseignement</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h2>Modifier Enseignement</h2>
    <?php if (!empty($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" id="editForm">
        <!-- Champ caché pour l'id -->
        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <!-- Champ pour le nom de l'enseignement -->
        <label for="nom_ens">Nom de l'enseignement:</label>
        <input type="text" id="nom_ens" name="nom_ens"
            value="<?php echo isset($enseignement['nom_Ens']) ? htmlspecialchars($enseignement['nom_Ens']) : ''; ?>"><br>

        <!-- Champ pour le semestre -->
        <label for="semestre">Semestre:</label>
        <input type="text" id="semestre" name="semestre"
            value="<?php echo isset($enseignement['Semestre']) ? htmlspecialchars($enseignement['Semestre']) : ''; ?>"><br>

        <!-- Champ pour le coefficient -->
        <label for="coefficient">Coefficient:</label>
        <input type="text" id="coefficient" name="coefficient"
            value="<?php echo isset($enseignement['Coefficient']) ? htmlspecialchars($enseignement['Coefficient']) : ''; ?>"><br>

        <!-- Bouton de soumission -->
        <input type="submit" value="Enregistrer">
    </form>
</body>

</html>
