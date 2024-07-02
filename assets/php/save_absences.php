<?php
session_start();
include '../php/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $absences = isset($_POST['absente']) ? $_POST['absente'] : [];

    foreach ($absences as $studentId) {
        $date = date('Y-m-d');
        // Prepare and execute the SQL statement to insert absence record
        $stmt = $conn->prepare("INSERT INTO attendance (identifier, date) VALUES (?, ?)");
        $stmt->bind_param("ss", $studentId, $date);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['message'] = "Absence enregistrée avec succès.";
    $_SESSION['status'] = "success";

    header("location: ../admin/absence.php");
    exit();
}
?>
