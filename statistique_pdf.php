<?php
require('fpdf/fpdf.php');
include('connexion.php');

// Données
$totalProduits = mysqli_fetch_assoc(mysqli_query($bdd, "SELECT COUNT(*) AS total FROM produits"))['total'];
$alertesStock = mysqli_fetch_assoc(mysqli_query($bdd, "SELECT COUNT(*) AS total FROM produits WHERE quantite <= seuil_alerte"))['total'];
$date = date("d/m/Y");

// Création PDF
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Statistiques de Stock - $date",0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(80,10,"Nombre total de produits :",0);
$pdf->Cell(0,10,$totalProduits,0,1);

$pdf->Cell(80,10,"Produits en alerte (stock faible) :",0);
$pdf->Cell(0,10,$alertesStock,0,1);

$pdf->Ln(10);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,"Généré par le système PGME Compagny",0,1,'C');

$pdf->Output("I", "statistiques_stock.pdf");
exit;
