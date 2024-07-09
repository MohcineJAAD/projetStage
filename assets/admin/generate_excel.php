<?php
require '../../vendor/autoload.php';
require "../php/db_connection.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use ZipArchive;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_adherents']) && isset($_POST['exam-session'])) {
    $selectedAdherents = $_POST['selected_adherents'];
    $examSession = $_POST['exam-session'];

    // Fetch admin details
    $stmt1 = $conn->prepare("SELECT * FROM admin");
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $admin = $result1->fetch_assoc();

    // Define the belt system
    $belts = ["أبيض", "أصفر بخط أبيض", "أصفر", "برتقالي", "أخضر", "أزرق", "أزرق بخط أحمر", "أحمر", "أحمر بخط أسود", "أحمر بخطين أسودين"];

    // Fetch current year
    $currentYear = date('Y');

    // Determine text based on the session
    $examText = $examSession === 'janvier' ? "امتحان مختلف الاحزمة لدورة يناير " . $currentYear : "امتحان مختلف الاحزمة لدورة يونيو " . $currentYear;

    // Create a temporary directory to store the Excel files
    $tempDir = sys_get_temp_dir() . '/adherents_' . uniqid();
    if (!mkdir($tempDir, 0777, true)) {
        exit("Cannot create temporary directory.\n");
    }

    $generatedFiles = [];

    foreach ($selectedAdherents as $identifier) {
        // Fetch adherent details from the database
        $stmt = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $adherent = $result->fetch_assoc();

        if ($adherent) {
            try {
                // Determine which template to use
                $templateFile = "../sheet/TestformExame.xlsx";
                if (array_search($adherent['next_belt'], $belts) >= array_search("أخضر", $belts)) {
                    $templateFile = "../sheet/SecondTemplate.xlsx"; // Adjust the path as needed
                }

                // Load the .xlsx template
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($templateFile);
                $sheet = $spreadsheet->getActiveSheet();

                // Replace placeholders with adherent details
                $sheet->setCellValue('A8', htmlspecialchars($adherent['prenom'] . " " . $adherent['nom'])); // Name
                $sheet->setCellValue('A9', htmlspecialchars($adherent['current_belt'])); // Current belt
                $sheet->setCellValue('A10', htmlspecialchars($adherent['next_belt'])); // Next belt
                $sheet->setCellValue('A11', htmlspecialchars($admin['club_name'])); // Club name
                $sheet->setCellValue('C6', $examText); // Exam session text

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
                    continue;
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
                        continue;
                    }
                }

                // Save the Excel file to the temporary directory
                $filename = $tempDir . '/adherent_' . $identifier . '.xlsx';
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($filename);

                // Add to generated files list
                $generatedFiles[] = $filename;
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                echo 'Error loading file: ', $e->getMessage();
                continue;
            }
        } else {
            echo "Adherent not found: " . htmlspecialchars($identifier);
            continue;
        }
    }

    if (count($generatedFiles) > 0) {
        // Create a zip file
        $zip = new ZipArchive();
        $zipFilename = $tempDir . '/adherents.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE) {
            exit("Cannot open <$zipFilename>\n");
        }

        // Add Excel files to the zip archive
        foreach ($generatedFiles as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        // Set headers and output the zip file to the browser
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment;filename="adherents.zip"');
        header('Cache-Control: max-age=0');
        readfile($zipFilename);

        // Clean up temporary files
        foreach ($generatedFiles as $file) {
            unlink($file);
        }
        rmdir($tempDir);

        exit();
    } else {
        echo "No files were generated.";
    }
}
$conn->close();
?>
