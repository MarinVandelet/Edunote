<?php
session_start();

include '../functions/config.php';
include '../includes/header.php';
$pdo = connexionDB();
?>

<body>
    <h1>Mes Enseignements</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nom de l'enseignement</th>
                <th>Semestre</th>
                <th>Coefficient</th>
                <th>Unité d'enseignement (UE)</th>
                <th>Type d'enseignement</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($enseignements)): ?>
                <?php foreach ($enseignements as $enseignement): ?>
                    <tr>
                        <td><?= htmlspecialchars($enseignement['nom_Ens']) ?></td>
                        <td><?= htmlspecialchars($enseignement['Semestre']) ?></td>
                        <td><?= htmlspecialchars($enseignement['Coefficient']) ?></td>
                        <td><?= htmlspecialchars($enseignement['Competence']) ?></td>
                        <td><?= htmlspecialchars($enseignement['type']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucun enseignement trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

<?php include "../includes/footer.php";

?>

</body>
</html>
