<?php
session_start();
require 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo $id;

    // Begin transaction
    $conn->begin_transaction();

    // Delete from `payments` table
    $query0 = "DELETE FROM payments WHERE identifier = ?";
    $stmt0 = $conn->prepare($query0);
    $stmt0->bind_param("s", $id);
    $stmt0->execute();
    $stmt0->close();

    // Delete from `adherents` table
    $query1 = "DELETE FROM adherents WHERE identifier = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("s", $id);
    $stmt1->execute();
    $stmt1->close();

    // Commit transaction
    $conn->commit();
    
    $_SESSION['message'] = 'Adhérent supprimé avec succès';
    $_SESSION['status'] = 'success';
} else {
    $_SESSION['message'] = "Erreur lors de la suppression d'adhérent: ID non spécifié";
    $_SESSION['status'] = 'error';
}

$conn->close();
header("Location: ../admin/adherentes.php");
exit();
?>
