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


if (!isset($_GET['id'])) {
    die("Produit non trouvé.");
}

$id = $_GET['id'];
$produit = $bdd->query("SELECT * FROM produits WHERE id = $id")->fetch_assoc();

if (isset($_POST['ajuster'])) {
    $type = $_POST['type'];
    $quantite = (int)$_POST['quantite'];

    if ($type === 'entree') {
        $bdd->query("UPDATE produits SET quantite = quantite + $quantite WHERE id = $id");
    } elseif ($type === 'sortie') {
        $bdd->query("UPDATE produits SET quantite = quantite - $quantite WHERE id = $id");
    }

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Stock ajusté avec succès',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'stock.php';
        });
    </script>";
    // Après avoir modifié la quantité dans la table produit on inserer dans l'historique

    $produit_id = $id; 
    $type_operation = 'modification'; // 'retrait'
    $quantites = $quantite;
    $utilisateur = $_SESSION['Nom_utilisateur']; 
    $date = date('Y-m-d H:i:s');

    $insertHistorique = $bdd->prepare("INSERT INTO historique_stock (produit_id, type_operation, quantite, utilisateur, date_operation) VALUES (?, ?, ?, ?, ?)");
    $insertHistorique->bind_param("isiss", $produit_id, $type_operation, $quantites, $utilisateur, $date);
    $insertHistorique->execute();
}
?>

<div class="content" id="content">
    <div class="form-wrapper">
        <h2>Ajuster le stock : <?php echo $produit['nom'] ?></h2>
<br><br>
        <form method="POST">
            <div class="form-group">
                <label>Type d'ajustement</label>
                <select name="type" class="form-control" required>
                    <option value="entree">Entrée en stock</option>
                    <option value="sortie">Sortie du stock</option>
                </select>
            </div>

            <div class="form-group">
                <label>Quantité</label>
                <input type="number" name="quantite" class="form-control" required min="1">
            </div>
            <center>
                <button type="submit" name="ajuster" class="btn btn-success">
                    <i class="fas fa-save"></i> Valider
                </button>
            </center>
            
        </form>
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

    .form-wrapper h2 {
        margin-bottom: 5px;
        color: #2c3e50;
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .form-row .form-group {
        flex: 1;
        min-width: 220px;
    }

    .btn {
        background-color: #3498db;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #2980b9;
    }

    .close {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 20px;
        cursor: pointer;
        color: #999;
    }

    .close:hover {
        color: #e74c3c;
    }

    @media screen and (max-width: 600px) {
        .form-row {
            flex-direction: column;
        }
    }
</style>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
