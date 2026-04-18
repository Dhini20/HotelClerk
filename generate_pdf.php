<?php
// Connect to database
include '../db/connection.php';

// Include Composer autoloader
require '../vendor/autoload.php';

// No "use" needed — FPDF is in the global namespace
// use Fpdf\Fpdf;  <-- ❌ Remove this line

// Check if reservation ID is passed
if (!isset($_GET['id'])) {
    die('Reservation ID is required.');
}

$reservationID = intval($_GET['id']);

// Fetch reservation and customer data
$query = $conn->prepare("
    SELECT r.*, c.FirstName, c.LastName, c.NIC, c.PhoneNo, c.Email, c.Address
    FROM Reservation r
    JOIN Customer c ON r.CustomerID = c.CustomerID
    WHERE r.ReservationID = :id
");
$query->bindParam(':id', $reservationID, PDO::PARAM_INT);
$query->execute();
$data = $query->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die('Reservation not found.');
}

// Extend FPDF to create custom header/footer
class PDF extends FPDF {
    function Header() {
        $this->SetFillColor(18, 65, 112); // #124170
        $this->Rect(0, 0, 210, 30, 'F');
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 15, 'Hotel Reservation System', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, 1, 'C');
        $this->SetTextColor(18, 65, 112);
        $this->Cell(0, 10, 'Thank you for choosing our hotel!', 0, 0, 'C');
    }

    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(18, 65, 112);
        $this->Cell(0, 10, $title, 0, 1, 'L');
        $this->Ln(2);
    }

    function InfoRow($label, $value) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(38, 102, 127);
        $this->Cell(50, 8, $label . ":", 0, 0, 'L');
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 8, $value, 0, 'L');
        $this->Ln(2);
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetMargins(20, 30, 20);

// Add reservation details
$pdf->SectionTitle('Reservation Details');
$pdf->InfoRow('Reservation ID', $data['ReservationID']);
$pdf->InfoRow('Check-In Date', $data['CheckIn']);
$pdf->InfoRow('Check-Out Date', $data['CheckOut']);
$pdf->InfoRow('Number of Guests', $data['NumGuests']);
$pdf->InfoRow('Number of Beds', $data['BedCount']);
$pdf->InfoRow('Status', $data['Status']);
$pdf->InfoRow('Total Fee (USD)', number_format($data['Total_Fee'], 2));
$pdf->InfoRow('Created Date', $data['CreatedDate']);
$pdf->Ln(5);

// Add customer details
$pdf->SectionTitle('Customer Details');
$fullname = trim($data['FirstName'] . ' ' . $data['LastName']);
$pdf->InfoRow('Customer Name', $fullname ?: 'N/A');
$pdf->InfoRow('NIC', $data['NIC']);
$pdf->InfoRow('Phone Number', $data['PhoneNo']);
$pdf->InfoRow('Email', $data['Email']);
$pdf->InfoRow('Address', $data['Address']);

// Separator
$pdf->Ln(8);
$pdf->SetDrawColor(38, 102, 127);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(8);

// Footer message
$pdf->SetFont('Arial', 'I', 12);
$pdf->SetTextColor(18, 65, 112);
$pdf->MultiCell(0, 10, "This document confirms that the above reservation details were recorded in the hotel system.\nPlease contact the hotel administration for any inquiries or updates.");

$filename = "Reservation_" . $data['ReservationID'] . ".pdf";
$pdf->Output('D', $filename);
exit;
?>
