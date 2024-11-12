<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil</title>

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
        /* Styles de base */
        body {
            background-image: url('images/fotor-ai-20241112125339.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            opacity: 0.5px;
        }
        
        /* Floutage de l'arrière-plan */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6); /* Couche semi-transparente */
            backdrop-filter: blur(4px); /* Flou arrière-plan */
            z-index: 1;
        }

        /* Style du contenu principal */
        .content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
            animation: fadeIn 3s ease-in-out;
        }

        /* Animation pour le bouton */
        .btn-start {
            font-size: 1.5rem;
            padding: 10px 30px;
            border-radius: 50px;
            color: #fff;
            background-color: red;
            border: none;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(250, 0, 0,1);

        }

        .btn-start:hover {
            background-color: tomato;
            color: white;
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.7);
            transform: translateY(10px);
        }

        /* Animation fadeIn */
        @keyframes fadeIn {
            0% {
                opacity:0;
            }
            100% {
                opacity:50;
            }
        }
    </style>
</head>
<body>
    
    <!-- Flou arrière-plan -->
    <div class="overlay" data-aos="flip-left"   data-aos-duration="1000"></div>

    <div class="content" data-aos="fade-up"  data-aos-duration="2000">
        <h1 class="animate__animated animate__swing" style="font-size: 3rem;">Bienvenue sur <span style="font-size: 3.5rem; font-family: calibri; color: orangered;" > My Cjm App</span>  <i class="bi bi-back" style="font-size: 2.5rem;"></i></h1><br>
        <div data-aos="fade-right">
            <p class="lead" style="font-size:1.1rem; font-family: lora, monospace;" >Démarrez une aventure époustouflante. <i class="bi bi-balloon-heart-fill" style="color:red"></i></p>
            </div>
        <br>
        
        <!-- Bouton animé de démarrage -->
        <a href="index.php" class="btn btn-start" style="font-family: lora,monospace;" >Démarrer <i class="bi bi-arrow-right-short"></i></a>
    </div>
    <!-- Lien vers Bootstrap JS et jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>