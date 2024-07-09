<?php
require '../php/db_connection.php';

// Define the days array
$days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

// Define the sports array with adherent types and Arabic translations
$sports = [
    ["type" => "fullcontact", "arabic" => "الفول كنتاكت شبان و كبار"],
    ["type" => "taekwondo", "arabic" => "التايكوندو كتاكيت و صغار"],
    ["type" => "taekwondo", "arabic" => "التايكوندو فتيان و فتيات"],
    ["type" => "taekwondo", "arabic" => "التايكوندو شبان و كبار"],
    ["type" => "aerobics for women", "arabic" => "اللياقة البدنية نساء"],
    ["type" => "aerobics for men", "arabic" => "اللياقة البدنية رجال"]
];

// Fetch current schedule
$schedule = [];
$sql = "SELECT * FROM schedule";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedule[$row['day']][$row['timeslot']] = $row['sport_type'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clear existing schedule
    $sql = "DELETE FROM schedule";
    if ($conn->query($sql) !== TRUE) {
        echo "Error deleting records: " . $conn->error;
    }

    // Insert new schedule
    $newSchedule = $_POST['sport'];
    foreach ($newSchedule as $day => $times) {
        foreach ($times as $timeslot => $sport_type) {
            if ($sport_type !== '--') {
                $arabic_name_index = array_search($sport_type, array_column($sports, 'type'));
                $arabic_name = $sports[$arabic_name_index]['arabic'];

                $day = $conn->real_escape_string($day);
                $timeslot = $conn->real_escape_string($timeslot);
                $sport_type = $conn->real_escape_string($sport_type);
                $arabic_name = $conn->real_escape_string($arabic_name);

                $sql = "INSERT INTO schedule (day, timeslot, sport_type, arabic_name) VALUES ('$day', '$timeslot', '$sport_type', '$arabic_name')";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
    echo "Schedule saved successfully!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/framework.css">
    <link rel="stylesheet" href="../css/dashbord.css">
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <title>Dashboard</title>
</head>

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">L'emploi du temps</h1>
            <div class="accordion-container">
                <div class="accordion-item m-20">
                    <div class="accordion-header">
                        <span>emploi temp</span>
                        <span class="toggle-icon">></span>
                    </div>
                    <div class="accordion-content">
                        <form class="horaire responsive-table" method="post" action="../php/opHoraire.php">
                            <input type="hidden" name="class" value="">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Jour/Heure</th>
                                        <th>19:30-20:30</th>
                                        <th>20:30-21:30</th>
                                        <th>21:30-22:30</th>
                                        <th>22:30-23:30</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($days as $day) : ?>
                                        <tr>
                                            <th><?= $day ?></th>
                                            <?php
                                            $timeSlots = [
                                                "19:30:00-20:30:00",
                                                "20:30:00-21:30:00",
                                                "21:30:00-22:30:00",
                                                "22:30:00-23:30:00"
                                            ];
                                            foreach ($timeSlots as $time) :
                                            ?>
                                                <td>
                                                    <select name="sport[<?= $day ?>][<?= $time ?>]" class="sport" disabled>
                                                        <option>--</option>
                                                        <?php foreach ($sports as $sport) : ?>
                                                            <option value="<?= $sport['type'] ?>" <?= isset($schedule[$day][$time]) && $schedule[$day][$time] == $sport['type'] ? 'selected' : '' ?>><?= $sport['arabic'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="action-buttons">
                                <button type="button" class="edit-btn btn-shape mb-10"><i class="fas fa-edit"></i> Modifier</button>
                                <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> Sauvegarder</button>
                                <button type="submit" class="delete-btn btn-shape mb-10" name="delete" value=""><i class="fas fa-trash"></i> Supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const accordionItems = document.querySelectorAll('.accordion-item');
            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');
                const content = item.querySelector('.accordion-content');
                const icon = header.querySelector('.toggle-icon');

                header.addEventListener('click', function() {
                    const isOpen = content.classList.contains('open');
                    // Close all accordions
                    accordionItems.forEach(acc => {
                        acc.querySelector('.accordion-content').classList.remove('open');
                        acc.querySelector('.toggle-icon').classList.remove('rotate');
                    });
                    // Toggle current accordion
                    if (!isOpen) {
                        content.classList.add('open');
                        icon.classList.add('rotate');
                    } else {
                        content.classList.remove('open');
                        icon.classList.remove('rotate');
                    }
                });
            });

            // Edit and Save functionality
            document.querySelectorAll('.accordion-content form').forEach(form => {
                const editBtn = form.querySelector('.edit-btn');
                const saveBtn = form.querySelector('.save-btn');
                const deleteBtn = form.querySelector('.delete-btn');
                const selectElements = form.querySelectorAll('select');

                // Initially hide the save button
                saveBtn.classList.add('hidden');

                editBtn.addEventListener('click', function() {
                    selectElements.forEach(select => {
                        select.disabled = false;
                        select.classList.add('editable');
                    });
                    editBtn.style.display = 'none'; // Hide the edit button
                    deleteBtn.style.display = 'none'; // Hide the delete button
                    saveBtn.style.display = 'inline-block'; // Show the save button
                });

                deleteBtn.addEventListener('click', function(event) {
                    selectElements.forEach(select => {
                        select.value = '--';
                    });
                    saveBtn.classList.remove('hidden'); // Show save button to confirm deletion
                    editBtn.style.display = 'none'; // Hide edit button
                    deleteBtn.style.display = 'none'; // Hide delete button
                });
            });
        });
    </script>
</body>

</html>