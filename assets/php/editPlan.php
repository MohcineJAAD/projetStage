<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $planId = $_POST['planId'];
    $newPrice = $_POST['planPrice'];
    $adherence = $_POST['adherence'];
    $assurance = $_POST['assurance'];
    $newDescription = $_POST['description'];

    if (!empty($planId) && !empty($newPrice))
    {
        $sql = "UPDATE plans SET price = ?, description = ?, assurance = ?, adherence = ?  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsddi", $newPrice, $newDescription, $assurance, $adherence, $planId);

        if ($stmt->execute())
        {
            header('Location: ../admin/plans.php');
            exit();
        }
        else
            echo "Error updating record: " . $conn->error;
        $stmt->close();
    }
    else
        echo "Form data missing.";
}
$conn->close();
?>