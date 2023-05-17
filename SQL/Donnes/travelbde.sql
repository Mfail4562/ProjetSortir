-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 17 mai 2023 à 06:29
-- Version du serveur : 8.0.32
-- Version de PHP : 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `travelbde`
--

-- --------------------------------------------------------

--
-- Structure de la table `campus`
--

DROP TABLE IF EXISTS `campus`;
CREATE TABLE IF NOT EXISTS `campus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9D0968115E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campus`
--

INSERT INTO `campus` (`id`, `name`) VALUES
(2, 'dieppe'),
(1, 'paris'),
(3, 'seoul');

-- --------------------------------------------------------

--
-- Structure de la table `city`
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2D5B02345E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `city`
--

INSERT INTO `city` (`id`, `name`, `zip_code`) VALUES
(1, 'Nantes', 58525),
(2, 'Bordeaux', 265845),
(3, 'Montpellier', 35258);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `place`
--

DROP TABLE IF EXISTS `place`;
CREATE TABLE IF NOT EXISTS `place` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_741D53CD8BAC62AF` (`city_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `place`
--

INSERT INTO `place` (`id`, `city_id`, `name`, `street`, `latitude`, `longitude`) VALUES
(1, 1, 'tour effeil', 'saint honoré', 39.28, 24.25),
(2, 3, 'place du vieux marché', 'Rue de mick jagger', 45.32, 61.17),
(3, 2, 'gros horloge', 'Rue du chien', 33.25, 51.25);

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `wording` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `wording`) VALUES
(1, 'Créée'),
(2, 'Ouverte'),
(3, 'Clôturée'),
(4, 'Activité en cours'),
(5, 'Passée'),
(6, 'Annulée');

-- --------------------------------------------------------

--
-- Structure de la table `travel`
--

DROP TABLE IF EXISTS `travel`;
CREATE TABLE IF NOT EXISTS `travel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leader_id` int NOT NULL,
  `status_id` int NOT NULL,
  `campus_organiser_id` int NOT NULL,
  `place_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_start` datetime NOT NULL,
  `duration` datetime NOT NULL,
  `limit_date_subscription` datetime NOT NULL,
  `nb_max_traveler` int NOT NULL,
  `infos` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D0B6BCE73154ED4` (`leader_id`),
  KEY `IDX_2D0B6BCE6BF700BD` (`status_id`),
  KEY `IDX_2D0B6BCEF2EF26CE` (`campus_organiser_id`),
  KEY `IDX_2D0B6BCEDA6A219` (`place_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `travel`
--

INSERT INTO `travel` (`id`, `leader_id`, `status_id`, `campus_organiser_id`, `place_id`, `name`, `date_start`, `duration`, `limit_date_subscription`, `nb_max_traveler`, `infos`) VALUES
(1, 2, 4, 1, 1, 'parachute', '2023-05-16 14:10:02', '2023-05-17 14:10:02', '2023-05-31 14:10:02', 50, '\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\n\r\n'),
(2, 4, 3, 3, 3, 'voyage', '2023-05-18 14:10:02', '2023-05-19 14:10:02', '2023-05-26 14:10:02', 50, '\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\r\n\r\n\r\n\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_campus_id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D64986CC499D` (`pseudo`),
  KEY `IDX_8D93D649AFBDD805` (`user_campus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `user_campus_id`, `email`, `roles`, `password`, `pseudo`, `lastname`, `firstname`, `phone_number`, `admin`, `actif`) VALUES
(2, 3, 'mathieu@test.com', '[]', '$2y$13$eOMjnpHsEffKgQg6TssnQ.vuDci4isVM9DtyZwJJt2/lP5fIX8fZ6', 'Chouchou', 'zefzer', 'zerzetttze', '0202020202', 0, 1),
(3, 2, 'mel@test.com', '[]', '$2y$13$eOMjnpHsEffKgQg6TssnQ.vuDci4isVM9DtyZwJJt2/lP5fIX8fZ6', 'mel', 'zfghfg', 'bgfdfz', '050505020', 0, 1),
(4, 3, 'namjoon@test.com', '[]', '$2y$13$eOMjnpHsEffKgQg6TssnQ.vuDci4isVM9DtyZwJJt2/lP5fIX8fZ6', 'RM', 'Kim', 'Namjoon', '050505020', 0, 1),
(5, 2, 'pouf@pouf.com', '[]', '$2y$13$eOMjnpHsEffKgQg6TssnQ.vuDci4isVM9DtyZwJJt2/lP5fIX8fZ6', 'Chou', 'thoumire', 'mathieu', '0252525252', 0, 1),
(6, 2, 'bonjour@lol.com', '[]', '$2y$13$eOMjnpHsEffKgQg6TssnQ.vuDci4isVM9DtyZwJJt2/lP5fIX8fZ6', 'chow2', 'jaques', 'jean', '0235854520', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user_travel`
--

DROP TABLE IF EXISTS `user_travel`;
CREATE TABLE IF NOT EXISTS `user_travel` (
  `user_id` int NOT NULL,
  `travel_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`travel_id`),
  KEY `IDX_485970F3A76ED395` (`user_id`),
  KEY `IDX_485970F3ECAB15B3` (`travel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `FK_741D53CD8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`);

--
-- Contraintes pour la table `travel`
--
ALTER TABLE `travel`
  ADD CONSTRAINT `FK_2D0B6BCE6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_2D0B6BCE73154ED4` FOREIGN KEY (`leader_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2D0B6BCEDA6A219` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`),
  ADD CONSTRAINT `FK_2D0B6BCEF2EF26CE` FOREIGN KEY (`campus_organiser_id`) REFERENCES `campus` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649AFBDD805` FOREIGN KEY (`user_campus_id`) REFERENCES `campus` (`id`);

--
-- Contraintes pour la table `user_travel`
--
ALTER TABLE `user_travel`
  ADD CONSTRAINT `FK_485970F3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_485970F3ECAB15B3` FOREIGN KEY (`travel_id`) REFERENCES `travel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
