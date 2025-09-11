-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 10 sep. 2025 à 14:44
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
  `obtained_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `theme_id`, `title`, `price`) VALUES
(1, 1, 'Cursus d’initiation à la guitare', 50),
(2, 1, 'Cursus d’initiation au piano', 50),
(3, 2, 'Cursus d’initiation au développement web', 60),
(4, 3, 'Cursus d’initiation au jardinage', 30),
(5, 4, 'Cursus d’initiation à la cuisine', 44),
(6, 4, 'Cursus d’initiation à l’art du dressage culinaire', 48);

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
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lesson`
--

INSERT INTO `lesson` (`id`, `course_id`, `title`, `price`, `content`, `video_url`) VALUES
(1, 1, 'Découverte de l’instrument', 26, NULL, NULL),
(2, 1, 'Les accords et les gammes', 26, NULL, NULL),
(3, 2, 'Découverte de l’instrument', 26, NULL, NULL),
(4, 2, 'Les accords et les gammes', 26, NULL, NULL),
(5, 3, 'Les langages Html et CSS', 32, NULL, NULL),
(6, 3, 'Dynamiser votre site avec Javascript', 32, NULL, NULL),
(7, 4, 'Les outils du jardinier', 16, NULL, NULL),
(8, 4, 'Jardiner avec la lune', 16, NULL, NULL),
(9, 5, 'Les modes de cuisson', 23, NULL, NULL),
(10, 5, 'Les saveurs', 23, NULL, NULL),
(11, 6, 'Mettre en œuvre le style dans l’assiette', 26, NULL, NULL),
(12, 6, 'Harmoniser un repas à quatre plats', 26, NULL, NULL);

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
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`id`, `name`) VALUES
(1, 'Musique'),
(2, 'Informatique'),
(3, 'Jardinage'),
(4, 'Cuisine');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
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

INSERT INTO `user` (`id`, `created_by_id`, `updated_by_id`, `email`, `roles`, `password`, `is_verified`, `created_at`, `updated_at`) VALUES
(143, NULL, NULL, 'user_68c16f13979334.71826821@example.com', '[\"ROLE_USER\"]', '$2y$12$E7S5I30de3aLmQVhVQTie.wTi6Idz/VLH9lEdtAOTjZ4lMdJLvcQy', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_lesson`
--

CREATE TABLE `user_lesson` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `validate_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_lesson`
--

INSERT INTO `user_lesson` (`id`, `user_id`, `lesson_id`, `validated`, `validate_at`) VALUES
(22, 143, 1, 1, '2025-09-10 12:29:07');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6C3C6D75A76ED395` (`user_id`),
  ADD KEY `IDX_6C3C6D75591CC992` (`course_id`);

--
-- Index pour la table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_169E6FB959027487` (`theme_id`);

--
-- Index pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F87474F3591CC992` (`course_id`);

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
  ADD KEY `IDX_6117D13BCDF80196` (`lesson_id`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  ADD KEY `IDX_8D93D649B03A8386` (`created_by_id`),
  ADD KEY `IDX_8D93D649896DBBDE` (`updated_by_id`);

--
-- Index pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9D266FCEA76ED395` (`user_id`),
  ADD KEY `IDX_9D266FCECDF80196` (`lesson_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `FK_6C3C6D75591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_6C3C6D75A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `FK_169E6FB959027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`);

--
-- Contraintes pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `FK_F87474F3591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Contraintes pour la table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `FK_6117D13B591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_6117D13BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6117D13BCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8D93D649B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_lesson`
--
ALTER TABLE `user_lesson`
  ADD CONSTRAINT `FK_9D266FCEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9D266FCECDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
