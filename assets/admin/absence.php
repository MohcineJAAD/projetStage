<?php
require "../php/db_connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']);

    $stmt = $conn->prepare("SELECT date, prenom, nom FROM attendance 
    JOIN adherents ON attendance.identifier = adherents.identifier
    WHERE attendance.identifier = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['search_results'] = [];
        while ($row = $result->fetch_assoc()) {
            if (!isset($_SESSION['search_results']['name'])) {
                $_SESSION['search_results']['name'] = $row['prenom'] . " " . $row['nom'];
            }
            $_SESSION['search_results']['dates'][] = $row['date'];
        }
    } else {
        $_SESSION['search_results'] = ['error' => 'Aucune absence trouvée pour cet identifiant.'];
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$searchResults = isset($_SESSION['search_results']) ? $_SESSION['search_results'] : null;
unset($_SESSION['search_results']);
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
</head>

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">Gestion des présences</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20 mt-20">Fiche d'absence</h2>
                <div class="responsive-table">
                    <div class="options w-full">
                        <div class="branch-filter mt-10 mb-10">
                            <button class="btn-shape bg-c-60 color-fff active mb-10" data-branch="all">Tous</button>
                            <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="Taekwondo">Taekwondo</button>
                            <button class="btn-shape bg-c-60 color-fff mb-10" data-branch="Fullcontact">Fullcontact</button>
                        </div>
                    </div>
                </div>
                <form id="absence-form" method="post" action="../php/save_absences.php">
                    <table class="fs-15 w-full" id="adherent-list">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Identifiant</th>
                                <th>Sport</th>
                                <th>Absente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt1 = $conn->prepare("SELECT * FROM users WHERE status = ? AND role = ?");
                            $status_active = 'active';
                            $role = 'adherent';
                            $stmt1->bind_param("ss", $status_active, $role);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();

                            if ($result1->num_rows > 0) {
                                while ($rows1 = $result1->fetch_assoc()) {
                                    $stmt2 = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
                                    $stmt2->bind_param("s", $rows1['identifier']);
                                    $stmt2->execute();
                                    $res1 = $stmt2->get_result();
                                    $row1 = $res1->fetch_assoc();
                                    echo "<tr data-branch='" . htmlspecialchars($row1['type']) . "'>";
                                    echo "<td>" . htmlspecialchars($row1['prenom'] . " " . $row1['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rows1['identifier']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['type']) . "</td>";
                                    $identifiant = $rows1['identifier'];
                                    echo "<td>
                                            <input type='checkbox' name='absente[]' value='$identifiant' class='absence-checkbox'>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "</tbody></table>";
                                echo "<div class='no-results'>Aucune adhérent trouvée</div>";
                            }

                            $stmt1->close();
                            ?>
                        </tbody>
                    </table>

                    <button type="submit" class="btn mt-20">Enregistrer</button>
                </form>
                <h2 class="mt-0 mb-20 mt-20">Recherche avancée</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="identifier">Rechercher par identifiant:</label>
                        <input type="text" id="identifier" name="identifier" class="form-control" required>
                        <button type="submit" class="btn btn-primary mt-20">Rechercher</button>
                    </div>
                </form>
                <?php
                if ($searchResults) {
                    if (isset($searchResults['error'])) {
                        echo "<p style='text-align: center;'>{$searchResults['error']}</p>";
                    } else {
                        echo "<h3>Absences de l'adhérent '{$searchResults['name']}':</h3>";
                        echo "<ul>";
                        foreach ($searchResults['dates'] as $date) {
                            echo "<li>" . htmlspecialchars($date) . "</li>";
                        }
                        echo "</ul>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- JavaScript scripts -->
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
</body>

</html>
