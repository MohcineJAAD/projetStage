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
    $stmt = $conn->prepare("SELECT identifier, role, password FROM users WHERE identifier = ?");
    $stmt->bind_param("s", $identifiant);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $role, $stored_password);
        $stmt->fetch();

        // Verify password directly
        if ($password === $stored_password) {
            unset($_SESSION['error_message']);

            // Fetch user details for session variables
            $stmt = $conn->prepare("SELECT nom, prenom, type FROM adherents WHERE identifier = ?");
            $stmt->bind_param("s", $identifiant);
            $stmt->execute();
            $stmt->bind_result($nom, $prenom, $type);
            $stmt->fetch();

            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['user_name'] = htmlspecialchars("$prenom $nom", ENT_QUOTES, 'UTF-8');

            // Update last_login timestamp if needed
            // Example: $conn->query("UPDATE utilisateurs SET last_login = NOW() WHERE id = $id");

            // Redirect based on role
            if ($role === "admin") {
                header("Location: ../admin/");
            } else {
                switch ($type) {
                    case 'Fullcontact':
                        header("Location: ../Fullcontact/");
                        break;
                    case 'Taekwondo':
                        header("Location: ../Taekwondo/");
                        break;
                    default:
                        header("Location: ../../login.php");
                        break;
                }
            }
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
