<?php
    include('db.php');
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'client') {
        die("Accès refusé");
    }

    // Recherche par date
    $voitures_disponibles = [];
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['date_debut']) && isset($_GET['date_fin'])) {
        $date_debut = $_GET['date_debut'];
        $date_fin = $_GET['date_fin'];

        if ($date_debut && $date_fin) {
            // Requête pour trouver les voitures disponibles dans la plage de dates
            $stmt = $conn->prepare("
                SELECT * 
                FROM Voitures v 
                WHERE v.Disponibilite = 1 
                AND NOT EXISTS (
                    SELECT 1 
                    FROM Reservations r 
                    WHERE r.Voiture_ID = v.ID 
                    AND (
                        (r.Date_debut <= ? AND r.Date_fin >= ?) OR 
                        (r.Date_debut <= ? AND r.Date_fin >= ?)
                    )
                )
            ");
            $stmt->execute([$date_fin, $date_debut, $date_debut, $date_fin]);
            $voitures_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['voiture_id']) && isset($_POST['date_debut']) && isset($_POST['date_fin'])) {
            // Réservation d'une voiture
            $voiture_id = $_POST['voiture_id'];
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $client_id = $_SESSION['client_id'];
    
            // Vérification de disponibilité
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM Reservations 
                WHERE Voiture_ID = ? 
                  AND (
                    (Date_debut <= ? AND Date_fin >= ?) OR 
                    (Date_debut <= ? AND Date_fin >= ?)
                  )
            ");
            $stmt->execute([$voiture_id, $date_fin, $date_debut, $date_debut, $date_fin]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result['count'] == 0) {
                // Réserver la voiture
                $stmt = $conn->prepare("INSERT INTO Reservations (Date_debut, Date_fin, Voiture_ID, Client_ID) VALUES (?, ?, ?, ?)");
                $stmt->execute([$date_debut, $date_fin, $voiture_id, $client_id]);
    
                echo "<p class='alert alert-success'>Réservation confirmée !</p>";
            } else {
                echo "<p class='alert alert-warning'>Cette voiture n'est pas disponible pour les dates sélectionnées.</p>";
            }
        } elseif (isset($_POST['delete_reservation'])) {
            // Annulation de la réservation
            $reservation_id = $_POST['reservation_id'];
            $voiture_id = $_POST['voiture_id'];
    
            // Supprimer la réservation
            $stmt = $conn->prepare("DELETE FROM Reservations WHERE ID = ?");
            $stmt->execute([$reservation_id]);
    
            // Remettre la voiture disponible
            $updateStmt = $conn->prepare("UPDATE Voitures SET Disponibilite = 1 WHERE ID = ?");
            $updateStmt->execute([$voiture_id]);
    
            echo "<p class='alert alert-success'>Réservation annulée avec succès !</p>";
        }
    }
?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Client</title>
    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
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
            background-color: #f8f9fa;
            font-family: 'Lato', sans-serif;
        }

        .navbar-brand h1 {
            color: #d31b1b;
            font-weight: bold;
        }

        .form-control,
        .btn {
            border-radius: 0.5rem;
        }

        .table {
            margin-top: 20px;
        }

        .container {
            margin-top: 30px;
        }

        .alert {
            margin-top: 20px;
        }

        .reservation-title {
            color: #d31b1b;
        }

        .btn-primary {
            background-color: #d31b1b;
            border-color: #d31b1b;
        }

        .btn-primary:hover {
            background-color: #b51515;
        }
    </style>
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

    <div class="container">
        <!-- Message de confirmation ou d'erreur -->
        <?php if (!empty($message)) echo $message; ?>

        <h2 class="text-center reservation-title">Réserver une voiture</h2>

        <!-- Formulaire de recherche -->
        <h3>Rechercher des voitures disponibles par date</h3>
        <form method="GET" action="client_dashboard.php" class="my-4">
            <div class="row">
                <div class="col-md-5">
                    <label for="date_debut" class="form-label">Date de début</label>
                    <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label for="date_fin" class="form-label">Date de fin</label>
                    <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
            </div>
        </form>

        <!-- Liste des voitures disponibles -->
        <h3>Voitures disponibles</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Immatriculation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($voitures_disponibles as $voiture): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($voiture['Marque'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($voiture['Modele'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($voiture['Annee'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($voiture['Immatriculation'] ?? ''); ?></td>
                            <td>
                                <form method="POST" action="client_dashboard.php">
                                    <input type="hidden" name="voiture_id" value="<?php echo $voiture['ID']; ?>">
                                    <div class="d-flex">
                                        <input type="date" name="date_debut" class="form-control me-2" required>
                                        <input type="date" name="date_fin" class="form-control me-2" required>
                                        <button type="submit" class="btn btn-primary">Réserver</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Liste des réservations -->
        <h3 class="mt-5">Vos voitures réservées</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT R.ID as ReservationID, V.Marque, V.Modele, R.Date_debut, R.Date_fin, R.Voiture_ID
                                            FROM Reservations R
                                            JOIN Voitures V ON R.Voiture_ID = V.ID
                                            WHERE R.Client_ID = ?");
                    $stmt->execute([$_SESSION['client_id']]);
                    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($reservations):
                        foreach ($reservations as $reservation):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['Marque'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['Modele'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['Date_debut'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($reservation['Date_fin'] ?? ''); ?></td>
                            <td>
                                <form method="POST" action="client_dashboard.php">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['ReservationID']; ?>">
                                    <input type="hidden" name="voiture_id" value="<?php echo $reservation['Voiture_ID']; ?>">
                                    <button type="submit" name="delete_reservation" class="btn btn-danger">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Aucune réservation trouvée.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
