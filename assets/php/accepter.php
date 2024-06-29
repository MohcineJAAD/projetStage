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

// Get the current date
$current_date = date('Y-m-d');

// Prepare and execute the UPDATE statement for adherents
$stmt1 = $conn->prepare("UPDATE adherents SET date_adhesion = ? WHERE identifier = ?");
$stmt1->bind_param("ss", $current_date, $id);

// Prepare and execute the UPDATE statement for users
$stmt2 = $conn->prepare("UPDATE users SET status = ? WHERE identifier = ?");
$status_active = 'active';
$stmt2->bind_param("ss", $status_active, $id);

// Execute both statements within a transaction for consistency
$conn->begin_transaction();

$success = true;

// Update adherents table
if (!$stmt1->execute()) {
    $success = false;
}

// Update users table
if (!$stmt2->execute()) {
    $success = false;
}

if ($success) {
    $_SESSION['message'] = "L'adhérent a été accepté avec succès.";
    $_SESSION['status'] = "success";
    $conn->commit(); // Commit transaction if both updates were successful
} else {
    $_SESSION['message'] = "Erreur lors de l'acceptation de l'adhérent.";
    $_SESSION['status'] = "error";
    $conn->rollback(); // Rollback transaction if any update fails
}

$stmt1->close(); // Close the statement for adherents
$stmt2->close(); // Close the statement for users
$conn->close(); // Close the database connection

// Redirect back to the adherents page
header("Location: ../admin/adherentes.php");
exit();
?>
