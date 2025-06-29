<?php
include('connexion.php');

$req = mysqli_query($bdd, "SELECT nom, quantite FROM produits LIMIT 5");

$labels = [];
$quantites = [];

while ($row = mysqli_fetch_assoc($req)) {
    $labels[] = $row['nom'];
    $quantites[] = $row['quantite'];
}

echo json_encode([
    "labels" => $labels,
    "quantites" => $quantites
]);


