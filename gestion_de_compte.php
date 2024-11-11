<?php 
require_once 'config.php'; 
session_start(); // Start session for CSRF token handling
$messageErreur = ""; 

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Création d'un compte 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['creer'])) { 
    // CSRF Protection
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $email = $_POST['email']; 
    $matricule = $_POST['matricule']; 
    $mot_de_passe = $_POST['mot_de_passe']; 
    $role = $_POST['role']; 
    try { 
        $sql = "INSERT INTO utilisateur (email, matricule, mot_de_passe, role) VALUES (:email, :matricule, :mot_de_passe, :role)"; 
        $stmt = $pdo->prepare($sql); 
        $stmt->execute(['email' => $email, 'matricule' => $matricule, 'mot_de_passe' => $mot_de_passe, 'role' => $role]); 
    } catch (PDOException $e) { 
        if ($e->getCode() == 23000) { 
            $messageErreur = "Erreur : Le matricule ou l'email est déjà attribué."; 
        } else { 
            $messageErreur = "Erreur : Impossible d'ajouter l'utilisateur."; 
        } 
    } 
} 

// Recherche de compte par matricule 
$utilisateurTrouve = null; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechercher'])) { 
    $matriculeRecherche = $_POST['matriculeRecherche']; 
    $sql = "SELECT * FROM utilisateur WHERE matricule = :matricule"; 
    $stmt = $pdo->prepare($sql); 
    $stmt->execute(['matricule' => $matriculeRecherche]); 
    $utilisateurTrouve = $stmt->fetch(); 
} 

// Suppression de compte 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) { 
    $matriculeSupprimer = $_POST['matricule']; 
    $sql = "DELETE FROM utilisateur WHERE matricule = :matricule"; 
    $stmt = $pdo->prepare($sql); 
    $stmt->execute(['matricule' => $matriculeSupprimer]); 
    header("Location: " . $_SERVER['PHP_SELF'] . "#table"); 
    exit; 
} 

// Modification d'un compte 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) { 
    // CSRF Protection
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $matriculeActuel = $_POST['matriculeActuel']; 
    $nouveauMatricule = $_POST['matricule']; 
    $email = $_POST['email']; 
    $mot_de_passe = $_POST['mot_de_passe'] ? ($_POST['mot_de_passe']) : null; 
    $role = $_POST['role']; 

    try { 
        $sql = "UPDATE utilisateur SET matricule = :nouveauMatricule, email = :email, role = :role" . ($mot_de_passe ? ", mot_de_passe = :mot_de_passe" : "") . " WHERE matricule = :matriculeActuel"; 
        $stmt = $pdo->prepare($sql); 
        $params = ['nouveauMatricule' => $nouveauMatricule, 'email' => $email, 'matriculeActuel' => $matriculeActuel, 'role' => $role]; 
        if ($mot_de_passe) { 
            $params['mot_de_passe'] = $mot_de_passe; 
        } 
        $stmt->execute($params); 
    } catch (PDOException $e) { 
        $messageErreur = "Erreur : Impossible de mettre à jour l'utilisateur."; 
    }
}

// Récupération des utilisateurs pour l'affichage 
$sql = "SELECT * FROM utilisateur"; 
$stmt = $pdo->query($sql); 
$utilisateurs = $stmt->fetchAll(); 
?> 

