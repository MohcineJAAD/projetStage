<?php
session_start();
require '../../vendor/autoload.php';
require "../php/db_connection.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

if (isset($_POST['adherent'])) {
    // Check if session is selected
    if (empty($_POST['session'])) {
        $_SESSION['message'] = 'يرجى اختيار الدورة';
        $_SESSION['status'] = 'error';
        header('Location: exame.php');
        exit();
    }
    $identifiers = $_POST['adherent'];
    $session = $_POST['session'];
    $year = date("Y"); // Current year

    // Fetch admin details
    $stmt1 = $conn->prepare("SELECT * FROM admin");
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $admin = $result1->fetch_assoc();
    $stmt1->close();

    $files = [];
    $tempDir = '../temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $beltsTae = ["أبيض", "أصفر بخط أبيض", "أصفر", "برتقالي", "أخضر", "أزرق", "أزرق بخط أحمر", "أحمر", "أحمر بخط أسود", "أحمر بخطين أسودين"];

    foreach ($identifiers as $identifier) {
        // Fetch adherent details from the database
        $stmt = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $adherent = $result->fetch_assoc();
        $stmt->close();

        if ($adherent) {
            try {
                // Determine which template to use based on the adherent's current belt
                $templateFile = in_array($adherent['current_belt'], array_slice($beltsTae, array_search("أخضر", $beltsTae))) ? 
                                '../sheet/TestformExameGreen.xlsx' : 
                                '../sheet/TestformExame.xlsx';

                // Load the selected template
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($templateFile);
                $sheet = $spreadsheet->getActiveSheet();

                // Replace placeholders with adherent details
                $sheet->setCellValue('A8', htmlspecialchars($adherent['prenom'] . " " . $adherent['nom'])); // Name
                $sheet->setCellValue('A9', htmlspecialchars($adherent['current_belt'])); // Current belt
                $sheet->setCellValue('A10', htmlspecialchars($adherent['next_belt'])); // Next belt
                $sheet->setCellValue('A11', htmlspecialchars($admin['club_name'])); // Club name

                // Set the value in cell C6
                $sheet->setCellValue('C6', 'امتحان مختلف الاحزمة لدورة ' . htmlspecialchars($session) . ' ' . $year);

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
                    echo "Logo file not found: " . htmlspecialchars($logoPath) . "<br>";
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

                        // Update calculation using column width and row height
                        $offsetX = ($columnWidth * 7 - $drawing->getWidth()) / 2; // 7 is an approximate character width in points
                        $offsetY = ($rowHeight - $drawing->getHeight()) / 2;

                        $drawing->setOffsetX($offsetX);
                        $drawing->setOffsetY($offsetY);

                        $drawing->setWorksheet($sheet);
                    } else {
                        echo "Image file not found: " . htmlspecialchars($imagePath) . "<br>";
                        continue;
                    }
                }

                // Save the spreadsheet to a temporary file
                $filename = 'adherent_' . $identifier . '.xlsx';
                $filePath = $tempDir . $filename;
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($filePath);

                if (file_exists($filePath) && filesize($filePath) > 0) {
                    $files[] = $filePath;
                } else {
                    echo "Failed to create file for adherent: $identifier<br>";
                }
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                echo 'Error loading file: ', $e->getMessage(), "<br>";
            }
        }
    }

    if (count($files) > 0) {
        // Create a ZIP file containing all the spreadsheets
        $zip = new ZipArchive();
        $zipFilename = $tempDir . 'adherents.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE) !== true) {
            exit("Cannot open <$zipFilename>\n");
        }

        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        // Check if the ZIP file was created successfully
        if (file_exists($zipFilename) && filesize($zipFilename) > 0) {
            // Set headers and output the ZIP file to browser
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="adherents.zip"');
            header('Content-Length: ' . filesize($zipFilename));

            // Use readfile to output the file and catch any errors
            if (!readfile($zipFilename)) {
                echo "Failed to read the ZIP file for output.<br>";
            }

            // Clean up temporary files
            foreach ($files as $file) {
                unlink($file);
            }
            unlink($zipFilename);
        } else {
            echo "Failed to create ZIP file.<br>";
        }
    } else {
        echo "No files were created.<br>";
    }

    exit();
} 
else 
{
    $_SESSION['message'] = 'يرجى اختيار منخرط واحد على الاقل';
    $_SESSION['status'] = 'error';
    header('Location: exame.php');
    exit();
}
$conn->close();
?>
