-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 16 nov. 2024 à 18:27
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cjmbdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_administrateur` int(11) NOT NULL,
  `nom_admin` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `id_classe` int(11) NOT NULL,
  `nom_classe` varchar(250) NOT NULL,
  `niveau` varchar(250) NOT NULL,
  `id_niveau` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`id_classe`, `nom_classe`, `niveau`, `id_niveau`) VALUES
(32, '6 EME', '', NULL),
(33, '5 EME', '', NULL),
(34, '4 EME', '', NULL),
(35, '3 EME', '', NULL),
(36, '2 NDE C', '', NULL),
(37, '2 NDE A', '', NULL),
(38, '1 ERE C', '', NULL),
(40, '1 ERE A', '', NULL),
(41, '1 ERE D', '', NULL),
(42, 'TLE A', '', NULL),
(43, 'TLE D', '', NULL),
(44, 'TLE C', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `id_eleve` int(11) NOT NULL,
  `nom_eleve` varchar(250) NOT NULL,
  `prenoms_eleve` varchar(250) NOT NULL,
  `matricule_eleve` varchar(15) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_classe` int(11) NOT NULL,
  `classe_eleve` varchar(250) NOT NULL,
  `moyenne` decimal(5,2) DEFAULT NULL,
  `rang` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `eleve`
--

INSERT INTO `eleve` (`id_eleve`, `nom_eleve`, `prenoms_eleve`, `matricule_eleve`, `id_utilisateur`, `id_classe`, `classe_eleve`, `moyenne`, `rang`) VALUES
(26, 'KOFFI', 'TCHIAMBLAH EDITH FABIENNE', '14255489L', NULL, 36, '', 7.00, '1'),
(27, 'DIE ZON', 'PRINCE ARCHIMEL', '14100210L', NULL, 36, '', 6.00, '3'),
(28, 'KOFFI', 'KOUMAN EMMANUEL TIMOTHEE', '11111111F', NULL, 40, '', NULL, ''),
(29, 'KOUAME', 'KOFFI JEAN STEPHANE', '26110486K', NULL, 40, '', NULL, ''),
(30, 'KPAN', 'ROMARIC', '14102611F', NULL, 40, '', NULL, '');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id_matiere` int(11) NOT NULL,
  `nom_matiere` varchar(250) NOT NULL,
  `id_classe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id_matiere`, `nom_matiere`, `id_classe`) VALUES
(15, 'FRANCAIS', NULL),
(17, 'MATHEMATIQUE', NULL),
(18, 'HISTOIRE GEOGRAPHIE', NULL),
(19, 'ESPÄGNOL', NULL),
(20, 'SCIENCES DE LA VIE ET DE LA TERRE', NULL),
(21, 'SCIENCES PHYSIQUE', NULL),
(22, 'ANGLAIS', NULL),
(23, 'ALLEMAND', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id_note` int(11) NOT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `trimestre` varchar(250) NOT NULL,
  `id_eleve` int(11) DEFAULT NULL,
  `id_matiere` int(11) DEFAULT NULL,
  `id_professeur` int(11) DEFAULT NULL,
  `coefficient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id_note`, `note`, `trimestre`, `id_eleve`, `id_matiere`, `id_professeur`, `coefficient`) VALUES
(214, 13.00, '1', 28, NULL, NULL, 0),
(215, 10.00, '1', 29, NULL, NULL, 0),
(216, 13.50, '1', 28, NULL, NULL, 0),
(217, 10.50, '1', 29, NULL, NULL, 0),
(218, 13.50, '1', 28, NULL, NULL, 0),
(219, 10.50, '1', 29, NULL, NULL, 0),
(220, 13.50, '1', 28, NULL, NULL, 0),
(221, 10.50, '1', 29, NULL, NULL, 0),
(222, 12.00, '1', 28, NULL, NULL, 0),
(223, 12.50, '1', 29, NULL, NULL, 0),
(224, 14.00, '1', 28, NULL, NULL, 0),
(225, 12.00, '1', 29, NULL, NULL, 0),
(226, 13.00, '1', 28, NULL, NULL, 0),
(227, 12.00, '1', 29, NULL, NULL, 0),
(228, 16.00, '1', 28, NULL, NULL, 0),
(229, 10.45, '1', 29, NULL, NULL, 0),
(230, 20.00, '1', 28, NULL, NULL, 0),
(231, 15.00, '1', 29, NULL, NULL, 0),
(232, 15.50, '1', 28, NULL, NULL, 0),
(233, 12.00, '1', 29, NULL, NULL, 0),
(234, 15.50, '1', 28, NULL, NULL, 0),
(235, 12.00, '1', 29, NULL, NULL, 0),
(327, 12.00, 'Deuxième Trimestre', 28, NULL, NULL, 0),
(328, 12.00, 'Deuxième Trimestre', 29, NULL, NULL, 0),
(329, 15.00, 'Deuxième Trimestre', 30, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `id_professeur` int(11) NOT NULL,
  `nom_professeur` varchar(250) NOT NULL,
  `prenoms_professeur` varchar(250) NOT NULL,
  `matricule_professeur` varchar(15) NOT NULL,
  `date_de_naissance_prof` date NOT NULL,
  `sexe_prof` varchar(10) NOT NULL,
  `contact_prof` varchar(15) NOT NULL,
  `matiere_enseignee` varchar(250) NOT NULL,
  `photo_prof` varchar(250) NOT NULL,
  `classe_attribuee` varchar(250) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`id_professeur`, `nom_professeur`, `prenoms_professeur`, `matricule_professeur`, `date_de_naissance_prof`, `sexe_prof`, `contact_prof`, `matiere_enseignee`, `photo_prof`, `classe_attribuee`, `id_utilisateur`) VALUES
