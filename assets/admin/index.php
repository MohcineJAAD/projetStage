<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/framework.css">
    <link rel="stylesheet" href="../css/dashbord.css">
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <title>Dashboard</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <?php
            require "../php/db_connection.php";
            $date = date('Y-m-d');
            $adhrent = $conn->query("SELECT COUNT(*) AS count FROM adherents WHERE status = 'active'");
            $row_adh = $adhrent->fetch_assoc();
            $adhrent_new = $conn->query("SELECT COUNT(*) AS count FROM adherents WHERE date_adhesion = $date");
            $row_new = $adhrent_new->fetch_assoc();
            $adhrent_att = $conn->query("SELECT COUNT(*) AS count FROM adherents WHERE status = 'pending'");
            $row_att = $adhrent_att->fetch_assoc();
            $pay = $conn->query("SELECT sum(amount) AS count FROM payments WHERE identifier != 'A000000001'");
            $row_pay = $pay->fetch_assoc();
            ?>
            <h1 class="p-relative">الرئيسية</h1>
            <div class="wrapper d-grid gap-20">
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Adhérentes</h3>
                        <p class="value"><?php echo $row_adh['count'];?></p>
                        <i class="fa-solid fa-user" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Revenu</h3>
                        <p class="value"><?php echo $row_pay['count']; ?> DH</p>
                        <i class="fa-solid fa-money-bills" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>nouveaux inscrits</h3>
                        <p class="value"><?php echo $row_new['count']; ?></p>
                        <i class="fa-solid fa-user-plus" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Adhérents en cours</h3>
                        <p class="value"><?php echo $row_att['count']; ?></p>
                        <i class="fa-solid fa-user-clock" style="color: #203a85;"></i>
                    </div>
                </div>
            </div>
            <!-- <div class="statistique p-20 bg-fff rad-10 m-20">
                <p>Répartition des Adhérents par sport</p>
                <div class="graphBox">
                    <canvas id="chart"></canvas>
                </div>
            </div>
            <div class="statistique p-20 bg-fff rad-10 m-20">
                <p>Répartition des professeur par sexe</p>
                <div class="graphBox">
                    <canvas id="chart1"></canvas>
                </div>
            </div>
            <div class="statistique p-20 bg-fff rad-10 m-20">
                <p>Répartition des Ressources par type</p>
                <div class="graphBox">
                    <canvas id="chart2"></canvas>
                </div>
            </div> -->

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   

</body>

</html>

<?php
$conn->close();
?>