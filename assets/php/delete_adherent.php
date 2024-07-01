<?php
session_start();
require 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete from `etudiants` table
        $query1 = "DELETE FROM adherents WHERE identifier = ?";
        $stmt1 = $conn->prepare($query1);
        if (!$stmt1) {
            throw new Exception("Préparation de la deuxième requête échouée: " . $conn->error);
        }
        $stmt1->bind_param("s", $id);
        if (!$stmt1->execute()) {
            throw new Exception("Exécution de la deuxième requête échouée: " . $stmt1->error);
        }
        $stmt1->close();

        // Delete from `utilisateurs` table
        $query2 = "DELETE FROM users WHERE identifier = ?";
        $stmt2 = $conn->prepare($query2);
        if (!$stmt2) {
            throw new Exception("Préparation de la troisième requête échouée: " . $conn->error);
        }
        $stmt2->bind_param("s", $id);
        if (!$stmt2->execute()) {
            throw new Exception("Exécution de la troisième requête échouée: " . $stmt2->error);
        }
        $stmt2->close();

        // Commit transaction
        $conn->commit();
        
        $_SESSION['message'] = 'Adhérent supprimé avec succès';
        $_SESSION['status'] = 'success';
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        $conn->rollback();
        
        $_SESSION['message'] = "Erreur lors de la suppression d'adhérent:" . $e->getMessage();
        $_SESSION['status'] = 'error';
    }
} else {
    $_SESSION['message'] = "Erreur lors de la suppression d'adhérent: ID non spécifié";
    $_SESSION['status'] = 'error';
}

$conn->close();
header("Location: ../admin/adherentes.php");
exit();
?>
