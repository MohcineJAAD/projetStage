<?php
require 'db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $conn->real_escape_string($_POST['planName']);
    $price = $conn->real_escape_string($_POST['planPrice']);
    $sql = "INSERT INTO plans (name, price) VALUES ('$name', '$price')";
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
