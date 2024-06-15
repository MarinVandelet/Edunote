<?php
session_start();
include '../functions/config.php';
include '../includes/header.php';
// Vérifiez si l'ID de l'enseignement est passé en paramètre
if (!isset($_GET['id_ens'])) {
    echo "ID de l'enseignement non spécifié.";
    exit;
}

$id_ens = $_GET['id_ens'];

try {
    $pdo = connexionDB();

    // Récupérer les informations de l'enseignement
    $sql_ens = "SELECT nom_Ens, Semestre, Coefficient, ID_UE FROM ENSEIGNEMENT WHERE ID_Ens = :id_ens";
    $stmt_ens = $pdo->prepare($sql_ens);
    $stmt_ens->execute(['id_ens' => $id_ens]);
    $enseignement = $stmt_ens->fetch(PDO::FETCH_ASSOC);

    // Récupérer les informations sur les classes pour le sélecteur de classe
    $sql_classes = "SELECT ID_classe, nom FROM CLASSE";
    $stmt_classes = $pdo->query($sql_classes);
    $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer une épreuve - <?php echo htmlspecialchars($enseignement['nom_Ens']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Styles généraux */
        .matiere_container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .matiere_body {
            margin-top: 20px;
        }

        /* Styles pour le formulaire */
        form {
            width: 100%;
            max-width: 900px;
            margin: auto;
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form input[type="date"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="date"] {
            width: auto;
            /* Ajustement pour la largeur spécifique du type date */
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Styles pour le tableau des étudiants */
        .students_table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .students_table th,
        .students_table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .students_table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .students_table td input[type="number"] {
            width: 65px;
            /* Ajustez cette valeur selon vos besoins */
            padding: 6px;
            /* Ajustez le padding selon vos préférences */
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .students_table td textarea {
            width: calc(100% - 12px);
            /* Ajuste la largeur du textarea */
            max-width: 100%;
            /* S'assure que le textarea ne dépasse pas de son conteneur */
            min-width: 100%;
            /* S'assure que le textarea prend la largeur complète */
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            word-wrap: break-word;
            /* Permet le retour à la ligne si nécessaire */
            resize: vertical;
            /* Permet le redimensionnement vertical */
        }
    </style>
</head>

<body>
    <div class="matiere_container">
        <div class="matiere_body">
            <h2>Créer une épreuve - <?php echo htmlspecialchars($enseignement['nom_Ens']); ?></h2>

            <form action="save_epreuve.php" method="post">
                <input type="hidden" name="id_ens" value="<?php echo htmlspecialchars($id_ens); ?>">

                <label for="nom_epreuve">Intitulé:</label>
                <input type="text" id="nom_epreuve" name="nom_epreuve" required><br><br>

                <label for="id_classe">Classe:</label>
                <select name="id_classe" id="classe" required>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?php echo htmlspecialchars($classe['ID_classe']); ?>">
                            <?php echo htmlspecialchars($classe['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>


                <label for="coefficient">Coefficient:</label>
                <input type="number" id="coefficient" name="coefficient" required><br><br>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required><br><br>

                <table class="students_table">
                    <thead>
                        <tr>
                            <th>ID ÉLÈVE</th>
                            <th>NOM</th>
                            <th>PRÉNOM</th>
                            <th>NOTE</th>
                            <th>APPRECIATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- La liste des élèves sera chargée ici via JavaScript -->
                    </tbody>
                </table>
                <table id="moyenne_classe" class="moyenne_table">
                    <thead>
                        <tr>
                            <th>Moyenne de la classe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td> <!-- Moyenne de la classe sera insérée ici -->
                        </tr>
                    </tbody>
                </table>
                <br>
                <input type="submit" value="Valider">
            </form>
        </div>
    </div>

    <script>
        // Fonction pour mettre à jour les élèves en fonction de la classe sélectionnée
        function updateStudents() {
            var classeId = document.getElementById('classe').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../pages/get_students.php?id_classe=' + classeId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var students = JSON.parse(xhr.responseText);
                    var tbody = document.querySelector('.students_table tbody');
                    var totalNote = 0;
                    var countNote = 0;

                    tbody.innerHTML = ''; // Efface le contenu précédent

                    students.forEach(function (student) {
                        var row = '<tr>' +
                            '<td>' + student.ID_eleve + '</td>' +
                            '<td>' + student.nom + '</td>' +
                            '<td>' + student.prenom + '</td>' +
                            '<td><input type="number" name="note[' + student.ID_eleve + ']" step="0.25" onchange="updateClassAverage()" required></td>' +
                            '<td><textarea name="appreciation[' + student.ID_eleve + ']" rows="2" ></textarea></td>' +
                            '</tr>';
                        tbody.innerHTML += row;
                    });

                    // Appel initial pour calculer la moyenne de classe
                    updateClassAverage();
                } else {
                    console.log('Erreur lors de la récupération des élèves : ' + xhr.status);
                }
            };
            xhr.send();
        }

        function updateClassAverage() {
            var inputs = document.querySelectorAll('.students_table tbody input[type="number"]');
            var totalNote = 0;
            var countNote = 0;

            inputs.forEach(function (input) {
                if (input.value !== '') {
                    totalNote += parseFloat(input.value);
                    countNote++;
                }
            });

            var moyenneClasse = totalNote / countNote;

            // Sélectionner les éléments du tableau moyenne_classe
            var moyenneClasseElement = document.getElementById('moyenne_classe');
            var moyenneCell = moyenneClasseElement.querySelector('tbody tr td:first-child');
            if (!moyenneClasse) {
                moyenneCell.textContent = "Entrez les Note pour obtenir la moyenne"
            } else {
                // Afficher la moyenne de classe et l'appréciation
                moyenneCell.textContent = moyenneClasse.toFixed(2);
            }
        }





        // Appeler updateStudents lorsque la classe change
        document.getElementById('classe').addEventListener('change', function () {
            updateStudents();
        });

        // Appeler updateStudents au chargement initial de la page
        updateStudents();
    </script>

    <?php include "../includes/footer.php"; ?>
</body>

</html>