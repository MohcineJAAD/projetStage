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
    header("Location: ../admin/adherentes.php");
    exit();
}

$id = $_GET['id'];

// Prepare and execute the DELETE statement for adherents
$stmt1 = $conn->prepare("DELETE FROM adherents WHERE identifier = ?");
$stmt1->bind_param("s", $id);

// Execute the DELETE statement
if ($stmt1->execute()) {
    $_SESSION['message'] = "L'adhérent a été rejeté avec succès.";
    $_SESSION['status'] = "success";
} else {
    $_SESSION['message'] = "Erreur lors de la suppression de l'adhérent: " . $stmt1->error;
    $_SESSION['status'] = "error";
}

// Close the statement and connection
$stmt1->close();
$conn->close();

// Redirect back to the adherents page
header("Location: ../admin/adherentes.php");
exit();
?>
