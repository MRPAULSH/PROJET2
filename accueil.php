<?php
include('menu.php');
include('connexion.php');

// Statistiques principales, compte le nombre total dans la table produit et compte les produit dont la quantité est inferieure ou egale a leur seuil d'alerte 
$totalProduits = mysqli_query($bdd, "SELECT COUNT(*) AS total FROM produits");
$totalProduits = mysqli_fetch_assoc($totalProduits)['total'];

$alertesStock = mysqli_query($bdd, "SELECT COUNT(*) AS total FROM produits WHERE quantite <= seuil_alerte");
$alertesStock = mysqli_fetch_assoc($alertesStock)['total'];
 // compte le nombre de commandes passée aujourd'hui 
$aujourdhui = date("Y-m-d");
$mouvementsJour = mysqli_query($bdd, "SELECT COUNT(*) AS total FROM commandes WHERE date_commande = '$aujourdhui'");
$mouvementsJour = mysqli_fetch_assoc($mouvementsJour)['total'];

// recupere les 5 derniere ajoutés en base, trie par Id 
$derniersProduits = mysqli_query($bdd, "SELECT * FROM produits ORDER BY id DESC LIMIT 5");


//verification d'autorisation

$role = $_SESSION['Role'];
$page = 'statistiques';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($Check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}
// carte de statistique, affiche 3 cartes avec les statistique calculées plus tot ($totalproduit, $alertesStock, $mouvementsJours)
?>
<div class="content" id="content">
    <section id="dashboard-section">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">Tableau de bord</h2>
        <div class="stats">
            <div class="stat-card shadow">
                <h3>Produits en stock</h3>
                <p id="total-products"><?php echo $totalProduits ?></p>
            </div>
            <div class="stat-card shadow alert">
                <h3>Alertes stock</h3>
                <p id="alert-products"><?php echo $alertesStock ?></p>
            </div>
            <div class="stat-card shadow">
                <h3>Mouvements aujourd'hui</h3>
                <p id="today-movements"><?php echo $mouvementsJour ?></p>
            </div>
        </div>  
        <div class="row mt-4" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
        
        <!-- affiche Tableau avec 5 dernier produits (nom et quantité) --> 
            <div class="table-container">
            <div class="col-md-6" style="flex: 1; min-width: 600px;">
                <h4>Derniers produits ajoutés</h4>
                <table id="products-table" class="table table-bordered table-striped">
                    <thead > 
                        <tr> 
                            <th style="background-color:#34495e; color:white;">Nom</th>
                            <th style="background-color:#34495e; color:white;">Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($prod = mysqli_fetch_assoc($derniersProduits)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prod['nom']) ?></td>
                                <td><?php echo $prod['quantite'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-container">
            <!-- Graphique chart.js pour affciher le graphique camembert, et les données sont recuperer via une requete AJAX vers statistique.php non visible dans ce code  -->
            <div class="col-md-6" style="flex: 1; min-width: 500px;">
                <h4>Répartition top 5 des produits en stocks</h4>
                <div style="width:100%; max-width:500px; margin:auto;">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
            </div>
        </div>
<br>
        <!-- Bouton Export PDF, un lien vers statistique_pdf.php pour gernerer un pdf des statistique  -->
        <div class="mt-4 text-right">
            <center>
                <a href="statistique_pdf.php" class="btn btn-danger" style="text-decoration:none;">
                    <i class="fas fa-file-pdf"></i> Exporter en PDF
                </a>
             </center>
        </div>

    </section>
</div>

</main>
</div>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('statistique.php')
    .then(res => res.json())
    .then(data => {
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.quantites,
                    backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#9b59b6', '#f1c40f']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
    // met en forme les carte de stats, le tableau et le graphique, et effet d'ombre (shadow) et animation au survol(hover) 
</script> 
<style>
    .stats {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 30px;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 25px;
        flex: 1 1 30%;
        min-width: 200px;
        text-align: center;
        border-left: 6px solid #3498db;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .stat-card h3 {
        margin-bottom: 10px;
        color: #34495e;
        font-size: 18px;
    }

    .stat-card p {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
    }

    .stat-card.alert {
        border-left-color: #e74c3c;
        background-color: #fef2f2;
    }

    .shadow {
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
    }

    #stockChart {
        width: 100% !important;
        height: 300px !important;
    }
</style>