<?php
require "../php/db_connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $discipline = intval($_POST['discipline']);
    $performance = intval($_POST['performance']);
    $behavior = intval($_POST['behavior']);
    $month = date('m');
    $year = date('Y');

    // Check if an evaluation for this month exists
    $stmt = $conn->prepare("SELECT * FROM evaluations WHERE identifier = ? AND month = ? AND year = ?");
    $stmt->bind_param("sii", $identifier, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the old evaluation if it exists
        $stmt = $conn->prepare("DELETE FROM evaluations WHERE identifier = ? AND month = ? AND year = ?");
        $stmt->bind_param("sii", $identifier, $month, $year);
        $stmt->execute();
    }

    // Insert the new evaluation
    $stmt = $conn->prepare("INSERT INTO evaluations (identifier, month, year, discipline, performance, behavior) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiii", $identifier, $month, $year, $discipline, $performance, $behavior);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Évaluation enregistrée avec succès.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erreur lors de l'enregistrement de l'évaluation.";
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../admin/evaluation.php");
    exit();
}
?>
