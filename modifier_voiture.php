<?php
include('db.php');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Voitures WHERE ID = ?");
    $stmt->execute([$id]);
    $car = $stmt->fetch();

    if (!$car) {
        die("Voiture introuvable.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $immatriculation = $_POST['immatriculation'];
    $disponibilite = $_POST['disponibilite'];

    $stmt = $conn->prepare("UPDATE Voitures SET Marque = ?, Modele = ?, Annee = ?, Immatriculation = ?, Disponibilite = ? WHERE ID = ?");
    $stmt->execute([$marque, $modele, $annee, $immatriculation, $disponibilite, $id]);

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Modifier une voiture</h2>
        <div class="card p-4 shadow-lg">
            <form method="POST">
                <div class="mb-3">
                    <label for="marque" class="form-label">Marque</label>
                    <input type="text" id="marque" name="marque" class="form-control" value="<?= htmlspecialchars($car['Marque']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="modele" class="form-label">Modèle</label>
                    <input type="text" id="modele" name="modele" class="form-control" value="<?= htmlspecialchars($car['Modele']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" id="annee" name="annee" class="form-control" value="<?= htmlspecialchars($car['Annee']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="immatriculation" class="form-label">Immatriculation</label>
                    <input type="text" id="immatriculation" name="immatriculation" class="form-control" value="<?= htmlspecialchars($car['Immatriculation']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="disponibilite" class="form-label">Disponibilité</label>
                    <select id="disponibilite" name="disponibilite" class="form-select" required>
                        <option value="1" <?= $car['Disponibilite'] ? 'selected' : '' ?>>Disponible</option>
                        <option value="0" <?= !$car['Disponibilite'] ? 'selected' : '' ?>>Indisponible</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Modifier</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>