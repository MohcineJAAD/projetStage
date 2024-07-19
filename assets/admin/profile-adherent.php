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

            <h1 class="p-relative">الملف الشخصي</h1>
            <div class="profile-container m-20 bg-fff rad-10" >
                <div class="profile-header">
                    <?php
                    $trophies = [];
                    if (isset($_GET['id'])) {
                        $id = urldecode($_GET['id']);
                        $stmt = $conn->prepare("SELECT * FROM adherents WHERE identifier = ?");
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $nom = $row['nom'];
                            $prenom = $row['prenom'];
                            $dob = $row['date_naissance'] ? $row['date_naissance'] : "N/A";
                            $poids = $row['poids'] ? $row['poids'] : "N/A";
                            $type = $row['type'] ? $row['type'] : "N/A";
                            $date_adhesion = $row['date_adhesion'];
                            $BC_path = $row['BC_path'];
                            $image = $row['image_path'] ? "../uploads/" . $row['image_path'] : "../images/defult_image.png";
                            $guardian_name = $row['guardian_name'];
                            $guardian_phone = $row['guardian_phone'];
                            $second_guardian_phone = $row['second_guardian_phone'];
                            $address = $row['address'];
                            $health_status = $row['health_status'];
                            $blood_type = $row['blood_type'];
                            $current_belt = $row['current_belt'];
                            $next_belt = $row['next_belt'];
                            $identifiant = $row['identifier'];
                            $licence = $row['licence'];
                            $note = $row['note'];
                        } else
                            echo "<h3 class='profile-name m-0'>Information non disponible</h3>";
                        $stmt->close();
                        $stmt = $conn->prepare("SELECT * FROM trophies WHERE adherent_id = ?");
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $trophies_result = $stmt->get_result();
                        if ($trophies_result->num_rows > 0)
                            while ($trophy = $trophies_result->fetch_assoc())
                                $trophies[] = $trophy;
                        $stmt->close();
                    } else
                        echo "<h3 class='profile-name m-0'>Identifiant non fourni</h3>";
                    ?>
                </div>
                <div class="p-20 mb-20">
                    <form id="sportsForm" action="../php/alterProfile.php" method="POST" enctype="multipart/form-data">
                        <div class="section">
                            <div class="image-preview">
                                <img id="imagePreview" src="<?php echo $image; ?>" alt="Aperçu de l'image" />
                                <label for="imageUpload" class="custom-file-upload" style="width: 173.84px;">اختر صورة</label>
                                <input type="file" id="imageUpload" class="file-input" accept="image/*" name="imageUpload" disabled />
                                <a href="<?php echo $image; ?>" download class="custom-file-upload file-input" style="width: 173.84px;">تحميل</a>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="prenom">الاسم الأول</label>
                                    <input type="text" id="prenom" name="prenom" placeholder="Entrez votre Prenom" value="<?php echo htmlspecialchars($prenom); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="nom">اسم العائلة</label>
                                    <input type="text" id="nom" name="nom" placeholder="Entrez votre Nom" value="<?php echo htmlspecialchars($nom); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="birthDate">تاريخ الازدياد</label>
                                    <input type="date" id="birthDate" name="birthDate" value="<?php echo htmlspecialchars($dob); ?>" disabled dir="ltr">
                                </div>
                                <div class="input-field">
                                    <label for="address">تاريخ الانخراط</label>
                                    <input type="date" id="adhesionDate" name="adhesionDate" value="<?php echo htmlspecialchars($date_adhesion); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="guardianName">اسم الوصي</label>
                                    <input type="text" id="guardianName" name="guardianName" placeholder="Entrez le nom du tuteur" value="<?php echo htmlspecialchars($guardian_name); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="guardianPhone">هاتف الوصي</label>
                                    <input type="tel" id="guardianPhone" name="guardianPhone" placeholder="Entrez le téléphone du tuteur" value="<?php echo htmlspecialchars($guardian_phone); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="secondGuardianPhone">الهاتف الثاني للوصي</label>
                                    <input type="tel" id="secondGuardianPhone" name="secondGuardianPhone" placeholder="Entrez le deuxième téléphone du tuteur" value="<?php echo htmlspecialchars($second_guardian_phone); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="address">العنوان</label>
                                    <input type="text" id="address" name="address" placeholder="Entrez votre adresse" value="<?php echo htmlspecialchars($address); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="sport">الرياضة</label>
                                    <input type="text" id="sport" name="sport" placeholder="Entrez votre sport" value="<?php echo htmlspecialchars($type); ?>" disabled>
                                </div>
                                <div class="input-field">
                                    <label for="beltLevel">الحزام الحالي</label>
                                    <select id="beltLevel" name="beltLevel" disabled>
                                        <option value="" disabled selected>اختر مستوى الحزام</option>
                                        <option value="Blanche" <?php echo $current_belt == 'Blanche' ? 'selected' : ''; ?>>Blanche</option>
                                        <option value="Jaune" <?php echo $current_belt == 'Jaune' ? 'selected' : ''; ?>>Jaune</option>
                                        <option value="Verte" <?php echo $current_belt == 'Verte' ? 'selected' : ''; ?>>Verte</option>
                                        <option value="Bleue" <?php echo $current_belt == 'Bleue' ? 'selected' : ''; ?>>Bleue</option>
                                        <option value="Marron" <?php echo $current_belt == 'Marron' ? 'selected' : ''; ?>>Marron</option>
                                        <option value="Noire" <?php echo $current_belt == 'Noire' ? 'selected' : ''; ?>>Noire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="nextBeltLevel">الحزام التالي</label>
                                    <select id="nextBeltLevel" name="nextBeltLevel" disabled>
                                        <option value="null" disabled selected>اختر مستوى الحزام</option>
                                        <option value="Blanche" <?php echo $next_belt == 'Blanche' ? 'selected' : ''; ?>>Blanche</option>
                                        <option value="Jaune" <?php echo $next_belt == 'Jaune' ? 'selected' : ''; ?>>Jaune</option>
                                        <option value="Verte" <?php echo $next_belt == 'Verte' ? 'selected' : ''; ?>>Verte</option>
                                        <option value="Bleue" <?php echo $next_belt == 'Bleue' ? 'selected' : ''; ?>>Bleue</option>
                                        <option value="Marron" <?php echo $next_belt == 'Marron' ? 'selected' : ''; ?>>Marron</option>
                                        <option value="Noire" <?php echo $next_belt == 'Noire' ? 'selected' : ''; ?>>Noire</option>
                                    </select>
                                </div>
                                <div class="input-field">
                                    <label for="weight">الوزن</label>
                                    <input type="number" id="weight" name="weight" placeholder="Entrez le poids" step="1" min="0" pattern="^\d*(\.\d{0,2})?$" value="<?php echo htmlspecialchars($poids); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="bloodType">فصيلة الدم</label>
                                    <select id="bloodType" name="bloodType" disabled>
                                        <option value="null" disabled selected>اختر فصيلة الدم</option>
                                        <option value="A+" <?php echo $blood_type == 'A+' ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo $blood_type == 'A-' ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo $blood_type == 'B+' ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo $blood_type == 'B-' ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo $blood_type == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo $blood_type == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo $blood_type == 'O+' ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo $blood_type == 'O-' ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                                <div class="input-field">
                                    <label for="healthStatus">الحالة الصحية</label>
                                    <select id="healthStatus" name="healthStatus" disabled>
                                        <option value="" disabled selected>اختر الحالة الصحية</option>
                                        <option value="سليم">سليم</option>
                                        <option value="فرط في الحركة">فرط في الحركة</option>
                                        <option value="يتناول دواء">يتناول دواء</option>
                                        <option value="ضيق التنفس">ضيق التنفس</option>
                                        <option value="السكري">السكري</option>
                                        <option value="التوثر">التوثر</option>
                                        <option value="الاعصاب">الاعصاب</option>
                                        <option value="التوحد">التوحد</option>
                                        <option value="إعاقة حركية">إعاقة حركية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="identifier">المعرف</label>
                                    <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifiant); ?>" disabled>
                                    <input type="hidden" id="identifier_hidden" name="identifier" value="<?php echo htmlspecialchars($identifiant); ?>">
                                </div>
                                <div class="input-field">
                                    <label for="licence">رقم الرخصة الجامعية</label>
                                    <input type="text" id="licence" name="licence" value="<?php echo htmlspecialchars($licence); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field">
                                    <label for="note">ملحوظات</label>
                                    <textarea id="note" name="note" placeholder="ادخل ملاحضات" style="width: 100%;" disabled><?php echo htmlspecialchars($note); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="file-upload">
                            <label for="fileUpload" class="file-upload-label">
                                اختر ملف
                            </label>
                            <input type="file" id="fileUpload" class="file-upload-input" accept="image/*" name="BCUpload" disabled />
                            <?php
                            echo !empty($BC_path)
                                ? "<span id='file-upload-filename'>" . basename($BC_path) . "</span>"
                                : "<span id='file-upload-filename'>Aucun fichier choisi</span>";
                            ?>
                            <?php if (!empty($BC_path)) : ?>
                                <a href="<?php echo "../uploads/$BC_path"; ?>" download class="custom-file-upload file-input" style="width: 173.84px;">تحميل</a>
                            <?php endif; ?>
                        </div>
                        <div class="action-buttons mt-20">
                            <button type="button" class="modify-btn btn-shape mb-10"><i class="fas fa-edit"></i> تعديل</button>
                            <button type="submit" class="save-btn btn-shape mb-10 hidden"><i class="fas fa-save"></i> حفض</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="profile-container m-20 bg-fff rad-10">
                <div class="p-20 mb-20" dir="rtl">
                    <h2>الالقاب</h2>
                    <div class="wrapper d-grid gap-20">
                        <div class="add-card cards rad-6 p-20 p-relative txt-c" id="add-button">
                            <div class="add-content">
                                <div class="circle-dashed">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <p class="mt-10 color-333">اضف</p>
                            </div>
                        </div>
                        <?php foreach ($trophies as $trophy) : ?>
                            <div class="cards rad-10 txt-c-mobile block-mobile">
                                <div class="card-content">
                                    <h3><?php echo htmlspecialchars($trophy['description']); ?></h3>
                                    <p class="value"><?php echo htmlspecialchars($trophy['created_at']); ?></p>
                                    <i class="fa-solid fa-trophy fa-fw" style="color: #203a85;"></i>
                                    <a href="../php/deleteTrophy.php?id=<?php echo htmlspecialchars($trophy['id']); ?>&identifier=<?php echo htmlspecialchars($identifiant); ?>" class="btn-shape mt-10 bg-f00">حذف</a>
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
            <h2>اضف لقب</h2>
            <form id="addCardForm" action="../php/addTrophy.php" method="POST">
                <div class="section">
                    <div class="row">
                        <div class="input-field">
                            <label for="cardDescription">الوصف</label>
                            <textarea id="cardDescription" name="trophyDescription" placeholder="ادخل الوصف" style="width: 100%;" required></textarea>
                            <input type="hidden" name="identifier" value="<?php echo htmlspecialchars($identifiant); ?>">
                        </div>
                    </div>
                </div>
                <button type="submit" class="save-btn btn-shape mb-10">اضف</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('fileUpload').addEventListener('change', function(event) {
            const fileInput = event.target;
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'Aucun fichier choisi';
            document.getElementById('file-upload-filename').textContent = fileName;
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

        document.querySelector('.modify-btn').addEventListener('click', function() {
            document.querySelectorAll('input, select, textarea').forEach(function(input) {
                if (input.id !== 'identifier')
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