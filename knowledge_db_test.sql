-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 08 oct. 2025 à 10:17
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `knowledge_db_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `certification`
--

CREATE TABLE `certification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `obtained_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `certification`
--

INSERT INTO `certification` (`id`, `user_id`, `course_id`, `obtained_at`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(33, 401, 152, '2025-09-29 11:25:51', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `theme_id`, `title`, `price`, `created_at`, `updated_at`, `created_by`, `updated_by`, `description`) VALUES
(1, 1, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(6, 6, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(7, 7, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(8, 8, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(9, 9, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(10, 10, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(11, 11, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(12, 12, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(13, 13, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(14, 14, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(15, 15, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(16, 16, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(17, 17, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(18, 18, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(19, 19, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(20, 20, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(21, 21, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(22, 22, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(23, 23, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(24, 24, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(25, 25, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(26, 26, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(27, 27, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(28, 28, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(29, 29, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(30, 30, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(31, 31, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(32, 32, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(33, 33, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(34, 34, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(35, 35, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(36, 36, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(37, 37, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(38, 38, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(39, 39, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(40, 40, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(41, 41, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(42, 42, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(43, 43, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(44, 44, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(45, 45, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(46, 46, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(47, 47, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(48, 48, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(49, 49, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(50, 50, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(51, 51, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(52, 52, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(53, 53, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(54, 54, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(55, 55, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(56, 56, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(57, 57, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(58, 58, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(59, 59, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(60, 60, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(61, 61, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(62, 62, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(63, 63, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(64, 64, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(65, 65, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(66, 66, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(67, 67, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(68, 68, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(69, 69, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(70, 70, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(71, 71, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(72, 72, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(73, 73, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(74, 74, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(75, 75, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(76, 76, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(77, 77, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(78, 78, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(79, 79, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(80, 80, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(81, 81, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(82, 82, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(83, 83, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(84, 84, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(85, 85, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(86, 86, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(87, 87, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(88, 88, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(89, 89, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(90, 90, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(91, 91, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(92, 92, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(93, 93, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(94, 94, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(95, 95, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(96, 96, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(97, 97, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(98, 98, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(99, 99, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(100, 100, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(101, 101, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(102, 102, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(103, 103, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(104, 104, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(105, 105, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(106, 106, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(107, 107, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(108, 108, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(109, 109, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(110, 110, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(111, 111, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(112, 112, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(113, 113, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(114, 114, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(115, 115, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(116, 116, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(117, 117, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(118, 118, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(119, 119, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(120, 120, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(121, 121, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(122, 122, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(123, 123, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(124, 124, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(125, 125, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(126, 126, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(127, 127, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(128, 128, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(129, 129, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(130, 130, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(131, 131, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(132, 132, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(133, 133, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(134, 134, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(135, 135, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(136, 136, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(137, 137, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(138, 138, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(139, 139, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(140, 140, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(141, 141, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(142, 142, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(143, 143, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(144, 144, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(145, 145, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(146, 146, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(147, 147, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(148, 148, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(149, 149, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(150, 150, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(151, 151, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL),
(152, 152, 'Test Course', 50, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250902124538', '2025-09-11 09:48:27', 196),
('DoctrineMigrations\\Version20250909065520', '2025-09-11 09:48:27', 42),
('DoctrineMigrations\\Version20250909083356', '2025-09-11 09:48:27', 5),
('DoctrineMigrations\\Version20250909155755', '2025-09-11 09:48:27', 67),
('DoctrineMigrations\\Version20250911055048', '2025-09-11 09:48:27', 8),
('DoctrineMigrations\\Version20250911065802', '2025-09-11 09:48:27', 401),
('DoctrineMigrations\\Version20250911071624', '2025-09-11 09:48:27', 567),
('DoctrineMigrations\\Version20250911084844', '2025-09-11 09:48:28', 36),
('DoctrineMigrations\\Version20250911093750', '2025-09-11 09:48:28', 34),
('DoctrineMigrations\\Version20250911094752', '2025-09-11 09:48:28', 8),
('DoctrineMigrations\\Version20250929072644', '2025-09-29 07:27:00', 44);

-- --------------------------------------------------------

--
-- Structure de la table `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `theme_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lesson`
--

