<?php
session_start();
require '../php/db_connection.php'; // Include your database connection file

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// Redirect if 'id' parameter is not set in the URL
if (!isset($_GET['id'])) {
    header("Location: ../admin/adhrentes.php");
    exit();
}

$id = $_GET['id'];

// Prepare and execute the DELETE statement for adherents
$stmt1 = $conn->prepare("DELETE FROM adherents WHERE identifier = ?");
$stmt1->bind_param("s", $id);

if ($stmt1->execute()) {
    $stmt1->close(); // Close the first statement

    // Prepare and execute the DELETE statement for users
    $stmt2 = $conn->prepare("DELETE FROM users WHERE identifier = ?");
    $stmt2->bind_param("s", $id);

    if ($stmt2->execute()) {
        $_SESSION['message'] = "L'adhérent a été rejeté avec succès.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression de l'utilisateur: " . $stmt2->error;
        $_SESSION['status'] = "error";
    }
    $stmt2->close(); // Close the second statement
} else {
    $_SESSION['message'] = "Erreur lors de la suppression de l'adhérent: " . $stmt1->error;
    $_SESSION['status'] = "error";
}

$conn->close(); // Close the database connection

// Redirect back to the adherents page
header("Location: ../admin/adherentes.php");
exit();
?>
