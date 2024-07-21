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

$currentYear = date('Y'); // Get the current year in 'YYYY' format
$sql = "
    SELECT a.nom, a.prenom, a.type
    FROM adherents a
    WHERE NOT EXISTS (
        SELECT 1
        FROM payments p
        WHERE a.identifier = p.identifier
          AND YEAR(p.payment_date) = ?
          AND p.type = 'assurance'
    )
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentYear);
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
$spreadsheet = IOFactory::load('../sheet/payment_assurance_reminder.xlsx');
$sheet = $spreadsheet->getActiveSheet();

// Define border style
$borderStyle = [
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

$sheet->getStyle('A7')->applyFromArray($borderStyle);
$sheet->getStyle('E7')->applyFromArray($borderStyle);

$rowNum = 8;
foreach ($adherents as $adherent) {
    $sheet->mergeCells("E{$rowNum}:I{$rowNum}");
    $sheet->setCellValue("E{$rowNum}", $adherent['nom'] . " " . $adherent['prenom']);
    
    // Merge cells for sport/type
    $sheet->mergeCells("A{$rowNum}:D{$rowNum}");
    $sheet->setCellValue("A{$rowNum}", $adherent['type']);
    
    // Apply borders and font size
    $sheet->getStyle("A{$rowNum}:I{$rowNum}")->applyFromArray($borderStyle);
    
    $rowNum++;
}

// Ensure the title row has borders
$sheet->getStyle('A7:I7')->applyFromArray($borderStyle);

// Set headers to force download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="list_assurance.xlsx"');
header('Cache-Control: max-age=0');

// Save to output stream
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>
