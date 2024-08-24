<?php
require "../php/db_connection.php";
session_start();

function getPlans($conn)
{
    $stmt = $conn->prepare("SELECT name FROM plans");
    $stmt->execute();
    $result = $stmt->get_result();
    $plans = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $plans;
}

$plans = getPlans($conn);
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">إدارة المنخرطين</h1>
            <div class="absences p-20 bg-fff rad-10 m-20 special">
                <h2 class="mt-0 mb-20">التسجيلات جديدة</h2>
                <?php
                $stmt = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                $status = 'pending';
                $stmt->bind_param("s", $status);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <div class="responsive-table">
                    <?php if ($result->num_rows > 0) : ?>
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
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['prenom'] . " " . $row['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($row['identifier']); ?></td>
                                        <td><?php echo htmlspecialchars($row['date_naissance']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['date_adhesion']); ?></td>
                                        <td>
                                            <a href='../php/rejeter.php?id=<?php echo $row['identifier']; ?>' class='supprimer-btn'><span class='label btn-shape bg-f00'>رفض</span></a>
                                            <a href='../php/accepter.php?id=<?php echo $row['identifier']; ?>' class='justification-btn'><span class='label btn-shape bg-green'>قبول</span></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p style="text-align: center;">ليس هناك اي تسجيلات جديدة</p>
                    <?php endif; ?>
                    <?php $stmt->close(); ?>
                </div>
            </div>

            <div class="absences p-20 bg-fff rad-10 m-20 special">
                <h2 class="mt-0 mb-20 mt-20">المنخرطين</h2>
                <div class="options w-full">
                    <div class="branch-filter mt-10 mb-10">
                        <button class="btn-shape bg-c-60 color-fff active mb-10" data-branch="all">الكل</button>
                        <?php
                        foreach ($plans as $plan) {
                            echo "<button class='btn-shape bg-c-60 color-fff mb-10' data-branch='" . htmlspecialchars($plan['name']) . "'>" . htmlspecialchars($plan['name']) . "</button>";
                        }
                        ?>
                    </div>
                </div>
                <div class="responsive-table special">
                    <?php
                    $stmt1 = $conn->prepare("SELECT * FROM adherents WHERE status = ?");
                    $status_active = 'active';
                    $stmt1->bind_param("s", $status_active);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    ?>
                    <?php if ($result1->num_rows > 0) : ?>
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
                                <?php while ($row1 = $result1->fetch_assoc()) : ?>
                                    <tr data-branch="<?php echo htmlspecialchars($row1['type']); ?>">
                                        <td><?php echo htmlspecialchars($row1['prenom'] . " " . $row1['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($row1['identifier']); ?></td>
                                        <td><?php echo htmlspecialchars($row1['type']); ?></td>
                                        <td><?php echo htmlspecialchars($row1['date_adhesion']); ?></td>
                                        <td>
                                            <a href='profile-adherent.php?id=<?php echo $row1['identifier']; ?>' class='supprimer-btn'><span class='label btn-shape bg-c-60'>الملف الشخصي</span></a>
                                            <a href='#' class='supprimer-btn' onclick="confirmDelete('<?php echo htmlspecialchars($row1['identifier']); ?>')">
                                                <span class='label btn-shape bg-f00'>حذف</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php
                                $stmt1->close();
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p style="text-align: center;">لم يتم العثور على أعضاء</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteConfirmationModal" class="modal" dir="rtl">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تأكيد الحذف</h2>
            <p>هل أنت متأكد أنك تريد حذف هذالمشترك؟</p>
            <div class="action-buttons">
                <button id="confirmDeleteButton" class="btn-shape color-fff bg-f00 p-10">حذف</button>
                <button id="cancelDeleteButton" class="btn-shape color-fff bg-c-60 p-10">إلغاء</button>
            </div>
        </div>
    </div>
</body>

<script>
    // Variables to store elements and selected adherent ID
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const deleteCloseButton = document.querySelector('.close');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const cancelDeleteButton = document.getElementById('cancelDeleteButton');
    let deleteAdherentID = null;

    // Show the delete confirmation modal
    window.confirmDelete = (adherentID) => {
        deleteAdherentID = adherentID;
        deleteModal.style.display = 'flex';
    };

    // Close the delete confirmation modal
    deleteCloseButton.addEventListener('click', () => {
        deleteModal.style.display = 'none';
        deleteAdherentID = null;
    });

    // Cancel deletion and close the modal
    cancelDeleteButton.addEventListener('click', () => {
        deleteModal.style.display = 'none';
        deleteAdherentID = null;
    });

    // Close the modal if clicked outside
    window.addEventListener('click', (event) => {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
            deleteAdherentID = null;
        }
    });

    // Confirm deletion
    confirmDeleteButton.addEventListener('click', () => {
        if (deleteAdherentID) {
            window.location.href = `../php/delete_adherent.php?id=${deleteAdherentID}`;
        }
    });
</script>


<script>
    // Show toast notification based on session message
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