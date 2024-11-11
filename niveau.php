<?php require_once 'config.php'; 

// Initialise les variables pour éviter les notifications d'index non définies
$niveauTrouve = null;
$niveaux = []; 

// Ajouter un niveau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nomNiveau = trim($_POST['nom_niveau']);
    if (!empty($nomNiveau)) {
        // Vérifiez si le niveau existe déjà
        $sqlCheck = "SELECT * FROM niveau WHERE nom_niveau = :nom_niveau";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['nom_niveau' => $nomNiveau]);
        
        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Ce niveau existe déjà.');</script>";
        } else {
            // Ajouter le niveau
            $sql = "INSERT INTO niveau (nom_niveau) VALUES (:nom_niveau)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nom_niveau' => $nomNiveau]);
            echo "<script>window.location.href='#top';</script>"; // Redirige vers le haut de la page
        }
    }
} 

// Recherche de niveau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechercher'])) {
    $nomNiveauRecherche = trim($_POST['nom_niveau_recherche']);
    if (!empty($nomNiveauRecherche)) {
        // Valider l'entrée
        $sql = "SELECT * FROM niveau WHERE nom_niveau = :nom_niveau";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom_niveau' => $nomNiveauRecherche]);
        $niveauTrouve = $stmt->fetch();
        echo "<script>window.location.href='#top';</script>"; // Redirige vers le haut de la page
    }
}

// Suppression de niveau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $nomSupprimer = trim($_POST['ancien_nom_niveau']);
    if (!empty($nomSupprimer)) {
        // Valider l'entrée
        $sql = "DELETE FROM niveau WHERE nom_niveau = :nom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nom' => $nomSupprimer]);
        echo "<script>window.location.href='#tableNiveaux';</script>"; // Reste sur le tableau
    }
}

// Modification d'un niveau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $nouveauNomNiveau = trim($_POST['nouveau_nom_niveau']);
    $ancienNomNiveau = trim($_POST['ancien_nom_niveau']);
    if (!empty($nouveauNomNiveau) && !empty($ancienNomNiveau)) {
        // Vérifiez si le nouveau nom existe déjà (et n'est pas l'ancien nom)
        $sqlCheck = "SELECT * FROM niveau WHERE nom_niveau = :nouveau_nom_niveau AND nom_niveau != :ancien_nom_niveau";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['nouveau_nom_niveau' => $nouveauNomNiveau, 'ancien_nom_niveau' => $ancienNomNiveau]);
        
        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Ce niveau existe déjà.');</script>";
        } else {
            // Modifier le niveau
            $sql = "UPDATE niveau SET nom_niveau = :nouveau_nom_niveau WHERE nom_niveau = :ancien_nom_niveau";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nouveau_nom_niveau' => $nouveauNomNiveau, 'ancien_nom_niveau' => $ancienNomNiveau]);
            echo "<script>window.location.href='#tableNiveaux';</script>"; // Reste sur le tableau
        }
    }
}

// Récupération des niveaux pour l'affichage
$sql = "SELECT * FROM niveau";
$stmt = $pdo->query($sql);
$niveaux = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des niveaux</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .custom-card {
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.5);
            border-radius: 10px;
            padding: 20px;
            background-color: white;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
        }
        .btn-custom:hover {
            background-color: darkblue;
        }
        .btn-outline-primary {
            transition: background-color 0.3s, color 0.3s;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body id="top">
<div class="container mt-5">
    <!-- Bouton Accueil -->
    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline btn-lg" style="border-radius: 20px; font-weight: bold;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
    <h2 class="text-center text-primary mb-4">Gestion des Niveaux</h2>
    
    <!-- Formulaire de recherche de niveau -->
    <form method="POST" class="mb-4">
        <h5><i class="fas fa-search"></i> Recherche de niveau</h5>
        <div class="input-group">
            <input type="text" class="form-control" name="nom_niveau_recherche" placeholder="Nom du niveau" required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="rechercher">Rechercher</button>
            </div>
        </div>
    </form>
    
    <!-- Résultat de la recherche -->
    <?php if ($niveauTrouve): ?>
        <h5><i class="fas fa-info-circle"></i> Résultat de la recherche</h5>
        <p>Nom du Niveau : <?= htmlspecialchars($niveauTrouve['nom_niveau']) ?></p>
    <?php elseif (isset($_POST['rechercher'])): ?>
        <p class="text-danger">Aucun niveau trouvé avec ce nom.</p>
    <?php endif; ?>
    
    <!-- Formulaire d'ajout de niveau -->
    <div class="custom-card mb-4">
        <form method="POST">
            <h5><i class="fas fa-plus-circle"></i> Ajouter un nouveau niveau</h5>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="nom_niveau" placeholder="Nom du niveau" required>
                </div>
                <div class="col">
                    <button type="submit" name="ajouter" class="btn btn-custom">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Liste des niveaux -->
    <h5 class="mt-4"><i class="fas fa-list"></i> Liste des niveaux</h5>
    <div class="custom-card" id="tableNiveaux">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nom du Niveau</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($niveaux as $niveau): ?>
                    <tr>
                        <td><?= htmlspecialchars($niveau['nom_niveau']) ?></td>
                        <td>
                            <!-- Formulaire de modification du niveau -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="ancien_nom_niveau" value="<?= htmlspecialchars($niveau['nom_niveau']) ?>">
                                <input type="text" name="nouveau_nom_niveau" placeholder="Nouveau nom" required>
                                <button type="submit" name="modifier" class="btn btn-warning btn-sm">Modifier</button>
                            </form>
                            <!-- Formulaire séparé pour la suppression du niveau -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="ancien_nom_niveau" value="<?= htmlspecialchars($niveau['nom_niveau']) ?>">
                                <button type="submit" name="supprimer" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
