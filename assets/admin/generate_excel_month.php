<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

require '../../vendor/autoload.php';
require "../php/db_connection.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

$currentMonth = date('Y-m'); // Get the current year and month in 'YYYY-MM' format
$sql = "
    SELECT a.nom, a.prenom, a.type
    FROM adherents a
    WHERE NOT EXISTS (
        SELECT 1
        FROM payments p
        WHERE a.identifier = p.identifier
          AND DATE_FORMAT(p.payment_date, '%Y-%m') = ?
          AND p.type = 'mois'
    )
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$result = $stmt->get_result();

$adherents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $adherents[] = $row;
    }
}

$stmt->close();
$conn->close();

// Load template
$spreadsheet = IOFactory::load('../sheet/payment_month_reminder.xlsx');
$sheet = $spreadsheet->getActiveSheet();

// Add data after the title (assuming the title is in the first row)
$rowNum = 9; // Start from the ninth row after the title and headers
foreach ($adherents as $adherent) {
    // Merge cells for name
    $sheet->mergeCells("E{$rowNum}:I{$rowNum}");
    $sheet->setCellValue("E{$rowNum}", $adherent['nom'] . " " . $adherent['prenom']);
    
    // Merge cells for sport/type
    $sheet->mergeCells("A{$rowNum}:D{$rowNum}");
    $sheet->setCellValue("A{$rowNum}", $adherent['type']);
    
    // Apply borders and font size
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'font' => [
            'size' => 16,
        ],
    ];
    $sheet->getStyle("A{$rowNum}:I{$rowNum}")->applyFromArray($styleArray);
    
    $rowNum++;
}

// Set headers to force download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="month_list.xlsx"');
header('Cache-Control: max-age=0');

// Save to output stream
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
