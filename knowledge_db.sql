-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 11 déc. 2025 à 14:02
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
-- Base de données : `knowledge_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `certification`
--

CREATE TABLE `certification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `obtained_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `certification`
--

INSERT INTO `certification` (`id`, `user_id`, `course_id`, `created_by`, `updated_by`, `obtained_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, '2025-12-10 20:52:56', NULL, NULL),
(2, 1, 2, NULL, NULL, '2025-12-10 20:53:05', NULL, NULL),
(3, 1, 3, NULL, NULL, '2025-12-10 20:54:01', NULL, NULL),
(4, 4, 3, NULL, NULL, '2025-12-11 12:54:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `theme_id`, `created_by`, `updated_by`, `title`, `price`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'Cursus d’initiation à la guitare', 50, NULL, NULL, NULL),
(2, 1, NULL, NULL, 'Cursus d’initiation au piano', 50, NULL, NULL, NULL),
(3, 2, NULL, NULL, 'Cursus d’initiation au développement web', 60, NULL, NULL, NULL),
(4, 3, NULL, NULL, 'Cursus d’initiation au jardinage', 30, NULL, NULL, NULL),
(5, 4, NULL, NULL, 'Cursus d’initiation à la cuisine', 44, NULL, NULL, NULL),
(6, 4, NULL, NULL, 'Cursus d’initiation à l’art du dressage culinaire', 48, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lesson`
--

INSERT INTO `lesson` (`id`, `course_id`, `theme_id`, `created_by`, `updated_by`, `title`, `price`, `content`, `video_url`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, 'Leçon n°1 : Découverte de l’instrument', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=yLPl2m1fh_8', NULL, NULL),
(2, 1, 1, NULL, NULL, 'Leçon n°2 : Les accords et les gammes', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=SnFjybPIUuo', NULL, NULL),
(3, 2, 1, NULL, NULL, 'Leçon n°1 : Découverte de l’instrument', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=rIU6o6Kvf_U&list=PLMOBvmgGAVCqigZDJm4k25iFubYFV8D30', NULL, NULL),
(4, 2, 1, NULL, NULL, 'Leçon n°2 : Les accords et les gammes', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=xYgbvCOE0Ck', NULL, NULL),
(5, 3, 2, NULL, NULL, 'Leçon n°1 : Les langages HTML et CSS', 32, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=Y80juYcu3ZI&list=PLwLsbqvBlImHG5yeUCXJ1aqNMgUKi1NK3', NULL, NULL),
(6, 3, 2, NULL, NULL, 'Leçon n°2 : Dynamiser votre site avec JavaScript', 32, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=VZLflMqC6dI&list=PLwLsbqvBlImFB8AuT6ENIg-s87ys4yGWI', NULL, NULL),
(7, 4, 3, NULL, NULL, 'Leçon n°1 : Les outils du jardinier', 16, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=Sv-ZzrYZNhk', NULL, NULL),
(8, 4, 3, NULL, NULL, 'Leçon n°2 : Jardiner avec la lune', 16, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=l7OVadWZlLQ', NULL, NULL),
(9, 5, 4, NULL, NULL, 'Leçon n°1 : Les modes de cuisson', 23, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=5xJ8S9HjHoo&list=PLHnvgEREG2HCeI9VQiikLD2yq_c99jVjg', NULL, NULL),
(10, 5, 4, NULL, NULL, 'Leçon n°2 : Les saveurs', 23, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=WUSGrHGRmp0&t=17s', NULL, NULL),
(11, 6, 4, NULL, NULL, 'Leçon n°1 : Mettre en œuvre le style dans l’assiette', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=f8rX_mAuCmA', NULL, NULL),
(12, 6, 4, NULL, NULL, 'Leçon n°2 : Harmoniser un repas à quatre plats', 26, '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'https://www.youtube.com/watch?v=oTLTVI4LUX4', NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `amount` double NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `purchase`
--

INSERT INTO `purchase` (`id`, `user_id`, `course_id`, `lesson_id`, `created_by`, `updated_by`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, 50, '2025-12-10 20:22:20', NULL),
(2, 1, 3, NULL, NULL, NULL, 60, '2025-12-10 20:26:44', NULL),
(3, 1, 3, NULL, NULL, NULL, 60, '2025-12-10 20:33:00', NULL),
(4, 1, NULL, 5, NULL, NULL, 32, '2025-12-10 20:33:19', NULL),
(5, 1, NULL, 3, NULL, NULL, 26, '2025-12-10 20:36:28', NULL),
(6, 1, NULL, 4, NULL, NULL, 26, '2025-12-10 20:36:39', NULL),
(7, 1, 2, NULL, NULL, NULL, 50, '2025-12-10 20:36:55', NULL),
(8, 1, 1, NULL, NULL, NULL, 50, '2025-12-10 20:37:21', NULL),
(9, 1, NULL, 1, NULL, NULL, 26, '2025-12-10 20:37:38', NULL),
(10, 1, 2, NULL, NULL, NULL, 50, '2025-12-10 20:38:01', NULL),
(11, 1, NULL, 4, NULL, NULL, 26, '2025-12-10 20:38:27', NULL),
(12, 1, 5, NULL, NULL, NULL, 44, '2025-12-10 20:55:03', NULL),
(13, 1, 4, NULL, NULL, NULL, 30, '2025-12-10 20:55:43', NULL),
(14, 1, 6, NULL, NULL, NULL, 48, '2025-12-10 20:57:48', NULL),
(15, 1, 3, NULL, NULL, NULL, 60, '2025-12-11 10:53:21', NULL),
(16, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 10:56:16', NULL),
(17, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 10:58:21', NULL),
(18, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 10:59:12', NULL),
(19, 2, 5, NULL, NULL, NULL, 44, '2025-12-11 11:03:12', NULL),
(20, 2, 3, NULL, NULL, NULL, 60, '2025-12-11 11:09:39', NULL),
(21, 2, 4, NULL, NULL, NULL, 30, '2025-12-11 11:10:49', NULL),
(22, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 11:16:23', NULL),
(23, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 11:19:12', NULL),
(24, 2, NULL, 1, NULL, NULL, 26, '2025-12-11 11:36:37', NULL),
(25, 2, NULL, 1, NULL, NULL, 26, '2025-12-11 11:39:08', NULL),
(26, 2, NULL, 2, NULL, NULL, 26, '2025-12-11 11:41:47', NULL),
(27, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 11:59:15', NULL),
(28, 2, 2, NULL, NULL, NULL, 50, '2025-12-11 12:17:17', NULL),
(29, 2, 1, NULL, NULL, NULL, 50, '2025-12-11 12:23:10', NULL),
(30, 2, 6, NULL, NULL, NULL, 48, '2025-12-11 12:27:00', NULL),
(31, 4, 1, NULL, NULL, NULL, 50, '2025-12-11 12:35:26', NULL),
(32, 4, 2, NULL, NULL, NULL, 50, '2025-12-11 12:40:05', NULL),
(33, 4, NULL, 3, NULL, NULL, 26, '2025-12-11 12:40:29', NULL),
(34, 4, 3, NULL, NULL, NULL, 60, '2025-12-11 12:53:39', NULL),
(35, 4, NULL, 5, NULL, NULL, 32, '2025-12-11 12:54:15', NULL),
(36, 4, NULL, 6, NULL, NULL, 32, '2025-12-11 12:54:48', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`id`, `created_by`, `updated_by`, `name`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Musique', NULL, NULL),
(2, NULL, NULL, 'Informatique', NULL, NULL),
(3, NULL, NULL, 'Jardinage', NULL, NULL),
(4, NULL, NULL, 'Cuisine', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `created_by`, `updated_by`, `email`, `roles`, `password`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'client@example.com', '[\"ROLE_CLIENT\"]', '$2y$13$DYsTMI4V7ZjfZXBvqI2dLuFh5bs7DYZa3g4F.4rFcuN2D5jkHv1b6', 1, NULL, NULL),
(2, NULL, NULL, 'client1@example.com', '[\"ROLE_CLIENT\"]', '$2y$13$1Mwsb/DlJ/mDsGXFMZ/wm.mEYD6VZO3V08CMYS4Dm71GtkSCGOAdm', 1, NULL, NULL),
(3, NULL, NULL, 'admin@example.com', '[\"ROLE_CLIENT\",\"ROLE_ADMIN\"]', '$2y$13$eZ5xgXc2Iwd0x4Boav3j0eLMEpiTb9Xhlbx5syZTXnjdvjdD8cy5W', 1, NULL, NULL),
(4, NULL, NULL, 'testuser1@example.com', '[\"ROLE_CLIENT\"]', '$2y$13$pZqLWPiKxcIELNk9SXkqe.H6p0Ub2k/a2djA5zefBIOOyu8lpOM2m', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_courses`
--

CREATE TABLE `user_courses` (
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_courses`
--

INSERT INTO `user_courses` (`user_id`, `course_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(4, 1),
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `user_lesson`
--

CREATE TABLE `user_lesson` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `validated` tinyint(1) NOT NULL,
  `validated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_lesson`
--

INSERT INTO `user_lesson` (`id`, `user_id`, `lesson_id`, `created_by`, `updated_by`, `validated`, `validated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, 1, NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, 1, NULL, NULL, NULL),
(3, 1, 5, NULL, NULL, 1, NULL, NULL, NULL),
(4, 1, 6, NULL, NULL, 1, NULL, NULL, NULL),
(5, 1, 3, NULL, NULL, 1, NULL, NULL, NULL),
(6, 1, 4, NULL, NULL, 1, NULL, NULL, NULL),
(7, 1, 9, NULL, NULL, 0, NULL, NULL, NULL),
(8, 1, 10, NULL, NULL, 0, NULL, NULL, NULL),
(9, 1, 7, NULL, NULL, 1, NULL, NULL, NULL),
(10, 1, 8, NULL, NULL, 0, NULL, NULL, NULL),
(11, 1, 11, NULL, NULL, 0, NULL, NULL, NULL),
(12, 1, 12, NULL, NULL, 0, NULL, NULL, NULL),
(13, 2, 1, NULL, NULL, 0, NULL, NULL, NULL),
(14, 2, 2, NULL, NULL, 0, NULL, NULL, NULL),
(15, 2, 9, NULL, NULL, 0, NULL, NULL, NULL),
(16, 2, 10, NULL, NULL, 0, NULL, NULL, NULL),
(17, 2, 5, NULL, NULL, 0, NULL, NULL, NULL),
(18, 2, 6, NULL, NULL, 0, NULL, NULL, NULL),
(19, 2, 7, NULL, NULL, 0, NULL, NULL, NULL),
(20, 2, 8, NULL, NULL, 0, NULL, NULL, NULL),
(21, 2, 3, NULL, NULL, 0, NULL, NULL, NULL),
(22, 2, 4, NULL, NULL, 0, NULL, NULL, NULL),
(23, 2, 11, NULL, NULL, 0, NULL, NULL, NULL),
(24, 2, 12, NULL, NULL, 0, NULL, NULL, NULL),
(25, 4, 1, NULL, NULL, 0, NULL, NULL, NULL),
(26, 4, 2, NULL, NULL, 0, NULL, NULL, NULL),
(27, 4, 3, NULL, NULL, 0, NULL, NULL, NULL),
(28, 4, 5, NULL, NULL, 1, NULL, NULL, NULL),
(29, 4, 6, NULL, NULL, 1, NULL, NULL, NULL);

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
  ADD KEY `IDX_F87474F359027487` (`theme_id`),
  ADD KEY `IDX_F87474F3DE12AB56` (`created_by`),
  ADD KEY `IDX_F87474F316FE72E1` (`updated_by`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
