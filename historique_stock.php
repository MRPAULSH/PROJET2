<?php
include('menu.php');

//verification d'autorisation

$role = $_SESSION['Role'];
$page = 'stocks';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}
//extraction de l'id

if (!isset($_GET['id'])) {
    echo "ID produit manquant.";
    exit;
}

$id = intval($_GET['id']);

// Requête pour récupérer les informations du produit
$produit = $bdd->query("SELECT nom FROM produits WHERE id = $id")->fetch_assoc();
if (!$produit) {
    echo "Produit introuvable.";
    exit;
}
?>

<div class="content" id="content">
    <section id="historique-section">
        <h2>Historique du stock - Produit : <strong><?php echo $produit['nom'] ?></strong></h2>

        <div class="table-container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-color:#34495e; color:white;">Date</th>
                        <th style="background-color:#34495e; color:white;">Type d'opération</th>
                        <th style="background-color:#34495e; color:white;">Quantité</th>
                        <th style="background-color:#34495e; color:white;">Effectué par</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $historique = $bdd->query("SELECT * FROM historique_stock WHERE produit_id = $id ORDER BY date_operation DESC");
                    while ($h = $historique->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $h['date_operation'] ?></td>
                            <td><?php echo $h['type_operation'] ?></td>
                            <td><?php echo $h['quantite'] ?></td>
                            <td><?php echo $h['utilisateur'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
