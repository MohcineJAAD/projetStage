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
            padding: 20px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            width: 100%;
        }
    </style>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">إدارة المنخرطين</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20">التسجيلات جديدة</h2>
                <?php
                $stmt = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                $status = 'pending';
                $stmt->bind_param("s", $status);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <div class="responsive-table">
                    <table class="fs-15 w-full">
                        <thead>
                            <tr>
                                <th>الاسم الكامل</th>
                                <th>المعرف</th>
                                <th>تاريخ الازدياد</th>
                                <th>الرياضة</th>
                                <th>تاريخ التسجيل</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['identifier'];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['prenom'] . " " . $row['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['identifier']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date_naissance']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date_adhesion']) . "</td>";
                                    echo "<td>
                                            <a href='../php/rejeter.php?id=$id' class='supprimer-btn'><span class='label btn-shape bg-f00'>رفض</span></a>
                                            <a href='../php/accepter.php?id=$id' class='justification-btn'><span class='label btn-shape bg-green'>قبول</span></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "</tbody></table>";
                                echo "<div class='no-results'>ليس هناك اي تسجيلات جديدة</div>";
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2 class="mt-0 mb-20 mt-20">المنخرطين</h2>
                <div class="options w-full">
                    <div class="branch-filter mt-10 mb-10">
                        <button class="btn-shape bg-c-60 color-fff active mb-10" data-branch="all">الكل</button>
                        <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="تايكواندو">تايكواندو</button>
                        <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="فول كونتاكت">فول كونتاكت</button>
                    </div>
                </div>
                <div class="responsive-table">
                    <?php
                    $stmt1 = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                    $status_active = 'active';
                    $stmt1->bind_param("s", $status_active);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    ?>
                    <table class="fs-15 w-full" id="adherent-list">
                        <thead>
                            <tr>
                                <th>الاسم الكامل</th>
                                <th>المعرف</th>
                                <th>الرياضة</th>
                                <th>تاريخ الانخراط</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    echo "<tr data-branch='" . htmlspecialchars($row1['type']) . "'>";
                                    echo "<td>" . htmlspecialchars($row1['prenom'] . " " . $row1['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['identifier']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['date_adhesion']) . "</td>";
                                    $identifiant = $row1['identifier'];
                                    echo "<td>
                                            <a href='profile-adherent.php?id={$identifiant}' class='supprimer-btn'><span class='label btn-shape bg-c-60'>الملف الشخصي</span></a>
                                            <a href='../php/delete_adherent.php?id={$identifiant}' class='supprimer-btn'><span class='label btn-shape bg-f00'>حذف</span></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "</tbody></table>";
                                echo "<div class='no-results'>Aucun adhérent trouvé</div>";
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

            const noResultsRow = document.querySelector("#adherent-list .no-results");
            if (hasVisibleRow) {
                noResultsRow.style.display = "none";
            } else {
                noResultsRow.style.display = "table-row";
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const noResultsRow = document.querySelector("#adherent-list .no-results");
        const rows = document.querySelectorAll("#adherent-list tbody tr");
        let hasVisibleRow = false;

        rows.forEach(row => {
            if (row.style.display !== "none" && !row.classList.contains('no-results')) {
                hasVisibleRow = true;
            }
        });

        if (!hasVisibleRow) {
            noResultsRow.style.display = "table-row";
        }
    });
</script>

<script>
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

</html>
