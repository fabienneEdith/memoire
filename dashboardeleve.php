<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Élève</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles généraux */
        body {
            font-family: calibri;
            background-color: #f8f9fa;
            background-image: url('images/Humaaans - Friend Meeting.png');
            background-repeat: no-repeat;
            background-position: center  -40%;
            background-size: 600px; /* Adjusted size to extend the image slightly */
            margin: 0;
            color: #333;
        }

        /* Barre latérale */
       .sidebar {
            width: 150px;
            position: fixed;
            top: 0;
            right: 100;
            height: 100%;
            background-color: #007bff;
            color: #FFFFFF;
            padding-top: 20px;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
        }
        /* Animation pour la barre latérale */
        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

       
        /* Contenu principal */
        .content {
            margin-left: 50px;
            padding: 20px;
            opacity: 1px;
            animation: fadeIn 1s ease-in-out 0.5s forwards;
        }

        /* Animation de fade-in pour le contenu */
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .custom-line {
            border: none; 
            height: 3px; 
            background-color: darkred; 
            width: 11%;
            margin-left:530px; 
        }

        /* Responsivité */
        @media (max-width: 768px) {
            body {
                margin-top: 180px;
                background-size: 500px; /* Adjust image size for mobile */
                margin-bottom: 0px;
                background-position: top; /* Centers image vertically */
            }

            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
            }

            .custom-line {
                background-color: transparent; 
            }
        }

    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar"  style="background-image: url('images/StockCake-Abstract Geometric Art_1727874380.jpg');  background-repeat: no-repeat;
            background-position: center;
            background-size: 400px;
            background-color: #f8f9fa;
            ">
        </div>
    <!-- Contenu principal -->
    <div class="content">
        <center><h1 style="color:orangered;"><?php echo "Bienvenue, " . $_SESSION['prenom']; ?></h1></center><hr class="custom-line">

        <center><h6 class="mb-4" style="opacity:0.7; color: gray;">Démarrons. <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-person-walking" viewBox="0 0 16 16" style="color: orangered;">
            <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z"/>
            <path d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 .53 1.28l-2.33 2.31a.75.75 0 0 1-.974.095Z"/>
        </svg></h6></center>
    </div>

</body>
</html>
