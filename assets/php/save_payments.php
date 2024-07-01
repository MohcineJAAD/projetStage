<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adherent = $conn->real_escape_string($_POST['adherent']);
    $months = $_POST['months'] ?? [];
    $assurance = isset($_POST['assurance']);
    $year = intval($_POST['year']);

    // Debugging output
    echo "<pre>";
    echo "Adherent: $adherent\n";
    echo "Year: $year\n";
    echo "Months: ";
    print_r($months);
    echo "Assurance: " . ($assurance ? 'Yes' : 'No') . "\n";
    echo "</pre>";

    $result = $conn->query("SELECT identifier FROM adherents WHERE identifier='$adherent'");
    if ($result->num_rows === 0) {
        $_SESSION['message'] = 'Identifiant adhérent invalide.';
        $_SESSION['status'] = 'error';
        exit();
    }

    // Clear previous payments for the specified year
    if (!$conn->query("DELETE FROM payments WHERE identifier='$adherent' AND YEAR(payment_date)='$year'")) {
        $_SESSION['message'] = 'Erreur lors de la suppression des paiements précédents: ' . $conn->error;
        $_SESSION['status'] = 'error';
        header('Location: ../admin/paiement.php');
        exit();
    }

    // Insert new payments
    $errors = [];
    $months_names = ["JANVIER", "FÉVRIER", "MARS", "AVRIL", "MAI", "JUIN", "JUILLET", "AOÛT", "SEPTEMBRE", "OCTOBRE", "NOVEMBRE", "DÉCEMBRE"];
    foreach ($months as $month) {
        $month_number = (int)$month;
        $payment_date = $year . '-' . str_pad($month_number, 2, '0', STR_PAD_LEFT) . '-01'; // Assuming payment on the first of the month
        $amount = 100; // Adjust the amount based on class type if necessary
        $sql = "INSERT INTO payments (identifier, payment_date, amount, type) VALUES ('$adherent', '$payment_date', $amount, 'mois')";
        if (!$conn->query($sql)) {
            $errors[] = 'Erreur lors de l\'insertion du paiement mensuel pour ' . $months_names[$month_number - 1] . ': ' . $conn->error;
        }
    }

    // Insert annual fee
    if ($assurance) {
        $amount = 200; // Adjust the amount based on class type if necessary
        $payment_date = $year . '-01-01'; // Assuming payment date as start of the year
        $sql = "INSERT INTO payments (identifier, payment_date, amount, type) VALUES ('$adherent', '$payment_date', $amount, 'assurance')";
        if (!$conn->query($sql)) {
            $errors[] = 'Erreur lors de l\'insertion de la cotisation annuelle: ' . $conn->error;
        }
    }

    if (empty($errors)) {
        $_SESSION['message'] = 'Les paiements ont été enregistrés avec succès.';
        $_SESSION['status'] = 'success';
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['status'] = "error";
    }

    header('Location: ../admin/paiement.php');
    exit();
}
?>
