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

<body>
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">Gestion des adhérents</h1>
            <div class="absences p-20 bg-fff rad-10 m-20">
                <h2 class="mt-0 mb-20 mt-20">Les adhérents</h2>
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
                                <th>Nom complet</th>
                                <th>Identifiant</th>
                                <th>Mot de passe</th>
                                <th>Sport</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    echo "<tr data-branch='" . htmlspecialchars($row1['type']) . "'>";
                                    echo "<td>" . htmlspecialchars($row1['prenom'] . " " . $row1['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['identifier']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['date_naissance']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row1['date_adhesion']) . "</td>";
                                    $identifiant = $row1['identifier'];
                                    echo "<td>
                                            <a href='generate_excel.php?id={$identifiant}' class='supprimer-btn'><span class='label btn-shape bg-c-60'>imprimer</span></a>
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

</html>
