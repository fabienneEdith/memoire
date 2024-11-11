<?php   
require_once 'config.php'; 

// Fetch classes and subjects for dropdowns
$classes = [];
$sqlClasses = "SELECT * FROM classe";
$stmtClasses = $pdo->query($sqlClasses);
$classes = $stmtClasses->fetchAll();

$matieres = [];
$sqlMatiere = "SELECT * FROM matiere";
$stmtMatiere = $pdo->query($sqlMatiere);
$matieres = $stmtMatiere->fetchAll();

// Handle Add Professor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nomProf = trim($_POST['nom_prof']);
    $prenomProf = trim($_POST['prenom_prof']);
    $matriculeProf = trim($_POST['matricule_prof']);
    $sexeProf = $_POST['sexe_prof'];
    $classeAttribut = $_POST['classe_attribuee'];
    $matiereEnseignee = $_POST['matiere_enseignee'];

    if (!empty($nomProf) && !empty($prenomProf) && !empty($matriculeProf) && !empty($sexeProf) && !empty($classeAttribut) && !empty($matiereEnseignee)) {
        // Check if matricule exists in users table and the role is 'professeur'
        $sqlCheckUser = "SELECT * FROM utilisateur WHERE matricule = :matricule_prof AND role = 'professeur'";
        $stmtCheckUser = $pdo->prepare($sqlCheckUser);
        $stmtCheckUser->execute(['matricule_prof' => $matriculeProf]);

        if ($stmtCheckUser->rowCount() > 0) {
            // Check if the professor is already teaching any subject in the same class
            $sqlCheckAssignment = "SELECT * FROM professeur 
                                   WHERE classe_attribuee = :classe_attribuee 
                                   AND matricule_professeur = :matricule_prof";
            $stmtCheckAssignment = $pdo->prepare($sqlCheckAssignment);
            $stmtCheckAssignment->execute([ 
                'classe_attribuee' => $classeAttribut, 
                'matricule_prof' => $matriculeProf
            ]);

            if ($stmtCheckAssignment->rowCount() > 0) {
                echo "<script>alert('Ce professeur enseigne déjà dans cette classe.');</script>";
            } else {
                // Insert into professor table
                $sqlInsertProf = "INSERT INTO professeur (matricule_professeur, nom_professeur, prenoms_professeur, sexe_prof, classe_attribuee, matiere_enseignee) 
                                  VALUES (:matricule_prof, :nom_prof, :prenom_prof, :sexe_prof, :classe_attribuee, :matiere_enseignee)";
                $stmtInsertProf = $pdo->prepare($sqlInsertProf);
                $stmtInsertProf->execute([ 
                    'matricule_prof' => $matriculeProf, 
                    'nom_prof' => $nomProf,
                    'prenom_prof' => $prenomProf,
                    'sexe_prof' => $sexeProf,
                    'classe_attribuee' => $classeAttribut,
                    'matiere_enseignee' => $matiereEnseignee
                ]);
                echo "<script>alert('Professeur ajouté avec succès.');</script>";
            }
        } else {
            echo "<script>alert('Le matricule doit exister dans la table utilisateur et le rôle doit être professeur.');</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.');</script>";
    }
}

// Handle Delete Professor
if (isset($_GET['delete_id'])) {
    $professeurId = $_GET['delete_id'];
    $sqlDeleteProf = "DELETE FROM professeur WHERE id_professeur = :id_professeur";
    $stmtDeleteProf = $pdo->prepare($sqlDeleteProf);
    $stmtDeleteProf->execute(['id_professeur' => $professeurId]);
    echo "<script>alert('Professeur supprimé avec succès.'); window.location.href='professeur.php';</script>";
}

// Fetch professors for display
$sqlProfessors = "SELECT p.*, c.nom_classe, m.nom_matiere 
                  FROM professeur AS p
                  INNER JOIN classe AS c ON p.classe_attribuee = c.id_classe
                  INNER JOIN matiere AS m ON p.matiere_enseignee = m.id_matiere";
