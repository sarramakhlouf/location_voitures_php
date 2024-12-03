<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour vérifier les informations de l'utilisateur dans la table Clients
    $stmt = $conn->prepare("SELECT * FROM Clients WHERE Email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();

    // Vérification du mot de passe et du rôle de l'utilisateur
    if ($client && password_verify($mot_de_passe, $client['Mot_de_passe'])) {
        $_SESSION['client_id'] = $client['ID'];
        $_SESSION['role'] = $client['role'];

        // Redirection en fonction du rôle
        if ($_SESSION['role'] == 'admin') {
            header('Location: admin_dashboard.php');  // Redirige l'admin vers la page admin
        } else {
            header('Location: client_dashboard.php');  // Redirige un client vers sa page
        }
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Lato', sans-serif;
        }
        .nav-bar {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand h1 {
            font-size: 24px;
            font-weight: 700;
            color: #d31b1b;
        }
        .navbar .nav-link {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }
        .navbar .nav-link.active {
            color: #d31b1b;
        }
        .login-form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
            margin-top: 100px
        }
        .login-form img {
            width: 60px;
            margin-bottom: 20px;
            margin-left: 150px
        }
        .login-form button {
            background: #d31b1b;
            color: #fff;
        }
        .login-form button:hover {
            background: #b51515;
        }
        .forgot-password a {
            color: #d31b1b;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid nav-bar sticky-top px-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light py-2">
                <a href="#" class="navbar-brand">
                    <h1><i class="fas fa-car-alt me-2"></i>CarMatch</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">Page d'accueil</a>
                        </li>
                        <!-- Décommentez si besoin -->
                        <!-- <li class="nav-item"><a href="login.php" class="nav-link">Connexion</a></li> -->
                        <!-- <li class="nav-item"><a href="inscription.php" class="nav-link">Inscription</a></li> -->
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Formulaire de connexion -->
    <div class="login-form">
        <img src="img/user-icon.png" alt="Icône utilisateur">
        <form method="POST" action="connexion.php">
            <div class="mb-3">
                <input type="text" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Se connecter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>