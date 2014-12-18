<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require('fpdf/fpdf.php');
include('../functies.php');

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 16);

$link = connectDB();

$bestelnr = $_GET["bestelnr"];
$result = mysqli_query($link, 'SELECT * FROM bestelling WHERE bestelnr = "' . $bestelnr . '";');
$row = mysqli_fetch_assoc($result);

$pdf->Image('../plaatjes/logo.png', 12, 6, 40);
$pdf->Ln(4);
$pdf->Cell(8, 30, 'Bestelnr: ' . $bestelnr);


$result2 = mysqli_query($link, 'SELECT * FROM anderadres WHERE bestelnr = "' . $bestelnr . '";');
$result3 = mysqli_query($link, 'SELECT voornaam, achternaam, bedrijfsnaam, adres, plaats, postcode FROM klant AS k JOIN bestelling AS b ON k.klantnr = b.klantnr WHERE bestelnr = "' . $bestelnr . '";');
$row3 = mysqli_fetch_assoc($result3);

$pdf->Ln(1);
$pdf->Cell(42, 40, 'Bedrijf: ');
$pdf->Cell(8, 40, $row3["bedrijfsnaam"]);
$pdf->Ln(6);
$pdf->Cell(42, 40, 'Ter Name Van: ');
$pdf->Cell(8, 40, $row3["voornaam"]." ".$row3["achternaam"]);
$pdf->Ln(10);
$pdf->Cell(25, 40, 'Afleveradres: ');


$pdf->SetFont('Helvetica', 'B', 12);

if (mysqli_num_rows($result2) == 1) {

    $row2 = mysqli_fetch_assoc($result2);

    $pdf->Ln(10);
    $pdf->Cell(25, 40, 'Plaats: ');
    $pdf->Cell(2, 40, $row2["plaats"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Adres: ');
    $pdf->Cell(2, 40, $row2["adres"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Postcode: ');
    $pdf->Cell(2, 40, $row2["postcode"]);
} else {
    $pdf->Ln(10);
    $pdf->Cell(25, 40, 'Plaats: ');
    $pdf->Cell(2, 40, $row3["plaats"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Adres: ');
    $pdf->Cell(2, 40, $row3["adres"]);
    $pdf->Ln(5);
    $pdf->Cell(25, 40, 'Postcode: ');
    $pdf->Cell(2, 40, $row3["postcode"]);
}

$result4 = mysqli_query($link, 'SELECT * FROM bestelregel AS b JOIN product AS p ON b.productnr = p.productnr WHERE bestelnr = "'.$bestelnr.'";');
$row4 = mysqli_fetch_assoc($result4);

$pdf->SetFont('Helvetica', 'B', 10);

    $pdf->Ln(30);
    $pdf->Cell(20, 10, 'Productnr', 1);
    $pdf->Cell(110, 10, 'Productnaam', 1);
    $pdf->Cell(15, 10, 'Prijs', 1);
    $pdf->Cell(15, 10, 'Aantal', 1);
    $pdf->Cell(25, 10, 'Totale Prijs', 1);

    $totaalBedrag = 0;
    $totaalBTW = 0;
    $totaalBedragBTW = 0;
    
while($row4){
    $pdf->Ln(10);
    $pdf->Cell(20, 10, $row4["productnr"], 1);
    $pdf->Cell(110, 10, $row4["productnaam"], 1);
    $pdf->Cell(15, 10, $row4["prijs"], 1);
    $pdf->Cell(15, 10, $row4["aantal"], 1);
    $pdf->Cell(25, 10, number_format($row4["prijs"] * $row4["aantal"], 2), 1);
    
    $totaalBedrag = $totaalBedrag + ($row4["prijs"] * $row4["aantal"]);
    $row4 = mysqli_fetch_assoc($result4);
}

$totaalBTW = $totaalBedrag * 0.21;
$totaalBedragBTW = $totaalBTW + $totaalBedrag;

$pdf->Ln(10);
$pdf->Cell(35, 10, number_format($totaalBedrag,2));
$pdf->Ln(10);
$pdf->Cell(35, 10, number_format($totaalBTW,2));
$pdf->Ln(10);
$pdf->Cell(35, 10, number_format($totaalBedragBTW,2));

$pdf->Output();
