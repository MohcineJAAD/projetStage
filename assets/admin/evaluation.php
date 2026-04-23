<?php
require "../php/db_connection.php";
session_start();
?>
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
    <style>
        table i {
            color: #203a85;
            font-size: 25px;
        }

        table i:hover {
            cursor: pointer;
        }
    </style>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">التقييم</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20 mt-20">المشتركين</h2>
                <div class="responsive-table special">
                    <div class="options w-full">
                        <div class="branch-filter mt-10 mb-10">
                            <?php
                            // Fetch all available plans
                            $plansQuery = "SELECT DISTINCT name FROM plans";
                            $plansResult = $conn->query($plansQuery);

                            if ($plansResult->num_rows > 0) {
                                echo "<button class='btn-shape bg-c-60 color-fff active mb-10' data-branch='all'>الكل</button>";
                                while ($plan = $plansResult->fetch_assoc()) {
                                    echo "<button class='btn-shape bg-c-60 color-fff mb-10' data-branch='" . htmlspecialchars($plan['name']) . "'>" . htmlspecialchars($plan['name']) . "</button>";
                                }
                            } else {
                                echo "<p>No plans found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <table class="fs-15 w-full" id="adherent-list">
                        <thead>
                            <tr>
                                <th>الاسم الكامل</th>
                                <th>الرياضة</th>
                                <th>الانضباط</th>
                                <th>الاداء الرياضي</th>
                                <th>السلوك</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt1 = $conn->prepare("
                                SELECT adherents.*, plans.name 
                                FROM adherents 
                                LEFT JOIN plans ON adherents.type = plans.name 
                                WHERE adherents.status = ?
                            ");
                            $status_active = 'active';
                            $stmt1->bind_param("s", $status_active);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();

                            if ($result1->num_rows > 0) {
                                while ($rows1 = $result1->fetch_assoc()) {
                                    $stmt2 = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
                                    $stmt2->bind_param("s", $rows1['identifier']);
                                    $stmt2->execute();
                                    $res1 = $stmt2->get_result();
                                    $row1 = $res1->fetch_assoc();

                                    $stmt3 = $conn->prepare("SELECT * FROM evaluations WHERE identifier = ? ORDER BY year DESC, month DESC LIMIT 1");
                                    $stmt3->bind_param("s", $rows1['identifier']);
                                    $stmt3->execute();
                                    $res2 = $stmt3->get_result();
                                    $evaluation = $res2->fetch_assoc();

                                    $discipline = $evaluation['discipline'] ?? 0;
                                    $performance = $evaluation['performance'] ?? 0;
                                    $behavior = $evaluation['behavior'] ?? 0;

                                    echo "<form action='../php/save_evaluation.php' method='post'>";
                                    echo "<tr data-branch='" . htmlspecialchars($rows1['name']) . "'>";
                                    echo "<td>" . htmlspecialchars($row1['prenom'] . " " . $row1['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['type']) . "</td>";

                                    echo "<td>";
                                    for ($i = 0; $i < 5; $i++) {
                                        $class = $i < $discipline ? 'fa-solid' : 'fa-regular';
                                        echo "<i class='$class fa-star' data-index='$i'></i>";
                                    }
                                    echo "<input type='hidden' name='discipline' value='$discipline'>";
                                    echo "</td>";

                                    echo "<td>";
                                    for ($i = 0; $i < 5; $i++) {
                                        $class = $i < $performance ? 'fa-solid' : 'fa-regular';
                                        echo "<i class='$class fa-star' data-index='$i'></i>";
                                    }
                                    echo "<input type='hidden' name='performance' value='$performance'>";
                                    echo "</td>";

                                    echo "<td>";
                                    for ($i = 0; $i < 5; $i++) {
                                        $class = $i < $behavior ? 'fa-solid' : 'fa-regular';
                                        echo "<i class='$class fa-star' data-index='$i'></i>";
                                    }
                                    echo "<input type='hidden' name='behavior' value='$behavior'>";
                                    echo "</td>";

                                    echo "<td><input type='hidden' name='identifier' value='" . htmlspecialchars($row1['identifier']) . "'>";
                                    echo "<button type='submit' class='btn-shape bg-c-60 color-30'>حفض</button></td>";
                                    echo "</tr>";
                                    echo "</form>";
                                }
                            }
                            $stmt1->close();
                            $conn->close();
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const branchButtons = document.querySelectorAll(".branch-filter button");
        const rows = document.querySelectorAll("#adherent-list tbody tr");
        const noResultsRow = document.querySelector("#adherent-list tbody .no-results");

        branchButtons.forEach(button => {
            button.addEventListener("click", function() {
                // Remove the 'active' class from all buttons and add it to the clicked button
                branchButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");

                const branch = button.getAttribute("data-branch");
                let hasVisibleRow = false;

                // Loop through all rows and filter based on the selected branch
                rows.forEach(row => {
                    const rowBranch = row.getAttribute("data-branch");

                    if (rowBranch === null) return; // Skip rows without the data-branch attribute

                    if (branch === "all" || rowBranch === branch) {
                        row.style.display = "";
                        hasVisibleRow = true;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Show or hide the "no results" row
                noResultsRow.style.display = hasVisibleRow ? "none" : "";
            });
        });

        // Initial check to see if any rows are visible
        let hasVisibleRow = Array.from(rows).some(row => row.style.display !== "none" && !row.classList.contains('no-results'));
        noResultsRow.style.display = hasVisibleRow ? "none" : "";

        // Star rating interaction functionality
        const tds = document.querySelectorAll("#adherent-list tbody td");
        tds.forEach(td => {
            const stars = td.querySelectorAll(".fa-star");
            const hiddenInput = td.querySelector("input[type='hidden']");
            stars.forEach((star, index) => {
                star.addEventListener("click", function() {
                    // Change the star classes based on the clicked star's index
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.remove("fa-regular");
                            s.classList.add("fa-solid");
                        } else {
                            s.classList.remove("fa-solid");
                            s.classList.add("fa-regular");
                        }
                    });
                    hiddenInput.value = index + 1;
                });
            });
        });
    });
</script>