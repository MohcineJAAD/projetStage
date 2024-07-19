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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
    <style>
        .no-results {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            height: 50px;
            display: table-cell;
        }

        table i {
            color: #203a85;
            font-size: 25px;
        }

        table i:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">Gestion des adhérents</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20 mt-20">Les adhérents</h2>
                <div class="responsive-table">
                    <div class="options w-full">
                        <div class="branch-filter mt-10 mb-10">
                            <button class="btn-shape bg-c-60 color-fff active mb-10" data-branch="all">Tous</button>
                            <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="تايكواندو">تايكواندو</button>
                            <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="فول كونتاكت">فول كونتاكت</button>
                        </div>
                    </div>
                    <table class="fs-15 w-full" id="adherent-list">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Sport</th>
                                <th>Discipline</th>
                                <th>Performances</th>
                                <th>Comportement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt1 = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                            $status_active = 'active';
                            $stmt1->bind_param("s", $status_active);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();

                            $currentMonth = date('m');
                            $currentYear = date('Y');

                            if ($result1->num_rows > 0) {
                                while ($rows1 = $result1->fetch_assoc()) {
                                    $stmt2 = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
                                    $stmt2->bind_param("s", $rows1['identifier']);
                                    $stmt2->execute();
                                    $res1 = $stmt2->get_result();
                                    $row1 = $res1->fetch_assoc();

                                    $stmt3 = $conn->prepare("SELECT * FROM evaluations WHERE identifier = ? AND month = ? AND year = ?");
                                    $stmt3->bind_param("sii", $rows1['identifier'], $currentMonth, $currentYear);
                                    $stmt3->execute();
                                    $res2 = $stmt3->get_result();
                                    $evaluation = $res2->fetch_assoc();

                                    $discipline = $evaluation['discipline'] ?? 0;
                                    $performance = $evaluation['performance'] ?? 0;
                                    $behavior = $evaluation['behavior'] ?? 0;

                                    echo "<form action='../php/save_evaluation.php' method='post'>";
                                    echo "<tr data-branch='" . htmlspecialchars($row1['type']) . "'>";
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
                                    echo "<button type='submit' class='btn-shape bg-c-60 color-fff'>Enregistrer</button></td>";
                                    echo "</tr>";
                                    echo "</form>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='no-results'>Aucun adhérent trouvé</td></tr>";
                            }

                            $stmt1->close();
                            $conn->close();
                            ?>
                            <tr class='no-results' style="display:none;">
                                <td colspan='6' class='no-results'>Aucun adhérent trouvé</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    const branchButtons = document.querySelectorAll(".branch-filter button");
    branchButtons.forEach(button => {
        button.addEventListener("click", function() {
            branchButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            const branch = button.getAttribute("data-branch");
            const rows = document.querySelectorAll("#adherent-list tbody tr");
            let hasVisibleRow = false;

            rows.forEach(row => {
                if (row.classList.contains('no-results')) return;

                if (branch === "all" || row.getAttribute("data-branch") === branch) {
                    row.style.display = "";
                    hasVisibleRow = true;
                } else {
                    row.style.display = "none";
                }
            });

            const noResultsRow = document.querySelector("#adherent-list tbody .no-results");
            if (hasVisibleRow) {
                noResultsRow.style.display = "none";
            } else {
                noResultsRow.style.display = "";
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const noResultsRow = document.querySelector("#adherent-list tbody .no-results");
        const rows = document.querySelectorAll("#adherent-list tbody tr");
        let hasVisibleRow = false;

        rows.forEach(row => {
            if (row.style.display !== "none" && !row.classList.contains('no-results')) {
                hasVisibleRow = true;
            }
        });

        if (!hasVisibleRow) {
            noResultsRow.style.display = "";
        }

        // Add event listener to toggle star icon and update hidden input values
        const tds = document.querySelectorAll("#adherent-list tbody td");
        tds.forEach(td => {
            const stars = td.querySelectorAll(".fa-star");
            const hiddenInput = td.querySelector("input[type='hidden']");
            stars.forEach((star, index) => {
                star.addEventListener("click", function() {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.remove("fa-regular");
                            s.classList.add("fa-solid");
                        } else {
                            s.classList.remove("fa-solid");
                            s.classList.add("fa-regular");
                        }
                    });
                    hiddenInput.value = index + 1; // Update hidden input value
                });
            });
        });
    });
</script>

<script>
    <?php
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        $status_message = $_SESSION['message'];
        $status_type = $_SESSION['status'];
        // Ensure proper quoting and escaping in the JavaScript string
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

</html>