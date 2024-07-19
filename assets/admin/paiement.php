<?php
require "../php/db_connection.php";
session_start();

$status = 'active';

// Prepare statements for each type of adherent
$types = ['فول كونتاكت', 'تايكواندو', 'إيروبيك / رجال', 'إيروبيك / سيدات'];
$results = [];
foreach ($types as $type) {
    $stmt = $conn->prepare("SELECT * FROM adherents WHERE status = ? AND type = ?");
    $stmt->bind_param("ss", $status, $type);
    $stmt->execute();
    $results[$type] = $stmt->get_result();
}

$filter_id = $_GET['id'] ?? '';
$filter_date = $_GET['datePaiemnt'] ?? '';

$payments_query = "SELECT * FROM payments join adherents on payments.identifier = adherents.identifier WHERE 1";
if ($filter_id) {
    $payments_query .= " AND identifier = ?";
}
if ($filter_date) {
    $payments_query .= " AND payment_date = ?";
}

$stmt_payments = $conn->prepare($payments_query);
if ($filter_id && $filter_date) {
    $stmt_payments->bind_param("ss", $filter_id, $filter_date);
} elseif ($filter_id) {
    $stmt_payments->bind_param("s", $filter_id);
} elseif ($filter_date) {
    $stmt_payments->bind_param("s", $filter_date);
}
$stmt_payments->execute();
$result_payments = $stmt_payments->get_result();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/framework.css">
    <link rel="stylesheet" href="../css/dashbord.css">
    <link rel="stylesheet" href="../css/master1.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">إدارة الدفع</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <div class="accordion-container">
                    <div class="options w-full m-20">
                        <div class="branch-filter mt-10 mb-10">
                            <button class="btn-shape bg-c-60 color-fff active mb-10"><a href='generate_excel_month.php'>لائحة الشهر</a></button>
                            <button class="btn-shape bg-c-60 color-fff active mb-10"><a href='generate_excel_adherence.php'>لائحة الاشتراك</a></button>
                            <button class="btn-shape bg-c-60 color-fff active mb-10"><a href='generate_excel_assurance.php'>لائحة التأمين</a></button>
                        </div>
                    </div>
                    <div class="accordion-item m-20">
                        <div class="accordion-header">
                            <span>السجل</span>
                            <span class="toggle-icon">></span>
                        </div>
                        <div class="accordion-content">
                            <form class="horaire responsive-table" method="get" action="">
                                <div class="row">
                                    <div class="input-field">
                                        <label for="datePaiemnt">تاريخ الدفع</label>
                                        <input type="date" id="datePaiemnt" name="datePaiemnt" value="<?= htmlspecialchars($filter_date) ?>">
                                    </div>
                                    <div class="input-field">
                                        <label for="id">المنخرط</label>
                                        <input type="text" id="id" name="id" placeholder="ادخل المعرف" value="<?= htmlspecialchars($filter_id) ?>">
                                    </div>
                                    <div class="input-field">
                                        <button type="submit" class="btn-shape bg-c-60 color-fff">بحث</button>
                                    </div>
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>الاسم الكامل</th>
                                            <th>المعرف</th>
                                            <th>نوع الدفع</th>
                                            <th>المبلغ</th>
                                            <th>تاريخ الدفع</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_payments->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['nom']." ".$row['nom']) ?></td>
                                                <td><?= htmlspecialchars($row['identifier']) ?></td>
                                                <td><?= htmlspecialchars($row['type']) ?></td>
                                                <td><?= htmlspecialchars($row['amount']) ?></td>
                                                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                    <?php foreach ($types as $type) : ?>
                        <div class="accordion-item m-20">
                            <div class="accordion-header">
                                <span><?= htmlspecialchars(ucfirst($type)) ?></span>
                                <span class="toggle-icon">></span>
                            </div>
                            <div class="accordion-content">
                                <form class="horaire responsive-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>الاسم الكامل</th>
                                                <th>المعرف</th>
                                                <th>الرياضة</th>
                                                <th>تاريخ الاشتراك</th>
                                                <th>اجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $results[$type]->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['prenom'] . " " . $row['nom']) ?></td>
                                                    <td><?= htmlspecialchars($row['identifier']) ?></td>
                                                    <td><?= htmlspecialchars($row['type']) ?></td>
                                                    <td><?= htmlspecialchars($row['date_adhesion']) ?></td>
                                                    <td><a href='paiement_child.php?id=<?= $row['identifier'] ?>&date=<?= date("Y") ?>'><span class='label btn-shape bg-c-60 color-fff'>دفع</span></a></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                backgroundColor: type === "error" ? "#FF3030" : "#2F8C37",
                stopOnFocus: true
            }).showToast();
        }
    </script>
</body>

</html>