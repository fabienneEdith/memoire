
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles généraux */
        body {
            font-family: calibri;
            background-color: #f8f9fa;
            background-image: url('images/Humaaans - Wireframe (2).png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 200px;
            margin: 0;
            color: #333;
        }

        /* Barre latérale */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0px;
            left: 0;
            height: 100%;
            background-color: #FF0000;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding-top: 10px;
            transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
        }
        @keyframes slideIn {
            to { transform: translateX(0); }
        }

        .sidebar h3 {
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            color: #FFFFFF;
            margin-bottom: 30px;
        }

        .sidebar .menu-item {
            padding: 25px 30px;
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

        /* Contenu principal */
        .content {
            margin-left: 250px;
            padding: 20px;
            opacity: 0;
            animation: fadeInContent 1s forwards 0.5s;
            text-align: center;
        }

        @keyframes fadeInContent {
            to { opacity: 1; }
        }

        /* Cartes du tableau de bord */
        .dashboard-card {
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s;
            animation: zoomIn 0.5s forwards;
            animation-delay: calc(var(--order) * 0.1s); /* Décalage d'animation pour chaque carte */
        }

        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        @keyframes zoomIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
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

        /* Grille de cartes centrée et adaptée */
        .card-grid {
            padding-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            max-width: 1200px;
            margin: auto;
        }

        .custom-line {
            border: none; 
            height: 3px; 
            background-color: darkred; 
            width: 11%;
            margin-right:450px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            body {
                margin-top: 180px;
                background-size: 150px;
                margin-bottom: 0px;
                background-position: top;
            }

            .custom-line {
                background-color: transparent;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 10px;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <div class="sidebar"><br>
        <button style="background-color: white; width: 250px; height:35px; border-color: red;">
            <h3 style="color: red; border-color:white;">Tableau de Bord</h3>
        </button>

        <a href="eleve.php" class="menu-item"><i class="fas fa-users"></i>Gestion des Élèves</a>
        <a href="professeur.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i>Gestion des Professeurs</a>
        <a href="matiere.php" class="menu-item"><i class="fas fa-book"></i>Gestion des Matières</a>
        <a href="gestion_classe.php" class="menu-item"><i class="fas fa-layer-group"></i>Gestion des Classes</a>
        <a href="gestion_de_compte.php" class="menu-item"><i class="fas fa-user-cog"></i>Gestion des Comptes Utilisateurs</a>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <center><h1 class="mb-4" style="color:orangered;"> Bienvenue, Administrateur</h1></center>
        <hr class="custom-line">
        <center><h6 class="mb-4" style="opacity:0.6;">Démarrons. <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-person-walking" viewBox="0 0 16 16" style="color: darkred;">
            <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z"/>
            <path d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z"/>
        </svg></h6></center>

        <!-- Grille de cartes de fonctionnalités -->
       <a href="eleve.php" style="text-decoration:none;" > <div class="card-grid">
            <div class="dashboard-card" style="--order:1;">
                <i class="fas fa-users"></i>
                <h5>Élèves</h5>
                <p>Gérer et suivre les élèves et leurs classes</p>
            </div></a>

            <a href="professeur.php" style="text-decoration:none;">
                <div class="dashboard-card" style="--order:2;">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h5>Professeurs</h5>
                    <p>Gérer les professeurs et leurs matières</p>
                </div>
            </a>
            <a href="matiere.php" style="text-decoration:none;">
                <div class="dashboard-card" style="--order:3;">
                    <i class="fas fa-book"></i>
                    <h5>Matières</h5>
                    <p>Gérer les matières scolaires</p>
                </div>
            </a>
            <a href="gestion_classe.php" style="text-decoration:none;"><div class="dashboard-card" style="--order:4;">
                <i class="fas fa-layer-group"></i>
                <h5>Classes</h5>
                <p>Gérer les Classes</p>
            </div></a>

           <a href="gestion_de_compte.php" style="text-decoration:none;"><div class="dashboard-card" style="--order:5;">
                <i class="fas fa-user-cog"></i>
                <h5>Comptes Utilisateurs</h5>
                <p>Administrer les comptes</p>
            </div></a> 
        </div>
    </div>
</body>
</html>
