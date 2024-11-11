<?php  
require_once 'config.php'; 

// Initialize variables to avoid undefined index notices
$matieres = []; 

// Add a subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nomMatiere = trim($_POST['nom_matiere']);
    
    // Validate that the subject name is not empty
    if (!empty($nomMatiere)) {
        // Check if the subject already exists
        $sqlCheck = "SELECT * FROM matiere WHERE nom_matiere = :nom_matiere";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['nom_matiere' => $nomMatiere]);
        
        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Cette matière existe déjà.');</script>";
        } else {
            // Add the subject
            $sql = "INSERT INTO matiere (nom_matiere) VALUES (:nom_matiere)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nom_matiere' => $nomMatiere]);
            echo "<script>alert('Matière ajoutée avec succès'); window.location.href='#top';</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs correctement.');</script>";
    }
}

// Update a subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $nomMatiere = trim($_POST['nom_matiere']);
    $idMatiere = $_POST['id_matiere'];
    
    if (!empty($nomMatiere)) {
        // Update the subject
        $sqlUpdate = "UPDATE matiere SET nom_matiere = :nom_matiere WHERE id_matiere = :id_matiere";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute(['nom_matiere' => $nomMatiere, 'id_matiere' => $idMatiere]);
        echo "<script>alert('Matière mise à jour avec succès'); window.location.href='#top';</script>";
    } else {
        echo "<script>alert('Veuillez remplir tous les champs correctement.');</script>";
    }
}

// Delete a subject
if (isset($_POST['delete_id'])) {
    $idMatiere = $_POST['delete_id']; // Get the subject ID from the POST data
    // Prepare and execute the delete query
    $sqlDelete = "DELETE FROM matiere WHERE id_matiere = :id_matiere";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute(['id_matiere' => $idMatiere]);
    echo "<script>alert('Matière supprimée avec succès'); window.location.href='';</script>"; // Reload the page after deletion
}

// Fetch subjects for display
$sql = "SELECT * FROM matiere";
$stmt = $pdo->query($sql);
$matieres = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matières</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #FFF8E1; }
        .custom-card { box-shadow: 0px 4px 8px rgba(255, 87, 34, 0.5); border-radius: 10px; padding: 20px; background-color: white; }
        .btn-custom:hover { background-color: #D84315; color:white }
        .btn-custom{ background-color: orange; color:white; }
        .btn-outline-primary:hover { background-color: antiquewhite; color: white; }
        .table th, .table td { text-align: center; }
        .search-container { margin-bottom: 20px; }
        .search-bar { width: 300px; border-radius: 20px; border: 1px solid #FF5722; padding: 8px 15px; }
        .search-bar:focus { border-color: #FF3D00; }
        th {
            background-color: #FF5722;
            color: white;
        }
        .table-bordered tbody tr:nth-child(even) {
            background-color: #FFE0B2;
        }
    </style>
</head>
<body id="top">
<div class="container mt-5">

    <!-- Back to Home Button (Moved to Top) -->
    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline btn-lg" style="border-radius: 20px; font-weight: bold; border-color: orangered; color: darkorange;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>

    <h2 class="text-center mb-4" style="color:#FF5722;">Gestion des Matières</h2>

    <!-- Search Bar -->
    <div class="search-container text-center">
        <input type="text" id="searchInput" class="search-bar" placeholder="Rechercher une matière...">
    </div>
    
    <!-- Add Subject Form -->
    <div class="custom-card mb-4">
        <form method="POST">
            <h5><i class="fas fa-plus-circle"></i> Ajouter une nouvelle matière</h5>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="nom_matiere" placeholder="Nom de la matière" required>
                </div>
                
                <div class="col">
                    <button type="submit" name="ajouter" class="btn btn-custom">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Subjects List -->
    <h5 class="mt-4"><i class="fas fa-list"></i> Liste des matières</h5>
    <div class="custom-card">
        <table class="table table-bordered" id="subjectsTable">
            <thead>
                <tr>
                    <th>Nom de la Matière</th>
                    <th>Actions</th> <!-- New Actions Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matieres as $matiere): ?>
                    <tr>
                        <td><?= htmlspecialchars($matiere['nom_matiere']) ?></td>
                        <td>
                            <!-- Modify Button -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $matiere['id_matiere'] ?>">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                             
                             <!-- Delete Button -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#deleteModal<?= $matiere['id_matiere'] ?>">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>

                            <!-- Modal for Deleting Subject -->
                            <div class="modal fade" id="deleteModal<?= $matiere['id_matiere'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer cette matière ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST">
                                                <input type="hidden" name="delete_id" value="<?= $matiere['id_matiere'] ?>">
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Editing Subject -->
                            <div class="modal fade" id="editModal<?= $matiere['id_matiere'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Modifier la matière</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="id_matiere" value="<?= $matiere['id_matiere'] ?>">
                                                <input type="text" class="form-control" name="nom_matiere" value="<?= htmlspecialchars($matiere['nom_matiere']) ?>" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="modifier" class="btn btn-primary">Modifier</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        </div>
                                            </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Search Functionality -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var rows = document.getElementById('subjectsTable').getElementsByTagName('tr');

        for (var i = 1; i < rows.length; i++) {
            var td = rows[i].getElementsByTagName('td')[0];
            if (td) {
                var textValue = td.textContent || td.innerText;
                rows[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }       
        }
    });
</script>

</body>
</html>
