<?php  
require_once 'config.php'; 

// Initialize variables to avoid undefined index notices
$classeTrouvee = null;
$classes = []; 

// Add a class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nomClasse = trim($_POST['nom_classe']);
 
    if (!empty($nomClasse)) {
        // Check if the class already exists
        $sqlCheck = "SELECT * FROM classe WHERE nom_classe = :nom_classe";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['nom_classe' => $nomClasse]);
        
        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Cette classe existe déjà.');</script>";
        } else {
            // Add the class
            $sql = "INSERT INTO classe (nom_classe) VALUES (:nom_classe)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nom_classe' => $nomClasse]);
            echo "<script>alert('Classe ajoutée avec succès'); window.location.href='#top';</script>";
        }
    }
} 

// Search for a class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechercher'])) {
    $nomClasseRecherche = trim($_POST['nom_classe_recherche']);
    if (!empty($nomClasseRecherche)) {
        $sql = "SELECT * FROM classe WHERE nom_classe = :nom_classe";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom_classe' => $nomClasseRecherche]);
        $classeTrouvee = $stmt->fetch();
        echo "<script>window.location.href='#top';</script>";
    }
}

// Delete a class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $nomSupprimer = trim($_POST['ancien_nom_classe']);
    if (!empty($nomSupprimer)) {
        $sql = "DELETE FROM classe WHERE nom_classe = :nom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nomSupprimer]);
        echo "<script>alert('Classe supprimée avec succès'); window.location.href='#tableClasses';</script>";
    }
}

// Update a class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $nouveauNomClasse = trim($_POST['nouveau_nom_classe']);
    $ancienNomClasse = trim($_POST['ancien_nom_classe']);
    
    if (!empty($nouveauNomClasse) && !empty($ancienNomClasse)) {
        $sqlCheck = "SELECT * FROM classe WHERE nom_classe = :nouveau_nom_classe AND nom_classe != :ancien_nom_classe";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['nouveau_nom_classe' => $nouveauNomClasse, 'ancien_nom_classe' => $ancienNomClasse]);
        
        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Cette classe existe déjà.');</script>";
        } else {
            $sql = "UPDATE classe SET nom_classe = :nouveau_nom_classe WHERE nom_classe = :ancien_nom_classe";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nouveau_nom_classe' => $nouveauNomClasse, 'ancien_nom_classe' => $ancienNomClasse]);
            echo "<script>alert('Classe modifiée avec succès'); window.location.href='#tableClasses';</script>";
        }
    }
}

// Fetch classes for display
$sql = "SELECT * FROM classe";
$stmt = $pdo->query($sql);
$classes = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Classes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .custom-card { box-shadow: 0px 4px 8px rgba(128, 0, 128, 0.5); border-radius: 10px; padding: 20px; background-color: white; }
        .btn-custom { background-color: #800080; color: white; border-radius: 20px; }
        .btn-custom:hover { background-color: #5e005e; }
        .btn-outline-primary:hover { background-color: blueviolet; color:white; }
    </style>
</head>
<body id="top">
<div class="container mt-5">
    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline- btn-lg" style="border-radius: 20px; font-weight: bold;  color: mediumvioletred; border-color: violet;">
            <i class="fas fa-home" style="color:mediumvioletred;"></i> Retour à l'accueil
        </a>
    </div>
    <h2 class="text-center mb-4" style="color:mediumvioletred;">Gestion des Classes</h2>
    
    <!-- Search Class Form -->
    <form method="POST" class="mb-4">
        <h5><i class="fas fa-search"></i> Recherche de classe</h5>
        <div class="input-group">
            <input type="text" class="form-control" name="nom_classe_recherche" placeholder="Nom de la classe" required>
            <div class="input-group-append">
                <button type="submit" name="rechercher" style="background-color:darkmagenta; color:white; border-color:violet;">Rechercher</button>
            </div>
        </div>
    </form>
    
    <!-- Search Result -->
    <?php if ($classeTrouvee): ?>
        <h5><i class="fas fa-info-circle"></i> Résultat de la recherche</h5>
        <p>Nom de la Classe : <?= htmlspecialchars($classeTrouvee['nom_classe']) ?></p>
    <?php elseif (isset($_POST['rechercher'])): ?>
        <p class="text-danger">Aucune classe trouvée avec ce nom.</p>
    <?php endif; ?>
    
    <!-- Add Class Form -->
    <div class="custom-card mb-4">
        <form method="POST">
            <h5><i class="fas fa-plus-circle"></i> Ajouter une nouvelle classe</h5>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="nom_classe" placeholder="Nom de la classe" required>
                </div>
               <div class="col">
                    <button type="submit" name="ajouter" class="btn btn-custom">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Classes List -->
    <h5 class="mt-4"><i class="fas fa-list"></i> Liste des classes</h5>
    <div class="custom-card" id="tableClasses">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nom de la Classe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $classe): ?>
                    <tr>
                        <td><?= htmlspecialchars($classe['nom_classe']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="ancien_nom_classe" value="<?= htmlspecialchars($classe['nom_classe']) ?>">
                                <button type="submit" name="supprimer" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="ancien_nom_classe" value="<?= htmlspecialchars($classe['nom_classe']) ?>">
                                <input type="text" name="nouveau_nom_classe" placeholder="Nouveau nom" required>
                                <button type="submit" name="modifier" class="btn btn-warning btn-sm">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
