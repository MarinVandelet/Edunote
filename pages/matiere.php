<?php
session_start();
include '../functions/config.php';
include '../includes/header.php';
$pdo = connexionDB();

// Vérifiez si l'ID de l'enseignant est défini dans la session
if (!isset($_SESSION['prof'])) {
    echo "ID de l'enseignant non défini.";
    exit;
}

$id_prof = $_SESSION['prof'];

$error = '';
$enseignements = [];

try {
    // Récupérer les enseignements liés à cet enseignant
    $sql = "SELECT E.ID_Ens, E.nom_Ens, E.Semestre, E.Coefficient, U.Competence, T.type
            FROM ENSEIGNEMENT E
            JOIN ENSEIGNE En ON E.ID_Ens = En.ID_Ens
            JOIN UE U ON E.ID_UE = U.ID_UE
            JOIN TYPE_ENSEIGNEMENT T ON E.type = T.type
            WHERE En.ID_prof = :id_prof";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_prof' => $id_prof]);
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
        .btn-note, .btn-view {
            background: #fff;
            color: #333;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal.open {
            display: block;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .modal-overlay.open {
            display: block;
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
                            <button class="btn-note" data-action="create" data-id="<?php echo htmlspecialchars($enseignement['ID_Ens']); ?>">Créer une note</button>
                            <button class="btn-view" data-action="view" data-id="<?php echo htmlspecialchars($enseignement['ID_Ens']); ?>">Voir les notes</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun enseignement trouvé.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Modale -->
    <div class="modal-overlay"></div>
    <div class="modal">
        <form id="matiereForm" method="post" action="update_matiere.php">
            <input type="hidden" id="id_ens" name="id_ens">
            <div>
                <label for="nom_ens">Libellé de l'enseignement:</label>
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
                    <!-- Options UE seront ajoutées par JS -->
                </select>
            </div>
            <div>
                <label for="type_ens">Type d'enseignement:</label>
                <select name="type_ens" id="type_ens" required>
                    <!-- Options Type seront ajoutées par JS -->
                </select>
            </div>
            <input type="submit" value="Enregistrer">
        </form>
    </div>

    <?php include '../includes/footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.querySelector('.modal');
            const overlay = document.querySelector('.modal-overlay');
            const matiereForm = document.getElementById('matiereForm');

            document.querySelectorAll('.btn-note, .btn-view').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation(); // Empêche le clic de se propager à l'élément parent
                    const idEns = button.getAttribute('data-id');
                    const action = button.getAttribute('data-action');

                    if (action === 'create') {
                        // Redirection vers la page de création de note
                        window.location.href = `create_note.php?id_ens=${idEns}`;
                    } else if (action === 'view') {
                        // Redirection vers la page de visualisation des notes
                        window.location.href = `view_epreuves.php?id_ens=${idEns}`;
                    }
                });
            });

            document.querySelectorAll('.matiere').forEach(matiere => {
                matiere.addEventListener('click', () => {
                    const idEns = matiere.getAttribute('data-id');
                    fetch(`fetch_matiere.php?id_ens=${idEns}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('id_ens').value = data.ID_Ens;
                            document.getElementById('nom_ens').value = data.nom_Ens;
                            document.getElementById('semestre').value = data.Semestre;
                            document.getElementById('coefficient').value = data.Coefficient;
                            // Ajouter les options UE et Type
                            const ueSelect = document.getElementById('ue');
                            ueSelect.innerHTML = data.ueOptions;
                            const typeSelect = document.getElementById('type_ens');
                            typeSelect.innerHTML = data.typeOptions;
                            modal.classList.add('open');
                            overlay.classList.add('open');
                        });
                });
            });

            overlay.addEventListener('click', () => {
                modal.classList.remove('open');
                overlay.classList.remove('open');
            });
        });
    </script>
</body>
</html>
