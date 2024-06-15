<link rel="stylesheet" href="../css/style.css">

<?php
session_start();
include '../functions/config.php';
$pdo = connexionDB();

$error = '';
$enseignement = [];
$selectedProfessors = [];
$selectedUE = '';
$selectedType = '';
$ueOptions = '';
$typeOptions = '';
$professeurs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nom_ens = $_POST['nom_ens'];
    $semestre = $_POST['semestre'];
    $coefficient = $_POST['coefficient'];
    $id_ue = $_POST['ue'];
    $professeurs = isset($_POST['professeurs']) ? $_POST['professeurs'] : [];
    $type_ens = $_POST['type_ens']; // Ajout du type d'enseignement

    try {
        $pdo->beginTransaction();

        $sql = "UPDATE ENSEIGNEMENT SET nom_Ens = :nom_ens, Semestre = :semestre, Coefficient = :coefficient, ID_UE = :id_ue, type = :type_ens WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom_ens' => $nom_ens,
            'semestre' => $semestre,
            'coefficient' => $coefficient,
            'id_ue' => $id_ue,
            'type_ens' => $type_ens,
            'id' => $id
        ]);

        // Supprimer les anciens professeurs associés à cet enseignement
        $sql = "DELETE FROM ENSEIGNE WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Ajouter les nouveaux professeurs
        $sql = "INSERT INTO ENSEIGNE (ID_prof, ID_Ens) VALUES (:id_prof, :id_ens)";
        $stmt = $pdo->prepare($sql);
        foreach ($professeurs as $id_prof) {
            $stmt->execute(['id_prof' => $id_prof, 'id_ens' => $id]);
        }

        $pdo->commit();

        echo "<script>window.close();window.opener.location.reload();</script>";
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Récupérer les détails de l'enseignement
        $sql = "SELECT * FROM ENSEIGNEMENT WHERE ID_Ens = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $enseignement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$enseignement) {
                $error = "Enseignement non trouvé";
            } else {
                // Récupérer les professeurs associés à cet enseignement
                $sql = "SELECT ID_prof FROM ENSEIGNE WHERE ID_Ens = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $selectedProfessors = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Récupérer l'UE associée à cet enseignement
                $selectedUE = $enseignement['ID_UE'];

                // Récupérer le type d'enseignement associé à cet enseignement
                $selectedType = $enseignement['type'];
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la récupération des données de l'enseignement: " . $e->getMessage();
        }
    } else {
        $error = "ID d'enseignement manquant";
    }
}

// Query to fetch UE options from the database
try {
    $sql = "SELECT ID_UE, Competence FROM UE";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ueOptions .= '<option value="' . htmlspecialchars($row['ID_UE']) . '"';
        if ($row['ID_UE'] == $selectedUE) {
            $ueOptions .= ' selected';
        }
        $ueOptions .= '>' . htmlspecialchars($row['Competence']) . '</option>';
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
}

// Query to fetch type options from the database
try {
    $sql = "SELECT type FROM TYPE_ENSEIGNEMENT";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $typeOptions .= '<option value="' . htmlspecialchars($row['type']) . '"';
        if ($row['type'] == $selectedType) {
            $typeOptions .= ' selected';
        }
        $typeOptions .= '>' . htmlspecialchars($row['type']) . '</option>';
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
}

// Query to fetch professor options from the database
try {
    $sql = "SELECT ID_prof, nom, prenom FROM ENSEIGNANT";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $professeurs[] = $row;
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
}

?>


<body>
    <!-- Formulaire pour modifier l'enseignement -->
    <h2>Modifier Enseignement</h2>
    <div class="container-connexion">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div>
                <label for="nom_ens">Libellé de l'enseignement:</label>
                <input type="text" id="nom_ens" name="nom_ens"
                    value="<?php echo htmlspecialchars($enseignement['nom_Ens']); ?>" required>
            </div>
            <div>
                <div>
    <label for="semestre">Semestre:</label>
    <input type="number" id="semestre" name="semestre"
        value="<?php echo htmlspecialchars($enseignement['Semestre']); ?>" required>
</div>
<div>
    <label for="coefficient">Coefficient:</label>
    <input type="number" id="coefficient" name="coefficient"
        value="<?php echo htmlspecialchars($enseignement['Coefficient']); ?>" required>
</div>
<div>
    <label for="ue">Unité d'enseignement (UE):</label>
    <select name="ue" id="ue" required>
        <?php echo $ueOptions; ?>
    </select>
</div>
<div>
    <label for="type_ens">Type d'enseignement:</label>
    <select name="type_ens" id="type_ens" required>
        <?php echo $typeOptions; ?>
    </select>
</div>
<div>
    <label for="professeurs">Professeurs concernés:</label>
    <select name="professeurs[]" id="professeurs" multiple required>
        <?php foreach ($professeurs as $prof) {
            $selected = in_array($prof['ID_prof'], $selectedProfessors) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($prof['ID_prof']) . '" ' . $selected . '>' . htmlspecialchars($prof['nom']) . ' ' . htmlspecialchars($prof['prenom']) . '</option>';
        } ?>
    </select>
</div>
<br>
<input type="submit" value="Enregistrer">
</form>
</div>

</body>

</html>