(22, 'zz', 'zz', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(23, 'zz', 'zz', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(25, 'ee', 'ee', 'ee', '0000-00-00', 'F', '', '', '', '', NULL),
(27, 'zz', 'zz', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(29, 'zz', 'zz', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(31, 'zz', 'zz', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(33, 'ee', 'ee', 'ee', '0000-00-00', 'F', '', '', '', '', NULL),
(34, 'ee', 'ee', 'ee', '0000-00-00', 'F', '', '', '', '', NULL),
(35, 'Koff', 'rt', 'zz', '0000-00-00', 'M', '', '', '', '', NULL),
(41, 'KOFFI', 'TCHIAMBLAH EDITH FABIENNE', '1345678K', '0000-00-00', 'Femme', '', '14', '', '33', NULL),
(42, 'KOFFI', 'TCHIAMBLAH EDITH FABIENNE', '1345678K', '0000-00-00', 'Femme', '', '13', '', '44', NULL),
(44, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '32', NULL),
(51, 'KOUAME', 'JOSE', '12231144C', '0000-00-00', 'Femme', '', '20', '', '40', NULL),
(52, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '33', NULL),
(53, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '43', NULL),
(54, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '35', NULL),
(55, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '34', NULL),
(56, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '37', NULL),
(57, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '36', NULL),
(58, 'KOUASSI', 'ROMEO JULES', '14255496B', '0000-00-00', 'Homme', '', '15', '', '38', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `matricule` varchar(15) NOT NULL,
  `mot_de_passe` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(250) NOT NULL,
  `id_administrateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `matricule`, `mot_de_passe`, `email`, `role`, `id_administrateur`) VALUES
(73, '14100210L', '14100210Larchimel', 'archimel@gmail.com', 'Élève', NULL),
(74, '14255489L', '14255489Ledith', 'edith@gmail.com', 'Élève', NULL),
(75, '14102611F', '14102611Fdita', 'dita@gmail.com', 'Élève', NULL),
(76, '26110486K', '26110486Kbene', 'bene11@gmail.com', 'Élève', NULL),
(77, '11111111F', '11111111timo', 'timo@gmail.com', 'Élève', NULL),
(78, '14255496B', '14255496Bromeo', 'kouess@gmail.com', 'Professeur', NULL),
(79, '12265199C', '12265199Cjose', 'irie12@gmail.com', 'Professeur', NULL),
(80, '12231122F', '12231122Flou', 'loubi08@gmail.com', 'Professeur', NULL),
(81, '15555244B', '15555244Bgregoire', 'gregoire1@gmail.com', 'Professeur', NULL),
(82, '12231144C', '12231144Cjose', 'jacob13@gmail.com', 'Professeur', NULL),
(83, '12121212J', 'dagnitchie', 'admin@gmail.com', 'Administrateur', NULL),
(87, '26112004E', 'sari', 'sari@gmail.com', 'Élève', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_administrateur`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`id_classe`),
  ADD KEY `classe_ibfk_1` (`id_niveau`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`id_eleve`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`id_matiere`),
  ADD KEY `matiere_ibfk_1` (`id_classe`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `id_eleve` (`id_eleve`),
  ADD KEY `id_matiere` (`id_matiere`),
  ADD KEY `id_professeur` (`id_professeur`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`id_professeur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mot_de_passe` (`mot_de_passe`),
  ADD KEY `id_administrateur` (`id_administrateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_administrateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `id_classe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `id_eleve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id_note` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT pour la table `professeur`
--
ALTER TABLE `professeur`
  MODIFY `id_professeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD CONSTRAINT `eleve_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `eleve_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`);

--
-- Contraintes pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD CONSTRAINT `matiere_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`) ON DELETE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`),
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`id_professeur`) REFERENCES `professeur` (`id_professeur`);

--
-- Contraintes pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD CONSTRAINT `professeur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_administrateur`) REFERENCES `administrateur` (`id_administrateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
