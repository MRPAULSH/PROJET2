<?php 

include('connexion.php');
session_start();

    $id=$_SESSION['id'];
    $username=$_SESSION['Nom_utilisateur'];
    $role=$_SESSION['Role'];

    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>

    
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="style.css">
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Complément pour styling menu */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: #2c3e50;
            padding: 20px 0;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar nav ul li {
            margin: 10px 0;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #1a252f;
        }

        .sidebar nav ul li a i {
            margin-right: 10px;
        }

        .logout-btn {
            background-color: #c0392b;
            color: white;
            border: none;
            margin: 20px;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #e74c3c;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #34495e;
        }

        .user-info {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div>
                <a href="accueil.php" style="text-decoration:none;">
                    <div class="logo">Gestion Stock</div>
                </a>
                <nav>
                    <ul>
                        <li><a href="accueil.php" class="active"><i class="fas fa-chart-line"></i> Tableau de bord</a></li>
                        <li><a href="produit.php"><i class="fas fa-boxes"></i> Produits</a></li>
                        <li><a href="gestion_commande.php"><i class="fas fa-shopping-cart"></i> Commandes</a></li>
                        <li><a href="stock.php"><i class="fas fa-warehouse"></i> Stock</a></li>
                        <li><a href="utilisateur.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                        <li><a href="configuration.php"><i class="fas fa-cogs"></i> Configuration</a></li>
                    </ul>
                </nav>
            </div>
            <button id="logout" class="logout-btn">
                <a href="deconnexion.php" style="color: white; text-decoration:none;">Déconnexion</a>
            </button>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1 id="page-title">PGME Company</h1>
                <div class="user-info">
                    Bienvenue, <strong><?php echo $_SESSION['Nom_utilisateur'] ?? 'Utilisateur' ?></strong>
                </div>
            </header>
