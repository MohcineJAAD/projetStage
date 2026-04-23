<?php
session_start();
require '../php/db_connection.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// Redirect if 'id' parameter is not set in the URL
if (!isset($_GET['id'])) {
    header("Location: ../admin/adherentes.php");
    exit();
}

$id = $_GET['id'];

// Get the current date
$current_date = date('Y-m-d');
$status = "active";

// Prepare and execute the UPDATE statement for adherents
$stmt1 = $conn->prepare("UPDATE adherents SET date_adhesion = ?, status = ? WHERE identifier = ?");
$stmt1->bind_param("sss", $current_date, $status, $id);

// Execute the statement within a transaction for consistency
$conn->begin_transaction();

$success = true;

// Update adherents table
if (!$stmt1->execute()) {
    $success = false;
    error_log("Error updating adherents: " . $stmt1->error);
}

// Commit or rollback the transaction
if ($success) {
    $conn->commit(); // Commit transaction if update was successful
    $_SESSION['message'] = "L'adhérent a été accepté avec succès.";
    $_SESSION['status'] = "success";
} else {
    $conn->rollback(); // Rollback transaction if update fails
    $_SESSION['message'] = "Erreur lors de l'acceptation de l'adhérent.";
    $_SESSION['status'] = "error";
}

// Close the statement and connection
$stmt1->close();
$conn->close();

// Redirect back to the adherents page
header("Location: ../admin/adherentes.php");
exit();
?>
