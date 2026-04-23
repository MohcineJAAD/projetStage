<?php
require '../php/db_connection.php';
session_start();

// Define the sports array
$sql = "SELECT name FROM plans";
$result = $conn->query($sql);

$sports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sports[] = ["type" => $row['name']];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $class = $_POST['delete'];
        $query = "DELETE FROM schedule";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "تم الحذف";
        $_SESSION['status'] = "success";
    } else {
        $sql = "DELETE FROM schedule";
        if ($conn->query($sql) !== TRUE) {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['status'] = "error";
        }

        $newSchedule = $_POST['sport'];
        foreach ($newSchedule as $day => $times) {
            foreach ($times as $timeslot => $sport_type) {
                if ($sport_type !== '--') {
                    // Since the 'arabic' key is removed, we don't need to fetch it anymore
                    $sql = "INSERT INTO schedule (day, timeslot, sport_type) VALUES ('$day', '$timeslot', '$sport_type')";
                    if ($conn->query($sql) !== TRUE) {
                        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
                        $_SESSION['status'] = "error";
                    }
                }
            }
        }
        $_SESSION['message'] = "تم التعديل";
        $_SESSION['status'] = "success";
    }
}

$conn->close();
header("Location: horaire.php");
exit;
