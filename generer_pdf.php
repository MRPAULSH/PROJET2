<?php
require('fpdf/fpdf.php');
include('connexion.php');

if (!isset($_GET['id'])) {
    die("ID commande manquant.");
}

$id = $_GET['id'];

// Récupération des données de la commande
$stmt = $bdd->prepare("SELECT c.*, p.nom AS produit_nom, p.prix_vente FROM commandes c JOIN produits p ON c.produit_id = p.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Commande introuvable.");
}

$data = $result->fetch_assoc();
$prix_total = $data['quantite'] * $data['prix_vente'];

// Création du PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// En-tête
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, ("Reçu de Commande"), 0, 1, 'C');
$pdf->Ln(5);

// Infos client/commande
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, ("Commande N° :"), 0, 0);
$pdf->Cell(0, 8, $data['id'], 0, 1);

$pdf->Cell(40, 8, "Client :", 0, 0);
$pdf->Cell(0, 8, ($data['client']), 0, 1);

$pdf->Cell(40, 8, "Date :", 0, 0);
$pdf->Cell(0, 8, $data['date_commande'], 0, 1);
$pdf->Ln(10);

// Tableau des produits
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(52, 73, 94); // Couleur bleu foncé
$pdf->SetTextColor(255);
$pdf->Cell(80, 10, "Produit", 1, 0, 'C', true);
$pdf->Cell(30, 10, "Prix", 1, 0, 'C', true);
$pdf->Cell(30, 10, "Quantité", 1, 0, 'C', true);
$pdf->Cell(50, 10, "Sous-total", 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->Cell(80, 10, ($data['produit_nom']), 1);
$pdf->Cell(30, 10, number_format($data['prix_vente'], 2) . " $", 1);
$pdf->Cell(30, 10, $data['quantite'], 1);
$pdf->Cell(50, 10, number_format($prix_total, 2) . " $", 1, 1);

$pdf->Ln(8);

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, "Total à payer :", 0, 0, 'R');
$pdf->Cell(50, 10, number_format($prix_total, 2) . " $", 0, 1, 'R');

$pdf->Ln(20);

// Pied de page
$pdf->SetFont('Arial', 'I', 11);
$pdf->SetTextColor(100);
$pdf->Cell(0, 10, ("Merci pour votre achat !"), 0, 1, 'C');

// Affichage
$pdf->Output("I", "recu_commande_{$data['id']}.pdf");
exit;