INSERT INTO `lesson` (`id`, `course_id`, `title`, `price`, `content`, `video_url`, `created_at`, `updated_at`, `created_by`, `updated_by`, `theme_id`) VALUES
(1, 1, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 2, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(3, 3, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 3),
(4, 4, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(5, 5, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 5),
(6, 6, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 6),
(7, 7, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 7),
(8, 8, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 8),
(9, 9, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 9),
(10, 10, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 10),
(11, 11, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 11),
(12, 12, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 12),
(13, 13, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 13),
(14, 14, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 14),
(15, 15, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 15),
(16, 16, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 16),
(17, 17, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 17),
(18, 18, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 18),
(19, 19, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 19),
(20, 20, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 20),
(21, 21, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 21),
(22, 22, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 22),
(23, 23, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 23),
(24, 24, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 24),
(25, 25, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 25),
(26, 26, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 26),
(27, 27, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 27),
(28, 28, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 28),
(29, 29, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 29),
(30, 30, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 30),
(31, 31, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 31),
(32, 32, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 32),
(33, 33, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 33),
(34, 34, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 34),
(35, 35, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 35),
(36, 36, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 36),
(37, 37, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 37),
(38, 38, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 38),
(39, 39, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 39),
(40, 40, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 40),
(41, 41, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 41),
(42, 42, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 42),
(43, 43, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 43),
(44, 44, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 44),
(45, 45, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 45),
(46, 46, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 46),
(47, 47, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 47),
(48, 48, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 48),
(49, 49, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 49),
(50, 50, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 50),
(51, 51, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 51),
(52, 52, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 52),
(53, 53, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 53),
(54, 54, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 54),
(55, 55, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 55),
(56, 56, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 56),
(57, 57, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 57),
(58, 58, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 58),
(59, 59, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 59),
(60, 60, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 60),
(61, 61, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 61),
(62, 62, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 62),
(63, 63, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 63),
(64, 64, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 64),
(65, 65, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 65),
(66, 66, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 66),
(67, 67, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 67),
(68, 68, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 68),
(69, 69, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 69),
(70, 70, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 70),
(71, 71, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 71),
(72, 72, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 72),
(73, 73, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 73),
(74, 74, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 74),
(75, 75, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 75),
(76, 76, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 76),
(77, 77, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 77),
(78, 78, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 78),
(79, 79, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 79),
(80, 80, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 80),
(81, 81, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 81),
(82, 82, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 82),
(83, 83, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 83),
(84, 84, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 84),
(85, 85, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 85),
(86, 86, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 86),
(87, 87, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 87),
(88, 88, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 88),
(89, 89, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 89),
(90, 90, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 90),
(91, 91, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 91),
(92, 92, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 92),
(93, 93, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 93),
(94, 94, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 94),
(95, 95, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 95),
(96, 96, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 96),
(97, 97, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 97),
(98, 98, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 98),
(99, 99, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 99),
(100, 100, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 100),
(101, 101, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 101),
(102, 102, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 102),
(103, 103, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 103),
(104, 104, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 104),
(105, 105, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 105),
(106, 106, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 106),
(107, 107, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 107),
(108, 108, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 108),
(109, 109, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 109),
(110, 110, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 110),
(111, 111, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 111),
(112, 112, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 112),
(113, 113, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 113),
(114, 114, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 114),
(115, 115, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 115),
(116, 116, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 116),
(117, 117, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 117),
(118, 118, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 118),
(119, 119, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 119),
(120, 120, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 120),
(121, 121, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 121),
(122, 122, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 122),
(123, 123, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 123),
(124, 124, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 124),
(125, 125, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 125),
(126, 126, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 126),
(127, 127, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 127),
(128, 128, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 128),
(129, 129, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 129),
(130, 130, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 130),
(131, 131, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 131),
(132, 132, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 132),
(133, 133, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 133),
(134, 134, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 134),
(135, 135, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 135),
(136, 136, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 136),
(137, 137, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 137),
(138, 138, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 138),
(139, 139, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 139),
(140, 140, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 140),
(141, 141, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 141),
(142, 142, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 142),
(143, 143, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 143),
(144, 144, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 144),
(145, 145, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 145),
(146, 146, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 146),
(147, 147, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 147),
(148, 148, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 148),
(149, 149, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 149),
(150, 150, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 150),
(151, 151, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 151),
(152, 152, 'Test Lesson', 50, NULL, NULL, NULL, NULL, NULL, NULL, 152);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `amount` double NOT NULL,
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Theme Test', NULL, NULL, NULL, NULL),
(2, 'Theme Test', NULL, NULL, NULL, NULL),
(3, 'Theme Test', NULL, NULL, NULL, NULL),
(4, 'Theme Test', NULL, NULL, NULL, NULL),
(5, 'Theme Test', NULL, NULL, NULL, NULL),
(6, 'Theme Test', NULL, NULL, NULL, NULL),
(7, 'Theme Test', NULL, NULL, NULL, NULL),
(8, 'Theme Test', NULL, NULL, NULL, NULL),
(9, 'Theme Test', NULL, NULL, NULL, NULL),
(10, 'Theme Test', NULL, NULL, NULL, NULL),
(11, 'Theme Test', NULL, NULL, NULL, NULL),
(12, 'Theme Test', NULL, NULL, NULL, NULL),
(13, 'Theme Test', NULL, NULL, NULL, NULL),
(14, 'Theme Test', NULL, NULL, NULL, NULL),
(15, 'Theme Test', NULL, NULL, NULL, NULL),
(16, 'Theme Test', NULL, NULL, NULL, NULL),
(17, 'Theme Test', NULL, NULL, NULL, NULL),
(18, 'Theme Test', NULL, NULL, NULL, NULL),
(19, 'Theme Test', NULL, NULL, NULL, NULL),
(20, 'Theme Test', NULL, NULL, NULL, NULL),
(21, 'Theme Test', NULL, NULL, NULL, NULL),
(22, 'Theme Test', NULL, NULL, NULL, NULL),
(23, 'Theme Test', NULL, NULL, NULL, NULL),
(24, 'Theme Test', NULL, NULL, NULL, NULL),
(25, 'Theme Test', NULL, NULL, NULL, NULL),
(26, 'Theme Test', NULL, NULL, NULL, NULL),
(27, 'Theme Test', NULL, NULL, NULL, NULL),
(28, 'Theme Test', NULL, NULL, NULL, NULL),
(29, 'Theme Test', NULL, NULL, NULL, NULL),
(30, 'Theme Test', NULL, NULL, NULL, NULL),
(31, 'Theme Test', NULL, NULL, NULL, NULL),
(32, 'Theme Test', NULL, NULL, NULL, NULL),
(33, 'Theme Test', NULL, NULL, NULL, NULL),
(34, 'Theme Test', NULL, NULL, NULL, NULL),
(35, 'Theme Test', NULL, NULL, NULL, NULL),
(36, 'Theme Test', NULL, NULL, NULL, NULL),
(37, 'Theme Test', NULL, NULL, NULL, NULL),
(38, 'Theme Test', NULL, NULL, NULL, NULL),
(39, 'Theme Test', NULL, NULL, NULL, NULL),
(40, 'Theme Test', NULL, NULL, NULL, NULL),
(41, 'Theme Test', NULL, NULL, NULL, NULL),
(42, 'Theme Test', NULL, NULL, NULL, NULL),
(43, 'Theme Test', NULL, NULL, NULL, NULL),
(44, 'Theme Test', NULL, NULL, NULL, NULL),
(45, 'Theme Test', NULL, NULL, NULL, NULL),
(46, 'Theme Test', NULL, NULL, NULL, NULL),
(47, 'Theme Test', NULL, NULL, NULL, NULL),
(48, 'Theme Test', NULL, NULL, NULL, NULL),
(49, 'Theme Test', NULL, NULL, NULL, NULL),
(50, 'Theme Test', NULL, NULL, NULL, NULL),
(51, 'Theme Test', NULL, NULL, NULL, NULL),
(52, 'Theme Test', NULL, NULL, NULL, NULL),
(53, 'Theme Test', NULL, NULL, NULL, NULL),
(54, 'Theme Test', NULL, NULL, NULL, NULL),
(55, 'Theme Test', NULL, NULL, NULL, NULL),
(56, 'Theme Test', NULL, NULL, NULL, NULL),
(57, 'Theme Test', NULL, NULL, NULL, NULL),
(58, 'Theme Test', NULL, NULL, NULL, NULL),
(59, 'Theme Test', NULL, NULL, NULL, NULL),
(60, 'Theme Test', NULL, NULL, NULL, NULL),
(61, 'Theme Test', NULL, NULL, NULL, NULL),
(62, 'Theme Test', NULL, NULL, NULL, NULL),
(63, 'Theme Test', NULL, NULL, NULL, NULL),
(64, 'Theme Test', NULL, NULL, NULL, NULL),
(65, 'Theme Test', NULL, NULL, NULL, NULL),
(66, 'Theme Test', NULL, NULL, NULL, NULL),
(67, 'Theme Test', NULL, NULL, NULL, NULL),
(68, 'Theme Test', NULL, NULL, NULL, NULL),
(69, 'Theme Test', NULL, NULL, NULL, NULL),
(70, 'Theme Test', NULL, NULL, NULL, NULL),
(71, 'Theme Test', NULL, NULL, NULL, NULL),
(72, 'Theme Test', NULL, NULL, NULL, NULL),
(73, 'Theme Test', NULL, NULL, NULL, NULL),
(74, 'Theme Test', NULL, NULL, NULL, NULL),
(75, 'Theme Test', NULL, NULL, NULL, NULL),
(76, 'Theme Test', NULL, NULL, NULL, NULL),
(77, 'Theme Test', NULL, NULL, NULL, NULL),
(78, 'Theme Test', NULL, NULL, NULL, NULL),
(79, 'Theme Test', NULL, NULL, NULL, NULL),
(80, 'Theme Test', NULL, NULL, NULL, NULL),
(81, 'Theme Test', NULL, NULL, NULL, NULL),
(82, 'Theme Test', NULL, NULL, NULL, NULL),
(83, 'Theme Test', NULL, NULL, NULL, NULL),
(84, 'Theme Test', NULL, NULL, NULL, NULL),
(85, 'Theme Test', NULL, NULL, NULL, NULL),
(86, 'Theme Test', NULL, NULL, NULL, NULL),
(87, 'Theme Test', NULL, NULL, NULL, NULL),
(88, 'Theme Test', NULL, NULL, NULL, NULL),
(89, 'Theme Test', NULL, NULL, NULL, NULL),
(90, 'Theme Test', NULL, NULL, NULL, NULL),
(91, 'Theme Test', NULL, NULL, NULL, NULL),
(92, 'Theme Test', NULL, NULL, NULL, NULL),
(93, 'Theme Test', NULL, NULL, NULL, NULL),
(94, 'Theme Test', NULL, NULL, NULL, NULL),
(95, 'Theme Test', NULL, NULL, NULL, NULL),
(96, 'Theme Test', NULL, NULL, NULL, NULL),
(97, 'Theme Test', NULL, NULL, NULL, NULL),
(98, 'Theme Test', NULL, NULL, NULL, NULL),
(99, 'Theme Test', NULL, NULL, NULL, NULL),
(100, 'Theme Test', NULL, NULL, NULL, NULL),
(101, 'Theme Test', NULL, NULL, NULL, NULL),
(102, 'Theme Test', NULL, NULL, NULL, NULL),
(103, 'Theme Test', NULL, NULL, NULL, NULL),
(104, 'Theme Test', NULL, NULL, NULL, NULL),
(105, 'Theme Test', NULL, NULL, NULL, NULL),
(106, 'Theme Test', NULL, NULL, NULL, NULL),
(107, 'Theme Test', NULL, NULL, NULL, NULL),
(108, 'Theme Test', NULL, NULL, NULL, NULL),
(109, 'Theme Test', NULL, NULL, NULL, NULL),
(110, 'Theme Test', NULL, NULL, NULL, NULL),
(111, 'Theme Test', NULL, NULL, NULL, NULL),
(112, 'Theme Test', NULL, NULL, NULL, NULL),
(113, 'Theme Test', NULL, NULL, NULL, NULL),
(114, 'Theme Test', NULL, NULL, NULL, NULL),
(115, 'Theme Test', NULL, NULL, NULL, NULL),
(116, 'Theme Test', NULL, NULL, NULL, NULL),
(117, 'Theme Test', NULL, NULL, NULL, NULL),
(118, 'Theme Test', NULL, NULL, NULL, NULL),
(119, 'Theme Test', NULL, NULL, NULL, NULL),
(120, 'Theme Test', NULL, NULL, NULL, NULL),
(121, 'Theme Test', NULL, NULL, NULL, NULL),
(122, 'Theme Test', NULL, NULL, NULL, NULL),
(123, 'Theme Test', NULL, NULL, NULL, NULL),
(124, 'Theme Test', NULL, NULL, NULL, NULL),
(125, 'Theme Test', NULL, NULL, NULL, NULL),
(126, 'Theme Test', NULL, NULL, NULL, NULL),
(127, 'Theme Test', NULL, NULL, NULL, NULL),
(128, 'Theme Test', NULL, NULL, NULL, NULL),
(129, 'Theme Test', NULL, NULL, NULL, NULL),
(130, 'Theme Test', NULL, NULL, NULL, NULL),
(131, 'Theme Test', NULL, NULL, NULL, NULL),
(132, 'Theme Test', NULL, NULL, NULL, NULL),
(133, 'Theme Test', NULL, NULL, NULL, NULL),
(134, 'Theme Test', NULL, NULL, NULL, NULL),
(135, 'Theme Test', NULL, NULL, NULL, NULL),
(136, 'Theme Test', NULL, NULL, NULL, NULL),
(137, 'Theme Test', NULL, NULL, NULL, NULL),
(138, 'Theme Test', NULL, NULL, NULL, NULL),
(139, 'Theme Test', NULL, NULL, NULL, NULL),
(140, 'Theme Test', NULL, NULL, NULL, NULL),
(141, 'Theme Test', NULL, NULL, NULL, NULL),
(142, 'Theme Test', NULL, NULL, NULL, NULL),
(143, 'Theme Test', NULL, NULL, NULL, NULL),
(144, 'Theme Test', NULL, NULL, NULL, NULL),
(145, 'Theme Test', NULL, NULL, NULL, NULL),
(146, 'Theme Test', NULL, NULL, NULL, NULL),
(147, 'Theme Test', NULL, NULL, NULL, NULL),
(148, 'Theme Test', NULL, NULL, NULL, NULL),
(149, 'Theme Test', NULL, NULL, NULL, NULL),
(150, 'Theme Test', NULL, NULL, NULL, NULL),
(151, 'Theme Test', NULL, NULL, NULL, NULL),
(152, 'Theme Test', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(401, 'user_68da6cbf012712.78784784@example.com', '[\"ROLE_USER\"]', '$2y$12$1p9m4RHsmXpWMdE5.yxZcOr88xn2KPbZtSCo0lm8L8y0G5uul.3HC', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_courses`
--

CREATE TABLE `user_courses` (
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_lesson`
--

CREATE TABLE `user_lesson` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `validated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_lesson`
--

INSERT INTO `user_lesson` (`id`, `user_id`, `lesson_id`, `validated`, `validated_at`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(30, 401, 152, 1, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6C3C6D75A76ED395` (`user_id`),
  ADD KEY `IDX_6C3C6D75591CC992` (`course_id`),
  ADD KEY `IDX_6C3C6D75DE12AB56` (`created_by`),
  ADD KEY `IDX_6C3C6D7516FE72E1` (`updated_by`);

--
-- Index pour la table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_169E6FB959027487` (`theme_id`),
  ADD KEY `IDX_169E6FB9DE12AB56` (`created_by`),
  ADD KEY `IDX_169E6FB916FE72E1` (`updated_by`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F87474F3591CC992` (`course_id`),
  ADD KEY `IDX_F87474F3DE12AB56` (`created_by`),
  ADD KEY `IDX_F87474F316FE72E1` (`updated_by`),
  ADD KEY `IDX_F87474F359027487` (`theme_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6117D13BA76ED395` (`user_id`),
  ADD KEY `IDX_6117D13B591CC992` (`course_id`),
  ADD KEY `IDX_6117D13BCDF80196` (`lesson_id`),
  ADD KEY `IDX_6117D13BDE12AB56` (`created_by`),
  ADD KEY `IDX_6117D13B16FE72E1` (`updated_by`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9775E708DE12AB56` (`created_by`),
  ADD KEY `IDX_9775E70816FE72E1` (`updated_by`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  ADD KEY `IDX_8D93D649DE12AB56` (`created_by`),
  ADD KEY `IDX_8D93D64916FE72E1` (`updated_by`);

--
-- Index pour la table `user_courses`
--
ALTER TABLE `user_courses`
  ADD PRIMARY KEY (`user_id`,`course_id`),
  ADD KEY `IDX_F1A84446A76ED395` (`user_id`),
  ADD KEY `IDX_F1A84446591CC992` (`course_id`);

--
-- Index pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9D266FCEA76ED395` (`user_id`),
  ADD KEY `IDX_9D266FCECDF80196` (`lesson_id`),
  ADD KEY `IDX_9D266FCEDE12AB56` (`created_by`),
  ADD KEY `IDX_9D266FCE16FE72E1` (`updated_by`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `certification`
--
ALTER TABLE `certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT pour la table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `FK_6C3C6D7516FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6C3C6D75591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_6C3C6D75A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6C3C6D75DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `FK_169E6FB916FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_169E6FB959027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`),
  ADD CONSTRAINT `FK_169E6FB9DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `FK_F87474F316FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_F87474F359027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`),
  ADD CONSTRAINT `FK_F87474F3591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_F87474F3DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `FK_6117D13B16FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6117D13B591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_6117D13BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6117D13BCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`),
  ADD CONSTRAINT `FK_6117D13BDE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `theme`
--
ALTER TABLE `theme`
  ADD CONSTRAINT `FK_9775E70816FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9775E708DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64916FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8D93D649DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_courses`
--
ALTER TABLE `user_courses`
  ADD CONSTRAINT `FK_F1A84446591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F1A84446A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  ADD CONSTRAINT `FK_9D266FCE16FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9D266FCEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9D266FCECDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`),
  ADD CONSTRAINT `FK_9D266FCEDE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
