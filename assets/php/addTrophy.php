<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['trophyDescription'];
    $identifier = $_POST['identifier'];

    // Validate inputs
    if (empty($description) || empty($identifier)) {
        $_SESSION['message'] = "Description vide";
        $_SESSION['status'] = "error";
        header("Location: ../admin/profile-adherent.php?id=$identifier"); // Correct redirection path
        exit();
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO trophies (adherent_id, description, created_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . $conn->error);
    }

    $date = date("Y-m-d");
    $stmt->bind_param("sss", $identifier, $description, $date);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Trophée ajouté avec succès.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erreur lors de l'ajout du trophée.";
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect to profile page
    header("Location: ../admin/profile-adherent.php?id=$identifier"); // Correct redirection path
    exit();
}
?>