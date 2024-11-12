<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté et est un professeur
if (!isset($_SESSION['prenom']) || $_SESSION['role'] !== 'Professeur') {
    header('Location: index.php');
    exit();
}

$prenom_professeur = $_SESSION['prenom'];

// Récupérer les classes et matières attribuées au professeur
$query = "SELECT c.id_classe, c.nom_classe, m.nom_matiere
          FROM professeur AS p
          JOIN classe AS c ON p.classe_attribuee = c.id_classe
          JOIN matiere AS m ON p.matiere_enseignee = m.id_matiere
          WHERE p.prenoms_professeur = :prenom_professeur";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':prenom_professeur', $prenom_professeur, PDO::PARAM_STR);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$trimestres = ['Premier Trimestre', 'Deuxième Trimestre', 'Troisième Trimestre'];
$nom_classe_selectionnee = '';
$nom_matiere_selectionnee = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classe_id = $_POST['classe'] ?? null;
    $trimestre = $_POST['trimestre'] ?? null;

    // Vérifier la classe sélectionnée pour l'affichage
    if ($classe_id) {
        $query = "SELECT c.nom_classe, m.nom_matiere
                  FROM classe AS c
                  JOIN matiere AS m ON m.id_matiere = (SELECT matiere_enseignee FROM professeur WHERE prenoms_professeur = :prenom_professeur AND classe_attribuee = :classe_id)
                  WHERE c.id_classe = :classe_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':prenom_professeur', $prenom_professeur, PDO::PARAM_STR);
        $stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
        $stmt->execute();
        $classe_matiere = $stmt->fetch(PDO::FETCH_ASSOC);

        $nom_classe_selectionnee = $classe_matiere['nom_classe'];
        $nom_matiere_selectionnee = $classe_matiere['nom_matiere'];

        // Récupérer les élèves de la classe sélectionnée avec leurs notes
        $query = "SELECT e.id_eleve, e.nom_eleve, e.prenoms_eleve, GROUP_CONCAT(n.note SEPARATOR ', ') AS notes
                  FROM eleve AS e
                  LEFT JOIN note AS n ON e.id_eleve = n.id_eleve AND n.trimestre = :trimestre
                  WHERE e.id_classe = :classe
                  GROUP BY e.id_eleve";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':classe', $classe_id, PDO::PARAM_INT);
        $stmt->bindValue(':trimestre', $trimestre, PDO::PARAM_STR);
        $stmt->execute();
        $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Traitement de l'enregistrement des notes
    if (isset($_POST['enregistrer']) && isset($_POST['notes'])) {
        foreach ($_POST['notes'] as $id_eleve => $note) {
            if (is_numeric($note) && $note >= 0 && $note <= 20) { // Vérification de la note entre 0 et 20
                $query = "INSERT INTO note (id_eleve, trimestre, note) VALUES (:id_eleve, :trimestre, :note)
                          ON DUPLICATE KEY UPDATE note = :note";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':id_eleve', $id_eleve, PDO::PARAM_INT);
                $stmt->bindValue(':trimestre', $trimestre, PDO::PARAM_STR);
                $stmt->bindValue(':note', $note, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des Notes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Entrer les Notes des Élèves</h2>
        <form method="POST">
            <div class="form-group">
                <label for="classe">Choisir la classe :</label>
                <select name="classe" id="classe" class="form-control" required>
                    <option value="">--Sélectionner une classe--</option>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?= $classe['id_classe'] ?>" <?= isset($classe_id) && $classe_id == $classe['id_classe'] ? 'selected' : '' ?> >
                            <?= htmlspecialchars($classe['nom_classe']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="trimestre">Choisir le trimestre :</label>
                <select name="trimestre" id="trimestre" class="form-control" required>
                    <option value="">--Sélectionner un trimestre--</option>
                    <?php foreach ($trimestres as $trimestre_item): ?>
                        <option value="<?= htmlspecialchars($trimestre_item) ?>" <?= isset($trimestre) && $trimestre == $trimestre_item ? 'selected' : '' ?>>
                            <?= htmlspecialchars($trimestre_item) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
        
        <?php if (isset($eleves) && !empty($eleves)): ?>
            <h3>Liste des élèves en classe : <?= htmlspecialchars($nom_classe_selectionnee) ?></h3>
            <h4>Matière : <?= htmlspecialchars($nom_matiere_selectionnee) ?></h4>
            <form method="POST">
                <input type="hidden" name="classe" value="<?= htmlspecialchars($classe_id) ?>">
                <input type="hidden" name="trimestre" value="<?= htmlspecialchars($trimestre) ?>">
                
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Notes</th>
                            <th>Saisie Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eleves as $eleve): ?>
                            <tr>
                                <td><?= htmlspecialchars($eleve['nom_eleve']) ?></td>
                                <td><?= htmlspecialchars($eleve['prenoms_eleve']) ?></td>
                                <td>
                                    <?php if (!empty($eleve['notes'])): ?>
                                        <span><?= htmlspecialchars($eleve['notes']) ?></span>
                                    <?php else: ?>
                                        <span>Aucune note</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <input type="number" name="notes[<?= htmlspecialchars($eleve['id_eleve']) ?>]" 
                                           class="form-control" step="0.01" 
                                           placeholder="Entrez la note">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <button type="submit" name="enregistrer" class="btn btn-success">Enregistrer les notes</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
