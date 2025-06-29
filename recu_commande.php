<?php
include('connexion.php');

if (!isset($_GET['id'])) {
    die("ID commande manquant.");
}

$id = $_GET['id'];
$stmt = $bdd->prepare("SELECT c.*, p.nom AS produit_nom, p.prix_vente FROM commandes c JOIN produits p ON c.produit_id = p.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Commande introuvable.");
}

$data = $result->fetch_assoc();
$prix_total = $data['quantite'] * $data['prix_vente'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            max-width: 800px;
            margin: auto;
            background: #fff;
            color: #333;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .infos {
            margin-bottom: 20px;
        }

        .infos div {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-style: italic;
            color: #777;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .no-print {
            margin-top: 30px;
            text-align: center;
        }

        .no-print button, .no-print a {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            background-color: #2c3e50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .no-print button:hover, .no-print a:hover {
            background-color: #1a242f;
        }
    </style>
</head>
<body>

<h1>Reçu de Commande</h1>

<div class="infos">
    <div><strong>Commande N° :</strong> <?php echo $data['id'] ?></div>
    <div><strong>Client :</strong> <?php echo htmlspecialchars($data['client']) ?></div>
    <div><strong>Date :</strong> <?php echo $data['date_commande'] ?></div>
</div>

<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Sous-total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo htmlspecialchars($data['produit_nom']) ?></td>
            <td><?php echo number_format($data['prix_vente'], 2) ?> $</td>
            <td><?php echo $data['quantite'] ?></td>
            <td><?php echo number_format($prix_total, 2) ?> $</td>
        </tr>
    </tbody>
</table>

<div class="total">
    Prix total : <?php echo number_format($prix_total, 2) ?> $
</div>

<div class="footer">
    Merci pour votre achat !
</div>

<div class="no-print">
    <button onclick="window.print()">Imprimer</button>
    <a href="generer_pdf.php?id=<?php echo $data['id'] ?>" target="_blank">Télécharger PDF</a>
</div>

</body>
</html>
