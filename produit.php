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

// pour enregistrer un produit 
if (isset($_POST['enregistrer'])) {
    $reference = $_POST['reference'];
    $nom = $_POST['product_name'];
    $description = $_POST['description'];
    $prix_achat = $_POST['prix_achat'];
    $prix_vente = $_POST['prix_vente'];
    $quantite = $_POST['quantite'];
    $seuil_alerte = $_POST['seuil_alerte'];

    $insertProduit = $conn->prepare("INSERT INTO produits (referencex, nom, descrption, prix_achat, prix_vente, quantite, seuil_alerte) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertProduit->execute([$reference, $nom, $description, $prix_achat, $prix_vente, $quantite, $seuil_alerte]);

        $produit_id = $id; // ou $_POST['produit_id'] selon le contexte
        $type_operation = 'ajout'; // ou 'retrait' ou 'commande' ou 'suppression commande'
        $quantites = $quantite;
        $utilisateur = $_SESSION['Nom_utilisateur']; // si tu stockes le nom de l'utilisateur connecté
        $date = date('Y-m-d H:i:s');

        $insertHistorique = $bdd->prepare("INSERT INTO historique_stock (produit_id, type_operation, quantite, utilisateur, date_operation) VALUES (?, ?, ?, ?, ?)");
        $insertHistorique->bind_param("isiss", $produit_id, $type_operation, $quantites, $utilisateur, $date);
        $insertHistorique->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Produit enregistré avec succès',
            showConfirmButton: false,
            timer: 2000
        });
    </script>";
}
?>

<!-- Contenu principal -->
<div class="content" id="content">
    <div class="form-wrapper">
        
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 id="modal-title" style="margin: 0;">Ajouter un produit</h2>
    <a href="liste_produit.php" style="text-decoration: none;">
        <button id="add-product" class="btn btn-primary">Liste des produits</button>
    </a>
</div>

        <form id="product-form" method="POST">
            <input type="hidden" id="product-id">

            <div class="form-group">
                <label for="reference">Référence</label>
                <input type="text" id="reference" name="reference" required>
            </div>

            <div class="form-group">
                <label for="product-name">Nom</label>
                <input type="text" id="product-name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Description du produit..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Categorie">Categorie</label>
                    <input type="text" id="Categorie" name="Categorie" required>
                </div>
                <div class="form-group">
                    <label for="prix-achat">Prix d'achat</label>
                    <input type="number" id="prix-achat" name="prix_achat" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="prix-vente">Prix de vente</label>
                    <input type="number" id="prix-vente" name="prix_vente" step="0.01" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="number" id="quantite" name="quantite" required>
                </div>
                
                <div class="form-group">
                    <label for="seuil-alerte">Seuil d'alerte</label>
                    <input type="number" id="seuil-alerte" name="seuil_alerte" required>
                </div>
            </div>

            <center>
                <button type="submit" name="enregistrer" class="btn">Enregistrer</button>
            </center>
        </form>
    </div>
</div>

<!-- Inclure SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CSS intégré -->
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
