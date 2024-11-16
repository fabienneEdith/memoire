<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- AOS for animations on scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles généraux */
        body {
            font-family: 'Calibri', sans-serif;
            background-color: #f8f9fa;
            background-image: url('images/Humaaans - Wireframe (2).png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 200px;
            color: #333;
        }

        /* Barre latérale */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #FF0000;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease-in-out forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        .sidebar h3 {
            text-align: center;
            font-weight: bold;
            color: #FFFFFF;
            margin-bottom: 30px;
        }

        .sidebar .menu-item {
            padding: 15px 25px;
            color: #FFFFFF;
            font-size: 1.1rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar .menu-item:hover {
            background-color: #e00000;
        }

        /* Contenu principal */
        .content {
            margin-left: 250px;
            padding: 20px;
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: auto;
            padding-top: 40px;
        }

        .custom-line {
            border: none;
            height: 3px;
            background-color: darkred;
            width: 10%;
            margin: 20px auto;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
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
    <div class="sidebar animate__animated animate__fadeInLeft">
        <button style="background-color: white; width: 250px; height: 35px; border: none;">
            <h3 style="color: red;">Tableau de Bord</h3>
        </button>

        <a href="eleve.php" class="menu-item"><i class="fas fa-users"></i>Gestion des Élèves</a>
        <a href="professeur.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i>Gestion des Professeurs</a>
        <a href="matiere.php" class="menu-item"><i class="fas fa-book"></i>Gestion des Matières</a>
        <a href="gestion_classe.php" class="menu-item"><i class="fas fa-layer-group"></i>Gestion des Classes</a>
        <a href="gestion_de_compte.php" class="menu-item"><i class="fas fa-user-cog"></i>Gestion des Comptes Utilisateurs</a>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <center>
            <h1 class="mb-4 animate__animated animate__bounceIn" style="color:orangered;">Bienvenue, Administrateur</h1>
        </center>
        <hr class="custom-line">
        <center>
            <h6 class="mb-4 animate__animated animate__fadeInUp" style="opacity:0.6;">
                Démarrons. <i class="fas fa-walking" style="color: darkred;"></i>
            </h6>
        </center>

        <!-- Grille de cartes de fonctionnalités -->
        <div class="card-grid">
            <a href="eleve.php" style="text-decoration:none;" class="dashboard-card" data-aos="flip-left" data-aos-delay="100">
                <i class="fas fa-users"></i>
                <h5>Élèves</h5>
                <p>Gérer et suivre les élèves et leurs classes</p>
            </a>
            <a href="professeur.php" style="text-decoration:none;" class="dashboard-card" data-aos="flip-left" data-aos-delay="200">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5>Professeurs</h5>
                <p>Gérer les professeurs et leurs matières</p>
            </a>
            <a href="matiere.php" style="text-decoration:none;" class="dashboard-card" data-aos="flip-left" data-aos-delay="300">
                <i class="fas fa-book"></i>
                <h5>Matières</h5>
                <p>Gérer les matières scolaires</p>
            </a>
            <a href="gestion_classe.php" style="text-decoration:none;" class="dashboard-card" data-aos="flip-left" data-aos-delay="400">
                <i class="fas fa-layer-group"></i>
                <h5>Classes</h5>
                <p>Gérer les Classes</p>
            </a>
            <a href="gestion_de_compte.php" style="text-decoration:none;" class="dashboard-card" data-aos="flip-left" data-aos-delay="500">
                <i class="fas fa-user-cog"></i>
                <h5>Comptes Utilisateurs</h5>
                <p>Administrer les comptes</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: lavenderblush; margin-top: 50px;">
        <p class="text-center">Droits d'auteur © 2024. <span style="color: red;">C J M</span> Tous droits réservés.</p>
    </footer>

    <!-- JavaScript for animations -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000 });
    </script>
</body>
</html>
