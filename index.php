<?php
require_once 'config.php'; // Connexion à la base de données


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Protéger contre les injections SQL avec des requêtes préparées PDO
    $sql = "SELECT * FROM utilisateur WHERE matricule = :matricule AND mot_de_passe = :mot_de_passe AND role = :role";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Démarrer une nouvelle session
        session_start();
        session_unset();
        session_destroy();

        session_start();
        session_regenerate_id(true); // Crée un nouvel ID de session

        $_SESSION['matricule'] = $matricule;
        $_SESSION['role'] = $role;

        // Récupérer le prénom en fonction du rôle
        if ($role == 'Professeur') {
            // Récupérer le prénom du professeur
            $sql_prenom = "SELECT prenoms_professeur FROM professeur WHERE matricule_professeur = :matricule";
            $stmt_prenom = $pdo->prepare($sql_prenom);
            $stmt_prenom->bindParam(':matricule', $matricule);
            $stmt_prenom->execute();
            $professeur = $stmt_prenom->fetch(PDO::FETCH_ASSOC);
            
            if ($professeur) {
                $_SESSION['prenom'] = $professeur['prenoms_professeur'];
            } else {
                $_SESSION['prenom'] = "Professeur introuvable"; // Si le professeur n'est pas trouvé
            }
        } elseif ($role == 'Élève') {
            // Récupérer le prénom de l'élève
            $sql_prenom = "SELECT prenoms_eleve FROM eleve WHERE matricule_eleve = :matricule";
            $stmt_prenom = $pdo->prepare($sql_prenom);
            $stmt_prenom->bindParam(':matricule', $matricule);
            $stmt_prenom->execute();
            $eleve = $stmt_prenom->fetch(PDO::FETCH_ASSOC);
            
            if ($eleve) {
                $_SESSION['prenom'] = $eleve['prenoms_eleve'];
            } else {
                $_SESSION['prenom'] = "Élève introuvable"; // Si l'élève n'est pas trouvé
            }
        }

        // Rediriger selon le rôle
        if ($role == 'Administrateur') {
            header("Location: dashboardadmin.php");
        } elseif ($role == 'Professeur') {
            header("Location: dashboardprofesseur.php");
        } elseif ($role == 'Élève') {
            header("Location: dashboardeleve.php");
        }
        exit();
    } else {
        $error = "Matricule, mot de passe ou rôle incorrect.";
    }
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
     <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <style>
        body {
            background-color:  ;
            background-image: url('images/Allura - Freelancing (4).png');
            background-repeat: no-repeat; 
            font-family: 'Roboto', sans-serif;

        }

        p {
            color: orangered; 
            font-size: 14px;
            font-family: 'calibri';
        }

        .typewriter  {
            overflow: hidden; 
            border-right: .15em solid #FF0000; 
            white-space: nowrap; 
            margin: 0 auto; 
            letter-spacing: .15em; 
            animation: typing 3.5s steps(30, end), blink-caret .75s step-end infinite; 
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: transparent; }
        }

       .login-container {
            background-color: white; 
            color: #FF0000;
            width: 100%;
            max-width: 310px;
            max-height: 390px;
            padding: 0px; 
            box-shadow: 0px 0px 15px 5px rgba(255, 0, 0, 0.6), 
                        0px 4px 8px rgba(0, 123, 255, 0.5); 
            transition: opacity 0.5s ease, transform 1s ease;
            opacity: 2; 
            transform: translateY(-40px); /* Commence un peu plus haut */
            animation: slideInZoom 1s ease-out forwards;
            margin-right: 20px;
                       }


@keyframes slideInZoom {
    0% {
        opacity: 0;
        transform: translateY(-30px) scale(0.7); /* Démarre en bas avec un petit zoom */
    }
    50% {
        opacity: 0.8;
        transform: translateY(9px) scale(1); /* Le conteneur prend un peu plus de taille */
    }
    100% {
        opacity: 1;
        transform: translateY(4px) scale(1); /* Atteint sa position finale avec une taille normale */
    }
}


        .btn-primary {
            background-color: #FF0000; 
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            width: 150px;
            border-radius: 20px;
        }

        h2 {
            color: #FF0000;
            padding-top: 15px;
        }

        .fade-in {
            opacity: 2;
            transform: translateX(0);
        }

        .custom-line {
            border: none; 
            height: 2px; 
            background-color: darkblue; 
            width: 40%; 
        }

        .animated-banner {
            opacity: 0;
            animation: slideInZoom 2s ease forwards;
        }

       
        .btn-primary:hover {
            background-color: #ff4500; 
            color: white;
            transition: background-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff4500;
            box-shadow: 0 0 5px rgba(255, 69, 0, 0.5);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 100px;
                background-size: 300px; 
                margin-bottom: 0px;
                background-position: top;
            }
            h2 { 
                color: transparent;
            }
            .typewriter {
                border-right: transparent; 
                animation: typing 3.5s steps(30, end), blink-caret .75s step-end infinite; 
                padding-top: 20px;
            }
            .form-control {
                font-size: 0.9rem; 
            }
        }
         .login-container {
            
            max-height: 400px;
           
                       }

    </style>
</head>
<body>
    <br><br>
    <div id="welcome-banner" class="text-center fixed-top">
        <h2 class="animate__animated animate__rubberBand">  My App CJM </h2> 
    </div><br>

    <div class="container-fluid d-flex justify-content-end align-items-center"style="margin-top: 50px;" >
        <div class="login-container p-5 rounded" >

            <form method="POST" action="index.php"  >

                <div class="form-group" >
                    <h5  style="font-family: 'Calibri';"  class="animate__animated animate__bounce">Bonjour ! Démarrons. <i class="bi bi-rocket-takeoff"></i></h5>
                    <hr class="custom-line">
                    <center><p class="typewriter" style="opacity: 0.5">Connectez-vous.<i class="bi bi-pc-display-horizontal"></i></p></center>

                    <!-- Matricule -->
                    <input type="text" name="matricule" class="form-control mb-3" id="matricule" placeholder="Matricule" required>

                    <!-- Mot de passe -->
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control mb-3" placeholder="Mot de Passe" required>

                    <!-- Role -->
                    <select name="role" id="role" class="form-control mb-3" required>
                        <option value="Administrateur">Administrateur</option>
                        <option value="Professeur">Professeur</option>
                        <option value="Élève">Élève</option>
                    </select>

                    <button type="submit" name="connexion" class="btn btn-primary w-100">Se connecter</button>
                    
                   
                </div>
            </form>
            <b> <?php
                    if (isset($error)) {
                        echo "<p class='text-danger text-center mt-3'>$error</p>";
                    }
                    ?></b>
        </div>
    </div>
     <script>
        window.onload = 
            </b>
        <
function() {
            // Vérifier si une variable de session indiquant la réussite de la connexion existe
            
         
<?php if (isset($_SESSION['matricule'])): ?>
                document.getElementById('matricule').value = '';
                document.getElementById('mot_de_passe').value = '';
                document.getElementById('role').value = '';
            
       
<?php endif; ?>
        
    </script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
