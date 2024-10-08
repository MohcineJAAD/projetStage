<!DOCTYPE html>
<?php
require '../php/db_connection.php';
$sql = "SELECT plans.*, COUNT(adherents.identifier) AS adherents_count 
        FROM plans 
        LEFT JOIN adherents ON adherents.type = plans.name
        GROUP BY plans.name";
$result = $conn->query($sql);
$plans = [];
if ($result->num_rows > 0)
    $plans = $result->fetch_all(MYSQLI_ASSOC);
?>

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
    <title>Dashboard - Plans</title>
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>
            <h1 class="p-relative">الخطط</h1>
            <div class="profile-container m-20 bg-fff rad-10">
                <div class="p-20 mb-20" dir="rtl">
                    <h2>الخطط</h2>
                    <div class="wrapper d-grid gap-20">
                        <div class="add-card cards rad-6 p-20 p-relative txt-c" id="add-button">
                            <div class="add-content">
                                <div class="circle-dashed">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <p class="mt-10 color-333">اضف</p>
                            </div>
                        </div>
                        <?php foreach ($plans as $plan) : ?>
                            <div class="cards rad-10 txt-c-mobile block-mobile">
                                <div class="card-content">
                                    <h3 class="mt-5"><?php echo htmlspecialchars($plan['name']); ?></h3>
                                    <p class="value">السعر: <?php echo htmlspecialchars($plan['price']); ?> د.م</p>
                                    <p class="value">عدد المنخرطين: <?php echo htmlspecialchars($plan['adherents_count']); ?></p>
                                    <div class="action-buttons">
                                        <a href="#" onclick="confirmDelete('<?php echo htmlspecialchars($plan['id']); ?>')" class="btn-shape bg-f00 p-10">حذف</a>
                                        <a href="#" onclick="openEditModal('<?php echo htmlspecialchars($plan['id']); ?>', '<?php echo htmlspecialchars($plan['price']); ?>', '<?php echo htmlspecialchars($plan['description']); ?>', '<?php echo htmlspecialchars($plan['assurance']); ?>', '<?php echo htmlspecialchars($plan['adherence']); ?>')" class="btn-shape bg-c-60 p-10">تعديل</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addCardModal" class="modal" dir="rtl">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>اضف خطة</h2>
            <form id="addCardForm" action="../php/addPlan.php" method="POST">
                <div class="section">
                    <div class="row">
                        <div class="input-field">
                            <label for="planName">اسم الخطة</label>
                            <input type="text" id="planName" name="planName" placeholder="ادخل اسم الخطة" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="planPrice">الواجب الشهري</label>
                            <input type="number" id="planPrice" name="planPrice" placeholder="ادخل السعر" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="adherence">الأنخراط</label>
                            <input type="number" id="adherence" name="adherence" placeholder="ادخل السعر" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="assurance">التأمين</label>
                            <input type="number" id="assurance" name="assurance" placeholder="ادخل السعر" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="description">الوصف</label>
                            <textarea id="description" name="description" placeholder="ادخل وصف" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="save-btn btn-shape mt-10">اضف</button>
            </form>
        </div>
    </div>
    <div id="editCardModal" class="modal" dir="rtl">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تعديل السعر</h2>
            <form id="editCardForm" action="../php/editPlan.php" method="POST">
                <input type="hidden" id="planId" name="planId">
                <div class="section">
                    <div class="row">
                        <div class="input-field">
                            <label for="editPlanPrice">الواجب الشهري</label>
                            <input type="number" id="editPlanPrice" name="planPrice" placeholder="ادخل السعر الجديد" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="adherence">الأنخراط</label>
                            <input type="number" id="editAdherence" name="adherence" placeholder="ادخل السعر" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="editAssurance">التأمين</label>
                            <input type="number" id="editAssurance" name="assurance" placeholder="ادخل السعر" style="width: 100%;" required />
                        </div>
                        <div class="input-field mt-5">
                            <label for="editDescription">الوصف</label>
                            <textarea id="editDescription" name="description" placeholder="ادخل وصف" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="save-btn btn-shape mt-10">تحديث</button>
            </form>
        </div>
    </div>
    <div id="deleteConfirmationModal" class="modal" dir="rtl">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تأكيد الحذف</h2>
            <p>هل أنت متأكد أنك تريد حذف هذه الخطة؟</p>
            <div class="action-buttons">
                <button id="confirmDeleteButton" class="btn-shape color-30 bg-f00 p-10">حذف</button>
                <button id="cancelDeleteButton" class="btn-shape color-30 bg-c-60 p-10">إلغاء</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addModal = document.getElementById('addCardModal');
            const addCardButton = document.querySelector('.add-card');
            const addCloseButton = addModal.querySelector('.close');
            addCardButton.addEventListener('click', () => {
                addModal.style.display = 'flex';
            });
            addCloseButton.addEventListener('click', () => {
                addModal.style.display = 'none';
            });
            window.addEventListener('click', (event) => {
                if (event.target === addModal) {
                    addModal.style.display = 'none';
                }
            });
            const editModal = document.getElementById('editCardModal');
            const editCloseButton = editModal.querySelector('.close');
            editCloseButton.addEventListener('click', () => {
                editModal.style.display = 'none';
            });
            window.addEventListener('click', (event) => {
                if (event.target === editModal) {
                    editModal.style.display = 'none';
                }
            });
            window.openEditModal = (planId, planPrice, description, assurance, adherence) => {
                document.getElementById('planId').value = planId;
                document.getElementById('editPlanPrice').value = planPrice;
                document.getElementById('editDescription').value = description;
                document.getElementById('editAdherence').value = adherence;
                document.getElementById('editAssurance').value = assurance;
                editModal.style.display = 'flex';
            };
            const deleteModal = document.getElementById('deleteConfirmationModal');
            const deleteCloseButton = deleteModal.querySelector('.close');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            let deletePlanId = null;
            deleteCloseButton.addEventListener('click', () => {
                deleteModal.style.display = 'none';
                deletePlanId = null;
            });
            document.getElementById('cancelDeleteButton').addEventListener('click', () => {
                deleteModal.style.display = 'none';
                deletePlanId = null;
            });
            window.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    deleteModal.style.display = 'none';
                    deletePlanId = null;
                }
            });
            confirmDeleteButton.addEventListener('click', () => {
                if (deletePlanId) {
                    window.location.href = `../php/deletePlan.php?id=${deletePlanId}`;
                }
            });
            window.confirmDelete = (planId) => {
                deletePlanId = planId;
                deleteModal.style.display = 'flex';
            };
        });
    </script>
</body>

</html>
<?php
$conn->close();
?>