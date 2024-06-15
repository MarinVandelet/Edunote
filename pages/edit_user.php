<link rel="stylesheet" href="../css/style.css">

<?php
session_start();
include '../functions/config.php';
$pdo = connexionDB();

$error = '';
$user = [];
$type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    if ($type == 'Élève') {
        $sql = "UPDATE ELEVE SET nom = :nom, prenom = :prenom, adresse_mail = :email WHERE ID_eleve = :id";
    } elseif ($type == 'Enseignant') {
        $sql = "UPDATE ENSEIGNANT SET nom = :nom, prenom = :prenom, adresse_mail = :email WHERE ID_prof = :id";
    } elseif ($type == 'Administrateur') {
        $sql = "UPDATE ADMINISTRATEUR SET nom = :nom, prenom = :prenom, adresse_mail = :email WHERE ID_admin = :id";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Type utilisateur inconnu']);
        exit;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'id' => $id
        ]);
        echo "<script>window.close();window.opener.location.reload();</script>";
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
} else {
    if (isset($_GET['id']) && isset($_GET['type'])) {
        $id = $_GET['id'];
        $type = $_GET['type'];

        if ($type === 'Élève') {
            $sql = "SELECT * FROM ELEVE WHERE ID_eleve = :id";
        } elseif ($type === 'Enseignant') {
            $sql = "SELECT * FROM ENSEIGNANT WHERE ID_prof = :id";
        } elseif ($type === 'Administrateur') {
            $sql = "SELECT * FROM ADMINISTRATEUR WHERE ID_admin = :id";
        } else {
            $error = "Type utilisateur inconnu";
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $error = "Erreur lors de la récupération des données de l'utilisateur: " . $e->getMessage();
            }
        }
    } else {
        $error = "ID ou type utilisateur manquant";
    }
}
?>


<body>
    <h2>Modifier Utilisateur</h2>
    <?php if (!empty($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" id="editForm">
        <!-- Champ caché pour l'id et le type -->
        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="hidden" id="type" name="type" value="<?php echo htmlspecialchars($type); ?>">

        <!-- Champ pour le nom -->
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom"
            value="<?php echo isset($user['nom']) ? htmlspecialchars($user['nom']) : ''; ?>"><br>

        <!-- Champ pour le prénom -->
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom"
            value="<?php echo isset($user['prenom']) ? htmlspecialchars($user['prenom']) : ''; ?>"><br>

        <!-- Champ pour l'adresse email -->
        <label for="email">Adresse Email:</label>
        <input type="text" id="email" name="email"
            value="<?php echo isset($user['adresse_mail']) ? htmlspecialchars($user['adresse_mail']) : ''; ?>"><br>

        <!-- Bouton de soumission -->
        <input type="submit" value="Enregistrer">
    </form>
</body>

</html>
