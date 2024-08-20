<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/framework.css">
    <link rel="stylesheet" href="../css/master1.css">
    <link rel="stylesheet" href="../css/dashbord.css">
    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <title>Profil</title>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body dir="rtl">
    <div class="page d-flex">
        <?php require 'sidebar.php'; ?>
        <div class="content w-full">
            <?php require 'header.php'; ?>

            <h1 class="p-relative">الاعدادات</h1>
            <div class="profile-container m-20 bg-fff rad-10">
                <div class="profile-header">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM admin");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $full_name = $row['full_name'];
                        $club_name = $row['club_name'];
                        $pass = $row['password'];
                        $iden = $row['identifier'];
                        $logo = "../images/" . $row['logo'];
                    }
                    $stmt->close();
                    ?>
                </div>
                <div class="p-20 mb-20">
                    <form id="sportsForm" action="../php/alterSettings.php" method="POST" enctype="multipart/form-data">
                        <div class="section">
                            <div class="image-preview">
                                <img id="imagePreview" src="<?php echo $logo; ?>" alt="Aperçu de l'image" />
                                <label for="imageUpload" class="custom-file-upload" style="width: 173.84px;">اختر صورة</label>
                                <input type="file" id="imageUpload" class="file-input" accept="image/*" name="imageUpload" disabled />
                                <a href="<?php echo $logo; ?>" download class="custom-file-upload file-input" style="width: 173.84px;">تحميل</a>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="full_name">الاسم الكامل</label>
                                    <input type="text" id="full_name" name="full_name" placeholder="ادخل الاسم الكامل" value="<?php echo htmlspecialchars($full_name); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="club_name">اسم النادي</label>
                                    <input type="text" id="club_name" name="club_name" placeholder="ادخل اسم النادي" value="<?php echo htmlspecialchars($club_name); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="identifier">المعرف</label>
                                    <input type="text" id="identifier" name="identifier" placeholder="ادخل المعرف" value="<?php echo htmlspecialchars($iden); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="password">الرمز السري</label>
                                    <input type="text" id="password" name="password" placeholder="ادخل الرمز السري" value="<?php echo htmlspecialchars($pass); ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="action-buttons mt-20">
                            <button type="button" class="modify-btn btn-shape mb-10"><i class="fas fa-edit"></i> تعديل</button>
                            <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> حفض</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.querySelector('.custom-file-upload').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('imageUpload').click();
        });

        document.querySelector('.modify-btn').addEventListener('click', function() {
            document.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.disabled = false;
            });
            document.querySelector('.modify-btn').classList.add('hidden');
            document.querySelector('.save-btn').classList.remove('hidden');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('addCardModal');
            const addCardButton = document.querySelector('.add-card');
            const closeButton = modal.querySelector('.close');

            addCardButton.addEventListener('click', () => {
                modal.style.display = 'flex';
            });

            closeButton.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        <?php
        if (isset($_SESSION['message'])) {
            $status_message = $_SESSION['message'];
            $status_type = $_SESSION['status'];
            echo "showToast('$status_message', '$status_type');";
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