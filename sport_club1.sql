-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 nov. 2024 à 19:15
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sport_club`
--

-- --------------------------------------------------------

--
-- Structure de la table `adherents`
--

CREATE TABLE `adherents` (
  `identifier` varchar(20) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `poids` decimal(5,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date_adhesion` date NOT NULL DEFAULT curdate(),
  `image_path` varchar(255) DEFAULT NULL,
  `BC_path` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(50) DEFAULT NULL,
  `guardian_phone` varchar(15) DEFAULT NULL,
  `second_guardian_phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `health_status` varchar(20) DEFAULT NULL,
  `blood_type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `current_belt` varchar(50) DEFAULT NULL,
  `next_belt` varchar(50) DEFAULT NULL,
  `licence` varchar(250) DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `adherents`
--

INSERT INTO `adherents` (`identifier`, `nom`, `prenom`, `date_naissance`, `poids`, `type`, `date_adhesion`, `image_path`, `BC_path`, `guardian_name`, `guardian_phone`, `second_guardian_phone`, `address`, `health_status`, `blood_type`, `status`, `current_belt`, `next_belt`, `licence`, `note`) VALUES
('A000000002', 'اكفاس', 'تفيق', '2004-01-24', 90.00, 'تايكواندو', '2024-07-04', 'mohcine.jpg', '6679c510176e2_avatar.png', 'houcine', '0612341234', '0612341234', 'hay errahma', NULL, 'O+', 'active', 'أخضر', 'أزرق', '1234567890', '');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `club_name` varchar(255) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `twitter` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `identifier`, `password`, `full_name`, `club_name`, `logo`, `email`, `phone`, `address`, `facebook`, `instagram`, `twitter`) VALUES
(1, 'O642634894', 'Oiif1234', 'Oussama  ', 'الحسني', 'logo officiel ASS CLUB SPORTIF-1.png', '', '', '123 شارع حي الحسني، الداخلة، المغرب', 'https://www.facebook.com/mouhcin.jaad/', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `identifier` varchar(20) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `attendance`
--

INSERT INTO `attendance` (`id`, `identifier`, `date`) VALUES
(1, 'A000000002', '2024-07-04'),
(2, 'A000000002', '2024-07-21'),
(3, 'A000000002', '2024-08-08'),
(4, 'A000000002', '2024-08-23'),
(5, 'A000000002', '2024-08-23'),
(6, 'A000000002', '2024-08-23');

-- --------------------------------------------------------

--
-- Structure de la table `ceinture`
--

CREATE TABLE `ceinture` (
  `id` int(11) NOT NULL,
  `ceinture_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ceinture`
--

INSERT INTO `ceinture` (`id`, `ceinture_level`) VALUES
(1, 'الأبيض');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `identifier` varchar(20) NOT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `discipline` int(11) DEFAULT NULL,
  `performance` int(11) DEFAULT NULL,
  `behavior` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `identifier`, `month`, `year`, `discipline`, `performance`, `behavior`) VALUES
(2, 'A000000002', 7, 2024, 3, 0, 4),
(5, 'A000000002', 8, 2024, 3, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `identifier` varchar(20) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `Date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `identifier`, `payment_date`, `amount`, `type`, `Date`) VALUES
(217, 'A000000002', '2024-01-01', 55.00, 'assurance', '2024-08-23'),
(219, 'A000000002', '2024-11-01', 155.00, 'mois', '2024-08-24'),
(220, 'A000000002', '2024-01-01', 150.00, 'adhesion', '2024-08-23'),
(221, 'A000000002', '2024-10-01', 155.00, 'mois', '2024-08-24'),
(222, 'A000000002', '2024-12-01', 155.00, 'mois', '2024-08-23'),
(225, 'A000000002', '2024-07-01', 155.00, 'mois', '2024-10-26');

-- --------------------------------------------------------

--
-- Structure de la table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `description` varchar(500) DEFAULT NULL,
  `assurance` decimal(10,2) DEFAULT NULL,
  `adherence` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plans`
--

INSERT INTO `plans` (`id`, `name`, `price`, `created_at`, `description`, `assurance`, `adherence`) VALUES
(1, 'تايكواندو', 155.00, '2024-08-18', 'خطتنا لتايكوندو تقدم تدريبًا شاملاً في الفنون القتالية. مناسبة لجميع مستويات المهارة، من المبتدئين إلى الممارسين المتقدمين.', 55.00, 150.00),
(5, 'فول كونتاكت', 150.00, '2024-08-20', 'خطتنا لتايكوندو تقدم تدريبًا شاملاً في الفنون القتالية. مناسبة لجميع مستويات المهارة، من المبتدئين إلى الممارسين المتقدمين.', 50.00, 100.00),
(7, 'إيروبيك / رجال', 120.00, '2024-08-22', 'انضم إلى دورات الإيروبيك لدينا لتحسين لياقتك القلبية الوعائية وصحتك العامة. جلسات ممتعة ونشيطة لجميع مستويات اللياقة البدنية.', 50.00, 100.00),
(8, 'إيروبيك / سيدات', 123.00, '2024-08-22', 'انضمّي إلى دورات الإيروبيك لدينا لتحسين لياقتك القلبية الوعائية وصحتك العامة. جلسات ممتعة ونشيطة لجميع مستويات اللياقة البدنية.', NULL, NULL),
(12, 'temp', 134.00, '2024-08-23', 'jkhh', 24.00, 1234.00);

-- --------------------------------------------------------

--
-- Structure de la table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `timeslot` varchar(20) NOT NULL,
  `sport_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `schedule`
--

INSERT INTO `schedule` (`id`, `day`, `timeslot`, `sport_type`) VALUES
(54, 'الاثنين', '16:30:00-17:30:00', 'إيروبيك / سيدات'),
(55, 'الاثنين', '19:30:00-20:30:00', 'فول كونتاكت'),
(56, 'الثلاثاء', '16:30:00-17:30:00', 'إيروبيك / سيدات'),
(57, 'الخميس', '18:30:00-19:30:00', 'إيروبيك / رجال');

-- --------------------------------------------------------

--
-- Structure de la table `trophies`
--

CREATE TABLE `trophies` (
  `id` int(11) NOT NULL,
  `adherent_id` varchar(20) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trophies`
--

INSERT INTO `trophies` (`id`, `adherent_id`, `description`, `created_at`) VALUES
(5, 'A000000002', 'الحزالم الاصفر', '2024-07-19');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adherents`
--
ALTER TABLE `adherents`
  ADD PRIMARY KEY (`identifier`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_identifier` (`identifier`);

--
-- Index pour la table `ceinture`
--
ALTER TABLE `ceinture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_identifier` (`identifier`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_identifier` (`identifier`);

--
-- Index pour la table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trophies`
--
ALTER TABLE `trophies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adherent_id` (`adherent_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ceinture`
--
ALTER TABLE `ceinture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT pour la table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `trophies`
--
ALTER TABLE `trophies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_identifier_adherents_identifier` FOREIGN KEY (`identifier`) REFERENCES `adherents` (`identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_identifier_adherents_identifier` FOREIGN KEY (`identifier`) REFERENCES `adherents` (`identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_identifier_adherents_identifier` FOREIGN KEY (`identifier`) REFERENCES `adherents` (`identifier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trophies`
--
ALTER TABLE `trophies`
  ADD CONSTRAINT `trophies_ibfk_1` FOREIGN KEY (`adherent_id`) REFERENCES `adherents` (`identifier`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
