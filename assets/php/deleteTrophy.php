<?php
session_start();
require 'db_connection.php';

if (isset($_GET['id']) && isset($_GET['identifier']))
{
    $id = intval($_GET['id']);
    $identifier = htmlspecialchars($_GET['identifier']);

    $stmt = $conn->prepare("DELETE FROM trophies WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Trophée supprimé avec succès.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression du trophée.";
        $_SESSION['status'] = "error";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID de trophée ou identifiant d'adhérent non fourni.";
    $_SESSION['status'] = "error";
}

header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
exit();
?>
