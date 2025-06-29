<?php
include('menu.php');

//verification d'autorisation

$role = $_SESSION['Role'];
$page = 'commandes';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}

// Supprimer une commande
if (isset($_GET['supp'])) {
    $id = $_GET['supp'];
    $delete = $bdd->prepare("DELETE FROM commandes WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Commande supprimée',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
}

// Ajouter une commande et mettre à jour le stock
if (isset($_POST['enregistrer'])) {
    $client = $_POST['client'];
    $produit_id = $_POST['produit'];
    $quantite = $_POST['quantite'];
    $date = $_POST['date'];

    // Vérifier le stock du produit
    $prod = $bdd->prepare("SELECT * FROM produits WHERE id = ?");
    $prod->bind_param("i", $produit_id);
    $prod->execute();
    $result = $prod->get_result();
    $produit = $result->fetch_assoc();

    if ($produit && $produit['quantite'] >= $quantite) {
        // Ajouter la commande
        $insert = $bdd->prepare("INSERT INTO commandes (client, produit_id, quantite, date_commande) VALUES (?, ?, ?, ?)");
        $insert->bind_param("siis", $client, $produit_id, $quantite, $date);
        $insert->execute();

        // Mettre à jour le stock
        $nv_qte = $produit['quantite'] - $quantite;
        $maj = $bdd->prepare("UPDATE produits SET quantite = ? WHERE id = ?");
        $maj->bind_param("ii", $nv_qte, $produit_id);
        $maj->execute();

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Commande enregistrée et stock mis à jour',
                showConfirmButton: false,
                timer: 2000
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Stock insuffisant',
                text: 'La quantité demandée dépasse le stock disponible.',
                showConfirmButton: true
            });
        </script>";
    }
    $recup = $bdd->prepare("SELECT * FROM commandes WHERE id = ?");
    $recup->bind_param("i", $id);
    $recup->execute();
    $res = $recup->get_result();
    $commande = $res->fetch_assoc();

    if ($commande) {
        $produit_id = $commande['produit_id'];
        $quantite = $commande['quantite'];

        // Restaurer le stock
        $restock = $bdd->prepare("UPDATE produits SET quantite = quantite + ? WHERE id = ?");
        $restock->bind_param("ii", $quantite, $produit_id);
        $restock->execute();

        // Supprimer la commande
        $delete = $bdd->prepare("DELETE FROM commandes WHERE id = ?");
        $delete->bind_param("i", $id);
        $delete->execute();

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Commande supprimée et stock restauré',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
            $produit_id = $id; // ou $_POST['produit_id'] selon le contexte
            $type_operation = 'retrait'; // ou 'retrait' ou 'commande' ou 'suppression commande'
            $quantites = $quantite;
            $utilisateur = $_SESSION['Nom_utilisateur']; // si tu stockes le nom de l'utilisateur connecté
            $date = date('Y-m-d H:i:s');

            $insertHistorique = $bdd->prepare("INSERT INTO historique_stock (produit_id, type_operation, quantite, utilisateur, date_operation) VALUES (?, ?, ?, ?, ?)");
            $insertHistorique->bind_param("isiss", $produit_id, $type_operation, $quantites, $utilisateur, $date);
            $insertHistorique->execute();
    }
}
?>
<div class="content" id="content">
    <div class="form-wrapper">
        

<div class="content" id="content">
    <div class="container mt-4">
        <h2 class="mb-3">Gestion des commandes</h2>

        <!-- Formulaire d'ajout -->
        <form method="POST" class="mb-4 p-3" style="border:1px solid #ccc; border-radius:10px;">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Client</label>
                    <input type="text" name="client" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Produit</label>
                    <select name="produit" class="form-control" required>
                        <option value="">-- Choisir un produit --</option>
                        <?php
                        $produits = $bdd->query("SELECT * FROM produits ORDER BY nom ASC");
                        while ($row = $produits->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nom']} (Stock: {$row['quantite']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Quantité</label>
                    <input type="number" name="quantite" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <br>

                <br>
                
                <br>
                
                    <button type="submit" name="enregistrer" class="btn btn-success">
                        <i class="fas fa-save"></i>
                    </button>
                
            </div>
        </form>
        <br>

        <br>
        <!-- Tableau des commandes -->
        <div class="table-container">
            <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="background-color:#34495e; color:white;">Client</th>
                    <th style="background-color:#34495e; color:white;">Produit</th>
                    <th style="background-color:#34495e; color:white;">Quantité</th>
                    <th style="background-color:#34495e; color:white;">Date</th>
                    <th colspan="2" style="background-color:#34495e; color:white;"><center>Actions</center></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT c.*, p.nom AS nom_produit 
                    FROM commandes c
                    JOIN produits p ON c.produit_id = p.id
                    ORDER BY c.date_commande DESC
                ";
                $res = $bdd->query($sql);
                while ($c = $res->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['client']) ?></td>
                        <td><?php echo htmlspecialchars($c['nom_produit']) ?></td>
                        <td><?php echo $c['quantite'] ?></td>
                        <td><?php echo $c['date_commande'] ?></td>
                        <td>
                            <a href="?supp=<?php echo $c['id']?>" style="color: red;"class="text-danger" onclick="return confirm('Supprimer cette commande ?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            
                        </td>
                        <td>
                        <a href="recu_commande.php?id=<?php echo $c['id']?>" style="color: green;" target="_blank" class="text-success ml-2">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        
        
    </div>
</div>


<style>
    .form-wrapper {
        background-color: #f9f9f9;
        padding: 30px;
        margin: 10 auto;
        max-width: 1030px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
    }

</style>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
