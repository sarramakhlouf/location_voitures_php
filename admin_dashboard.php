<?php
include('db.php');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé. Vous devez être administrateur pour accéder à cette page.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $immatriculation = $_POST['immatriculation'];
    $disponibilite = $_POST['disponibilite'];

    $stmt = $conn->prepare("INSERT INTO Voitures (Marque, Modele, Annee, Immatriculation, Disponibilite) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$marque, $modele, $annee, $immatriculation, $disponibilite]);

    echo "Voiture ajoutée avec succès!";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <!-- <link href="css/style.css" rel="stylesheet">-->
</head>
<body>
    <div class="container-fluid bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <h1><i class="fas fa-car"></i> CarMatch</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Page d'accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="connexion.php" class="nav-link text-danger">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Gestion des Voitures</h2>

        <!-- Formulaire pour ajouter une voiture -->
        <form method="POST" action="admin_dashboard.php" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="marque" class="form-control" placeholder="Marque" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="modele" class="form-control" placeholder="Modèle" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="annee" class="form-control" placeholder="Année" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="immatriculation" class="form-control" placeholder="Immatriculation" required>
                </div>
                <div class="col-md-2">
                    <select name="disponibilite" class="form-select" required>
                        <option value="1">Disponible</option>
                        <option value="0">Indisponible</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-center">
                <button type="submit" class="btn btn-primary">Ajouter une voiture</button>
            </div>
        </form>

        <!-- Tableau des voitures -->
        <h3 class="mb-3">Liste des voitures</h3>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Immatriculation</th>
                    <th>Disponibilité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM Voitures");
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$row['Marque']}</td>
                            <td>{$row['Modele']}</td>
                            <td>{$row['Annee']}</td>
                            <td>{$row['Immatriculation']}</td>
                            <td>" . ($row['Disponibilite'] ? "Disponible" : "Indisponible") . "</td>
                            <td>
                                <a href='modifier_voiture.php?id={$row['ID']}' class='btn btn-sm btn-success'>Modifier</a>
                                <a href='supprimer_voiture.php?id={$row['ID']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette voiture ?\");'>Supprimer</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>