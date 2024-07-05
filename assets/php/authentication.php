<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = trim($_POST['userName']);
    $password = trim($_POST['password']);

    // Check if inputs are empty
    if (empty($identifiant) || empty($password)) {
        $_SESSION['error_message'] = "Identifiant ou mot de passe incorrect";
        header("Location: ../../login.php");
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT identifier, password, full_name FROM admin WHERE identifier = ?");
    $stmt->bind_param("s", $identifiant);
    $stmt->execute();
    $stmt->store_result();

    // Check if the admin exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $stored_password, $full_name);
        $stmt->fetch();

        // Verify password directly
        if ($password === $stored_password) {
            unset($_SESSION['error_message']);

            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = htmlspecialchars("$full_name", ENT_QUOTES, 'UTF-8');

            // Redirect to admin area
            header("Location: ../admin/");
            exit();
        } else {
            $_SESSION['error_message'] = "Identifiant ou mot de passe incorrect";
            header("Location: ../../login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Identifiant ou mot de passe incorrect";
        header("Location: ../../login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
