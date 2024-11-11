<?php
require_once 'config.php'; 

// Initialiser les variables pour éviter les erreurs d'index non définis
$eleves = []; 

// Récupérer les classes pour les menus déroulants
$classes = [];
$sqlClasses = "SELECT * FROM classe";
$stmtClasses = $pdo->query($sqlClasses);
$classes = $stmtClasses->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nomEleve = trim($_POST['nom_eleve']);
    $prenomsEleve = trim($_POST['prenoms_eleve']);
    $idClasse = (int)$_POST['classe_eleve'];  
    $matriculeEleve = trim($_POST['matricule_eleve']);
    
    if (!empty($nomEleve) && !empty($prenomsEleve) && !empty($idClasse) && !empty($matriculeEleve)) {
        
        // Vérifier si le matricule existe dans la table utilisateur et si son rôle est 'Élève'
        $sqlCheckMatricule = "SELECT * FROM utilisateur WHERE matricule = :matricule AND role = 'Élève'";
        $stmtCheckMatricule = $pdo->prepare($sqlCheckMatricule);
        $stmtCheckMatricule->execute(['matricule' => $matriculeEleve]);
        
        if ($stmtCheckMatricule->rowCount() === 0) {
            // Si le matricule n'existe pas ou si son rôle n'est pas 'Élève'
            echo "<script>alert('Le matricule n\'existe pas ou n\'a pas le rôle d\'Élève.');</script>";
        } else {
            // Vérifier si le matricule existe déjà dans la table eleve
            $sqlCheckEleve = "SELECT * FROM eleve WHERE matricule_eleve = :matricule";
            $stmtCheckEleve = $pdo->prepare($sqlCheckEleve);
            $stmtCheckEleve->execute(['matricule' => $matriculeEleve]);
            
            if ($stmtCheckEleve->rowCount() > 0) {
                // Si le matricule existe déjà
                echo "<script>alert('Le matricule existe déjà dans la base de données.');</script>";
            } else {
                // Vérifier si l'élève est déjà dans cette classe
                $sqlCheckClasse = "SELECT * FROM eleve WHERE id_classe = :id_classe AND matricule_eleve = :matricule";
                $stmtCheckClasse = $pdo->prepare($sqlCheckClasse);
                $stmtCheckClasse->execute(['id_classe' => $idClasse, 'matricule' => $matriculeEleve]);
                
                if ($stmtCheckClasse->rowCount() > 0) {
                    // Si l'élève est déjà dans cette classe
                    echo "<script>alert('L\'élève appartient déjà à cette classe.');</script>";
                } else {
                    // Ajouter l'élève
                    $sql = "INSERT INTO eleve (nom_eleve, prenoms_eleve, id_classe, matricule_eleve) 
                            VALUES (:nom_eleve, :prenoms_eleve, :id_classe, :matricule_eleve)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'nom_eleve' => $nomEleve,
                        'prenoms_eleve' => $prenomsEleve,
                        'id_classe' => $idClasse,
                        'matricule_eleve' => $matriculeEleve
                    ]);
                    echo "<script>alert('Élève ajouté avec succès.');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $idEleve = (int)$_POST['id_eleve'];  // Assurez-vous que l'ID de l'élève est envoyé avec le formulaire
    $nomEleve = trim($_POST['nom_eleve']);
    $prenomsEleve = trim($_POST['prenoms_eleve']);
    $matriculeEleve = trim($_POST['matricule_eleve']);
    $idClasse = (int)$_POST['classe_eleve'];

    // Vérifier que tous les champs nécessaires sont remplis
    if (!empty($nomEleve) && !empty($prenomsEleve) && !empty($matriculeEleve) && !empty($idClasse)) {
        
        // Vérifier si le matricule existe dans la table utilisateur
        $sqlCheckMatriculeUtilisateur = "SELECT * FROM utilisateur WHERE matricule = :matricule";
        $stmtCheckMatriculeUtilisateur = $pdo->prepare($sqlCheckMatriculeUtilisateur);
        $stmtCheckMatriculeUtilisateur->execute(['matricule' => $matriculeEleve]);

        if ($stmtCheckMatriculeUtilisateur->rowCount() == 0) {
            // Si le matricule n'existe pas dans la table utilisateur
            echo "<script>alert('Le matricule n\'existe pas dans la table utilisateur.');</script>";
        } else {
            // Vérifier si le matricule existe déjà pour un autre élève
            $sqlCheckMatricule = "SELECT * FROM eleve WHERE matricule_eleve = :matricule AND id_eleve != :id_eleve";
            $stmtCheckMatricule = $pdo->prepare($sqlCheckMatricule);
            $stmtCheckMatricule->execute(['matricule' => $matriculeEleve, 'id_eleve' => $idEleve]);

            if ($stmtCheckMatricule->rowCount() > 0) {
                // Si le matricule existe déjà pour un autre élève
                echo "<script>alert('Le matricule existe déjà pour un autre élève.');</script>";
            } else {
                // Vérifier si la classe existe
                $sqlCheckClasse = "SELECT COUNT(*) FROM classe WHERE id_classe = :id_classe";
                $stmtCheckClasse = $pdo->prepare($sqlCheckClasse);
                $stmtCheckClasse->execute(['id_classe' => $idClasse]);
                $classeExists = $stmtCheckClasse->fetchColumn();

                if ($classeExists) {
                    // Si la classe existe, mettre à jour les informations de l'élève
                    $sqlUpdateEleve = "UPDATE eleve SET nom_eleve = :nom_eleve, prenoms_eleve = :prenoms_eleve, matricule_eleve = :matricule_eleve, id_classe = :id_classe WHERE id_eleve = :id_eleve";
                    $stmtUpdateEleve = $pdo->prepare($sqlUpdateEleve);
                    $stmtUpdateEleve->execute([
                        'nom_eleve' => $nomEleve,
                        'prenoms_eleve' => $prenomsEleve,
                        'matricule_eleve' => $matriculeEleve,
                        'id_classe' => $idClasse,
                        'id_eleve' => $idEleve
                    ]);
                    echo "<script>alert('Les informations de l\'élève ont été mises à jour avec succès.'); window.location.href='';</script>";
                } else {
                    echo "<script>alert('La classe sélectionnée est invalide.');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.');</script>";
    }
}



// Suppression d'un élève
if (isset($_POST['delete_id'])) {
    $idEleve = $_POST['delete_id']; 
    // Préparer et exécuter la requête de suppression
    $sqlDelete = "DELETE FROM eleve WHERE id_eleve = :id_eleve";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute(['id_eleve' => $idEleve]);
    echo "<script>alert('Élève supprimé avec succès'); window.location.href='';</script>";
}

$sql = "SELECT e.*, c.nom_classe 
        FROM eleve e 
        JOIN classe c ON e.id_classe = c.id_classe";
$stmt = $pdo->query($sql);
$eleves = $stmt->fetchAll();
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Élèves</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #FFF8E1; }
        .custom-card { box-shadow: 0px 4px 8px rgba(139, 69, 19, 0.5); border-radius: 10px; padding: 20px; background-color: white; }
        .btn-custom:hover { background-color: #8B4513; color:white }
        .btn-custom{ background-color: #A52A2A; color:white; }
        .btn-outline-primary:hover { background-color: antiquewhite; color: white; }
        .table th, .table td { text-align: center; }
        .search-container { margin-bottom: 20px; }
        .search-bar { width: 300px; border-radius: 20px; border: 1px solid #8B4513; padding: 8px 15px; }
        .search-bar:focus { border-color: #8B3D14; }
        th {
            background-color: #8B4513;
            color: white;
        }
        .table-bordered tbody tr:nth-child(even) {
            background-color: #F4E1D2;
        }

        @media (max-width: 510px){
        #table {
                width: 500px;
            }
              #table button{ height:30px;
            width:30px }

        }
    </style>
</head>
<body id="top">
<div class="container mt-5">

    <!-- Back to Home Button -->
    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline btn-lg" style="border-radius: 20px; font-weight: bold; border-color: #8B4513; color: #A52A2A;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>

    <h2 class="text-center mb-4" style="color:#8B4513;">Gestion des Élèves</h2>

    <!-- Search Bar -->
    <div class="search-container text-center">
        <input type="text" id="searchInput" class="search-bar" placeholder="Rechercher un élève...">
    </div>
    
    <!-- Add Student Form -->
    <div class="custom-card mb-4">
        <form method="POST">
            <h5><i class="fas fa-plus-circle"></i> Ajouter un nouvel élève</h5>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="nom_eleve" placeholder="Nom" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="prenoms_eleve" placeholder="Prénoms" required>
                </div>
            </div>
            <div class="form-row mt-3">
                  <div class="col-md-4">
                    <select class="form-control" name="classe_eleve" required>
                        <option value="">Sélectionnez une classe</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= htmlspecialchars($classe['id_classe']) ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="matricule_eleve" placeholder="Matricule" required>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" name="ajouter" class="btn btn-custom">Ajouter</button>
            </div>
        </form>
    </div>
    
    <!-- Students List -->
    <h5 class="mt-4"><i class="fas fa-list"></i> Liste des élèves</h5>
    <div class="custom-card"id="table">
        <table class="table table-border" id="studentsTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénoms</th>
                    <th>Classe</th>
                    <th>Matricule</th>
                    <th>Actions</th> <!-- New Actions Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eleves as $eleve): ?>
                    <tr>
                        <td><?= htmlspecialchars($eleve['nom_eleve']) ?></td>
                        <td><?= htmlspecialchars($eleve['prenoms_eleve']) ?></td>
                        <td><?= htmlspecialchars($eleve['nom_classe']) ?></td>
                        <td><?= htmlspecialchars($eleve['matricule_eleve']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $eleve['id_eleve'] ?>"><i class="fas fa-edit"></i> Modifier</button>
                            <!-- Delete Button -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $eleve['id_eleve'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Student Modal -->
    <?php foreach ($eleves as $eleve): ?>
    <div class="modal fade" id="editModal<?= $eleve['id_eleve'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifier l'élève</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_eleve" value="<?= $eleve['id_eleve'] ?>">
                        <div class="form-group">
                            <label for="nom_eleve">Nom</label>
                            <input type="text" class="form-control" name="nom_eleve" value="<?= htmlspecialchars($eleve['nom_eleve']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="prenoms_eleve">Prénoms</label>
                            <input type="text" class="form-control" name="prenoms_eleve" value="<?= htmlspecialchars($eleve['prenoms_eleve']) ?>" required>
                        </div>
                        <div class="col">
                <!-- Classe dropdown -->
                <select class="form-control" name="classe_eleve" required>
                    <option value="">Sélectionner une classe</option>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?= $classe['id_classe'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                        <div class="form-group">
                            <label for="matricule_eleve">Matricule</label>
                            <input type="text" class="form-control" name="matricule_eleve" value="<?= htmlspecialchars($eleve['matricule_eleve']) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="modifier" class="btn btn-custom">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Search Functionality
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toUpperCase();
    let rows = document.getElementById('studentsTable').getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let found = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                if (cells[j].innerText.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        rows[i].style.display = found ? "" : "none";
    }
});


</script>
</body>
</html>
