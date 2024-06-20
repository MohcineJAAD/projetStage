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
    <?php require "header.php";?>
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
                <a href="#form-section" class="button">Commencer Maintenant</a>
            </div>
        </div>
    </section>
    <section id="about">
        <div class="container">
            <h2>À propos de notre salle de sport</h2>
            <p>Chez [Nom de la salle de sport], nous sommes convaincus de l'importance de proposer des programmes d'entraînement de haute qualité pour aider nos membres à atteindre leurs objectifs de remise en forme. Grâce à des installations à la pointe de la technologie et une équipe de coachs expérimentés, nous proposons une large gamme de cours, incluant le Taekwondo, le full contact et l'aérobic.</p>
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
                        <th>Taekwondo</th>
                        <th>Full Contact</th>
                        <th>Aérobic</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Lundi</th>
                        <td>18:00 - 19:00</td>
                        <td>19:00 - 20:00</td>
                        <td>20:00 - 21:00</td>
                    </tr>
                    <tr>
                        <th>Mardi</th>
                        <td>18:00 - 19:00</td>
                        <td>19:00 - 20:00</td>
                        <td>20:00 - 21:00</td>
                    </tr>
                    <tr>
                        <th>Mercredi</th>
                        <td>18:00 - 19:00</td>
                        <td>19:00 - 20:00</td>
                        <td>20:00 - 21:00</td>
                    </tr>
                    <tr>
                        <th>Jeudi</th>
                        <td>18:00 - 19:00</td>
                        <td>19:00 - 20:00</td>
                        <td>20:00 - 21:00</td>
                    </tr>
                    <tr>
                        <th>Vendredi</th>
                        <td>18:00 - 19:00</td>
                        <td>19:00 - 20:00</td>
                        <td>20:00 - 21:00</td>
                    </tr>
                    <tr>
                        <th>Samedi</th>
                        <td>10:00 - 11:00</td>
                        <td>11:00 - 12:00</td>
                        <td>12:00 - 13:00</td>
                    </tr>
                    <tr>
                        <th>Dimanche</th>
                        <td>Fermé</td>
                        <td>Fermé</td>
                        <td>Fermé</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <div class="footer-sections">
                <div class="footer-section">
                    <h3>À propos</h3>
                    <p>Chez [Nom de la salle de sport], nous nous engageons à offrir des programmes d'entraînement de haute qualité pour aider nos membres à atteindre leurs objectifs de remise en forme.</p>
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
                        <li>Adresse: 123 Rue de la Gym, Dakhla, Maroc</li>
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
            <p>&copy; 2024 [Nom de la salle de sport]. Tous droits réservés.</p>
        </div>
    </footer>


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
        });
    </script>
</body>

</html>