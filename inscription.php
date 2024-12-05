<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $numero_telephone = $_POST['numero_telephone'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];  // Récupérer le rôle sélectionné

    // Requête pour insérer l'utilisateur dans la base de données avec son rôle
    $stmt = $conn->prepare("INSERT INTO Clients (Nom, Adresse, Numero_telephone, Email, Mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $adresse, $numero_telephone, $email, $mot_de_passe, $role]);

    header('Location: connexion.php');

    echo "Inscription réussie!";

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Lato', sans-serif;
        }
        .registration-form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 50px auto;
        }
        .registration-form img {
            width: 60px;
            margin-bottom: 20px;
            margin-left: 200px
        }
        .registration-form button {
            background: #d31b1b;
            color: #fff;
        }
        .registration-form button:hover {
            background: #b51515;
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
    <div class="container">
        <?php if (!empty($message)) echo $message; ?>

        <div class="registration-form">
            <img src="img/register-icon.png" alt="Icône">
            <form method="POST" action="inscription.php">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" required>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Adresse" required>
                </div>
                <div class="mb-3">
                    <label for="numero_telephone" class="form-label">Numéro de téléphone</label>
                    <input type="tel" name="numero_telephone" id="numero_telephone" class="form-control" placeholder="Numéro de téléphone" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="client">Client</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger w-100">S'inscrire</button>
                <label>Vous avez déjà un compte ? <a href="connexion.php">connectez-vous</a></label>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

