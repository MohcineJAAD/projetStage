<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $full_name = $_POST['full_name'];
    $club_name = $_POST['club_name'];
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT logo FROM admin");
    if ($stmt === false)
    {
        $_SESSION['message'] = "حدث خطأ في جلب الشعار الحالي.";
        $_SESSION['status'] = "error";
        header("Location: ../admin/settings.php");
        exit();
    }
    $stmt->execute();
    $stmt->bind_result($existingImagePath);
    $stmt->fetch();
    $stmt->close();

    $upload_dir = '../images/';
    $logo = $existingImagePath;

    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK)
    {
        $file_type = mime_content_type($_FILES['imageUpload']['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png'];

        if (in_array($file_type, $allowed_types))
        {
            $imageFileName = basename($_FILES['imageUpload']['name']);
            $imagePath = $upload_dir . $imageFileName;
            unlink($upload_dir . $existingImagePath);

            if (move_uploaded_file($_FILES['imageUpload']['tmp_name'], $imagePath))
                $logo = $imageFileName;
            else
            {
                $_SESSION['message'] = "حدث خطأ في تحميل الصورة.";
                $_SESSION['status'] = "error";
                header("Location: ../admin/settings.php");
                exit();
            }
        }
        else
        {
            $_SESSION['message'] = "يقبل فقط .png او .jpeg";
            $_SESSION['status'] = "error";
            header("Location: ../admin/settings.php");
            exit();
        }
    }
    $stmt = $conn->prepare("UPDATE admin SET identifier = ?, password = ?, full_name = ?, club_name = ?, logo = ? WHERE id = 1");
    if ($stmt === false)
    {
        $_SESSION['message'] = "حدث خطأ في تحديث الاعدادات.";
        $_SESSION['status'] = "error";
        header("Location: ../admin/settings.php");
        exit();
    }
    $stmt->bind_param("sssss", $identifier, $password, $full_name, $club_name, $logo);
    
    if ($stmt->execute())
    {
        $_SESSION['message'] = "تم ضبط الاعدادات.";
        $_SESSION['status'] = "success";
    }
    else
    {
        $_SESSION['message'] = "حدث خطأ في ضبط الاعدادات: " . $stmt->error;
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../admin/settings.php");
    exit();
}
else
{
    header("Location: ../admin/settings.php");
    exit();
}
?>
