<?php
session_start();
include '../functions/config.php';
include '../includes/header.php';

$pdo = connexionDB();

// Vérifiez si l'ID de l'élève est défini dans la session
if (!isset($_SESSION['eleve'])) {
    echo "ID de l'élève non défini.";
    exit;
}

$id_eleve = $_SESSION['eleve'];

$error = '';
$enseignements = [];

try {
    // Récupérer les enseignements liés à cet élève
    $sql = "SELECT DISTINCT E.ID_Ens, E.nom_Ens, E.Semestre, E.Coefficient, U.Competence, T.type
            FROM ENSEIGNEMENT E
            JOIN EPREUVE P ON E.ID_Ens = P.ID_Ens
            JOIN UE U ON E.ID_UE = U.ID_UE
            JOIN TYPE_ENSEIGNEMENT T ON E.type = T.type
            WHERE P.ID_eleve = :id_eleve";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_eleve' => $id_eleve]);
    $enseignements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des enseignements: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mes Matières</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .matiere {
            position: relative;
            width: 200px;
            height: 200px;
            background-size: cover;
            background-position: center;
            border: 2px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .matiere-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
        }

        .buttons {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            flex-direction: column;
            gap: 10px;
        }

        .matiere:hover .buttons {
            display: flex;
        }

        .btn-view {
            background: #fff;
            color: #333;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h2>Mes Matières</h2>
    <div class="container">
        <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <?php if (count($enseignements) > 0): ?>
                <?php foreach ($enseignements as $enseignement): ?>
                    <div class="matiere" style="background-image: url('../img/menu.png');" data-id="<?php echo htmlspecialchars($enseignement['ID_Ens']); ?>">
                        <div class="matiere-label">
                            <?php echo htmlspecialchars($enseignement['nom_Ens']); ?>
                        </div>
                        <div class="buttons">
                            <button class="btn-view" data-action="view" data-id="<?php echo htmlspecialchars($enseignement['ID_Ens']); ?>">Voir les épreuves</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun enseignement trouvé pour cet élève.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-view').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation(); // Empêche le clic de se propager à l'élément parent
                    const idEns = button.getAttribute('data-id');
                    // Redirection vers la page de visualisation des épreuves
                    window.location.href = `notes.php?id_ens=${idEns}`;
                });
            });
        });
    </script>
</body>

</html>