$stmtProfessors = $pdo->query($sqlProfessors);
$professeurs = $stmtProfessors->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #E8F5E9; }
        .custom-card { box-shadow: 0px 4px 8px rgba(76, 175, 80, 0.5); border-radius: 10px; padding: 20px; background-color: white; }
        .btn-custom:hover { background-color: #388E3C; color:white }
        .btn-custom{ background-color: #4CAF50; color:white; }
        .table th, .table td { text-align: center; }
        .search-container { margin-bottom: 20px; }
        .search-bar { width: 300px; border-radius: 20px; border: 1px solid #4CAF50; padding: 8px 15px; }
        .search-bar:focus { border-color: #388E3C; }
        th { background-color: #4CAF50; color: white; }
        .table-bordered tbody tr:nth-child(even) { background-color: #C8E6C9; }
        .form-row { margin-bottom: 15px; }
        .form-row .col { margin-bottom: 10px; }
        .back-btn { margin-bottom: 20px; }

        @media (max-width: 510px){
        #table {
                width: 800px;
            }
              #table a{ height:60px;
            width:100px }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Retour à l'accueil -->
    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline btn-lg" style="border-radius: 20px; font-weight: bold; border-color: green; color: olive;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>

    <h2 class="text-center mb-4" style="color:#4CAF50;">Gestion des Professeurs</h2>

    <!-- Search Bar -->
    <div class="search-container text-center">
        <input type="text" id="searchInput" class="search-bar" placeholder="Rechercher un professeur...">
    </div>

    <!-- Add Professor Form -->
    <div class="custom-card mb-4">
        <form method="POST">
            <h5><i class="fas fa-plus-circle"></i> Ajouter un professeur</h5>
            <div class="form-row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nom_prof" placeholder="Nom du professeur" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prenom_prof" placeholder="Prénom du professeur" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="matricule_prof" placeholder="Matricule" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <select class="form-control" name="sexe_prof" required>
                        <option value="">Sélectionnez le sexe</option>
                        <option value="Femme">Femme</option>
                        <option value="Homme">Homme</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="classe_attribuee" required>
                        <option value="">Sélectionnez une classe</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= htmlspecialchars($classe['id_classe']) ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="matiere_enseignee" required>
                        <option value="">Sélectionnez une matière</option>
                        <?php foreach ($matieres as $matiere): ?>
                            <option value="<?= htmlspecialchars($matiere['id_matiere']) ?>"><?= htmlspecialchars($matiere['nom_matiere']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" name="ajouter" class="btn btn-custom mt-3"><i class="fas fa-save"></i> Ajouter</button>
        </form>
    </div>

    <!-- Professors Table -->
    <div class="custom-card" id="table">
        <h5><i class="fas fa-list"></i> Liste des professeurs</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Sexe</th>
                    <th>Classe Attribuée</th>
                    <th>Matière Enseignée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="professorTable">
                <?php foreach ($professeurs as $professeur): ?>
                    <tr>
                        <td><?= htmlspecialchars($professeur['matricule_professeur']) ?></td>
                        <td><?= htmlspecialchars($professeur['nom_professeur']) ?></td>
                        <td><?= htmlspecialchars($professeur['prenoms_professeur']) ?></td>
                        <td><?= htmlspecialchars($professeur['sexe_prof']) ?></td>
                        <td><?= htmlspecialchars($professeur['nom_classe']) ?></td>
                        <td><?= htmlspecialchars($professeur['nom_matiere']) ?></td>
                        <td>
    <a href="modifier_professeur.php?id=<?= $professeur['id_professeur'] ?>" class="btn btn-warning">
        <i class="fas fa-edit"></i> Modifier
    </a>
    <a href="professeur.php?delete_id=<?= $professeur['id_professeur'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?')">
        <i class="fas fa-trash"></i> Supprimer
    </a>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toUpperCase();
        let rows = document.getElementById("professorTable").getElementsByTagName("tr");
        
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let found = false;
            for (let j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    if (cells[j].textContent.toUpperCase().includes(filter)) {
                        found = true;
                    }
                }
            }
            if (found) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
</script>

</body>
</html>
