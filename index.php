<?php
session_start();

$id = '';

if (isset($_SESSION['message'])) {
    $id = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Database connection
require 'assets/php/db_connection.php';

// Fetch timetable data
$sql = "SELECT * FROM schedule ORDER BY FIELD(day, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'), timeslot";
$result = $conn->query($sql);

$timetable = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[$row['day']][$row['timeslot']] = $row['arabic_name'];
    }
}

$conn->close();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <title>Document</title>
</head>

<body>
    <?php require "header.php"; ?>
    <section id="hero">
        <div class="hero-slider">
            <div class="slide active" style="background-image: url('assets/images/FUllcontct.jpg');background-position: center;background-size: cover;"></div>
            <div class="slide" style="background-image: url('assets/images/aerobic.jpg');background-position: center;background-size: cover;"></div>
            <div class="slide" style="background-image: url('assets/images/taekwondo.jpg');background-position: center;background-size: cover;"></div>
        </div>
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Transformez-vous</h1>
            <p>Rejoignez-nous et atteignez vos objectifs.</p>
            <div class="button-container">
                <a href="sign_up.php" class="button">Commencer Maintenant</a>
            </div>
        </div>
    </section>
    <section id="about">
        <div class="container">
            <h2>À propos de notre salle de sport</h2>
            <p>Chez A.C.S.E, nous sommes convaincus de l'importance de proposer des programmes d'entraînement de haute qualité pour aider nos membres à atteindre leurs objectifs de remise en forme. Grâce à des installations à la pointe de la technologie et une équipe de coachs expérimentés, nous proposons une large gamme de cours, incluant le Taekwondo, le full contact et l'aérobic.</p>
        </div>
    </section>
    <section id="plans">
        <h2 class="speacial-heading">Nos Plans</h2>
        <div class="container">
            <div class="plan taekwondo">
                <h3>Taekwondo</h3>
                <p>Notre plan Taekwondo offre une formation complète en arts martiaux. Convient à tous les niveaux de compétence, des débutants aux pratiquants avancés.</p>
                <p>Prix : 100 DH / mois</p>
            </div>
            <div class="plan full-contact">
                <h3>Full Contact</h3>
                <p>Participez à des séances de full contact intenses conçues pour renforcer la force et l'endurance. Parfait pour ceux qui cherchent à se dépasser.</p>
                <p>Prix : 100 DH / mois</p>
            </div>
            <div class="plan aerobic">
                <h3>Aérobic</h3>
                <p>Rejoignez nos cours d'aérobic pour améliorer votre forme cardiovasculaire et votre santé globale. Des séances amusantes et énergiques pour tous les niveaux de forme physique.</p>
                <p>Prix : 100 DH / mois</p>
            </div>
        </div>
    </section>
    <section id="Horaire">
        <h2 class="speacial-heading">Horaire</h2>
        <div class="container">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>19:30-20:30</th>
                        <th>20:30-21:30</th>
                        <th>21:30-22:30</th>
                        <th>22:30-23:30</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                    $timeSlots = ['19:30:00-20:30:00', '20:30:00-21:30:00', '21:30:00-22:30:00', '22:30:00-23:30:00'];
                    
                    foreach ($days as $day) {
                        echo "<tr>";
                        echo "<th>{$day}</th>";
                        foreach ($timeSlots as $time) {
                            $sport = isset($timetable[$day][$time]) ? $timetable[$day][$time] : 'مغلق';
                            echo "<td>{$sport}</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <div class="footer-sections">
                <div class="footer-section">
                    <h3>À propos</h3>
                    <p>Chez A.C.S.E, nous nous engageons à offrir des programmes d'entraînement de haute qualité pour aider nos membres à atteindre leurs objectifs de remise en forme.</p>
                </div>
                <div class="footer-section">
                    <h3>Liens rapides</h3>
                    <ul>
                        <li><a href="#hero">Accueil</a></li>
                        <li><a href="#about">À propos de nous</a></li>
                        <li><a href="#plans">Des plans</a></li>
                        <li><a href="#Horaire">Horaire</a></li>
                        <li><a href="login.php">Se connecter</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contactez-nous</h3>
                    <ul>
                        <li>Email: info@salledesport.com</li>
                        <li>Téléphone: +212 123 456 789</li>
                        <li>Adresse: 123 Rue hay el hassani, Dakhla, Maroc</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Suivez-nous</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 A.C.S.E Tous droits réservés.</p>
        </div>
    </footer>

    <div class="modal" id="modal"></div>
    <div class="popup" id="popup">
        <span class="x-close" onclick="closePopup()">&times;</span>
        <div class="popup-content">
            <h2>Compte Créé Avec Succès</h2>
            <p>Votre compte a été créé avec succès, votre identifiant est : <strong id="user-identifier"></strong></p>
            <p>Pour activer votre compte, veuillez vous rendre en personne à notre club à l'adresse suivante :</p>
            <p><strong>Adresse :</strong> <span id="club-address"></span></p>
            <p>Pour plus d'informations, contactez l'administrateur au : <strong id="admin-phone"></strong></p>
        </div>
        <button class="close-btn" onclick="closePopup()">Fermer</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;
            const slideInterval = setInterval(nextSlide, 3000);

            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }

            function closePopup() {
                document.getElementById('popup').style.display = 'none';
            }

            if ('<?php echo $id; ?>' !== '') {
                document.getElementById('user-identifier').textContent = '<?php echo $id; ?>';
                document.getElementById('club-address').textContent = '123 Rue hay el hassani, Dakhla, Maroc';
                document.getElementById('admin-phone').textContent = '+212 123 456 789';
                document.getElementById('popup').style.display = 'block';
            }
        });
    </script>
</body>

</html>
