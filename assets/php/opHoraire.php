<?php
require 'db_connection.php';

// Define the sports array with adherent types and Arabic translations
$sports = [
    ["type" => "fullcontact", "arabic" => "الفول كنتاكت شبان و كبار"],
    ["type" => "taekwondo", "arabic" => "التايكوندو كتاكيت و صغار"],
    ["type" => "taekwondo", "arabic" => "التايكوندو فتيان و فتيات"],
    ["type" => "taekwondo", "arabic" => "التايكوندو شبان و كبار"],
    ["type" => "aerobics for women", "arabic" => "اللياقة البدنية نساء"],
    ["type" => "aerobics for men", "arabic" => "اللياقة البدنية رجال"]
];

// Initialize schedule array
$schedule = [];

// Fetch current schedule
$sql = "SELECT * FROM schedule";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedule[$row['day']][$row['timeslot']] = $row['sport_type'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['delete'])) 
    {
        // Delete operation
        $class = $_POST['delete'];
        $query = "DELETE FROM schedule";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->close();
    } 
    else
    {
        // Clear existing schedule
        $sql = "DELETE FROM schedule";

        // Insert new schedule
        $newSchedule = $_POST['sport'];
        foreach ($newSchedule as $day => $times) {
            foreach ($times as $timeslot => $sport_type) {
                if ($sport_type !== '--') {
                    $arabic_name_index = array_search($sport_type, array_column($sports, 'type'));
                    $arabic_name = $sports[$arabic_name_index]['arabic'];

                    $sql = "INSERT INTO schedule (day, timeslot, sport_type, arabic_name) VALUES ('$day', '$timeslot', '$sport_type', '$arabic_name')";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        }
    }
}

$conn->close();
header("Location: ../admin/horaire.php");
?>
