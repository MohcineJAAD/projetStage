<?php
require 'db_connection.php';
if (isset($_GET['id']))
{
    $planId = intval($_GET['id']);
    $sql = "DELETE FROM plans WHERE id = ?";
    if ($stmt = $conn->prepare($sql))
    {
        $stmt->bind_param("i", $planId);
        if ($stmt->execute())
        {
            header("Location: ../admin/plans.php");
            exit();
        }
        else
            echo "Error deleting record: " . $stmt->error;
        $stmt->close();
    }
    else
        echo "Error preparing statement: " . $conn->error;
}
else
{
    header("Location: ../admin/plans.php");
    exit();
}
$conn->close();