<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $date = $_POST['date'];
    $adherent_id = $_POST['adherent_id'];

    $query = "INSERT INTO trophies (adherent_id, description, created_at) VALUES ('$adherent_id', '$description', '$date')";
    if ($conn->query($query) === TRUE) {
        // Redirect back to the page with the list of titles
        header("Location: ../admin/profile-adherent.php?id=$adherent_id");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
