<?php
require_once '../config/config.php';
session_start();
require_once '../config/database.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo isset($pageTitle) ? $pageTitle : 'Plateforme de dons caritatifs'; ?></title>
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/owl.carousel.css">
    <link rel="stylesheet" href="../assets/owl.theme.default.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/logo.webp">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utilities.css">
    <link rel="stylesheet" href="../assets/css/common.css">
</head>
<body>
    <!-- Particles.js Container -->
    <div id="particles-js"></div>

    <header class="header">
        <nav class="navbar">
            <div class="container">
                <!-- Logo -->
                <a href="../" class="logo">
                    <img src="../assets/logo.webp" alt="Logo HelpHub">
                    <span>HelpHub</span>
                </a>

                <!-- Navigation -->
                <ul class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_type'] === 'manager'): ?>
                            <li><a href="../manager/dashboard.php">Tableau de bord</a></li>
                            <li><a href="../manager/donations.php">Dons</a></li>
                            <li><a href="../manager/reports.php">Rapports</a></li>
                        <?php else: ?>
                            <li><a href="../donor/dashboard.php">Tableau de bord</a></li>
                            <li><a href="../donor/donations.php">Mes dons</a></li>
                            <li><a href="../donor/profile.php">Mon profil</a></li>
                        <?php endif; ?>
                        <li><a href="../auth/logout.php" class="btn btn-secondary">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="../auth/login.php" class="btn">Connexion</a></li>
                        <li><a href="../auth/signup.php" class="btn btn-secondary">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> fade-in">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                </div>
            <?php endif; ?> 