<?php
require '../php/db_connection.php';

$sql = "SELECT name FROM plans";
$result = $conn->query($sql);

$sports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sports[] = ["type" => $row['name']];
    }
}

$days = ["الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت", "الأحد"];
$schedule = [];

$sql = "SELECT * FROM schedule";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedule[$row['day']][$row['timeslot']] = $row['sport_type'];
    }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">إدارة الجدول الزمني</h1>
            <div class="accordion-container">
                <div class="accordion-item m-20">
                    <div class="accordion-header">
                        <span>الجدول الزمني</span>
                        <span class="toggle-icon">></span>
                    </div>
                    <div class="accordion-content">
                        <form class="horaire responsive-table special" method="post" action="opHoraire.php">
                            <input type="hidden" name="class" value="">
                            <table>
                                <thead>
                                    <tr>
                                        <th>اليوم/الوقت</th>
                                        <th>16:30-17:30</th>
                                        <th>17:30-18:30</th>
                                        <th>18:30-19:30</th>
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
                                                "16:30:00-17:30:00",
                                                "17:30:00-18:30:00",
                                                "18:30:00-19:30:00",
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
                                                            <option value="<?= $sport['type'] ?>" <?= isset($schedule[$day][$time]) && $schedule[$day][$time] == $sport['type'] ? 'selected' : '' ?>><?= $sport['type'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="action-buttons">
                                <button type="button" class="edit-btn modify-btn btn-shape mb-10"><i class="fas fa-edit"></i> تعديل</button>
                                <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> حفض</button>
                                <button type="submit" class="delete-btn btn-shape mb-10" name="delete" value="class_value_here"><i class="fas fa-trash"></i> حذف</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionItems = document.querySelectorAll('.accordion-item');
            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');
                const content = item.querySelector('.accordion-content');
                const icon = header.querySelector('.toggle-icon');

                header.addEventListener('click', function() {
                    const isOpen = content.classList.contains('open');
                    accordionItems.forEach(acc => {
                        acc.querySelector('.accordion-content').classList.remove('open');
                        acc.querySelector('.toggle-icon').classList.remove('rotate');
                    });
                    if (!isOpen) {
                        content.classList.add('open');
                        icon.classList.add('rotate');
                    } else {
                        content.classList.remove('open');
                        icon.classList.remove('rotate');
                    }
                });
            });

            document.querySelectorAll('.accordion-content form').forEach(form => {
                const editBtn = form.querySelector('.edit-btn');
                const saveBtn = form.querySelector('.save-btn');
                const deleteBtn = form.querySelector('.delete-btn');
                const selectElements = form.querySelectorAll('select');

                saveBtn.classList.add('hidden');

                editBtn.addEventListener('click', function() {
                    selectElements.forEach(select => {
                        select.disabled = false;
                        select.classList.add('editable');
                    });
                    editBtn.style.display = 'none';
                    deleteBtn.style.display = 'none';
                    saveBtn.style.display = 'inline-block';
                });

                deleteBtn.addEventListener('click', function(event) {
                    selectElements.forEach(select => {
                        select.value = '--';
                    });
                    saveBtn.classList.remove('hidden');
                    editBtn.style.display = 'none';
                    deleteBtn.style.display = 'none';
                });
            });
        });

        <?php
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
            $status_message = $_SESSION['message'];
            $status_type = $_SESSION['status'];
            echo "showToast('" . addslashes($status_message) . "', '" . addslashes($status_type) . "');";
            unset($_SESSION['message']);
            unset($_SESSION['status']);
        }
        ?>

        function showToast(message, type) {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: type === "error" ? "#FF3030" : "#2F8C37",
                },
                stopOnFocus: true
            }).showToast();
        }
    </script>
</body>

</html>
