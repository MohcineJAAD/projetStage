<?php
require 'db_connection.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$identifier = isset($_GET['id']) ? $_GET['id'] : '';
$monthsPaid = [];
$annualPaid = false;

if (!empty($identifier)) {
    $sql = "SELECT type, payment_date FROM payments WHERE identifier = '$identifier' AND YEAR(payment_date) = $year";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['type'] == 'assurance') {
                $annualPaid = true;
            } else {
                $paymentMonth = date('n', strtotime($row['payment_date']));
                $monthsPaid[] = (int)$paymentMonth;
            }
        }
    }
}

$months = [
    1 => 'JANVIER', 2 => 'FÉVRIER', 3 => 'MARS',
    4 => 'AVRIL', 5 => 'MAI', 6 => 'JUIN',
    7 => 'JUILLET', 8 => 'AOÛT', 9 => 'SEPTEMBRE',
    10 => 'OCTOBRE', 11 => 'NOVEMBRE', 12 => 'DÉCEMBRE'
];

foreach (array_chunk($months, 3, true) as $monthChunk) {
    echo '<div class="flex-row">';
    foreach ($monthChunk as $value => $name) {
        $paidClass = in_array($value, $monthsPaid) ? ' paid' : '';
        $isChecked = in_array($value, $monthsPaid) ? 'checked' : '';
        echo '<div class="flex-cell' . $paidClass . '">';
        echo '<input type="checkbox" name="months[]" value="' . $value . '" ' . $isChecked . ' disabled>';
        echo '<h4>' . $name . '</h4>';
        echo '</div>';
    }
    echo '</div>';
}

echo '<div class="flex-row">';
echo '<div class="flex-cell' . ($annualPaid ? ' paid' : '') . '" style="flex: 1;">';
echo '<input type="checkbox" name="assurance" value="assurance" ' . ($annualPaid ? 'checked' : '') . ' disabled>';
echo '<h4>Assurance et adhésion annuelles</h4>';
echo '</div>';
echo '</div>';

$conn->close();
?>
