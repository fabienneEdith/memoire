<?php
require_once 'config.php';

// Get professor ID from URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID du professeur non spécifié.'); window.location.href='professeur.php';</script>";
    exit;
}
$professorId = $_GET['id'];

// Fetch current professor details
$sql = "SELECT * FROM professeur WHERE id_professeur = :id_professeur";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_professeur' => $professorId]);
$professor = $stmt->fetch();

if (!$professor) {
    echo "<script>alert('Professeur non trouvé.'); window.location.href='professeur.php';</script>";
    exit;
}

// Fetch classes and subjects for dropdowns
$classes = $pdo->query("SELECT * FROM classe")->fetchAll();
$matieres = $pdo->query("SELECT * FROM matiere")->fetchAll();

// Handle form submission for updating professor details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomProf = trim($_POST['nom_prof']);
    $prenomProf = trim($_POST['prenom_prof']);
    $sexeProf = $_POST['sexe_prof'];
    $classeAttribut = $_POST['classe_attribuee'];
    $matiereEnseignee = $_POST['matiere_enseignee'];

    // Update professor in the database
    $sqlUpdate = "UPDATE professeur 
                  SET nom_professeur = :nom_prof, prenoms_professeur = :prenom_prof, sexe_prof = :sexe_prof, 
                      classe_attribuee = :classe_attribuee, matiere_enseignee = :matiere_enseignee 
                  WHERE id_professeur = :id_professeur";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([
        'nom_prof' => $nomProf,
        'prenom_prof' => $prenomProf,
        'sexe_prof' => $sexeProf,
        'classe_attribuee' => $classeAttribut,
        'matiere_enseignee' => $matiereEnseignee,
        'id_professeur' => $professorId
    ]);

    echo "<script>alert('Professeur mis à jour avec succès.'); window.location.href='professeur.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Professeur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Modifier Professeur</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_prof" class="form-control" value="<?= htmlspecialchars($professor['nom_professeur']) ?>" required>
        </div>
        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom_prof" class="form-control" value="<?= htmlspecialchars($professor['prenoms_professeur']) ?>" required>
        </div>
        <div class="form-group">
            <label>Sexe</label>
            <select name="sexe_prof" class="form-control" required>
                <option value="Homme" <?= $professor['sexe_prof'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                <option value="Femme" <?= $professor['sexe_prof'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
            </select>
        </div>
        <div class="form-group">
            <label>Classe attribuée</label>
            <select name="classe_attribuee" class="form-control" required>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id_classe'] ?>" <?= $professor['classe_attribuee'] == $classe['id_classe'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classe['nom_classe']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Matière enseignée</label>
            <select name="matiere_enseignee" class="form-control" required>
                <?php foreach ($matieres as $matiere): ?>
                    <option value="<?= $matiere['id_matiere'] ?>" <?= $professor['matiere_enseignee'] == $matiere['id_matiere'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($matiere['nom_matiere']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="professeur.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
