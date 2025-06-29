<?php
include('menu.php');


$role = $_SESSION['Role'];
$page = 'produits';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}

?>

<div class="content" id="content">
    <section id="stocks-section">
        <h2>Gestion des Stocks</h2>

        <a href="produit.php" class="text-decoration:none;">
            <button id="add-product" class="btn btn-primary mb-2">Ajouter un produit</button>
        </a>

        <div class="table-container">
            <table id="stock-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-color:#34495e; color:white;">Référence</th>
                        <th style="background-color:#34495e; color:white;">Nom</th>
                        <th style="background-color:#34495e; color:white;">Quantité</th>
                        <th style="background-color:#34495e; color:white;">Seuil</th>
                        <th colspan="2" style="background-color:#34495e; color:white;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $produits = mysqli_query($bdd, "SELECT * FROM produits");
                    while ($p = mysqli_fetch_assoc($produits)) {
                    ?>
                        <tr>
                            <td><?php echo $p['referencex'] ?></td>
                            <td><?php echo $p['nom'] ?></td>
                            <td><?php echo $p['quantite'] ?></td>
                            <td><?php echo $p['seuil_alerte'] ?></td>
                            <td>
                                <a href="ajustement_stock.php?id=<?php echo $p['id'] ?>">
                                    <i class="fas fa-edit text-warning" style="color:orange;"></i>
                                </a>
                            </td>
                            <td>
                                <a href="historique_stock.php?id=<?php echo $p['id'] ?>" style="color:#34495e;">
                                    <i class="fas fa-history text-info"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- SweetAlert si besoin -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
