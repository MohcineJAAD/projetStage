<?php
require "../php/db_connection.php";
session_start();

// Function to get all plans
function getPlans($conn)
{
    $stmt = $conn->prepare("SELECT name FROM plans");
    $stmt->execute();
    $result = $stmt->get_result();
    $plans = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $plans;
}

// Function to get adherents based on status and type
function getAdherents($conn, $status, $type)
{
    $stmt = $conn->prepare("SELECT * FROM adherents WHERE status = ? AND type = ?");
    $stmt->bind_param("ss", $status, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

// Function to get filtered payments
function getFilteredPayments($conn, $filter_date)
{
    $payments_query = "SELECT payments.*, nom, prenom FROM payments JOIN adherents ON payments.identifier = adherents.identifier WHERE payments.Date = ?";
    $stmt_payments = $conn->prepare($payments_query);
    $stmt_payments->bind_param("s", $filter_date);
    $stmt_payments->execute();
    $result_payments = $stmt_payments->get_result();
    $stmt_payments->close();
    return $result_payments;
}

$status = 'active';
$plans = getPlans($conn);
$types = array_column($plans, 'name');
$results = [];

// Fetch adherents for each plan type
foreach ($types as $type) {
    $results[$type] = getAdherents($conn, $status, $type);
}

// Get current date or the user-selected date
$current_date = date('Y-m-d');
$filter_date = $_POST['datePaiemnt'] ?? $current_date;

$result_payments = getFilteredPayments($conn, $filter_date);

?>
<!DOCTYPE html>
<html lang="ar">

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
                <div class="options w-full m-20">
                    <div class="branch-filter mt-10 mb-10">
                        <button class="btn-shape bg-c-60 color-fff mb-10"><a href='generate_excel_month.php'>لائحة الشهر</a></button>
                        <button class="btn-shape bg-c-60 color-fff mb-10"><a href='generate_excel_adhesion.php'>لائحة الاشتراك</a></button>
                        <button class="btn-shape bg-c-60 color-fff mb-10"><a href='generate_excel_assurance.php'>لائحة التأمين</a></button>
                    </div>
                </div>
                <h2 class="mt-0 mb-20">بحث متقدم</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="section mb-20">
                        <div class="row">
                            <div class="input-field">
                                <label for="datePaiemnt">تاريخ الدفع :</label>
                                <input type="date" id="datePaiemnt" name="datePaiemnt" value="<?= htmlspecialchars($filter_date) ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-20">بحث</button>
                    </div>
                </form>
                <div class="responsive-table">
                    <?php if ($result_payments->num_rows > 0) : ?>
                        <table class="fs-15 w-full">
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
                                        <td><?= htmlspecialchars($row['prenom'] . " " . $row['nom']) ?></td>
                                        <td><?= htmlspecialchars($row['identifier']) ?></td>
                                        <td><?= htmlspecialchars($row['type']) ?></td>
                                        <td><?= htmlspecialchars($row['amount']) ?></td>
                                        <td><?= htmlspecialchars($row['Date']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p style="text-align: center;">لا توجد نتائج مطابقة</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <div class="accordion-container">
                    <?php foreach ($types as $type) : ?>
                        <div class="accordion-item m-20">
                            <div class="accordion-header">
                                <span><?= htmlspecialchars(ucfirst($type)) ?></span>
                                <span class="toggle-icon">></span>
                            </div>
                            <div class="accordion-content">
                                <form class="horaire responsive-table special">
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
                                                    <td><a href='paiement_child.php?id=<?= htmlspecialchars($row['identifier']) ?>&date=<?= date("Y") ?>'><span class='label btn-shape bg-c-60 color-fff'>دفع</span></a></td>
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
