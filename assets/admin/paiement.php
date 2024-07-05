<?php
require "../php/db_connection.php";
session_start();

$stmt_fullcontact = $conn->prepare("SELECT * FROM adherents WHERE status = ? AND type = ?");
$status = 'active';
$type_fullcontact = 'Fullcontact';
$stmt_fullcontact->bind_param("ss", $status, $type_fullcontact);
$stmt_fullcontact->execute();
$result_fullcontact = $stmt_fullcontact->get_result();

$stmt_taekwondo = $conn->prepare("SELECT * FROM adherents WHERE status = ? AND type = ?");
$type_taekwondo = 'Taekwondo';
$stmt_taekwondo->bind_param("ss", $status, $type_taekwondo);
$stmt_taekwondo->execute();
$result_taekwondo = $stmt_taekwondo->get_result();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/framework.css">
    <link rel="stylesheet" href="../css/dashbord.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
</head>

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">Paiement</h1>
            <div class="accordion-container">
                <div class="accordion-item m-20">
                    <div class="accordion-header">
                        <span>Fullcontact</span>
                        <span class="toggle-icon">></span>
                    </div>
                    <div class="accordion-content">
                        <form class="horaire responsive-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom complet</th>
                                        <th>Identifiant</th>
                                        <th>Sport</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result_fullcontact->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['prenom'] . " " . $row['nom']) ?></td>
                                            <td><?= htmlspecialchars($row['identifier']) ?></td>
                                            <td><?= htmlspecialchars($row['type']) ?></td>
                                            <td><?= htmlspecialchars($row['date_adhesion']) ?></td>
                                            <td><a href='paiement_child.php?id=<?php echo $row['identifier'] ?>&date=<?php echo date("Y") ?>'><span class='label btn-shape bg-c-60 color-fff'>Paiement</span></a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="accordion-item m-20">
                    <div class="accordion-header">
                        <span>Taekwondo</span>
                        <span class="toggle-icon">></span>
                    </div>
                    <div class="accordion-content">
                        <form class="horaire responsive-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom complet</th>
                                        <th>Identifiant</th>
                                        <th>Sport</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result_taekwondo->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['prenom'] . " " . $row['nom']) ?></td>
                                            <td><?= htmlspecialchars($row['identifier']) ?></td>
                                            <td><?= htmlspecialchars($row['type']) ?></td>
                                            <td><?= htmlspecialchars($row['date_adhesion']) ?></td>
                                            <td><a href='paiement_child.php?id=<?php echo $row['identifier'] ?>&date=<?php echo date("Y") ?>'><span class='label btn-shape bg-c-60 color-fff'>Paiement</span></a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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
        });
    </script>
    <script>
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
                backgroundColor: type === "error" ? "#FF3030" : "#2F8C37",
                stopOnFocus: true
            }).showToast();
        }
    </script>
</body>

</html>