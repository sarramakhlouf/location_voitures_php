<?php
    include('db.php');
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        die("Accès refusé.");
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM Voitures WHERE ID = ?");
        $stmt->execute([$id]);

        header("Location: admin_dashboard.php");
        exit;
    } else {
        die("ID de voiture non spécifié.");
    }
?>
