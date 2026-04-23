<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = urldecode($_GET['id']);
    $new_password = $_POST['newMotPass'];

    // Password validation function
    function validatePassword($password) {
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasLowerCase = preg_match('/[a-z]/', $password);
        $hasNumbers = preg_match('/\d/', $password);
        $isValidLength = strlen($password) >= 8 && strlen($password) <= 12;
        $noSpaces = !preg_match('/\s/', $password);
        return $hasUpperCase && $hasLowerCase && $hasNumbers && $isValidLength && $noSpaces;
    }

    if (!empty($new_password) && !validatePassword($new_password)) {
        $_SESSION['message'] = "Le mot de passe doit contenir au moins 8 caractères, inclure des chiffres, des lettres minuscules et majuscules, et ne pas contenir d'espaces.";
        $_SESSION['status'] = "error";
    } else {
        // Update the users table
        $user_query = "UPDATE users SET password = ? WHERE identifier = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("ss", $new_password, $id);

        if ($user_stmt->execute()) {
            $user_stmt->close();

            $_SESSION['message'] = "Le mot de passe a été mis à jour avec succès.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Une erreur est survenue lors de la mise à jour du mot de passe.";
            $_SESSION['status'] = "error";
            $user_stmt->close();
        }
    }

    $conn->close();
    header("Location: ../admin/profile-adherent.php?id=" . urlencode($id));
    exit();
} else {
    header("Location: ../admin/profile-adherent.php");
    exit();
}
?>
