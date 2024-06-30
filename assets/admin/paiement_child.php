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
    <style>
        .hidden {
            display: none;
        }

        .flex-table {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            margin: 0 auto;
        }

        .flex-row {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .flex-cell {
            flex: 1 1 33.33%;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .flex-cell h4 {
            margin: 10px 0 0;
            font-weight: normal;
        }

        .paid {
            background-color: #d4edda;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .action-buttons button {
            margin: 0 10px;
        }

        @media (max-width: 768px) {
            .flex-cell {
                flex: 1 1 50%;
            }
        }

        @media (max-width: 480px) {
            .flex-cell {
                flex: 1 1 100%;
            }
        }
    </style>
</head>

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">L'emploi du temps</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20">Les nouveaux inscriptions</h2>
                <?php
                // Fetch payment data from the database
                $identifier = isset($_GET['id']) ? $_GET['id'] : '';
                $monthsPaid = [];
                $annualPaid = false;

                if (!empty($identifier)) {
                    require '../php/db_connection.php';

                    $sql = "SELECT type, payment_date FROM payments WHERE identifier = '$identifier'";
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

                    $conn->close();
                }
                ?>
                <form class="horaire responsive-table" method="POST" action="../php/save_payments.php">
                    <input type="hidden" name="adherent" value="<?php echo htmlspecialchars($identifier); ?>">
                    <div class="flex-table">
                        <?php
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
                        ?>
                        <div class="flex-row">
                            <div class="flex-cell<?php echo $annualPaid ? ' paid' : ''; ?>" style="flex: 1;">
                                <input type="checkbox" name="assurance" value="assurance" <?php echo $annualPaid ? 'checked' : ''; ?> disabled>
                                <h4>Assurance et adhésion annuelles</h4>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="modify-btn btn-shape mb-10"><i class="fas fa-edit"></i> Modifier</button>
                        <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> Sauvegarder</button>
                    </div>
                </form>

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

        document.querySelector('.horaire').addEventListener('submit', function(event) {
            // Keep checkboxes enabled for form submission
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.disabled = false;
            });
        });
    </script>
</body>

</html>
