<?php 
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté et est un professeur
if (!isset($_SESSION['prenom']) || $_SESSION['role'] !== 'Professeur') {
    header('Location: index.php');
    exit();
}

$prenom_professeur = $_SESSION['prenom'];

// Initialiser les variables
$classe_id = $_POST['classe'] ?? null;
$trimestre = $_POST['trimestre'] ?? null;
$nom_classe_selectionnee = '';
$nom_matiere_selectionnee = '';
$eleves = [];

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

// Réinitialiser les moyennes et rangs pour la classe sélectionnée
if ($classe_id && $trimestre) {
    $query = "UPDATE eleve SET moyenne = NULL, rang = NULL WHERE id_classe = :classe_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
    $stmt->execute();
}


if (isset($_POST['enregistrer']) && isset($_POST['notes']) && !empty($_POST['notes'])) {
    $notes = $_POST['notes']; // Tableau associatif : id_eleve => note
    $trimestre = $_POST['trimestre']; // Vous pouvez ajuster cette variable selon le trimestre en cours
    
    $allValid = true; // Variable to track if all notes are valid
    $invalidStudents = []; // Array to store invalid students

    // Check if there is only one number in the array (indicating incorrect input)
    if (count($notes) == 1) {
        echo "<div class='alert alert-danger text-center'>Veuillez saisir plusieurs notes pour les élèves.</div>";
        $allValid = false;
    }

    // First, validate all notes without saving anything
    foreach ($notes as $id_eleve => $note) {
        // Fetch student's name and surname from the database
        $query = "SELECT nom_eleve, prenoms_eleve FROM eleve WHERE id_eleve = :id_eleve";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id_eleve', $id_eleve, PDO::PARAM_INT);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier que la note est valide
        if (!(is_numeric($note) && $note >= 0 && $note <= 20)) {
            // If the note is invalid, add to the invalid students array
            $nom = $student['nom_eleve'];
            $prenom = $student['prenoms_eleve'];
            $invalidStudents[] = "$prenom $nom"; // Store invalid student's name
            $allValid = false;
        }
    }

    // If there were invalid notes, display the error message and stop further processing
    if (!$allValid) {
        $invalidList = implode(', ', $invalidStudents);
        echo "<div class='alert alert-danger text-center'>Veuillez saisir une note valide pour tous les élèves </div>";
    } else {
        // If all notes are valid, insert them into the database
        foreach ($notes as $id_eleve => $note) {
            // Insert the valid notes into the database
            $query = "INSERT INTO note (id_eleve, note, trimestre) 
                      VALUES (:id_eleve, :note, :trimestre)
                      ON DUPLICATE KEY UPDATE note = :note";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_eleve', $id_eleve, PDO::PARAM_INT);
            $stmt->bindValue(':note', $note, PDO::PARAM_STR);
            $stmt->bindValue(':trimestre', $trimestre, PDO::PARAM_STR);
            $stmt->execute();
        }
        // Display success message
        echo "<div class='alert alert-success text-center'>Les notes ont été ajoutées avec succès.</div>";
    }

}

    // Calcul des moyennes et des rangs
  if (isset($_POST['calculer_moyenne']) && $classe_id && $trimestre) {
    // Calcul des moyennes pour chaque élève
    $query = "UPDATE eleve e
              JOIN (
                  SELECT id_eleve, AVG(note) AS moyenne_calculee
                  FROM note
                  WHERE trimestre = :trimestre
                  GROUP BY id_eleve
              ) moyennes
              ON e.id_eleve = moyennes.id_eleve
              SET e.moyenne = moyennes.moyenne_calculee
              WHERE e.id_classe = :classe_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':trimestre', $trimestre, PDO::PARAM_STR);
    $stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
    $stmt->execute();

    // Calculer les rangs en fonction des moyennes
    $query = "
        UPDATE eleve e
        JOIN (
            SELECT id_eleve, 
                   FIND_IN_SET(moyenne, (
                       SELECT GROUP_CONCAT(DISTINCT moyenne ORDER BY moyenne DESC)
                       FROM eleve
                       WHERE id_classe = :classe_id
                   )) AS rang_calcule
            FROM eleve
            WHERE id_classe = :classe_id
        ) ranks
        ON e.id_eleve = ranks.id_eleve
        SET e.rang = ranks.rang_calcule";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
    $stmt->execute();
}


    // Récupérer les élèves de la classe sélectionnée pour un trimestre spécifique
    if ($classe_id && $trimestre) {
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

        $query = "SELECT e.id_eleve, e.nom_eleve, e.prenoms_eleve, GROUP_CONCAT(n.note ORDER BY n.id_note SEPARATOR ' ') AS notes, e.moyenne, e.rang
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {
    try {
        // Remplacez 'table_notes' et 'trimestre' par vos noms réels
        $query = "DELETE FROM note WHERE trimestre = :trimestre";
        
        // Spécifiez le trimestre à supprimer
        $trimestre = $_POST['trimestre']; // Par exemple, changez selon vos besoins

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':trimestre', $trimestre, PDO::PARAM_STR);
        
        // Exécutez la suppression
        if ($stmt->execute()) {
            echo " <div class='alert alert-success text-center'> Tous les enregistrements pour le $trimestre ont été supprimés avec succès. </div> ";
                   

        } else {
            echo " <div class='alert alert-danger text-center'>Une erreur s'est produite lors de la suppression. </div> ";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }}

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- AOS CSS for scroll animations -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <style>
        /* Color theme and general styling */
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .sidebar {
    width: 150px;
    position: fixed; /* Fixe la position */
    top: 0;
    left: 0; /* Place à gauche */
    height: 100%;
    background-color: #007bff;
    color: #FFFFFF;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000; /* Toujours au-dessus */
     transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
}
        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        .container {
            max-width: 900px;
        }

        h2, h3, h4 {
            color: #5e2a84;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Styled buttons */
        .btn-primary, .btn-info, .btn-success, .btn-outline-primary {
            background-color: #6a1b9a;
            color: white;
            border-radius: 25px;
        }

        .btn-primary:hover, .btn-info:hover, .btn-success:hover {
            background-color: #4a148c;
            border-color: #4a148c;
        }

        /* Table styling */
        .table {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        .table th {
            background-color: #7b1fa2;
            color: white;
            text-transform: uppercase;
        }

        .table td {
            color: #6a1b9a;
        }

        .table-hover tbody tr:hover {
            background-color: #f3e5f5;
        }

        /* Card shadow on form elements */
        .form-group, .table {
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(94, 42, 132, 0.3);
            border-radius: 10px;
            background-color: #ffffff;
            transition: transform 0.3s ease-in-out;
        }

        .form-group:hover, .table:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <div class="sidebar"  style="background-image: url('images/StockCake-Abstract Geometric Art_1727874380.jpg');  background-repeat: no-repeat;
            background-position: center;
            background-size: 400px;
            background-color: #f8f9fa;
            ">
        </div>

    <div class="container mt-5">
        <div class="text-center mb-4" data-aos="fade-right">
            <a href="dashboardprofesseur.php" class="btn btn-outline-primary btn-lg" style="font-weight: bold;">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
        <h2 class="animate__animated animate__fadeIn">Ajouter les Notes des Élèves</h2>

        <!-- Selection form -->
        <form method="POST" class="animate__animated animate__fadeInUp" data-aos="zoom-in">
            <div class="form-group">
                <label for="classe">Sélectionner la classe :</label>
                <select name="classe" id="classe" class="form-control" required>
                    <option value="">--Sélectionner une classe--</option>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?= $classe['id_classe'] ?>" <?= $classe_id == $classe['id_classe'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($classe['nom_classe']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="trimestre">Sélectionner le trimestre :</label>
                <select name="trimestre" id="trimestre" class="form-control" required>
                    <option value="">--Sélectionner un trimestre--</option>
                    <?php foreach ($trimestres as $trimestre_item): ?>
                        <option value="<?= htmlspecialchars($trimestre_item) ?>" <?= $trimestre == $trimestre_item ? 'selected' : '' ?>>
                            <?= htmlspecialchars($trimestre_item) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 animate__animated animate__zoomIn">Valider</button><br><br>
        </form>
        <br><br>
        <?php if ($classe_id && $trimestre): ?>
            <h3 data-aos="fade-up">Liste des élèves pour la classe : <?= htmlspecialchars($nom_classe_selectionnee) ?></h3>
            <h4 data-aos="fade-up">Matière : <?= htmlspecialchars($nom_matiere_selectionnee) ?></h4>
            
            <br><br><form method="POST">
                <input type="hidden" name="classe" value="<?= htmlspecialchars($classe_id) ?>">
                <input type="hidden" name="trimestre" value="<?= htmlspecialchars($trimestre) ?>">
                
                <table class="table table-hover mt-4" >
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Notes</th>
                            <th>Moyenne</th>
                            <th>Rang</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eleves as $eleve): ?>
                            <tr>
                                <td><?= htmlspecialchars($eleve['nom_eleve']) ?></td>
                                <td><?= htmlspecialchars($eleve['prenoms_eleve']) ?></td>
                                <td><?= htmlspecialchars($eleve['notes']) ?></td>
                                <td>
                                    <?= isset($eleve['moyenne']) && $eleve['moyenne'] !== null ? number_format($eleve['moyenne'], 2) : 'Non calculée' ?>
                                </td>
                                <td>
                                    <?php 
                                        $rang = isset($eleve['rang']) && $eleve['rang'] !== null ? $eleve['rang'] : 'Non attribué';
                                        echo $rang !== 'Non attribué' ? $rang . "<sup>" . ($rang == 1 ? "er" : "ème") . "</sup>" : $rang;
                                    ?>
                                </td>
                                <td>
                                    <input type="number" name="notes[<?= $eleve['id_eleve'] ?>]" 
                                           class="form-control" placeholder="Note" 
                                           value="<?= isset($eleve['notes']) ? htmlspecialchars($eleve['notes']) : '' ?>" 
                                           step="0.01" min="0" max="20" >
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="enregistrer" class="btn btn-success w-100 animate__animated animate__zoomIn mt-3">Enregistrer les Notes</button>
                <button type="submit" name="calculer_moyenne" class="btn btn-info w-100 animate__animated animate__zoomIn mt-2">Calculer Moyennes et Rangs</button>
                <form action="delete_trimester.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer tous les enregistrements du trimestre ? Cette action est irréversible.')">
    <button type="submit" name="delete_all" class="btn btn-success w-100 animate__animated animate__zoomIn mt-3">Supprimer tous les enregistrements du trimestre</button>
</form>

            </form>
        <?php endif; ?>
    </div>

    <!-- Scripts for Bootstrap, AOS, and font-awesome icons -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>


// Fetch all students ordered by their averages
$query = "
    SELECT id_eleve, moyenne
    FROM eleve
    WHERE id_classe = :classe_id
    ORDER BY moyenne DESC, id_eleve ASC";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
$stmt->execute();
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rank = 1; // Initial rank
$previous_average = null; // To store the previous average for comparison
$rank_counter = 1; // Counter for students in the same rank group

foreach ($eleves as $index => $eleve) {
    // If the current average is different from the previous average, set the new rank
    if ($eleve['moyenne'] != $previous_average) {
        $rank = $index + 1; // New rank based on current index
        $rank_counter = 1; // Reset the counter for the new rank group
    } else {
        // If the average is the same as the previous, they share the same rank
        $rank_counter++;
    }

    // Update the rank for this student in the database
    $query = "
        UPDATE eleve
        SET rang = :rang
        WHERE id_eleve = :id_eleve";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':rang', $rank, PDO::PARAM_INT);
    $stmt->bindValue(':id_eleve', $eleve['id_eleve'], PDO::PARAM_INT);
    $stmt->execute();

    // Update the previous average for the next iteration
    $previous_average = $eleve['moyenne'];
}





// Calcul des moyennes et des rangs
if (isset($_POST['calculer_moyenne']) && $classe_id && $trimestre) {
    // Calcul des moyennes pour chaque élève
    $query = "UPDATE eleve e
              JOIN (
                  SELECT id_eleve, AVG(note) AS moyenne_calculee
                  FROM note
                  WHERE trimestre = :trimestre
                  GROUP BY id_eleve
              ) moyennes
              ON e.id_eleve = moyennes.id_eleve
              SET e.moyenne = moyennes.moyenne_calculee
              WHERE e.id_classe = :classe_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':trimestre', $trimestre, PDO::PARAM_STR);
    $stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
    $stmt->execute();

    // Calcul des rangs avec gestion des ex æquo
// Étape 1 : Calcul des rangs avec gestion des ex æquo
$query = "
    SET @rank = 0, @previousMoyenne = NULL, @tieCount = 1;

UPDATE eleve e
JOIN (
    SELECT id_eleve, moyenne,
           @rank := IF(@previousMoyenne = moyenne, @rank, @rank + @tieCount) AS calculated_rank,
           @tieCount := IF(@previousMoyenne = moyenne, @tieCount + 1, 1),
           @previousMoyenne := moyenne
    FROM (
        SELECT id_eleve, moyenne
        FROM eleve
        WHERE id_classe = :classe_id
        ORDER BY moyenne DESC, nom_eleve ASC, prenoms_eleve ASC
    ) ranked
) ranks ON e.id_eleve = ranks.id_eleve
SET e.rang = ranks.calculated_rank
WHERE e.id_classe = :classe_id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
$stmt->execute();

// Étape 2 : Ajouter "ex" uniquement aux élèves après le premier ex æquo
$query = "
    UPDATE eleve e
    JOIN (
        SELECT id_eleve, moyenne,
               ROW_NUMBER() OVER (PARTITION BY moyenne ORDER BY nom_eleve ASC, prenoms_eleve ASC) AS row_num
        FROM eleve
        WHERE id_classe = :classe_id
    ) ranked ON e.id_eleve = ranked.id_eleve
    SET e.rang = CONCAT(e.rang, ' ex')
    WHERE ranked.row_num > 1 AND e.id_classe = :classe_id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':classe_id', $classe_id, PDO::PARAM_INT);
$stmt->execute();


}