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
    <title>Dashboard</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">الدفع</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20">بطاقة الدفع</h2>
                <?php
                $years = range(date("Y"), 2010);
                $identifier = $_GET['id'] ?? '';
                $year = intval($_GET['date'] ?? date("Y"));

                $monthsPaid = [];
                $annualPaid = false;
                $adhesionPaid = false;

                if (!empty($identifier)) {
                    require '../php/db_connection.php';

                    $sql = "SELECT type, payment_date FROM payments WHERE identifier = ? AND YEAR(payment_date) = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $identifier, $year);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        if ($row['type'] == 'assurance') {
                            $annualPaid = true;
                        } elseif ($row['type'] == 'adhesion') {
                            $adhesionPaid = true;
                        } else {
                            $monthsPaid[] = (int)date('n', strtotime($row['payment_date']));
                        }
                    }

                    $stmt->close();
                    $conn->close();
                }
                ?>
                <select id="seasonSelect" name="season" style="width: 100%; border-color: #333;" class="mb-20 p-10">
                    <option value="">اختر الموسم</option>
                    <?php foreach ($years as $yearOption) : ?>
                        <option value="<?= htmlspecialchars($yearOption); ?>" <?= ($year == $yearOption) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($yearOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="payment-info">
                    <form class="horaire responsive-table special" method="POST" action="../php/save_payments.php">
                        <input type="hidden" name="adherent" value="<?= htmlspecialchars($identifier); ?>">
                        <input type="hidden" name="year" id="yearInput" value="<?= htmlspecialchars($year); ?>">
                        <div class="flex-table">
                            <?php
                            $months = [
                                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس',
                                4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
                                7 => 'يوليو', 8 => 'غشت', 9 => 'شتنبر',
                                10 => 'أكتوبر', 11 => 'نونبر', 12 => 'دجنبر'
                            ];
                            foreach (array_chunk($months, 3, true) as $monthChunk) {
                                echo '<div class="flex-row">';
                                foreach ($monthChunk as $value => $name) {
                                    $paidClass = in_array($value, $monthsPaid) ? ' paid' : '';
                                    $isChecked = in_array($value, $monthsPaid) ? 'checked' : '';
                                    echo '<div class="flex-cell' . $paidClass . '">';
                                    echo '<input type="checkbox" name="months[]" value="' . htmlspecialchars($value) . '" ' . $isChecked . ' disabled>';
                                    echo '<h4>' . htmlspecialchars($name) . '</h4>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                            ?>
                            <div class="flex-row">
                                <div class="flex-cell<?= $annualPaid ? ' paid' : ''; ?>" style="flex: 1;">
                                    <input type="checkbox" name="assurance" value="التأمين" <?= $annualPaid ? 'checked' : ''; ?> disabled>
                                    <h4>التأمين</h4>
                                </div>
                                <div class="flex-cell<?= $adhesionPaid ? ' paid' : ''; ?>" style="flex: 1;">
                                    <input type="checkbox" name="adhesion" value="الانخراط" <?= $adhesionPaid ? 'checked' : ''; ?> disabled>
                                    <h4>الانخراط السنوي</h4>
                                </div>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button type="button" class="modify-btn btn-shape mb-10"><i class="fas fa-edit"></i> تعديل</button>
                            <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.modify-btn').addEventListener('click', function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.disabled = false;
            });
            document.querySelector('.modify-btn').classList.add('hidden');
            document.querySelector('.save-btn').classList.remove('hidden');
        });

        document.querySelector('.horaire').addEventListener('submit', function() {
            // Keep checkboxes enabled for form submission
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.disabled = false;
            });
        });

        document.getElementById('seasonSelect').addEventListener('change', function() {
            const year = this.value;
            const identifier = '<?= htmlspecialchars($identifier); ?>';
            if (year && identifier) {
                // Refresh the page with the selected year in the URL
                window.location.href = `?id=${identifier}&date=${year}`;
            }
        });
    </script>
</body>

</html>
