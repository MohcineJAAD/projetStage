<?php
session_start();
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
                <h2 class="title">Inscription</h2>

                <form id="sportsForm" action="assets/php/sign_upOperation.php" method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <h3>Informations personnelles</h3>
                        <div class="image-preview">
                            <img id="imagePreview" src="assets/images/defult_image.png" alt="Aperçu de l'image" />
                            <label for="imageUpload" class="custom-file-upload">Choisir un fichier</label>
                            <input type="file" id="imageUpload" class="file-input" accept="image/*" name="imageUpload" />
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="prenom">Prénom</label>
                                <input type="text" id="prenom" name="prenom" placeholder="Entrez votre Prenom">
                            </div>
                            <div class="input-field">
                                <label for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" placeholder="Entrez votre Nom">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="birthDate">Date de naissance</label>
                                <input type="date" id="birthDate" name="birthDate">
                            </div>
                            <div class="input-field">
                                <label for="address">Adresse</label>
                                <input type="text" id="address" name="address" placeholder="Entrez votre adresse">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="guardianName">Nom du tuteur</label>
                                <input type="text" id="guardianName" name="guardianName" placeholder="Entrez le nom du tuteur">
                            </div>
                            <div class="input-field">
                                <label for="guardianPhone">Téléphone du tuteur</label>
                                <input type="tel" id="guardianPhone" name="guardianPhone" placeholder="Entrez le téléphone du tuteur">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="sport">Sport</label>
                                <select id="sport" name="sport">
                                    <option value="" disabled selected>Sélectionner le sport</option>
                                    <option value="Taekwondo">Taekwondo</option>
                                    <option value="Fullcontact">Full-contact</option>
                                    <option value="aerobic">Aérobic</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="beltLevel">Niveau de ceinture</label>
                                <select id="beltLevel" name="beltLevel">
                                    <option value="" disabled selected>Sélectionner le niveau de ceinture</option>
                                    <option value="Blanche">Blanche</option>
                                    <option value="Jaune">Jaune</option>
                                    <option value="Verte">Verte</option>
                                    <option value="Bleue">Bleue</option>
                                    <option value="Marron">Marron</option>
                                    <option value="Noire">Noire</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="weight">Poids</label>
                                <input type="number" id="weight" name="weight" placeholder="Entrez le poids" step="1" min="0" pattern="^\d*(\.\d{0,2})?$">
                            </div>
                            <div class="input-field">
                                <label for="healthStatus">État de santé</label>
                                <select id="healthStatus" name="healthStatus">
                                    <option value="" disabled selected>Sélectionner l'état de santé</option>
                                    <option value="Bon">Bon</option>
                                    <option value="Moyen">Moyen</option>
                                    <option value="Mauvais">Mauvais</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="bloodType">Groupe sanguin</label>
                                <select id="bloodType" name="bloodType">
                                    <option value="" disabled selected>Sélectionner le groupe sanguin</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn" name="send" value="send">Suivant</button>
                </form>
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