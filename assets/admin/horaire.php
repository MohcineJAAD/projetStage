<?php
require '../php/db_connect.php';

// Fetch subjects
$mat = ["EDI", "MCOO", "GL", "FR", "SGBD", "POO", "ENG", "MATH", "GP", "QTM", "AR", "DEV WEB", "TEC", "C/S", "PFE"];
$classes = ["1DSI", "2DSI", "1PME", "2PME"];
$days = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];

// Fetch professors affiliated with each class
$professorsByClass = [];
foreach ($classes as $class) {
    $profQuery = "SELECT p.id, CONCAT('Pr.', u.nom, ' ', u.prenom) as name, matricule 
                  FROM professeurs p
                  JOIN utilisateurs u ON p.matricule = u.identifiant
                  WHERE branche LIKE '%$class%'";
    $profResult = $conn->query($profQuery);
    while ($row = $profResult->fetch_assoc()) {
        $professorsByClass[$class][$row['matricule']] = ['id' => $row['id'], 'name' => $row['name']];
    }
}

// Fetch schedule data from database
$scheduleQuery = "SELECT h.*, CONCAT('Pr.', u.nom, ' ', u.prenom) as professor_name, p.matricule 
                  FROM horaires h 
                  JOIN professeurs p ON h.professeur_id = p.id 
                  JOIN utilisateurs u ON p.matricule = u.identifiant";
$result = $conn->query($scheduleQuery);
$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[$row['classe']][$row['jour']][$row['heure_debut'] . '-' . $row['heure_fin']] = [
        'subject' => $row['matiere'],
        'professor' => $row['professor_name'],
        'matricule' => $row['matricule']
    ];
}
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
                <?php foreach ($classes as $class) : ?>
                    <div class="accordion-item m-20">
                        <div class="accordion-header">
                            <span><?= $class ?></span>
                            <span class="toggle-icon">></span>
                        </div>
                        <div class="accordion-content">
                            <form class="horaire responsive-table" method="post" action="../php/opHoraire.php">
                                <input type="hidden" name="class" value="<?= $class ?>">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Jour/Heure</th>
                                            <th colspan="2">8-10</th>
                                            <th colspan="2">10-12</th>
                                            <th colspan="2">14-16</th>
                                            <th colspan="2">16-18</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($days as $day) : ?>
                                            <tr>
                                                <th><?= $day ?></th>
                                                <?php
                                                $timeSlots = [
                                                    "08:00:00-10:00:00",
                                                    "10:00:00-12:00:00",
                                                    "14:00:00-16:00:00",
                                                    "16:00:00-18:00:00"
                                                ];
                                                foreach ($timeSlots as $time) :
                                                    $subject = $schedule[$class][$day][$time]['subject'] ?? '--';
                                                    $professor = $schedule[$class][$day][$time]['professor'] ?? '--';
                                                    $matricule = $schedule[$class][$day][$time]['matricule'] ?? '--';
                                                ?>
                                                    <td colspan="2">
                                                        <select name="subject[<?= $day ?>][<?= $time ?>]" class="subject" disabled>
                                                            <option><?= $subject ?></option>
                                                            <option>--</option>
                                                            <?php foreach ($mat as $value) : ?>
                                                                <option><?= $value ?></option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                        <select name="professor[<?= $day ?>][<?= $time ?>]" class="professor" disabled>
                                                            <option value="<?= $matricule ?>"><?= $professor ?></option>
                                                            <option value="--">--</option>
                                                            <?php foreach ($professorsByClass[$class] as $matriculeKey => $value) : ?>
                                                                <option value="<?= $matriculeKey ?>"><?= $value['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <input type="hidden" name="matricule[<?= $day ?>][<?= $time ?>]" value="<?= $matricule ?>">
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="action-buttons">
                                    <button type="button" class="edit-btn btn-shape mb-10"><i class="fas fa-edit"></i> Modifier</button>
                                    <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> Sauvegarder</button>
                                    <button type="submit" class="delete-btn btn-shape mb-10" name="delete" value="<?php echo $class; ?>"><i class="fas fa-trash"></i> Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
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
