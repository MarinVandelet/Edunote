<?php
session_start();
include '../functions/config.php';

// Vérifiez si l'ID de l'épreuve est passé en paramètre
if (!isset($_GET['id'])) {
    echo "ID de l'épreuve non spécifié.";
    exit;
}

$id_epreuve = $_GET['id'];

try {
    $pdo = connexionDB();

    // Récupérer les informations de l'épreuve
    $sql_epreuve = "SELECT nom_epreuve, ID_classe, coefficient, date FROM EPREUVE WHERE id_epreuve = :id_epreuve";
    $stmt_epreuve = $pdo->prepare($sql_epreuve);
    $stmt_epreuve->execute(['id_epreuve' => $id_epreuve]);
    $epreuve = $stmt_epreuve->fetch(PDO::FETCH_ASSOC);

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
    <title>Modifier une épreuve - <?php echo htmlspecialchars($epreuve['nom_epreuve']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
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
            width: auto; /* Ajustement pour la largeur spécifique du type date */
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Modifier une épreuve - <?php echo htmlspecialchars($epreuve['nom_epreuve']); ?></h2>

        <form action="save_epreuve.php" method="post">
            <input type="hidden" name="id_epreuve" value="<?php echo htmlspecialchars($id_epreuve); ?>">

            <label for="nom_epreuve">Intitulé:</label>
            <input type="text" id="nom_epreuve" name="nom_epreuve" value="<?php echo htmlspecialchars($epreuve['nom_epreuve']); ?>" required><br><br>

            <label for="id_classe">Classe:</label>
            <select name="id_classe" id="id_classe" required>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?php echo htmlspecialchars($classe['ID_classe']); ?>" <?php if ($classe['ID_classe'] == $epreuve['ID_classe']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($classe['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="coefficient">Coefficient:</label>
            <input type="number" id="coefficient" name="coefficient" value="<?php echo htmlspecialchars($epreuve['coefficient']); ?>" required><br><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($epreuve['date']); ?>" required><br><br>

            <!-- Tableau des élèves -->
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

            <br>
            <input type="submit" value="Enregistrer">
        </form>
    </div>

    <!-- Script JavaScript pour charger les élèves et calculer la moyenne de classe -->
    <script>
        // Fonction pour mettre à jour les élèves en fonction de la classe sélectionnée
        function updateStudents() {
            var classeId = document.getElementById('id_classe').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../pages/update_students.php?id_classe=' + classeId, true);
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
                moyenneCell.textContent = "Entrez les notes pour obtenir la moyenne";
            } else {
                moyenneCell.textContent = moyenneClasse.toFixed(2);
            }
        }

        // Appeler updateStudents lorsque la classe change
        document.getElementById('id_classe').addEventListener('change', function () {
            updateStudents();
        });

        // Appeler updateStudents au chargement initial de la page
        updateStudents();
    </script>
</body>

</html>
