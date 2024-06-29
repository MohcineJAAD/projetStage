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

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <?php
            // $active_period = 7;

            // // Fetch number of masculin and femasculin students
            // $students_result_m_D = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u join etudiants e on u.identifiant = e.CNE  WHERE role = 'etudiant' AND sexe = 'masculin' AND section = 'DSI' ");
            // $students_result_m_P = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u join etudiants e on u.identifiant = e.CNE  WHERE role = 'etudiant' AND sexe = 'masculin' AND section = 'PME' ");
            // $students_row_m_D = $students_result_m_D->fetch_assoc();
            // $students_row_m_P = $students_result_m_P->fetch_assoc();
            // $students_count_m_D = $students_row_m_D['count'];
            // $students_count_m_P = $students_row_m_P['count'];

            // $students_result_f_D = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u join etudiants e on u.identifiant = e.CNE  WHERE role = 'etudiant' AND sexe = 'feminin' AND section = 'DSI'");
            // $students_result_f_P = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u join etudiants e on u.identifiant = e.CNE  WHERE role = 'etudiant' AND sexe = 'feminin' AND section = 'PME'");
            // $students_row_f_D = $students_result_f_D->fetch_assoc();
            // $students_row_f_P = $students_result_f_P->fetch_assoc();
            // $students_count_f_D = $students_row_f_D['count'];
            // $students_count_f_P = $students_row_f_P['count'];
            // $total_students = $students_count_m_P + $students_count_m_D + $students_count_f_P + $students_count_f_D;

            // // Fetch number of masculin and femasculin professors
            // // Count male professors in DSI, PME, and DSI_PME sections
            // $professors_result_m_DSI = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u JOIN professeurs p ON u.identifiant = p.matricule WHERE role = 'prof' AND sexe = 'masculin' AND (section = 'DSI' OR section = 'DSI_PME')");
            // $professors_result_m_PME = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u JOIN professeurs p ON u.identifiant = p.matricule WHERE role = 'prof' AND sexe = 'masculin' AND (section = 'PME' OR section = 'DSI_PME')");
            // $professors_row_m_DSI = $professors_result_m_DSI->fetch_assoc();
            // $professors_row_m_PME = $professors_result_m_PME->fetch_assoc();

            // $professors_count_m_D = $professors_row_m_DSI['count'];
            // $professors_count_m_P = $professors_row_m_PME['count'];

            // // Count female professors in DSI, PME, and DSI_PME sections
            // $professors_result_f_DSI = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u JOIN professeurs p ON u.identifiant = p.matricule  WHERE role = 'prof' AND sexe = 'feminin' AND (section = 'DSI' OR section = 'DSI_PME')");
            // $professors_result_f_PME = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs u JOIN professeurs p ON u.identifiant = p.matricule  WHERE role = 'prof' AND sexe = 'feminin' AND (section = 'PME' OR section = 'DSI_PME')");

            // $professors_row_f_DSI = $professors_result_f_DSI->fetch_assoc();
            // $professors_row_f_PME = $professors_result_f_PME->fetch_assoc();

            // $professors_count_f_D = $professors_row_f_DSI['count'];
            // $professors_count_f_P = $professors_row_f_PME['count'];

            // // Total professors
            // $professors_result_total_DSI = $conn->query("SELECT COUNT(*) AS count FROM professeurs");
            // $professors_row_total_DSI = $professors_result_total_DSI->fetch_assoc();
            // $total_professors = $professors_row_total_DSI['count'];

            // // Fetch number of resources
            // $resources_result = $conn->query("SELECT type, COUNT(*) AS count FROM ressources GROUP BY type");
            // $resource_counts = array();
            // $total_count = 0;
            // // Arrays to hold resource types and their counts for chart
            // $resource_types = array();
            // $resource_values = array();

            // // Fetch and store each row in the array
            // while ($resources_row = $resources_result->fetch_assoc()) {
            //     $resource_counts[$resources_row["type"]] = $resources_row["count"];
            //     $total_count += $resources_row["count"];
            //     $resource_types[] = $resources_row["type"];
            //     $resource_values[] = $resources_row["count"];
            // }

            // // Fetch number of active students
            // $active_students_result = $conn->query("SELECT COUNT(*) AS count FROM utilisateurs WHERE role = 'etudiant' AND last_login >= NOW() - INTERVAL $active_period DAY");
            // $active_students_row = $active_students_result->fetch_assoc();
            // $active_students_count = $active_students_row['count'];
            ?>
            <h1 class="p-relative">Dashboard</h1>
            <div class="wrapper d-grid gap-20">
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Adhérentes</h3>
                        <p class="value">10</p>
                        <i class="fa-solid fa-user" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Revenu</h3>
                        <p class="value">10 DH</p>
                        <i class="fa-solid fa-money-bills" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>nouveaux inscrits</h3>
                        <p class="value">45</p>
                        <i class="fa-solid fa-user-plus" style="color: #203a85;"></i>
                    </div>
                </div>
                <div class="cards rad-10 txt-c-mobile block-mobile">
                    <div class="card-content">
                        <h3>Adhérents en cours</h3>
                        <p class="value">57</p>
                        <i class="fa-solid fa-user-clock" style="color: #203a85;"></i>
                    </div>
                </div>
            </div>

            <div class="statistique p-20 bg-fff rad-10 m-20">
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
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['PME', 'DSI'],
                datasets: [{
                        label: 'Mâles',
                        data: [12, 12],
                        backgroundColor: '#0075ff',
                        borderColor: '#0056b3',
                        borderWidth: 1
                    },
                    {
                        label: 'Femelles',
                        data: [12, 12],
                        backgroundColor: '#ff0075',
                        borderColor: '#b30056',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        barPercentage: 0.9,
                        categoryPercentage: 0.5
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' Etudient';
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctx1 = document.getElementById('chart1').getContext('2d');
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['PME', 'DSI'],
                datasets: [{
                        label: 'Mâles',
                        data: [12, 12],
                        backgroundColor: '#0075ff',
                        borderColor: '#0056b3',
                        borderWidth: 1
                    },
                    {
                        label: 'Femelles',
                        data: [12, 12],
                        backgroundColor: '#ff0075',
                        borderColor: '#b30056',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        barPercentage: 0.9,
                        categoryPercentage: 0.5
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' Professeur';
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctx2 = document.getElementById('chart2').getContext('2d');
        const chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($resource_types as $type) echo "'$type', "; ?>],
                datasets: [{
                    label: 'Resource',
                    data: [<?php foreach ($resource_values as $value) echo "$value, "; ?>],
                    backgroundColor: '#0075ff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        barPercentage: 0.9,
                        categoryPercentage: 0.5
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>

<?php
$conn->close();
?>