<?php
require "db_connection.php";
session_start();

function generateUniqueIdentifier($conn) {
    $query = "SELECT identifier FROM adherents ORDER BY identifier DESC LIMIT 1";
    $result = $conn->query($query);
    $lastIdentifier = $result->fetch_assoc()['identifier'] ?? null;

    return $lastIdentifier ? incrementIdentifier($lastIdentifier) : 'A000000001';
}

function incrementIdentifier($identifier) {
    $alphaPart = substr($identifier, 0, 1);
    $numPart = substr($identifier, 1);
    $incrementedNumPart = str_pad((int)$numPart + 1, 9, '0', STR_PAD_LEFT);

    if ($incrementedNumPart === '1000000000') {
        $incrementedNumPart = '000000001';
        $alphaPart = chr(ord($alphaPart) + 1);
    }

    return $alphaPart . $incrementedNumPart;
}

function handleFileUpload($file, &$fileName) {
    $allowedTypes = ['image/jpeg', 'image/png'];
    $uploadDir = '../uploads/';

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($file['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $uniqueName = uniqid() . "_" . basename($file['name']);
            $targetFile = $uploadDir . $uniqueName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!file_exists($targetFile)) {
                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    if ($fileName !== 'default_image.png' && file_exists($uploadDir . $fileName)) {
                        unlink($uploadDir . $fileName);
                    }
                    $fileName = $uniqueName;
                    return true;
                }
            } else {
                $_SESSION['toast_message'] = 'Le fichier existe déjà.';
                $_SESSION['toast_type'] = 'warning';
            }
        } else {
            $_SESSION['toast_message'] = 'Type de fichier non autorisé.';
            $_SESSION['toast_type'] = 'error';
        }
    } else {
        $_SESSION['toast_message'] = 'Erreur lors de l\'upload de l\'image.';
        $_SESSION['toast_type'] = 'error';
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['birthDate'])) {
    // Store all posted data in session in case of an error
    $_SESSION['formData'] = $_POST;

    $birthDate = trim($_POST['birthDate']);
    $currentDate = date('Y-m-d');

    if ($birthDate >= $currentDate) {
        $_SESSION['message'] = "La date de naissance ne peut pas être dans le futur.";
        $_SESSION['status'] = "error";
        header("Location: ../../sign_up.php");
        exit();
    }

    $identifier = generateUniqueIdentifier($conn);
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $address = trim($_POST['address']);
    $guardianName = trim($_POST['guardianName']);
    $guardianPhone = trim($_POST['guardianPhone']);
    $sport = trim($_POST['sport']);
    $beltLevel = trim($_POST['beltLevel']);
    $weight = trim($_POST['weight']);
    $healthStatus = trim($_POST['healthStatus']);
    $bloodType = !empty($_POST['bloodType']) ? trim($_POST['bloodType']) : null;
    $membershipDate = date('Y-m-d');

    if (empty($prenom) || empty($nom) || empty($birthDate) || empty($address) || empty($sport) || empty($weight) || empty($healthStatus)) {
        $_SESSION['message'] = " *الرجاء ملء جميع الحقول الإلزامية";
        $_SESSION['status'] = "error";
        header("Location: ../../sign_up.php");
        exit();
    }

    $fileName = 'default_image.png';

    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] !== UPLOAD_ERR_NO_FILE) {
        handleFileUpload($_FILES['imageUpload'], $fileName);
    }

    $sql = "INSERT INTO adherents (identifier, nom, prenom, date_naissance, poids, type, date_adhesion, image_path, guardian_name, guardian_phone, address, blood_type, health_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $identifier, $nom, $prenom, $birthDate, $weight, $sport, $membershipDate, $fileName, $guardianName, $guardianPhone, $address, $bloodType, $healthStatus);

    if ($stmt->execute()) {
        $_SESSION['message'] = $identifier;
        $_SESSION['status'] = "success";
        // Clear form data from session after successful submission
        unset($_SESSION['formData']);
    } else {
        $_SESSION['message'] = "Erreur lors de l'inscription: " . $stmt->error;
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../../index.php");
    exit();
}

$conn->close();
?>
