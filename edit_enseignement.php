<?php
session_start();
include 'config.php';
$pdo = connexionDB();

$error = '';
$enseignement = [];
$selectedProfessors = [];
$selectedUE = '';
$ueOptions = '';
$professeurs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $libelle_ens = $_POST['libelle_ens'];
    $semestre = $_POST['semestre'];
    $coefficient = $_POST['coefficient'];
    $id_ue = $_POST['ue'];
    $professeurs = isset($_POST['professeurs']) ? $_POST['professeurs'] : [];

    try {
        $pdo->beginTransaction();

        $sql = "UPDATE ENSEIGNEMENT SET Libelle_Ens = :libelle_ens, Semestre = :semestre, Coefficient = :coefficient, ID_UE = :id_ue WHERE ID_Ens = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'libelle_ens' => $libelle_ens,
            'semestre' => $semestre,
            'coefficient' => $coefficient,
            'id_ue' => $id_ue,
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
        $ueOptions = [];
        try {
            $sql = "SELECT ID_UE, Competence FROM UE";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ueOptions[] = $row; // Ajouter chaque option d'UE au tableau
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
        }

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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Enseignement</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .checkbox-container,
        .radio-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .checkbox-wrapper,
        .radio-wrapper {
            display: flex;
            align-items: center;
        }

        .checkbox-wrapper input[type="checkbox"],
        .radio-wrapper input[type="radio"] {
            display: none;
        }

        .checkbox-wrapper label,
        .radio-wrapper label {
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-wrapper label::before,
        .radio-wrapper label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 3px;
            background-color: #fff;
        }

        .checkbox-wrapper input[type="checkbox"]:checked+label::before {
            content: "\2713";
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            background-color: #007bff;
        }

        .radio-wrapper input[type="radio"]:checked+label::before {
            content: "\2713";
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            background-color: #007bff;
        }

        .checkbox-wrapper label:hover::before,
        .radio-wrapper label:hover::before {
            border-color: #007bff;
        }

        .popup-body {
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>

<body class="popup-body">
    <h2>Modifier Enseignement</h2>
    <div class="container-connexion">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div>
                <label for="libelle_ens">Libellé de l'enseignement:</label>
                <input type="text" id="libelle_ens" name="libelle_ens"
                    value="<?php echo htmlspecialchars($enseignement['Libelle_Ens']); ?>" required>
            </div>
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
                <div class="radio-container">
                    <?php
                    foreach ($ueOptions as $ue) {
                        $checked = $ue['ID_UE'] == $selectedUE ? 'checked' : '';
                        echo '<div class="radio-wrapper">';
                        echo '<input type="radio" id="ue_' . $ue['ID_UE'] . '" name="ue" value="' . $ue['ID_UE'] . '" ' . $checked . '>';
                        echo '<label for="ue_' . $ue['ID_UE'] . '">' . htmlspecialchars($ue['Competence']) . '</label>';
                        echo '</div>';
                    }
                    ?>
                    <br>
                </div>
            </div>
            <div>
                <label for="professeurs">Professeurs concernés:</label>
                <div class="checkbox-container">
                    <?php
                    foreach ($professeurs as $prof) {
                        $checked = in_array($prof['ID_prof'], $selectedProfessors) ? 'checked' : '';
                        echo '<div class="checkbox-wrapper">';
                        echo '<input type="checkbox" id="prof_' . $prof['ID_prof'] . '" name="professeurs[]" value="' . $prof['ID_prof'] . '" ' . $checked . '>';
                        echo '<label for="prof_' . $prof['ID_prof'] . '">' . htmlspecialchars($prof['nom']) . ' ' . htmlspecialchars($prof['prenom']) . '</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <br>
            <input type="submit" value="Enregistrer">
        </form>
    </div>
    <script>
        window.onload = function () {
            window.resizeTo(900, 550); // Définissez les dimensions souhaitées
        };
    </script>
</body>

</html>