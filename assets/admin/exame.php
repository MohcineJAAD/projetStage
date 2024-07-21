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
            <h1 class="p-relative">إدارة الامتحان</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20 mt-20">المنخرطين</h2>
                <form class="responsive-table" method="post" action="generate_excel.php">
                    <?php
                    // Prepare and execute the query
                    $stmt1 = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                    if ($stmt1 === false) {
                        die("Prepare failed: " . htmlspecialchars($conn->error));
                    }

                    $status_active = 'active';
                    $stmt1->bind_param("s", $status_active);
                    if (!$stmt1->execute()) {
                        die("Execute failed: " . htmlspecialchars($stmt1->error));
                    }

                    $result1 = $stmt1->get_result();
                    if ($result1 === false) {
                        die("Get result failed: " . htmlspecialchars($stmt1->error));
                    }
                    ?>
                    <div class="action-buttons">
                        <button type="submit" class="save-btn btn-shape mb-10"><i class="fa-solid fa-print"></i> طبع</button>
                        <div class="row mb-10">
                            <div class="input-field">
                                <label for="session"> الدورة</label>
                                <select name="session" id="session">
                                    <option value="" selected disabled>اختر الدورة</option>
                                    <option value="يناير">يناير</option>
                                    <option value="يونيو">يونيو</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="fs-15 w-full" id="adherent-list">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>الاسم الكامل</th>
                                <th>المعرف</th>
                                <th>تاريخ الازدياد</th>
                                <th>الرياضة</th>
                                <th>تاريخ الانخراط</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    echo "<tr data-branch='" . htmlspecialchars($row1['type']) . "'>";
                                    $identifiant = $row1['identifier'];
                                    echo "<td>
                                            <input type='checkbox' name='adherent[]' value='" . htmlspecialchars($identifiant) . "'>
                                          </td>";
                                    echo "<td>" . htmlspecialchars($row1['prenom'] . " " . $row1['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['identifier']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['date_naissance']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['date_adhesion']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "</tbody></table>";
                                echo "<div class='no-results'>لم يتم العثور على أعضاء</div>";
                            }
                            $stmt1->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByName('adherent[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
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
            style: {
                background: type === "error" ? "#FF3030" : "#2F8C37",
            },
            stopOnFocus: true
        }).showToast();
    }
</script>
</body>

</html>
