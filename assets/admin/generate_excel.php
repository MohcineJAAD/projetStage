<?php
require '../../vendor/autoload.php';
require "../php/db_connection.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

if (isset($_GET['id'])) {
    $identifier = $_GET['id'];

    // Fetch adherent details from the database
    $stmt = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    $adherent = $result->fetch_assoc();

    // Fetch admin details
    $stmt1 = $conn->prepare("SELECT * FROM admin");
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $admin = $result1->fetch_assoc();

    if ($adherent) {
        try {
            // Load the .xlsx template
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load('../sheet/TestformExame.xlsx');
            $sheet = $spreadsheet->getActiveSheet();

            // Replace placeholders with adherent details
            $sheet->setCellValue('A8', htmlspecialchars($adherent['prenom'] . " " . $adherent['nom'])); // Name
            $sheet->setCellValue('A9', htmlspecialchars($adherent['current_belt'])); // Current belt
            $sheet->setCellValue('A10', htmlspecialchars($adherent['next_belt'])); // Next belt
            $sheet->setCellValue('A11', htmlspecialchars($admin['club_name'])); // Club name

            // Add logo image
            $logoPath = realpath('../images/logo3osba.jpg');
            if ($logoPath && file_exists($logoPath)) {
                $logoDrawing = new Drawing();
                $logoDrawing->setName('Logo');
                $logoDrawing->setDescription('Logo');
                $logoDrawing->setPath($logoPath);
                $logoDrawing->setHeight(120); // Adjust height to fit in the merged cell

                // Set coordinates to the top-left cell of the merged area
                $logoDrawing->setCoordinates('A2');
                $logoDrawing->setWorksheet($sheet);
            } else {
                echo "Logo file not found: " . htmlspecialchars($logoPath);
                exit();
            }

            // Add adherent image if available
            if (!empty($adherent['image_path'])) {
                $imagePath = realpath('../uploads/' . $adherent['image_path']);
                if ($imagePath && file_exists($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Adherent Image');
                    $drawing->setDescription('Adherent Image');
                    $drawing->setPath($imagePath); // Path to the image
                    $drawing->setHeight(120); // Adjust height to fit in the merged cell

                    // Set coordinates to the top-left cell of the merged area
                    $drawing->setCoordinates('H2');

                    // Center the image within the merged cells
                    $columnWidth = $sheet->getColumnDimension('H')->getWidth();
                    $rowHeight = 0;
                    for ($row = 2; $row <= 7; $row++) {
                        $rowHeight += $sheet->getRowDimension($row)->getRowHeight();
                    }

                    $offsetX = ($columnWidth * 7 - $drawing->getWidth()) / 2; // 7 is an approximate character width in points
                    $offsetY = ($rowHeight - $drawing->getHeight()) / 2;

                    $drawing->setOffsetX($offsetX);
                    $drawing->setOffsetY($offsetY);

                    $drawing->setWorksheet($sheet);
                } else {
                    echo "Image file not found: " . htmlspecialchars($imagePath);
                    exit();
                }
            }

            // Set headers and output to browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="adherent_' . $identifier . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            echo 'Error loading file: ',  $e->getMessage();
            exit();
        }
    } else {
        echo "Adherent not found.";
    }

    $stmt->close();
}
$conn->close();
?>
