<?php
session_start();
$formData = $_SESSION['formData'] ?? [];
require "assets/php/db_connection.php";
$sports = [];
$sql = "SELECT id, name FROM plans";
$result = $conn->query($sql);

if ($result->num_rows > 0)
    while ($row = $result->fetch_assoc())
        $sports[] = $row;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/master1.css" />
    <link rel="stylesheet" href="assets/css/normalize.css" />
    <link rel="stylesheet" href="assets/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;600&family=Work+Sans:wght@100;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Inscription</title>
</head>

<body>
    <?php require "header.php" ?>
    <div class="landing sign_up">
        <div class="container">
            <div class="form-container">
                <h2 class="title">تسجيل</h2>

                <form id="sportsForm" action="assets/php/sign_upOperation.php" method="POST" enctype="multipart/form-data" dir="rtl">
                    <div class="section">
                        <h3 dir="rtl">المعلومات الشخصية</h3>
                        <div class="image-preview">
                            <img id="imagePreview" src="assets/images/defult_image.png" alt="Aperçu de l'image" />
                            <label for="imageUpload" class="custom-file-upload">اختر الصورة</label>
                            <input type="file" id="imageUpload" class="file-input" accept="image/*" name="imageUpload" />
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="prenom">الاسم الأول<span style="color: #f00;"> *</span></label>
                                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($formData['prenom'] ?? ''); ?>" placeholder="أدخل اسمك الأول">
                            </div>
                            <div class="input-field">
                                <label for="nom">اسم العائلة<span style="color: #f00;"> *</span></label>
                                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($formData['nom'] ?? ''); ?>" placeholder="أدخل اسمك الأخير">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="birthDate">تاريخ الميلاد<span style="color: #f00;"> *</span></label>
                                <input type="date" id="birthDate" name="birthDate" value="<?php echo htmlspecialchars($formData['birthDate'] ?? ''); ?>">
                            </div>
                            <div class="input-field">
                                <label for="address">العنوان<span style="color: #f00;"> *</span></label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>" placeholder="أدخل العنوان">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="healthStatus">الحالة الصحية<span style="color: #f00;"> *</span></label>
                                <select id="healthStatus" name="healthStatus">
                                    <option value="" disabled selected>اختر الحالة الصحية</option>
                                    <option value="سليم" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'سليم') ? 'selected' : ''; ?>>سليم</option>
                                    <option value="فرط في الحركة" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'فرط في الحركة') ? 'selected' : ''; ?>>فرط في الحركة</option>
                                    <option value="يتناول دواء" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'يتناول دواء') ? 'selected' : ''; ?>>يتناول دواء</option>
                                    <option value="ضيق التنفس" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'ضيق التنفس') ? 'selected' : ''; ?>>ضيق التنفس</option>
                                    <option value="السكري" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'السكري') ? 'selected' : ''; ?>>السكري</option>
                                    <option value="التوثر" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'التوثر') ? 'selected' : ''; ?>>التوثر</option>
                                    <option value="الاعصاب" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'الاعصاب') ? 'selected' : ''; ?>>الاعصاب</option>
                                    <option value="التوحد" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'التوحد') ? 'selected' : ''; ?>>التوحد</option>
                                    <option value="إعاقة حركية" <?php echo (isset($formData['healthStatus']) && $formData['healthStatus'] == 'إعاقة حركية') ? 'selected' : ''; ?>>إعاقة حركية</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="bloodType">فصيلة الدم</label>
                                <select id="bloodType" name="bloodType">
                                    <option value="" disabled selected>اختر فصيلة الدم</option>
                                    <option value="A+" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                    <option value="A-" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                    <option value="B+" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                    <option value="B-" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                    <option value="AB+" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                    <option value="AB-" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                    <option value="O+" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                    <option value="O-" <?php echo (isset($formData['bloodType']) && $formData['bloodType'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="weight">الوزن<span style="color: #f00;"> *</span></label>
                                <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($formData['weight'] ?? ''); ?>" placeholder="أدخل الوزن" step="1" min="0" pattern="^\d*(\.\d{0,2})?$">
                            </div>
                            <div class="input-field">
                                <label for="sport">الرياضة<span style="color: #f00;"> *</span></label>
                                <select id="sport" name="sport">
                                    <option value="" disabled selected>اختر الرياضة</option>
                                    <?php foreach ($sports as $sport): ?>
                                        <option value="<?php echo htmlspecialchars($sport['name']); ?>" <?php echo (isset($formData['sport']) && $formData['sport'] == $sport['name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sport['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="guardianName">اسم الوصي</label>
                                <input type="text" id="guardianName" name="guardianName" value="<?php echo htmlspecialchars($formData['guardianName'] ?? ''); ?>" placeholder="أدخل اسم الوصي">
                            </div>
                            <div class="input-field">
                                <label for="guardianPhone">هاتف الوصي</label>
                                <input type="tel" id="guardianPhone" name="guardianPhone" value="<?php echo htmlspecialchars($formData['guardianPhone'] ?? ''); ?>" placeholder="أدخل هاتف الوصي" dir="rtl">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="secondGuardianPhone">الهاتف الثاني للوصي</label>
                                <input type="tel" id="secondGuardianPhone" name="secondGuardianPhone" value="<?php echo htmlspecialchars($formData['secondGuardianPhone'] ?? ''); ?>" placeholder="أدخل الهاتف الثاني للوصي" dir="rtl">
                            </div>
                            <div class="input-field">
                                <label for="beltLevel">مستوى الحزام</label>
                                <select id="beltLevel" name="beltLevel">
                                    <option value="" disabled selected>اختر مستوى الحزام</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn" name="send" value="send">تسجيل</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const beltsTae = ["أبيض", "أصفر بخط أبيض", "أصفر", "برتقالي", "أخضر", "أزرق", "أزرق بخط أحمر", "أحمر", "أحمر بخط أسود", "أحمر بخطين أسودين"];
        const beltsFull = ["أبيض", "أصفر", "برتقالي", "أخضر", "أزرق", "بني", "أسود"];

        document.getElementById('sport').addEventListener('change', function() {
            const beltSelect = document.getElementById('beltLevel');
            beltSelect.innerHTML = '<option value="" disabled selected>اختر مستوى الحزام</option>'; // Reset options

            const selectedSport = this.value;
            let belts = [];

            if (selectedSport === 'تايكواندو') {
                belts = beltsTae;
            } else if (selectedSport === 'فول كونتاكت') {
                belts = beltsFull;
            }

            belts.forEach(belt => {
                const option = document.createElement('option');
                option.value = belt;
                option.textContent = belt;
                beltSelect.appendChild(option);
            });
        });

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