<?php
include('menu.php');


// Vérifie si l'ID est présent
if (!isset($_GET['id'])) {
    echo "ID produit manquant.";
    exit;
}

$id = $_GET['id'];

// Récupération des données du produit
$stmt = $bdd->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produit = $result->fetch_assoc();

if (!$produit) {
    echo "Produit introuvable.";
    exit;
}

// Mise à jour du produit
if (isset($_POST['modifier'])) {
    $reference = $_POST['reference'];
    $nom = $_POST['product_name'];
    $description = $_POST['description'];
    $prix_achat = $_POST['prix_achat'];
    $prix_vente = $_POST['prix_vente'];
    $quantite = $_POST['quantite'];
    $seuil_alerte = $_POST['seuil_alerte'];

    $update = $bdd->prepare("UPDATE produits SET referencex=?, nom=?, descrption=?, prix_achat=?, prix_vente=?, quantite=?, seuil_alerte=? WHERE id=?");
    $update->bind_param("sssddiii", $reference, $nom, $description, $prix_achat, $prix_vente, $quantite, $seuil_alerte, $id);
    $update->execute();

    $produit_id = $id; // ou $_POST['produit_id'] selon le contexte
    $type_operation = 'modification'; // ou 'retrait' ou 'commande' ou 'suppression commande'
    $quantites = $quantite;
    $utilisateur = $_SESSION['Nom_utilisateur']; // si tu stockes le nom de l'utilisateur connecté
    $date = date('Y-m-d H:i:s');

    $insertHistorique = $bdd->prepare("INSERT INTO historique_stock (produit_id, type_operation, quantite, utilisateur, date_operation) VALUES (?, ?, ?, ?, ?)");
    $insertHistorique->bind_param("isiss", $produit_id, $type_operation, $quantites, $utilisateur, $date);
    $insertHistorique->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Produit modifié avec succès',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href='produit.php';
        });
    </script>";
}
?>

<!-- Formulaire de modification -->
<div class="content" id="content">
    <div class="form-wrapper">
        <span class="close" onclick="window.location.href='produit.php';">&times;</span>
        <h2>Modifier le produit</h2>
        <form method="POST">
            <div class="form-group">
                <label>Référence</label>
                <input type="text" name="reference" value="<?php echo htmlspecialchars($produit['referencex']) ?>" required>
            </div>

            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="product_name" value="<?php echo htmlspecialchars($produit['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($produit['descrption']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix d'achat</label>
                    <input type="number" name="prix_achat" step="0.01" value="<?php echo $produit['prix_achat'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Prix de vente</label>
                    <input type="number" name="prix_vente" step="0.01" value="<?php echo $produit['prix_vente'] ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="number" name="quantite" value="<?php echo $produit['quantite'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Seuil d'alerte</label>
                    <input type="number" name="seuil_alerte" value="<?php echo $produit['seuil_alerte'] ?>" required>
                </div>
            </div>

            <center>
                <button type="submit" name="modifier" class="btn">Modifier</button>
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
