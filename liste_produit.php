<?php  
include('menu.php');

//verification d'autorisation

$role = $_SESSION['Role'];
$page = 'produits';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}

?>

<div class="content" id="content">
    <section id="produits-section">
        <h2>Gestion des Produits</h2>
        <a href="produit.php" class="text-decoration:none;"><button id="add-product" class="btn btn-primary mb-2">Ajouter un produit</button>
</a>
        <div class="table-container">
            <table id="products-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-color:#34495e; color:white;">Référence</th>
                        <th style="background-color:#34495e; color:white;">Nom</th>
                        <th style="background-color:#34495e; color:white;">Prix d'achat</th>
                        <th style="background-color:#34495e; color:white;">Prix vente</th>
                        <th style="background-color:#34495e; color:white;">Quantité</th>
                        <th style="background-color:#34495e; color:white;">Seuil</th>
                        <th colspan="2" style="background-color:#34495e; color:white;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET['supp'])) {
                        $id = $_GET['supp'];
                       
                        mysqli_query($bdd, "DELETE FROM produits WHERE id='$id'");
                        echo "<script>Swal.fire('Supprimé', 'Produit supprimé avec succès.', 'success');</script>";
                        

                    }

                    
                    $result = mysqli_query($bdd, "SELECT * FROM produits");
                    while ($p = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $p['referencex'] ?></td>
                            <td><?php echo $p['nom'] ?></td>
                            <td><?php echo $p['prix_achat'] ?> $</td>
                            <td><?php echo $p['prix_vente'] ?> $</td>
                            <td><?php echo $p['quantite'] ?></td>
                            <td><?php echo $p['seuil_alerte'] ?></td>
                            <td>
                                <a href="modifier_produit.php?id=<?php echo $p['id'] ?>">
                                    <i class="fas fa-edit text-primary" style="color:blue ;"></i>
                                </a>
                               
                            </td>
                            <td> <a href="?supp=<?php echo $p['id'] ?>" onclick="return confirm('Supprimer ce produit ?')">
                                    <i class="fas fa-trash text-danger ml-2" style="color:red;"></i>
                                </a></td>
                        </tr>

                        
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

