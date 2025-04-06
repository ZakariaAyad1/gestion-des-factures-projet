<?php
ob_clean();
require('../../libs/fpdf186/fpdf.php');
require_once '../../BD/FactureModel.php';
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['facture_id']) || empty($_POST['facture_id'])) {
    echo json_encode(["error" => "Aucun ID de facture reçu.", "POST" => $_POST]);
    exit();
}

$factureId = $_POST['facture_id'];
$factureModel = new FactureModel();
$facture = $factureModel->getFactureById($factureId);

if ($facture) {
    try {
        // Configuration initiale
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 30);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetDrawColor(0, 0, 0);
        
        // Définition des couleurs
        $primaryColor = array(9, 47, 97);       // Bleu foncé
        $secondaryColor = array(240, 240, 240);   // Gris clair
        $accentColor = array(76, 175, 80);        // Vert
        $textColor = array(51, 51, 51);           // Gris foncé

        // En-tête stylisé
        $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Rect(0, 0, 210, 40, 'F');
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor(255);
        $pdf->SetY(12);
        $pdf->Cell(0, 10, utf8_decode('FACTURE'), 0, 1, 'C');
        
        // Informations société
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(200, 200, 200);
        $pdf->SetXY(10, 45);
        $societe = "Société EnergeX\n123 Avenue Mohammed VI\nCasablanca, Maroc\nTél: +212 522 123 456\ncontact@energex.ma";
        $pdf->MultiCell(80, 5, utf8_decode($societe), 0, 'L');

        // Bloc client
        $pdf->SetXY(120, 45);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(80, 7, utf8_decode('CLIENT'), 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, utf8_decode($facture['nom'] . ' ' . $facture['prenom']), 0, 1);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, utf8_decode($facture['adresse']), 0, 1);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, utf8_decode($facture['email']), 0, 1);

        // Détails facture
        $pdf->SetY(80);
        $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        $pdf->SetDrawColor(200);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('DÉTAILS DE LA FACTURE'), 1, 1, 'C', true);

        // Contenu tableau
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        
        $rows = array(
            array(utf8_decode("Référence"), $facture['facture_id']),
            array(utf8_decode("Date d'émission"), date('d/m/Y')),
            array(utf8_decode("Période de consommation"), $facture['mois'] . '/' . $facture['annee']),
            array(utf8_decode("Consommation (KWH)"), $facture['consommation']),
            array(utf8_decode("Prix HT (DH)"), number_format($facture['prix_ht'], 2, ',', ' ')),
            array(utf8_decode("TVA (%)"), $facture['tva']),
            array(utf8_decode("Montant TTC (DH)"), number_format($facture['prix_ttc'], 2, ',', ' ')),
            array(utf8_decode("Statut"), utf8_decode($facture['etat']))
        );

        foreach ($rows as $row) {
            $pdf->Cell(95, 8, $row[0], 'L', 0, 'L');
            $pdf->SetFont('', 'B');
            $pdf->Cell(95, 8, $row[1], 'R', 1, 'R');
            $pdf->SetFont('', '');
            $pdf->SetDrawColor(200);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        }

        // Encadré total
        $pdf->SetY($pdf->GetY() + 10);
        $pdf->SetFillColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 14);
        $totalText = 'TOTAL A PAYER : ' . number_format($facture['prix_ttc'], 2, ',', ' ') . ' DH';
        $pdf->Cell(190, 12, utf8_decode($totalText), 1, 1, 'C', true);

        // Section photo compteur
        if (!empty($facture['photo_compteur'])) {
            $pdf->SetY($pdf->GetY() + 15);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
            $pdf->Cell(0, 10, utf8_decode('RELEVE DE COMPTEUR'), 0, 1, 'C');
            
            $imagePath = '../../Traitement/' . $facture['photo_compteur'];
            if (file_exists($imagePath)) {
                $imgWidth = 50;
                $imgHeight = 0;
                $pdf->Image($imagePath, (210 - $imgWidth) / 2, $pdf->GetY(), $imgWidth, $imgHeight);
                $pdf->Ln(80);
            } else {
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(0, 10, utf8_decode("Image du compteur introuvable"), 0, 1, 'C');
            }
        }

        // Footer
        $pdf->SetY(-25);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetTextColor(150);
        $pdf->Cell(0, 5, utf8_decode('EnergeX - Société de distribution électrique'), 0, 1, 'C');
        $pdf->Cell(0, 5, utf8_decode('RCS Casablanca 123456789 - TVA Intracom MA123456789'), 0, 1, 'C');

        // Sauvegarde
        $pdfOutputPath = '../../factures/facture_' . $facture['facture_id'] . '.pdf';
        $pdf->Output('F', $pdfOutputPath);

        echo json_encode(["url" => $pdfOutputPath]);
        exit();

    } catch (Exception $e) {
        echo json_encode(["error" => "Erreur de génération : " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["error" => "Facture introuvable"]);
    exit();
}
?>
