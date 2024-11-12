<?php
require_once 'config.php';

// Vérifier si un ID de professeur est passé dans l'URL
if (isset($_GET['id'])) {
    $professeurId = $_GET['id'];

    // Récupérer les informations du professeur
    $sqlProfesseur = "SELECT * FROM professeur WHERE id_professeur = :id_professeur";
    $stmtProfesseur = $pdo->prepare($sqlProfesseur);
    $stmtProfesseur->execute(['id_professeur' => $professeurId]);
    $professeur = $stmtProfesseur->fetch();

    // Si le professeur n'existe pas
    if (!$professeur) {
        echo "<script>alert('Professeur introuvable'); window.location.href='professeur.php';</script>";
        exit;
    }
}

// Récupérer les classes et matières pour les options de sélection
$classes = [];
$sqlClasses = "SELECT * FROM classe";
$stmtClasses = $pdo->query($sqlClasses);
$classes = $stmtClasses->fetchAll();

$matieres = [];
$sqlMatiere = "SELECT * FROM matiere";
$stmtMatiere = $pdo->query($sqlMatiere);
$matieres = $stmtMatiere->fetchAll();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $nomProf = trim($_POST['nom_prof']);
    $prenomProf = trim($_POST['prenom_prof']);
    $matriculeProf = trim($_POST['matricule_prof']);
    $sexeProf = $_POST['sexe_prof'];
    $classeAttribut = $_POST['classe_attribuee'];
    $matiereEnseignee = $_POST['matiere_enseignee'];

    if (!empty($nomProf) && !empty($prenomProf) && !empty($matriculeProf) && !empty($sexeProf) && !empty($classeAttribut) && !empty($matiereEnseignee)) {
        
        // Vérifier si le matricule existe dans la table utilisateur et si son rôle est 'Professeur'
        $sqlCheckMatricule = "SELECT * FROM utilisateur WHERE matricule = :matricule AND role = 'Professeur'";
        $stmtCheckMatricule = $pdo->prepare($sqlCheckMatricule);
        $stmtCheckMatricule->execute(['matricule' => $matriculeProf]);
        
        if ($stmtCheckMatricule->rowCount() === 0) {
            echo "<script>alert('Le matricule n\'existe pas ou n\'a pas le rôle de Professeur.');</script>";
        } else {
            // Vérifier si le matricule existe déjà dans la table professeur (à part pour le professeur actuel)
           
            
                // Vérifier si le professeur est déjà attribué à cette classe et matière
                $sqlCheckAttribution = "SELECT * FROM professeur WHERE classe_attribuee = :classe_attribuee AND matiere_enseignee = :matiere_enseignee AND id_professeur != :id_professeur";
                $stmtCheckAttribution = $pdo->prepare($sqlCheckAttribution);
                $stmtCheckAttribution->execute([
                    'classe_attribuee' => $classeAttribut,
                    'matiere_enseignee' => $matiereEnseignee,
                    'id_professeur' => $professeurId
                ]);
                
                if ($stmtCheckAttribution->rowCount() > 0) {
                    echo "<script>alert('Le professeur est déjà attribué à cette classe et matière.');</script>";
                } else {
                    // Mettre à jour les informations du professeur
                    $sqlUpdateProf = "UPDATE professeur SET nom_professeur = :nom_prof, prenoms_professeur = :prenom_prof, matricule_professeur = :matricule_prof, sexe_prof = :sexe_prof, classe_attribuee = :classe_attribuee, matiere_enseignee = :matiere_enseignee WHERE id_professeur = :id_professeur";
                    $stmtUpdateProf = $pdo->prepare($sqlUpdateProf);
                    $stmtUpdateProf->execute([
                        'nom_prof' => $nomProf,
                        'prenom_prof' => $prenomProf,
                        'matricule_prof' => $matriculeProf,
                        'sexe_prof' => $sexeProf,
                        'classe_attribuee' => $classeAttribut,
                        'matiere_enseignee' => $matiereEnseignee,
                        'id_professeur' => $professeurId
                    ]);
                    
                    echo "<script>alert('Professeur modifié avec succès.'); window.location.href='professeur.php';</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.');</script>";
    }


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Professeur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <a href="professeur.php" class="btn btn-outline btn-lg" style="border-radius: 20px; font-weight: bold; border-color: green; color: olive;">
            <i class="fas fa-arrow-left"></i> Retour à la liste des professeurs
        </a>
    </div>

    <h2 class="text-center mb-4" style="color:#4CAF50;">Modifier un Professeur</h2>

    <div class="custom-card">
        <form method="POST">
            <div class="form-row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nom_prof" value="<?= htmlspecialchars($professeur['nom_professeur']) ?>" placeholder="Nom du professeur" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prenom_prof" value="<?= htmlspecialchars($professeur['prenoms_professeur']) ?>" placeholder="Prénom du professeur" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="matricule_prof" value="<?= htmlspecialchars($professeur['matricule_professeur']) ?>" placeholder="Matricule" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <select class="form-control" name="sexe_prof" required>
                        <option value="Femme" <?= $professeur['sexe_prof'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
                        <option value="Homme" <?= $professeur['sexe_prof'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="classe_attribuee" required>
                        <option value="">Sélectionnez une classe</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= htmlspecialchars($classe['id_classe']) ?>" <?= $professeur['classe_attribuee'] == $classe['id_classe'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($classe['nom_classe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="matiere_enseignee" required>
                        <option value="">Sélectionnez une matière</option>
                        <?php foreach ($matieres as $matiere): ?>
                            <option value="<?= htmlspecialchars($matiere['id_matiere']) ?>" <?= $professeur['matiere_enseignee'] == $matiere['id_matiere'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($matiere['nom_matiere']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
<br>
            <button type="submit" name="modifier" class="btn btn-custom mt-3" style="color:white; background-color: green; width: 1000px;height: 40px;"><center> Modifier </center></button>
        </form>
    </div>
</div>
</body>
</html>
