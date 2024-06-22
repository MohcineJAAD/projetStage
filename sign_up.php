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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <title>Document</title>
</head>

<body>
    <?php require "header.php" ?>
    <div class="landing sing_up">
        <div class="container">
            <div class="form-container">
                <h2 class="title">Inscription</h2>

                <form id="sportsForm">
                    <div class="section">
                        <h3>Informations personnelles</h3>
                        <div class="image-preview">
                            <img id="imagePreview" src="assets/images/defult_image.png" alt="Aperçu de l'image" />
                            <label for="imageUpload" class="custom-file-upload">Choisir un fichier</label>
                            <input type="file" id="imageUpload" class="file-input" accept="image/*" />
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="fullName">Nom complet</label>
                                <input type="text" id="fullName" placeholder="Entrez votre nom" required>
                            </div>
                            <div class="input-field">
                                <label for="birthDate">Date de naissance</label>
                                <input type="date" id="birthDate" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="address">Adresse</label>
                                <input type="text" id="address" placeholder="Entrez votre adresse" required>
                            </div>
                            <div class="input-field">
                                <label for="guardianName">Nom du tuteur</label>
                                <input type="text" id="guardianName" placeholder="Entrez le nom du tuteur" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="guardianPhone">Téléphone du tuteur</label>
                                <input type="tel" id="guardianPhone" placeholder="Entrez le téléphone du tuteur" required>
                            </div>
                            <div class="input-field">
                                <label for="membershipDate">Date d'adhésion</label>
                                <input type="date" id="membershipDate" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="sport">Sport</label>
                                <select id="sport" required>
                                    <option value="" disabled selected>Sélectionner le sport</option>
                                    <option value="Karate">Karaté</option>
                                    <option value="Judo">Judo</option>
                                    <option value="Taekwondo">Taekwondo</option>
                                    <option value="Boxe">Boxe</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="beltLevel">Niveau de ceinture</label>
                                <select id="beltLevel" required>
                                    <option value="" disabled selected>Sélectionner le niveau de ceinture</option>
                                    <option value="Blanche">Blanche</option>
                                    <option value="Jaune">Jaune</option>
                                    <option value="Verte">Verte</option>
                                    <option value="Bleue">Bleue</option>
                                    <option value="Marron">Marron</option>
                                    <option value="Noire">Noire</option>
                                    <!-- Ajoutez d'autres niveaux de ceinture au besoin -->
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="weight">Poids</label>
                                <input type="number" id="weight" placeholder="Entrez le poids" required>
                            </div>
                            <div class="input-field">
                                <label for="sportsSeason">Saison sportive</label>
                                <input type="text" id="sportsSeason" placeholder="Entrez la saison sportive" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field">
                                <label for="healthStatus">État de santé</label>
                                <select id="healthStatus" required>
                                    <option value="" disabled selected>Sélectionner l'état de santé</option>
                                    <option value="Bon">Bon</option>
                                    <option value="Moyen">Moyen</option>
                                    <option value="Mauvais">Mauvais</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="bloodType">Groupe sanguin</label>
                                <select id="bloodType" required>
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
                    <button type="submit" class="btn">Suivant</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript pour prévisualiser l'image téléchargée
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

        // Déclencher l'événement de clic sur l'entrée de fichier
        document.querySelector('.custom-file-upload').addEventListener('click', function() {
            document.getElementById('imageUpload').click();
        });
    </script>
</body>

</html>
