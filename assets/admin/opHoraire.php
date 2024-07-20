<?php
require '../php/db_connection.php';
session_start();

// Define the sports array with adherent types and Arabic translations
$sports = [
    ["type" => "فول كونتاكت", "arabic" => "الفول كنتاكت شبان و كبار"],
    ["type" => "تايكواندو", "arabic" => "التايكوندو كتاكيت و صغار"],
    ["type" => "تايكواندو", "arabic" => "التايكوندو فتيان و فتيات"],
    ["type" => "تايكواندو", "arabic" => "التايكوندو شبان و كبار"],
    ["type" => "aerobics for women", "arabic" => "اللياقة البدنية نساء"],
    ["type" => "aerobics for men", "arabic" => "اللياقة البدنية رجال"]
];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Delete operation
        $class = $_POST['delete'];
        $query = "DELETE FROM schedule";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "تم الحذف";
        $_SESSION['status'] = "successe";
    } else
    {
        $sql = "DELETE FROM schedule";
        if ($conn->query($sql) !== TRUE) {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['status'] = "error";
        }

        $newSchedule = $_POST['sport'];
        foreach ($newSchedule as $day => $times) {
            foreach ($times as $timeslot => $sport_type) {
                if ($sport_type !== '--') {
                    $arabic_name_index = array_search($sport_type, array_column($sports, 'type'));
                    $arabic_name = $sports[$arabic_name_index]['arabic'];

                    $sql = "INSERT INTO schedule (day, timeslot, sport_type, arabic_name) VALUES ('$day', '$timeslot', '$sport_type', '$arabic_name')";
                    if ($conn->query($sql) !== TRUE) {
                        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
                        $_SESSION['status'] = "error";
                    }
                }
            }
        }
        $_SESSION['message'] = "تم التعديل";
        $_SESSION['status'] = "successe";
    }
}

$conn->close();
header("Location: horaire.php");
exit;
