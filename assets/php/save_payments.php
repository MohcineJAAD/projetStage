<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adherent = $conn->real_escape_string($_POST['adherent']);
    $months = $_POST['months'] ?? [];
    $assurance = isset($_POST['assurance']);
    $adhesion = isset($_POST['adhesion']);
    $year = intval($_POST['year']);

    // Fetch adherent's sport type
    $stmt = $conn->prepare("SELECT type FROM adherents WHERE identifier = ?");
    $stmt->bind_param('s', $adherent);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['message'] = 'معرف العضو غير صالح';
        $_SESSION['status'] = 'error';
        header('Location: ../admin/paiement.php');
        exit();
    }

    $adherent_data = $result->fetch_assoc();
    $sport_type = $adherent_data['type'];

    // Fetch prices for the corresponding sport type
    $stmt = $conn->prepare("SELECT price, assurance, adherence FROM plans WHERE name = ?");
    $stmt->bind_param('s', $sport_type);
    $stmt->execute();
    $plan_prices = $stmt->get_result()->fetch_assoc();
    
    if (!$plan_prices) {
        $_SESSION['message'] = 'حدث خطأ أثناء استرجاع الأسعار للرياضة المحددة';
        $_SESSION['status'] = 'error';
        header('Location: ../admin/paiement.php');
        exit();
    }

    // Fetch existing payments for the year
    $stmt = $conn->prepare("SELECT payment_date, type FROM payments WHERE identifier = ? AND YEAR(payment_date) = ?");
    $stmt->bind_param('si', $adherent, $year);
    $stmt->execute();
    $existing_payments_result = $stmt->get_result();

    $existing_payments = [];
    while ($row = $existing_payments_result->fetch_assoc()) {
        $existing_payments[$row['type']][] = $row['payment_date'];
    }

    // Insert new or missing payments
    $stmt = $conn->prepare("INSERT INTO payments (identifier, payment_date, amount, type) VALUES (?, ?, ?, ?)");

    // Process monthly payments
    $errors = [];
    $months_names = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
        7 => 'يوليو', 8 => 'غشت', 9 => 'شتنبر', 10 => 'أكتوبر', 11 => 'نونبر', 12 => 'دجنبر'
    ];

    foreach ($months as $month) {
        $month_number = (int)$month;
        $payment_date = "{$year}-" . str_pad($month_number, 2, '0', STR_PAD_LEFT) . '-01'; // Payment on the first of the month
        if (!in_array($payment_date, $existing_payments['mois'] ?? [])) {
            $amount = $plan_prices['price'];
            $type = 'mois';
            $stmt->bind_param('ssis', $adherent, $payment_date, $amount, $type);
            if (!$stmt->execute()) {
                $errors[] = 'خطأ عند إدخال الدفع الشهري ل ' . $months_names[$month_number] . ': ' . $conn->error;
            }
        }
    }

    // Process annual fee
    if ($assurance && empty($existing_payments['assurance'])) {
        $amount = $plan_prices['assurance']; // Fetch the assurance amount
        $payment_date = "{$year}-01-01"; // Payment date at the start of the year
        $type = 'assurance';
        $stmt->bind_param('ssis', $adherent, $payment_date, $amount, $type);
        if (!$stmt->execute()) {
            $errors[] = 'حدث خطأ أثناء إدراج التأمين: ' . $conn->error;
        }
    }

    if ($adhesion && empty($existing_payments['adhesion'])) {
        $amount = $plan_prices['adherence']; // Fetch the adhesion amount
        $payment_date = "{$year}-01-01"; // Payment date at the start of the year
        $type = 'adhesion';
        $stmt->bind_param('ssis', $adherent, $payment_date, $amount, $type);
        if (!$stmt->execute()) {
            $errors[] = 'خطأ أثناء إدخال الاشتراك السنوي: ' . $conn->error;
        }
    }

    // Remove payments that are not in the submitted data
    $stmt = $conn->prepare("DELETE FROM payments WHERE identifier = ? AND payment_date = ? AND type = ?");
    
    // Remove unselected months
    foreach ($existing_payments['mois'] ?? [] as $existing_date) {
        $month_number = (int)date('n', strtotime($existing_date));
        if (!in_array($month_number, $months)) {
            $type = 'mois';
            $stmt->bind_param('sss', $adherent, $existing_date, $type);
            if (!$stmt->execute()) {
                $errors[] = 'خطأ أثناء حذف الدفع الشهري ل ' . $months_names[$month_number] . ': ' . $conn->error;
            }
        }
    }

    // Remove assurance if not selected
    if (!$assurance && !empty($existing_payments['assurance'])) {
        foreach ($existing_payments['assurance'] as $existing_date) {
            $type = 'assurance';
            $stmt->bind_param('sss', $adherent, $existing_date, $type);
            if (!$stmt->execute()) {
                $errors[] = 'خطأ أثناء حذف التأمين: ' . $conn->error;
            }
        }
    }

    // Remove adhesion if not selected
    if (!$adhesion && !empty($existing_payments['adhesion'])) {
        foreach ($existing_payments['adhesion'] as $existing_date) {
            $type = 'adhesion';
            $stmt->bind_param('sss', $adherent, $existing_date, $type);
            if (!$stmt->execute()) {
                $errors[] = 'خطأ أثناء حذف الاشتراك السنوي: ' . $conn->error;
            }
        }
    }

    $stmt->close();
    $conn->close();

    if (empty($errors)) {
        $_SESSION['message'] = 'تم تسجيل المدفوعات بنجاح.';
        $_SESSION['status'] = 'success';
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['status'] = 'error';
    }

    header('Location: ../admin/paiement.php');
    exit();
}
?>
