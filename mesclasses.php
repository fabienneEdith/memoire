<?php 
session_start();
require_once 'config.php'; // Connexion à la base de données

// Vérifie si le professeur est connecté
if (!isset($_SESSION['prenom'])) {
    header("Location: index.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

// Utilisez le prénom du professeur depuis la session
$prenoms_professeur = $_SESSION['prenom'];

// Requête pour récupérer les classes et matières du professeur connecté avec jointure
$sql = "SELECT c.nom_classe AS classe, m.nom_matiere AS matiere
        FROM professeur AS p
        LEFT JOIN classe AS c ON p.classe_attribuee = c.id_classe
        LEFT JOIN matiere AS m ON p.matiere_enseignee = m.id_matiere
        WHERE p.prenoms_professeur = :prenoms_professeur"; // On utilise maintenant le prénom

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':prenoms_professeur', $prenoms_professeur, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Classes et Mes Matières</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            margin-top: 60px;
            margin-left: 150px; /* Espace pour la sidebar */
            margin-right: 180px;
        }
        .class-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .class-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .class-card h5 {
            color: #007bff;
            font-size: 1.2em;
        }
        .class-card p {
            color: #555;
            font-size: 1em;
        }
        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 20px;
        }
        h2 {
            color: #007bff;
            font-size: 2em;
            font-weight: bold;
        }
        .sidebar {
            width: 150px;
            position: fixed;
            top: 0;
            right: 100;
            height: 100%;
            background-color: #007bff;
            color: #FFFFFF;
            padding-top: 20px;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
        }
         @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }
       
    </style>
</head>
<body>
    <br>
   <div class="text-center mb-4">
        <a href="dashboardprofesseur.php" class="btn btn-outline-primary btn-lg" style="border-radius: 20px; font-weight: bold;  margin-left: 180px;">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>

    <div class="container">
        <h2 class="text-center mb-5 animate__animated animate__bounce">Mes Classes et Mes Matières</h2>
        <div class="class-grid" data-aos="fade-down-right">
            <?php if (count($result) > 0): ?>
                <?php foreach ($result as $row): ?>
                    <div class="class-card animate__animated animate__backInLeft" data-aos="fade-up" data-aos-delay="200">
                        <h5>Classe : <?php echo htmlspecialchars($row['classe']); ?></h5>
                        <p>Matière : <?php echo htmlspecialchars($row['matiere']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucune classe ou matière attribuée pour le professeur "<?php echo htmlspecialchars($prenoms_professeur); ?>"</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" style="background-image: url('images/StockCake-Abstract Geometric Art_1727874380.jpg');  background-repeat: no-repeat;
            background-position: center;
            background-size: 400px;
            background-color: #f8f9fa;
            ">
       
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>

<?php
// Ferme la connexion PDO (optionnel)
$pdo = null;
?>
