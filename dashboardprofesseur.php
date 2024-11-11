
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Professeur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      body {
            font-family: calibri;
            background-color: #f8f9fa;
            background-image: url('images/Humaaans - Graphs.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 210px;
            margin: 0;
            color: #333;
        }

        .sidebar {
            width: 300px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #FF0000;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
        }

        .custom-line {
         border: none; 
         height: 3px; 
         background-color: darkred; 
         width: 11%;
         margin-right:450px; 
}


        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        .sidebar h3 {
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            color: #FFFFFF;
            margin-bottom: 30px;
        }

        .sidebar .menu-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .sidebar .menu-item:hover {
            background-color: #e00000;
        }

        .sidebar .menu-item i {
            margin-right: 15px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            opacity: 0;
            animation: fadeIn 1s ease-in-out 0.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .dashboard-card {
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            padding: 20px;
            text-align: center;
            transform: scale(1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            animation: popIn 0.5s ease forwards;
        }

        @keyframes popIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card i {
            font-size: 2.5rem;
            color: #FF0000;
            margin-bottom: 15px;
        }

        .dashboard-card h5 {
            font-size: 1.2rem;
            color: #333;
            margin-top: 10px;
        }

        .dashboard-card p {
            font-size: 1rem;
            color: #666;
        }

        .card-grid {
            padding-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 600px; /* Centering width */
            margin: auto;
        }

        @media (max-width: 510px) {
             body {
                margin-top: 180px;
                background-size:150px; /* Contient l'image sans la rogner */
                margin-bottom: 0px;
                background-position:top; /* Centre l'image verticalement et horizontalement */
    }
            .sidebar {
                display: none;
            }
            .content {
                margin-left: 0;
            }
            .card-grid {
                grid-template-columns: 1fr;
            }
            .custom-line {
         
         background-color: transparent; 
         
                        }

        }


        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar">
        <button style="background-color: white; width: 300px; height:35px; border-color: red;"><h3 style="color: red; border-color:white;">Tableau de Bord</h3></button>
        <a href="#" class="menu-item"><i class="fas fa-chalkboard"></i>Mes Classes</a>
        <a href="#" class="menu-item"><i class="fas fa-book"></i>Matières</a>
        <a href="#" class="menu-item"><i class="fas fa-user-edit"></i>Entrer les Notes</a>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <center><h1 class="mb-4" style="color:orangered;">  <?php echo "Bonjour, " . $_SESSION['prenom']; // Afficher le prénom sur ces pages
 ?></h1></center>
         <hr class="custom-line">

<center><h6 class="mb-4" style="opacity:0.6;">Démarrons. <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-person-walking" viewBox="0 0 16 16" style="color: red;">
            <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z"/>
            <path d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z"/>
        </svg></h6></center>

        <!-- Grille de cartes de fonctionnalités -->
        <div class="card-grid">
            <div class="dashboard-card">
                <i class="fas fa-chalkboard"></i>
                <h5>Mes Classes</h5>
                <p>Accéder aux classes où vous enseignez</p>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-user-edit"></i>
                <h5>Entrer les Notes</h5>
                <p>Ajouter des notes pour chaque élève</p>
            </div>
            
            
        </div>
    </div>

 <footer style="background-color:lavenderblush; margin-top: 100px;">
        
            <p align="center">Droits d'auteur © 2024. <span style="color:red;">C J M </span>Tous droits réservés. </p>
        
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>