<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $birthDate = $_POST['birthDate'];
    $adhesionDate = $_POST['adhesionDate'];
    $guardianName = $_POST['guardianName'];
    $guardianPhone = $_POST['guardianPhone'];
    $secondGuardianPhone = $_POST['secondGuardianPhone'];
    $address = $_POST['address'];
    $sport = $_POST['sport'];
    $beltLevel = isset($_POST['beltLevel']) ? $_POST['beltLevel'] : null;
    $nextBeltLevel = isset($_POST['nextBeltLevel']) ? $_POST['nextBeltLevel'] : null;
    $poids = $_POST['weight'];
    $healthStatus = $_POST['healthStatus'];
    $bloodType = $_POST['bloodType'];
    $licence = $_POST['licence'];
    $note = $_POST['note'];
    
    // Retrieve existing file paths
    $stmt = $conn->prepare("SELECT image_path, BC_path FROM adherents WHERE identifier = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->bind_result($existingImagePath, $existingBCPath);
    $stmt->fetch();
    $stmt->close();
    
    // File upload handling
    $imageFileName = $existingImagePath;
    $BCFileName = $existingBCPath;

    // Handle new image upload
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) 
    {
        $file_type = mime_content_type($_FILES['imageUpload']['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png'];

        if (in_array($file_type, $allowed_types)) {
            $upload_dir = '../uploads/';
            $imageFileName = basename($_FILES['imageUpload']['name']);
            $imagePath = $upload_dir . $imageFileName;

            // Ensure the file does not already exist
            if (!file_exists($imagePath)) {
                if (move_uploaded_file($_FILES['imageUpload']['tmp_name'], $imagePath)) {
                    // Delete the old image file
                    if ($existingImagePath && file_exists('../uploads/' . $existingImagePath)) {
                        unlink('../uploads/' . $existingImagePath);
                    }
                } else {
                    $_SESSION['message'] = "Erreur lors du téléchargement de l'image.";
                    $_SESSION['status'] = "error";
                    header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
                    exit();
                }
            } else {
                $_SESSION['message'] = "Le fichier image existe déjà.";
                $_SESSION['status'] = "warning";
                header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
                exit();
            }
        } else {
            $_SESSION['message'] = "Type de fichier non autorisé.";
            $_SESSION['status'] = "error";
            header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
            exit();
        }
    }

    // Handle new birth certificate upload
    if (isset($_FILES['BCUpload']) && $_FILES['BCUpload']['error'] === UPLOAD_ERR_OK)
    {
        $file_type = mime_content_type($_FILES['BCUpload']['tmp_name']);
        $allowed_types = ['application/pdf'];

        if (in_array($file_type, $allowed_types)) 
        {
            $upload_dir = '../uploads/';
            $BCFileName = basename($_FILES['BCUpload']['name']);
            $BCPath = $upload_dir . $BCFileName;

            // Ensure the file does not already exist
            if (!file_exists($BCPath)) {
                if (move_uploaded_file($_FILES['BCUpload']['tmp_name'], $BCPath)) {
                    // Delete the old birth certificate file
                    if ($existingBCPath && file_exists('../uploads/' . $existingBCPath)) {
                        unlink('../uploads/' . $existingBCPath);
                    }
                } else {
                    $_SESSION['message'] = "Erreur lors du téléchargement du certificat de naissance.";
                    $_SESSION['status'] = "error";
                    header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
                    exit();
                }
            } 
            else 
            {
                $_SESSION['message'] = "Le fichier certificat de naissance existe déjà.";
                $_SESSION['status'] = "warning";
                header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
                exit();
            }
        } 
        else 
        {
            $_SESSION['message'] = "Type de fichier non autorisé.";
            $_SESSION['status'] = "error";
            header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
            exit();
        }
    }

    // Update adherent information
    $stmt = $conn->prepare("UPDATE adherents SET prenom = ?, nom = ?, date_naissance = ?, date_adhesion = ?, guardian_name = ?, guardian_phone = ?, second_guardian_phone = ?, address = ?, type = ?, current_belt = ?, next_belt = ?, poids = ?, health_status = ?, blood_type = ?, licence = ?, image_path = ?, BC_path = ?, note = ? WHERE identifier = ?");
    $stmt->bind_param("sssssssssssdsssssss", $prenom, $nom, $birthDate, $adhesionDate, $guardianName, $guardianPhone, $secondGuardianPhone, $address, $sport, $beltLevel, $nextBeltLevel, $poids, $healthStatus, $bloodType, $licence, $imageFileName, $BCFileName, $note, $identifier);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Profil mis à jour avec succès.";
        $_SESSION['status'] = "success";
        header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour du profil: " . $stmt->error;
        $_SESSION['status'] = "error";
        header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
    }
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['message'] = "Méthode non autorisée";
    $_SESSION['status'] = "error";
    header("Location: ../admin/profile-adherent.php");
}
?>