<!DOCTYPE html> 
<html lang="fr"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Gestion des Comptes Utilisateurs</title> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <style> 
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; } 
        .custom-card, .form-create { box-shadow: 0px 4px 8px rgba(255, 0, 0, 0.5); border-radius: 10px; padding: 20px; background-color: white; margin-bottom: 20px; } 
        .btn-custom { background-color: #ff0000; color: white; border: none; border-radius: 20px; } 
        .btn-custom:hover { background-color: darkred; } 
        @media (max-width: 768px) { .form-create, .custom-card { padding: 15px; } .table-responsive { overflow-x: auto; } } 
    </style> 
</head> 
<body> 

    <div class="text-center mb-4">
        <a href="dashboardadmin.php" class="btn btn-outline-danger btn-lg" style="border-radius: 20px; font-weight: bold;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>

<div class="container mt-5"> 
    <h2 class="text-center text-danger mb-4">Gestion des Comptes Utilisateurs</h2> 

    <!-- Barre de recherche de compte par matricule --> 
    <form method="POST" class="mb-4 form-create"> 
        <h5><i class="fas fa-search"></i> Recherche de compte par matricule</h5> 
        <div class="input-group"> 
            <input type="text" class="form-control" name="matriculeRecherche" placeholder="Matricule" required> 
            <div class="input-group-append"> 
                <button class="btn btn-danger btn-custom" type="submit" name="rechercher">Rechercher</button> 
            </div> 
        </div> 
    </form> 

    <!-- Résultat de la recherche --> 
    <?php if ($utilisateurTrouve): ?> 
        <h5><i class="fas fa-user"></i> Résultat de la recherche</h5> 
        <p>Matricule : <?= htmlspecialchars($utilisateurTrouve['matricule']) ?></p> 
        <p>Email : <?= htmlspecialchars($utilisateurTrouve['email']) ?></p> 
        <p>Rôle : <?= htmlspecialchars($utilisateurTrouve['role']) ?></p> 
        <p>Mot de Passe : <?= htmlspecialchars($utilisateurTrouve['mot_de_passe']) ?></p> 

    <?php elseif (isset($_POST['rechercher'])): ?> 
        <p class="text-danger">Aucun utilisateur trouvé avec ce matricule.</p> 
    <?php endif; ?> 

    <!-- Formulaire de création de compte --> 
    <div class="custom-card" id="creation-form"> 
        <form method="POST" class="form-create"> 
            <h5><i class="fas fa-user-plus"></i> Créer un compte utilisateur</h5> 
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"> <!-- CSRF Token -->
            <div class="form-group"> 
                <input type="text" class="form-control" name="matricule" placeholder="Matricule" required> 
            </div> 
            <div class="form-group"> 
                <input type="email" class="form-control" name="email" placeholder="Email" required> 
            </div> 
            <div class="form-group"> 
                <select class="form-control" name="role"> 
                    <option>Professeur</option> 
                    <option>Élève</option> 
                </select> 
            </div> 
            <div class="form-group"> 
                <input type="password" class="form-control" name="mot_de_passe" placeholder="Mot de passe" required> 
            </div> 
            <button type="submit" class="btn btn-danger btn-custom" name="creer">Créer un compte</button> 
            <p class="text-danger"><?= htmlspecialchars($messageErreur) ?></p> 
        </form> 
    </div> 

    <!-- Tableau des utilisateurs --> 
    <h4 class="mt-5 mb-4">Liste des Utilisateurs</h4> 
    <div class="table-responsive"> 
        <table class="table table-bordered table-hover"> 
            <thead class="thead-light"> 
                <tr> 
                    <th>Matricule</th> 
                    <th>Email</th> 
                    <th>Rôle</th> 
                    <th>Mot de Passe</th>
                    <th>Actions</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php foreach ($utilisateurs as $utilisateur): ?> 
                    <tr> 
                        <td><?= htmlspecialchars($utilisateur['matricule']) ?></td> 
                        <td><?= htmlspecialchars($utilisateur['email']) ?></td> 
                        <td><?= htmlspecialchars($utilisateur['role']) ?></td> 
                        <td><?= htmlspecialchars($utilisateur['mot_de_passe']) ?></td> 

                        <td> 
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalUpdate<?= htmlspecialchars($utilisateur['matricule']) ?>">Modifier</button> 
                            <form method="POST" class="d-inline"> 
                                <input type="hidden" name="matricule" value="<?= htmlspecialchars($utilisateur['matricule']) ?>"> 
                                <button type="submit" class="btn btn-danger" name="supprimer">Supprimer</button> 
                            </form> 
                        </td> 
                    </tr> 

                    <!-- Modal pour modifier un utilisateur --> 
                    <div class="modal fade" id="modalUpdate<?= htmlspecialchars($utilisateur['matricule']) ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"> 
                        <div class="modal-dialog" role="document"> 
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <h5 class="modal-title" id="modalLabel">Modifier l'utilisateur</h5> 
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                                        <span aria-hidden="true">&times;</span> 
                                    </button> 
                                </div> 
                                <div class="modal-body"> 
                                    <form method="POST"> 
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"> <!-- CSRF Token -->
                                        <input type="hidden" name="matriculeActuel" value="<?= htmlspecialchars($utilisateur['matricule']) ?>"> 
                                        <div class="form-group"> 
                                            <input type="text" class="form-control" name="matricule" placeholder="Matricule" value="<?= htmlspecialchars($utilisateur['matricule']) ?>" required> 
                                        </div> 
                                        <div class="form-group"> 
                                            <input type="email" class="form-control" name="email" placeholder="Email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required> 
                                        </div> 
                                        <div class="form-group"> 
                                            <select class="form-control" name="role"> 
                                                <option <?= ($utilisateur['role'] === 'Administrateur') ? 'selected' : '' ?>>Administrateur</option> 
                                                <option <?= ($utilisateur['role'] === 'Professeur') ? 'selected' : '' ?>>Professeur</option> 
                                                <option <?= ($utilisateur['role'] === 'Élève') ? 'selected' : '' ?>>Élève</option> 
                                            </select> 
                                        </div> 
                                        <div class="form-group"> 
                                            <input type="password" class="form-control" name="mot_de_passe" placeholder="Nouveau mot de passe (laisser vide pour garder l'ancien)"> 
                                        </div> 
                                        <button type="submit" class="btn btn-warning" name="update">Mettre à jour</button> 
                                    </form> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                <?php endforeach; ?> 
            </tbody> 
        </table> 
    </div> 
</div> 

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
</body> 
</html>