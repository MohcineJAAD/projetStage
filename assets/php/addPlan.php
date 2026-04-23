<?php
require 'db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $conn->real_escape_string($_POST['planName']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['planPrice']);
    $adherence = $conn->real_escape_string($_POST['adherence']);
    $assurance = $conn->real_escape_string($_POST['assurance']);
    $sql = "INSERT INTO plans (name, price, description, assurance, adherence) VALUES ('$name', '$price', '$description', '$assurance', '$adherence')";
    if ($conn->query($sql) === TRUE)
    {
        header("Location: ../admin/plans.php");
        exit;
    }
    else
        echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
