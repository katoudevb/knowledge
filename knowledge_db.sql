-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 21 oct. 2025 à 16:12
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
(1, 1, NULL, NULL, 'Introduction to Guitar', 50, NULL, NULL, NULL),
(2, 1, NULL, NULL, 'Introduction to Piano', 50, NULL, NULL, NULL),
(3, 2, NULL, NULL, 'Introduction to Web Development', 60, NULL, NULL, NULL),
(4, 3, NULL, NULL, 'Introduction to Gardening', 30, NULL, NULL, NULL),
(5, 4, NULL, NULL, 'Introduction to Cooking', 44, NULL, NULL, NULL),
(6, 4, NULL, NULL, 'Introduction to Culinary Plating', 48, NULL, NULL, NULL);

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
(1, 1, 1, NULL, NULL, 'Discovering the Instrument', 26, NULL, NULL, NULL, NULL),
(2, 1, 1, NULL, NULL, 'Chords and Scales', 26, NULL, NULL, NULL, NULL),
(3, 2, 1, NULL, NULL, 'Discovering the Instrument', 26, NULL, NULL, NULL, NULL),
(4, 2, 1, NULL, NULL, 'Chords and Scales', 26, NULL, NULL, NULL, NULL),
(5, 3, 2, NULL, NULL, 'HTML and CSS Basics', 32, NULL, NULL, NULL, NULL),
(6, 3, 2, NULL, NULL, 'Bring Your Website to Life with JavaScript', 32, NULL, NULL, NULL, NULL),
(7, 4, 3, NULL, NULL, 'Gardening Tools', 16, NULL, NULL, NULL, NULL),
(8, 4, 3, NULL, NULL, 'Gardening by the Moon', 16, NULL, NULL, NULL, NULL),
(9, 5, 4, NULL, NULL, 'Cooking Methods', 23, NULL, NULL, NULL, NULL),
(10, 5, 4, NULL, NULL, 'Flavors', 23, NULL, NULL, NULL, NULL),
(11, 6, 4, NULL, NULL, 'Plating Techniques', 26, NULL, NULL, NULL, NULL),
(12, 6, 4, NULL, NULL, 'Harmonizing a Four-Course Meal', 26, NULL, NULL, NULL, NULL);

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
(1, NULL, NULL, 'Music', NULL, NULL),
(2, NULL, NULL, 'IT', NULL, NULL),
(3, NULL, NULL, 'Gardening', NULL, NULL),
(4, NULL, NULL, 'Cooking', NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `validated` tinyint(1) NOT NULL,
  `validated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
