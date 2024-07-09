<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['adherent_id'];
    $id = $_POST['trophy_id'];

    $query = "DELETE FROM trophies WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Supprimer";
        $_SESSION['status'] = "success";
        header("Location: ../admin/profile-adherent.php?id=" . urlencode($identifier));
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
        $_SESSION['message'] = "Error: "."<br>" . $conn->error;
        $_SESSION['status'] = "error";
    }
    $conn->close();
}
?>
