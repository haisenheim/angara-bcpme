-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 06 mai 2026 à 14:02
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `angara_bcpme`
--

-- --------------------------------------------------------

--
-- Structure de la table `agences`
--

CREATE TABLE `agences` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `representation_id` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `agences`
--

INSERT INTO `agences` (`id`, `name`, `representation_id`, `active`) VALUES
(1, 'AGENCE NGAOUNDERE', 1, 1),
(2, 'AGENCE DE GAROUA', 1, 1),
(3, 'AGENCE DE MAROUA', 1, 1),
(4, 'AGENCE YAOUNDE', 2, 1),
(5, 'AGENCE BERTOUA', 2, 1),
(6, 'AGENCE EBOLOWA', 2, 1),
(7, 'AGENCE DOUALA', 3, 1),
(8, 'AGENCE BUEA', 3, 1),
(9, 'AGENCE BAMENDA', 3, 1),
(10, 'AGENCE BAFOUSSAM', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `analyse_critique_avis`
--

CREATE TABLE `analyse_critique_avis` (
  `id` bigint UNSIGNED NOT NULL,
  `dossier_analyse_critique_id` bigint UNSIGNED NOT NULL,
  `source_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instruction_dossier_id` int UNSIGNED DEFAULT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci,
  `etat` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'emis',
  `emis_at` timestamp NULL DEFAULT NULL,
  `emis_par_user_id` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `analyse_critique_avis`
--

INSERT INTO `analyse_critique_avis` (`id`, `dossier_analyse_critique_id`, `source_type`, `source_label`, `instruction_dossier_id`, `contenu`, `etat`, `emis_at`, `emis_par_user_id`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'gestionnaire', 'Soumission initiale du prospect', NULL, 'Prospect soumis par le gestionnaire pour avis juridique et conformite.', 'integre', '2026-04-18 22:05:57', 2, 10, '2026-04-19 13:41:08', '2026-04-19 13:41:08'),
(2, 1, 'juridique', 'Avis juridique', NULL, '<p>Ceci est mo<i>n avis sur </i>ce dossier</p><p>C<u>ette </u><u>partie<b> est</b></u><b><u> p</u>artiellement en gras</b></p><p>Ceci est une liste:</p><ul><li>une</li><li>deux</li></ul><p><b><br></b></p>', 'integre', '2026-04-19 13:41:08', 4, 20, '2026-04-19 13:41:08', '2026-04-19 13:41:08'),
(3, 1, 'conformite', 'Avis conformite', NULL, '<p>Ceci est l\'avis du responsable conformite.</p><p>ci-dessous quelques points d\'attention:</p><ul><li>point 1</li><li>point 2</li><li>point3</li><li>point 4</li></ul>', 'integre', '2026-04-19 13:59:56', 5, 30, '2026-04-19 13:59:56', '2026-04-19 13:59:56'),
(4, 1, 'chef_agence', 'Validation chef d\'agence', NULL, 'Dossier d’instruction multi-programmes validé par le chef d’agence. Programmes : PURATOS.', 'integre', '2026-04-19 15:07:33', 7, 50, '2026-04-19 15:07:33', '2026-04-23 19:31:59'),
(5, 1, 'chef_filiere', 'Qualification chef de filiere', NULL, 'Analyse strategique:\n<p>Ceci est une petite an<b>alyse stratégiqu</b>e pour le dossier de ce client</p>\n\nAnalyse operationnelle:\n<p>Ceci est une petite analyse opérationnelle pour le dossier du client alliages technologies&nbsp;</p>\n\nEligibilite:\nIci se trouve la section liée aux critères d\'eligibilite&nbsp;\n\nIdentification des besoins:\n<p>Nous voici dans la section identification et notes pour compléter notre qualification</p>\n\nNotes de qualification:\n<p>Ici sont lister les notes de fin</p><ol><li>note a</li><li>note b</li><li>note c</li><li>etc ...</li></ol><p><b>C\'est la fin.</b></p>', 'integre', '2026-04-19 19:01:18', 6, 40, '2026-04-19 19:01:18', '2026-04-19 19:01:18'),
(6, 1, 'instruction', 'Dossier instruction - PIISAH', 31, 'Dossier d\'instruction rattache au client.', 'integre', '2026-04-21 06:56:32', 2, 131, '2026-04-19 19:44:34', '2026-04-23 16:53:50'),
(7, 2, 'gestionnaire', 'Soumission initiale du prospect', NULL, 'Prospect soumis par le gestionnaire pour avis juridique et conformite.', 'integre', '2026-04-21 12:35:18', 2, 10, '2026-04-21 12:36:24', '2026-04-21 12:36:24'),
(8, 2, 'juridique', 'Avis juridique', NULL, '<p>Ceci<b> est mo</b>n avis</p><ul><li>Un&nbsp;</li><li>deux</li><li>trois</li></ul>', 'integre', '2026-04-21 12:36:24', 4, 20, '2026-04-21 12:36:24', '2026-04-21 12:36:24'),
(9, 2, 'conformite', 'Avis conformite', NULL, '<p><b><u>Avis de le conformité.</u></b></p><ol><li>un</li><li>deux</li><li>trois</li><li>quatre&nbsp;</li></ol>', 'integre', '2026-04-21 12:38:14', 5, 30, '2026-04-21 12:38:14', '2026-04-21 12:38:14'),
(10, 2, 'chef_agence', 'Validation chef d\'agence', NULL, 'Dossier d’instruction multi-programmes validé par le chef d’agence. Programmes : TRANSFAGRI, PURATOS.', 'integre', '2026-04-21 12:51:23', 7, 50, '2026-04-21 12:51:23', '2026-04-21 21:25:47'),
(11, 2, 'chef_filiere', 'Qualification chef de filiere', NULL, 'Analyse strategique:\n<p>analyse strategique</p>\n\nAnalyse operationnelle:\n<p>Petite analyse operationnelle</p>\n\nEligibilite:\n<p>Petite saisie sur l\'eligibilite</p>\n\nIdentification des besoins:\n<p>Quelques besoins:</p><ul><li>premier besoin</li><li>deuxieme</li><li>troisieme</li></ul>\n\nNotes de qualification:\n<p>Quelques notes de qualification</p>', 'integre', '2026-04-21 12:59:14', 6, 40, '2026-04-21 12:59:14', '2026-04-21 12:59:14'),
(12, 2, 'instruction', 'Dossier instruction - PIISAH', 32, 'Dossier d\'instruction créé suite à l\'inscription du client au programme (chef de filière).', 'integre', '2026-04-21 13:02:55', 2, 132, '2026-04-21 13:02:55', '2026-04-21 13:02:55'),
(13, 2, 'instruction', 'Dossier instruction - TRANSFAGRI', NULL, 'Dossier d\'instruction multi-programmes créé et soumis au chef d\'agence (chef de filière). Programmes : TRANSFAGRI, PURATOS.', 'integre', '2026-04-21 20:08:13', 2, 133, '2026-04-21 20:08:13', '2026-04-21 20:08:13'),
(14, 2, 'instruction', 'Dossier instruction - Programme', 35, 'Dossier d’instruction validé par le chef d’agence (programmes : TRANSFAGRI, PURATOS).', 'integre', '2026-04-21 21:25:47', 2, 135, '2026-04-21 21:23:49', '2026-04-21 21:25:47'),
(15, 1, 'chef_filiere', 'Structuration chef de filière', NULL, 'Analyse strategique:\n<p>Ceci est une petite an<b>alyse stratégiqu</b>e pour le dossier de ce client</p>\n\nAnalyse operationnelle:\n<p>Ceci est une petite analyse opérationnelle pour le dossier du client alliages technologies&nbsp;</p>\n\nEligibilite:\nIci se trouve la section liée aux critères d\'eligibilite&nbsp;\n\nIdentification des besoins:\n<p>Nous voici dans la section identification et notes pour compléter notre qualification</p>\n\nNotes de structuration:\n<p>Ici sont lister les notes de fin</p><ol><li>note a</li><li>note b</li><li>note c</li><li>etc ...</li></ol><p><b>C\'est la fin.</b></p>', 'integre', '2026-04-19 19:01:18', 6, 40, '2026-04-23 16:53:50', '2026-04-23 16:53:50'),
(16, 1, 'instruction', 'Dossier instruction - Programme', 36, 'Dossier d’instruction validé par le chef d’agence (programmes : PURATOS).', 'integre', '2026-04-23 19:31:59', 2, 136, '2026-04-23 18:26:18', '2026-04-23 19:31:59'),
(17, 3, 'gestionnaire', 'Soumission initiale du prospect', NULL, 'Prospect soumis par le gestionnaire pour avis juridique et conformite.', 'integre', '2026-04-24 13:56:18', 2, 10, '2026-04-24 13:59:24', '2026-04-24 13:59:24'),
(18, 3, 'juridique', 'Avis juridique', NULL, '<p><b>un petit texte en gras</b></p><p><b>une liste:</b></p><ul><li><b>un&nbsp;</b></li><li>deux</li><li>trois</li></ul><p><br></p>', 'integre', '2026-04-24 13:59:24', 4, 20, '2026-04-24 13:59:24', '2026-04-24 13:59:24'),
(19, 3, 'conformite', 'Avis conformite', NULL, '<p>Mon avis en tant q<b>ue respons</b>able conformité&nbsp;</p>', 'integre', '2026-04-24 14:35:13', 5, 30, '2026-04-24 14:35:13', '2026-04-24 14:35:13'),
(20, 3, 'chef_agence', 'Validation chef d\'agence', NULL, 'Dossier d’instruction multi-programmes validé par le chef d’agence. Programmes : PURATOS.', 'integre', '2026-04-24 14:41:18', 7, 50, '2026-04-24 14:41:18', '2026-04-30 12:51:07'),
(21, 3, 'chef_filiere', 'Structuration chef de filière', NULL, 'Analyse strategique:\n<p>gjfdhjfd hfdjhfd nfdknk</p>\n\nAnalyse operationnelle:\n<p>fdjfd fdnnfdknfd</p>\n\nEligibilite:\n<p>fdkjdfk fdknkndf nfkdnkfd nfkndf</p>\n\nIdentification des besoins:\n<p>fdjdfjkjfd kfndnfd nfdnkdf ndfkndkf nkdfnknfdk ndfknkdfn</p>\n\nNotes de structuration:\n<p>hrjrh brknkrnrk nreknkre</p>', 'integre', '2026-04-24 14:49:41', 6, 40, '2026-04-24 14:49:41', '2026-04-24 14:49:41'),
(22, 3, 'instruction', 'Dossier instruction - Programme', 37, 'Dossier d\'instruction rattache au client.', 'integre', '2026-04-25 08:33:05', 2, 137, '2026-04-24 15:01:45', '2026-04-29 12:38:18'),
(23, 4, 'gestionnaire', 'Soumission initiale du prospect', NULL, 'Prospect soumis par le gestionnaire pour avis juridique et conformite.', 'integre', '2026-05-06 10:28:53', 2, 10, '2026-04-29 15:31:50', '2026-05-06 10:31:18'),
(24, 4, 'juridique', 'Avis juridique', NULL, '<p>mon avis en tant resp. jur.</p>', 'integre', '2026-05-06 10:32:26', 4, 20, '2026-04-29 15:31:50', '2026-05-06 10:32:26'),
(25, 4, 'conformite', 'Avis conformite', NULL, '<p>mon avis en tant que resp. conf.</p>', 'integre', '2026-05-06 10:31:18', 5, 30, '2026-04-29 15:33:40', '2026-05-06 10:31:18'),
(26, 3, 'instruction', 'Dossier instruction - Programme', 38, 'Clôture du dossier d’instruction (délégation de pouvoir).', 'integre', '2026-04-30 14:00:30', 2, 138, '2026-04-30 12:46:18', '2026-04-30 14:00:30'),
(27, 5, 'gestionnaire', 'Soumission initiale du prospect', NULL, 'Prospect soumis par le gestionnaire pour avis juridique et conformite.', 'integre', '2026-05-06 10:14:05', 2, 10, '2026-05-06 10:15:24', '2026-05-06 10:15:24'),
(28, 5, 'juridique', 'Avis juridique', NULL, '<p>Mon avis en tant responsable juridique</p>', 'integre', '2026-05-06 10:15:24', 4, 20, '2026-05-06 10:15:24', '2026-05-06 10:15:24'),
(29, 5, 'conformite', 'Avis conformite', NULL, '<p>Mon avis en tant que responsable conformite</p>', 'integre', '2026-05-06 10:16:09', 5, 30, '2026-05-06 10:16:09', '2026-05-06 10:16:09'),
(30, 5, 'chef_agence', 'Refus chef d\'agence', NULL, 'Prospect refuse par le chef d\'agence (non promu client).', 'integre', '2026-05-06 10:17:28', 7, 45, '2026-05-06 10:17:28', '2026-05-06 10:17:28'),
(31, 4, 'chef_agence', 'Refus chef d\'agence', NULL, 'Prospect refuse par le chef d\'agence (non promu client).\n\nMotif : pas bon, bien vouloir reprendre', 'integre', '2026-05-06 10:27:06', 7, 45, '2026-05-06 10:27:06', '2026-05-06 10:27:06'),
(32, 4, 'chef_agence', 'Validation chef d\'agence', NULL, 'Dossier d’instruction multi-programmes validé par le chef d’agence. Programmes : PIISAH.', 'integre', '2026-05-06 10:33:32', 7, 50, '2026-05-06 10:33:32', '2026-05-06 12:53:37'),
(33, 4, 'chef_filiere', 'Structuration chef de filière', NULL, 'Analyse strategique:\n<p>analyse strategique ici</p>\n\nAnalyse operationnelle:\n<p>Analyse op ici apres refus</p>\n\nEligibilite:\n<p>Elig ici apres refus</p>\n\nIdentification des besoins:\n<p>Identif.&nbsp; apres refus</p>\n\nNotes de structuration:\n<p>Notes de struct. apres refus</p>', 'integre', '2026-05-06 12:34:43', 6, 40, '2026-05-06 12:31:00', '2026-05-06 12:34:43'),
(34, 4, 'chef_agence', 'Refus structuration (chef d\'agence)', NULL, 'Structuration refusée par le chef d\'agence. Le chef de filière peut la corriger et la resoumettre.\n\nMotif : Je refuse tout', 'integre', '2026-05-06 12:33:21', 7, 46, '2026-05-06 12:33:21', '2026-05-06 12:33:21'),
(35, 4, 'instruction', 'Dossier instruction - Programme', 39, 'Dossier d’instruction validé par le chef d’agence (programmes : PIISAH).', 'integre', '2026-05-06 12:53:37', 2, 139, '2026-05-06 12:37:25', '2026-05-06 12:53:37');

-- --------------------------------------------------------

--
-- Structure de la table `approches`
--

CREATE TABLE `approches` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `approches`
--

INSERT INTO `approches` (`id`, `name`) VALUES
(1, 'Appui groupé ou personnalisé selon le cas'),
(2, 'Appui groupé'),
(3, 'Appui personnalisé'),
(4, 'Appui groupé ou personnalisé');

-- --------------------------------------------------------

--
-- Structure de la table `arrondissements`
--

CREATE TABLE `arrondissements` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departement_id` int NOT NULL DEFAULT '0',
  `region_id` int NOT NULL DEFAULT '0',
  `departement_` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `arrondissements`
--

INSERT INTO `arrondissements` (`id`, `name`, `departement_id`, `region_id`, `departement_`, `region`) VALUES
(1, 'Bankim', 1, 1, 'Mayo-Banyo', 'Adamaoua'),
(2, 'Banyo', 1, 1, 'Mayo-Banyo', 'Adamaoua'),
(3, 'Belel', 2, 1, 'Vina', 'Adamaoua'),
(4, 'Dir', 3, 1, 'Mbéré', 'Adamaoua'),
(5, 'Djohong', 3, 1, 'Mbéré', 'Adamaoua'),
(6, 'Galim-Tignère', 4, 1, 'Faro-et-Déo', 'Adamaoua'),
(7, 'Kontcha', 4, 1, 'Faro-et-Déo', 'Adamaoua'),
(8, 'Martap', 2, 1, 'Vina', 'Adamaoua'),
(9, 'Mayo-Baléo', 4, 1, 'Faro-et-Déo', 'Adamaoua'),
(10, 'Mayo-Darlé', 1, 1, 'Mayo-Banyo', 'Adamaoua'),
(11, 'Mbe', 2, 1, 'Vina', 'Adamaoua'),
(12, 'Meiganga', 3, 1, 'Mbéré', 'Adamaoua'),
(13, 'Nganha', 2, 1, 'Vina', 'Adamaoua'),
(14, 'Ngaoui', 3, 1, 'Mbéré', 'Adamaoua'),
(15, 'Ngaoundal', 5, 1, 'Djerem', 'Adamaoua'),
(16, 'Ngaoundéré Ier', 2, 1, 'Vina', 'Adamaoua'),
(17, 'Ngaoundéré IIe', 2, 1, 'Vina', 'Adamaoua'),
(18, 'Ngaoundéré IIIe', 2, 1, 'Vina', 'Adamaoua'),
(19, 'Nyambaka', 2, 1, 'Vina', 'Adamaoua'),
(20, 'Tibati', 5, 1, 'Djerem', 'Adamaoua'),
(21, 'Tignère', 4, 1, 'Faro-et-Déo', 'Adamaoua'),
(22, 'Afanloum', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(23, 'Akoeman', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(24, 'Akono', 8, 2, 'Méfou-et-Akono', 'Centre'),
(25, 'Akonolinga', 9, 2, 'Nyong-et-Mfoumou', 'Centre'),
(26, 'Awaé', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(27, 'Ayos', 9, 2, 'Nyong-et-Mfoumou', 'Centre'),
(28, 'Bafia', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(29, 'Batchenga', 11, 2, 'Lekié', 'Centre'),
(30, 'Bibey', 12, 2, 'Haute-Sanaga', 'Centre'),
(31, 'Bikok', 8, 2, 'Méfou-et-Akono', 'Centre'),
(32, 'Biyouha', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(33, 'Bokito', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(34, 'Bondjock', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(35, 'Bot-Makak', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(36, 'Deuk', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(37, 'Dibang', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(38, 'Dzeng', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(39, 'Ebebda', 11, 2, 'Lekié', 'Centre'),
(40, 'Edzendouan', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(41, 'Elig-Mfomo', 11, 2, 'Lekié', 'Centre'),
(42, 'Endom', 9, 2, 'Nyong-et-Mfoumou', 'Centre'),
(43, 'Éséka', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(44, 'Esse', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(45, 'Evodoula', 11, 2, 'Lekié', 'Centre'),
(46, 'Kiiki', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(47, 'Kobdombo', 9, 2, 'Nyong-et-Mfoumou', 'Centre'),
(48, 'Kon-Yambetta', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(49, 'Lembe-Yezoum', 12, 2, 'Haute-Sanaga', 'Centre'),
(50, 'Lobo', 11, 2, 'Lekié', 'Centre'),
(51, 'Makak', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(52, 'Makénéné', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(53, 'Matomb', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(54, 'Mbalmayo', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(55, 'Mbandjock', 12, 2, 'Haute-Sanaga', 'Centre'),
(56, 'Mbangassina', 14, 2, 'Mbam-et-Kim', 'Centre'),
(57, 'Mbankomo', 8, 2, 'Méfou-et-Akono', 'Centre'),
(58, 'Mengang', 9, 2, 'Nyong-et-Mfoumou', 'Centre'),
(59, 'Mengueme', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(60, 'Messondo', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(61, 'Mfou', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(62, 'Minta', 12, 2, 'Haute-Sanaga', 'Centre'),
(63, 'Monatélé', 11, 2, 'Lekié', 'Centre'),
(64, 'Nanga-Eboko', 12, 2, 'Haute-Sanaga', 'Centre'),
(65, 'Ndikiniméki', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(66, 'Ngambè-Tikar', 14, 2, 'Mbam-et-Kim', 'Centre'),
(67, 'Ngog-Mapubi', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(68, 'Ngomedzap', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(69, 'Ngoro', 14, 2, 'Mbam-et-Kim', 'Centre'),
(70, 'Ngoumou', 8, 2, 'Méfou-et-Akono', 'Centre'),
(71, 'Ngui-Bassal', 13, 2, 'Nyong-et-Kéllé', 'Centre'),
(72, 'Nitoukou', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(73, 'Nkolafamba', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(74, 'Nkolmetet', 7, 2, 'Nyong-et-So\'o', 'Centre'),
(75, 'Nkoteng', 12, 2, 'Haute-Sanaga', 'Centre'),
(76, 'Nsem', 12, 2, 'Haute-Sanaga', 'Centre'),
(77, 'Ntui', 14, 2, 'Mbam-et-Kim', 'Centre'),
(78, 'Obala', 11, 2, 'Lekié', 'Centre'),
(79, 'Okola', 11, 2, 'Lekié', 'Centre'),
(80, 'Olanguina', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(81, 'Ombessa', 10, 2, 'Mbam-et-Inoubou', 'Centre'),
(82, 'Sa\'a', 11, 2, 'Lekié', 'Centre'),
(83, 'Soa', 6, 2, 'Méfou-et-Afamba', 'Centre'),
(84, 'Yaoundé Ier', 15, 2, 'Mfoundi', 'Centre'),
(85, 'Yaoundé IIe', 15, 2, 'Mfoundi', 'Centre'),
(86, 'Yaoundé IIIe', 15, 2, 'Mfoundi', 'Centre'),
(87, 'Yaoundé IVe', 15, 2, 'Mfoundi', 'Centre'),
(88, 'Yaoundé Ve', 15, 2, 'Mfoundi', 'Centre'),
(89, 'Yaoundé VIe', 15, 2, 'Mfoundi', 'Centre'),
(90, 'Yaoundé VIIe', 15, 2, 'Mfoundi', 'Centre'),
(91, 'Yoko', 14, 2, 'Mbam-et-Kim', 'Centre'),
(92, 'Abong-Mbang', 16, 3, 'Haut-Nyong', 'Est'),
(93, 'Angossas', 16, 3, 'Haut-Nyong', 'Est'),
(94, 'Atok', 16, 3, 'Haut-Nyong', 'Est'),
(95, 'Batouri', 17, 3, 'Kadey', 'Est'),
(96, 'Bélabo', 18, 3, 'Lom-et-Djérem', 'Est'),
(97, 'Bertoua Ier', 18, 3, 'Lom-et-Djérem', 'Est'),
(98, 'Bertoua IIe', 18, 3, 'Lom-et-Djérem', 'Est'),
(99, 'Bétaré-Oya', 18, 3, 'Lom-et-Djérem', 'Est'),
(100, 'Diang', 18, 3, 'Lom-et-Djérem', 'Est'),
(101, 'Dimako', 16, 3, 'Haut-Nyong', 'Est'),
(102, 'Doumaintang', 16, 3, 'Haut-Nyong', 'Est'),
(103, 'Doumé', 16, 3, 'Haut-Nyong', 'Est'),
(104, 'Gari-Gombo', 19, 3, 'Boumba-et-Ngoko', 'Est'),
(105, 'Garoua-Boulaï', 18, 3, 'Lom-et-Djérem', 'Est'),
(106, 'Kentzou', 17, 3, 'Kadey', 'Est'),
(107, 'Kette', 17, 3, 'Kadey', 'Est'),
(108, 'Lomié', 16, 3, 'Haut-Nyong', 'Est'),
(109, 'Mandjou', 18, 3, 'Lom-et-Djérem', 'Est'),
(110, 'Mbang', 17, 3, 'Kadey', 'Est'),
(111, 'Mboma', 16, 3, 'Haut-Nyong', 'Est'),
(112, 'Messamena', 16, 3, 'Haut-Nyong', 'Est'),
(113, 'Messok', 16, 3, 'Haut-Nyong', 'Est'),
(114, 'Mindourou', 16, 3, 'Haut-Nyong', 'Est'),
(115, 'Moloundou', 19, 3, 'Boumba-et-Ngoko', 'Est'),
(116, 'Ndelele', 17, 3, 'Kadey', 'Est'),
(117, 'Ngoura', 18, 3, 'Lom-et-Djérem', 'Est'),
(118, 'Ngoyla', 16, 3, 'Haut-Nyong', 'Est'),
(119, 'Nguelebok', 17, 3, 'Kadey', 'Est'),
(120, 'Nguelemendouka', 16, 3, 'Haut-Nyong', 'Est'),
(121, 'Ouli', 17, 3, 'Kadey', 'Est'),
(122, 'Salapoumbé', 19, 3, 'Boumba-et-Ngoko', 'Est'),
(123, 'Somalomo', 16, 3, 'Haut-Nyong', 'Est'),
(124, 'Yokadouma', 19, 3, 'Boumba-et-Ngoko', 'Est'),
(125, 'Blangoua', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(126, 'Bogo', 21, 4, 'Diamaré', 'Extrême-Nord'),
(127, 'Bourrha', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(128, 'Dargala', 21, 4, 'Diamaré', 'Extrême-Nord'),
(129, 'Darak', 21, 4, 'Diamaré', 'Extrême-Nord'),
(130, 'Datcheka', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(131, 'Dziguilao', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(132, 'Fotokol', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(133, 'Gazawa', 21, 4, 'Diamaré', 'Extrême-Nord'),
(134, 'Gobo', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(135, 'Goulfey', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(136, 'Guémé', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(137, 'Guéré', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(138, 'Guidiguis', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(139, 'Hile-Alifa', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(140, 'Hina', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(141, 'Kaélé', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(142, 'Kai-Kai', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(143, 'Kalfou', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(144, 'Kar-Hay', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(145, 'Kolofata', 25, 4, 'Mayo-Sava', 'Extrême-Nord'),
(146, 'Kousséri', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(147, 'Koza', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(148, 'Logone-Birni', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(149, 'Maga', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(150, 'Makary', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(151, 'Maroua Ier', 21, 4, 'Diamaré', 'Extrême-Nord'),
(152, 'Maroua IIe', 21, 4, 'Diamaré', 'Extrême-Nord'),
(153, 'Maroua IIIe', 21, 4, 'Diamaré', 'Extrême-Nord'),
(154, 'Meri', 21, 4, 'Diamaré', 'Extrême-Nord'),
(155, 'Mindif', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(156, 'Mogodé', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(157, 'Mokolo', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(158, 'Mora', 25, 4, 'Mayo-Sava', 'Extrême-Nord'),
(159, 'Moulvoudaye', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(160, 'Moutourwa', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(161, 'Mozogo', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(162, 'Ndoukoula', 21, 4, 'Diamaré', 'Extrême-Nord'),
(163, 'Petté', 21, 4, 'Diamaré', 'Extrême-Nord'),
(164, 'Soulédé-Roua', 22, 4, 'Mayo-Tsanaga', 'Extrême-Nord'),
(165, 'Tchati-Bali', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(166, 'Tokombéré', 25, 4, 'Mayo-Sava', 'Extrême-Nord'),
(167, 'Touloum', 24, 4, 'Mayo-Kani', 'Extrême-Nord'),
(168, 'Waza', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(169, 'Wina', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(170, 'Yagoua', 23, 4, 'Mayo-Danay', 'Extrême-Nord'),
(171, 'Zina', 20, 4, 'Logone-et-Chari', 'Extrême-Nord'),
(172, 'Baré', 26, 5, 'Moungo', 'Littoral'),
(173, 'Bonaléa', 26, 5, 'Moungo', 'Littoral'),
(174, 'Dibamba', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(175, 'Dibombari', 26, 5, 'Moungo', 'Littoral'),
(176, 'Dizangué', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(177, 'Douala Ier', 28, 5, 'Wouri', 'Littoral'),
(178, 'Douala IIe', 28, 5, 'Wouri', 'Littoral'),
(179, 'Douala IIIe', 28, 5, 'Wouri', 'Littoral'),
(180, 'Douala IVe', 28, 5, 'Wouri', 'Littoral'),
(181, 'Douala Ve', 28, 5, 'Wouri', 'Littoral'),
(182, 'Douala VIe', 28, 5, 'Wouri', 'Littoral'),
(183, 'Ebone', 26, 5, 'Moungo', 'Littoral'),
(184, 'Édéa Ier', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(185, 'Édéa IIe', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(186, 'Loum', 26, 5, 'Moungo', 'Littoral'),
(187, 'Manjo', 26, 5, 'Moungo', 'Littoral'),
(188, 'Massock-Songloulou', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(189, 'Mbanga', 26, 5, 'Moungo', 'Littoral'),
(190, 'Melong', 26, 5, 'Moungo', 'Littoral'),
(191, 'Mombo', 26, 5, 'Moungo', 'Littoral'),
(192, 'Mouanko', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(193, 'Ndobian', 29, 5, 'Nkam', 'Littoral'),
(194, 'Ndom', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(195, 'Ngambe', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(196, 'Ngwei', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(197, 'Nkondjock', 29, 5, 'Nkam', 'Littoral'),
(198, 'Nkongsamba Ier', 26, 5, 'Moungo', 'Littoral'),
(199, 'Nkongsamba IIe', 26, 5, 'Moungo', 'Littoral'),
(200, 'Nkongsamba IIIe', 26, 5, 'Moungo', 'Littoral'),
(201, 'Nyanon', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(202, 'Penja', 26, 5, 'Moungo', 'Littoral'),
(203, 'Pouma', 27, 5, 'Sanaga-Maritime', 'Littoral'),
(204, 'Yabassi', 29, 5, 'Nkam', 'Littoral'),
(205, 'Yingui', 29, 5, 'Nkam', 'Littoral'),
(206, 'Barndaké', 30, 6, 'Bénoué', 'Nord'),
(207, 'Bashéo', 30, 6, 'Bénoué', 'Nord'),
(208, 'Beka', 31, 6, 'Faro', 'Nord'),
(209, 'Bibemi', 30, 6, 'Bénoué', 'Nord'),
(210, 'Dembo', 30, 6, 'Bénoué', 'Nord'),
(211, 'Figuil', 32, 6, 'Mayo-Louti', 'Nord'),
(212, 'Garoua Ier', 30, 6, 'Bénoué', 'Nord'),
(213, 'Garoua IIe', 30, 6, 'Bénoué', 'Nord'),
(214, 'Garoua IIIe', 30, 6, 'Bénoué', 'Nord'),
(215, 'Gashiga', 30, 6, 'Bénoué', 'Nord'),
(216, 'Guider', 32, 6, 'Mayo-Louti', 'Nord'),
(217, 'Lagdo', 30, 6, 'Bénoué', 'Nord'),
(218, 'Madingring', 33, 6, 'Mayo-Rey', 'Nord'),
(219, 'Mayo-Oulo', 32, 6, 'Mayo-Louti', 'Nord'),
(220, 'Ngong', 30, 6, 'Bénoué', 'Nord'),
(221, 'Pitoa', 30, 6, 'Bénoué', 'Nord'),
(222, 'Poli', 31, 6, 'Faro', 'Nord'),
(223, 'Rey-Bouba', 33, 6, 'Mayo-Rey', 'Nord'),
(224, 'Tcholliré', 33, 6, 'Mayo-Rey', 'Nord'),
(225, 'Touboro', 33, 6, 'Mayo-Rey', 'Nord'),
(226, 'Touroua', 30, 6, 'Bénoué', 'Nord'),
(227, 'Ako', 34, 7, 'Donga-Mantung', 'Nord-Ouest'),
(228, 'Andek', 35, 7, 'Momo', 'Nord-Ouest'),
(229, 'Babessi', 36, 7, 'Ngo-Ketunjia', 'Nord-Ouest'),
(230, 'Bafut', 37, 7, 'Mezam', 'Nord-Ouest'),
(231, 'Bali', 37, 7, 'Mezam', 'Nord-Ouest'),
(232, 'Balikumbat', 36, 7, 'Ngo-Ketunjia', 'Nord-Ouest'),
(233, 'Bamenda Ier', 37, 7, 'Mezam', 'Nord-Ouest'),
(234, 'Bamenda IIe', 37, 7, 'Mezam', 'Nord-Ouest'),
(235, 'Bamenda IIIe', 37, 7, 'Mezam', 'Nord-Ouest'),
(236, 'Batibo', 35, 7, 'Momo', 'Nord-Ouest'),
(237, 'Belo', 38, 7, 'Boyo', 'Nord-Ouest'),
(238, 'Benakuma', 39, 7, 'Menchum', 'Nord-Ouest'),
(239, 'Elak-Oku', 40, 7, 'Bui', 'Nord-Ouest'),
(240, 'Fonfuka', 38, 7, 'Boyo', 'Nord-Ouest'),
(241, 'Fundong', 38, 7, 'Boyo', 'Nord-Ouest'),
(242, 'Furu-Awa', 39, 7, 'Menchum', 'Nord-Ouest'),
(243, 'Jakiri', 40, 7, 'Bui', 'Nord-Ouest'),
(244, 'Kumbo', 40, 7, 'Bui', 'Nord-Ouest'),
(245, 'Mbengwi', 35, 7, 'Momo', 'Nord-Ouest'),
(246, 'Mbiame', 40, 7, 'Bui', 'Nord-Ouest'),
(247, 'Misaje', 34, 7, 'Donga-Mantung', 'Nord-Ouest'),
(248, 'Ndop', 36, 7, 'Ngo-Ketunjia', 'Nord-Ouest'),
(249, 'Ndu', 34, 7, 'Donga-Mantung', 'Nord-Ouest'),
(250, 'Njikwa', 35, 7, 'Momo', 'Nord-Ouest'),
(251, 'Njinikom', 38, 7, 'Boyo', 'Nord-Ouest'),
(252, 'Nkambé', 34, 7, 'Donga-Mantung', 'Nord-Ouest'),
(253, 'Nkor', 40, 7, 'Bui', 'Nord-Ouest'),
(254, 'Nkum', 40, 7, 'Bui', 'Nord-Ouest'),
(255, 'Nwa', 34, 7, 'Donga-Mantung', 'Nord-Ouest'),
(256, 'Santa', 37, 7, 'Mezam', 'Nord-Ouest'),
(257, 'Tubah', 37, 7, 'Mezam', 'Nord-Ouest'),
(258, 'Widikum-Boffe', 35, 7, 'Momo', 'Nord-Ouest'),
(259, 'Wum', 39, 7, 'Menchum', 'Nord-Ouest'),
(260, 'Zhoa', 39, 7, 'Menchum', 'Nord-Ouest'),
(261, 'Babadjou', 41, 8, 'Bamboutos', 'Ouest'),
(262, 'Bafang', 42, 8, 'Haut-Nkam', 'Ouest'),
(263, 'Bafoussam Ier', 43, 8, 'Mifi', 'Ouest'),
(264, 'Bafoussam IIe', 43, 8, 'Mifi', 'Ouest'),
(265, 'Bafoussam IIIe', 43, 8, 'Mifi', 'Ouest'),
(266, 'Baham', 44, 8, 'Hauts-Plateaux', 'Ouest'),
(267, 'Bakou', 42, 8, 'Haut-Nkam', 'Ouest'),
(268, 'Bamendjou', 44, 8, 'Hauts-Plateaux', 'Ouest'),
(269, 'Bana', 42, 8, 'Haut-Nkam', 'Ouest'),
(270, 'Bandja', 42, 8, 'Haut-Nkam', 'Ouest'),
(271, 'Bandjoun', 45, 8, 'Koung-Khi', 'Ouest'),
(272, 'Bangangté', 46, 8, 'Ndé', 'Ouest'),
(273, 'Banka', 42, 8, 'Haut-Nkam', 'Ouest'),
(274, 'Bangou', 44, 8, 'Hauts-Plateaux', 'Ouest'),
(275, 'Bangourain', 47, 8, 'Noun', 'Ouest'),
(276, 'Banwa', 42, 8, 'Haut-Nkam', 'Ouest'),
(277, 'Bassamba', 46, 8, 'Ndé', 'Ouest'),
(278, 'Batcham', 41, 8, 'Bamboutos', 'Ouest'),
(279, 'Batié', 44, 8, 'Hauts-Plateaux', 'Ouest'),
(280, 'Bayangam', 45, 8, 'Koung-Khi', 'Ouest'),
(281, 'Bazou', 46, 8, 'Ndé', 'Ouest'),
(282, 'Demdeng', 45, 8, 'Koung-Khi', 'Ouest'),
(283, 'Dschang', 48, 8, 'Menoua', 'Ouest'),
(284, 'Fokoué', 48, 8, 'Menoua', 'Ouest'),
(285, 'Fongo-Tongo', 48, 8, 'Menoua', 'Ouest'),
(286, 'Foumban', 47, 8, 'Noun', 'Ouest'),
(287, 'Foumbot', 47, 8, 'Noun', 'Ouest'),
(288, 'Galim', 41, 8, 'Bamboutos', 'Ouest'),
(289, 'Kekem', 42, 8, 'Haut-Nkam', 'Ouest'),
(290, 'Kouoptamo', 47, 8, 'Noun', 'Ouest'),
(291, 'Koutaba', 47, 8, 'Noun', 'Ouest'),
(292, 'Magba', 47, 8, 'Noun', 'Ouest'),
(293, 'Malentouen', 47, 8, 'Noun', 'Ouest'),
(294, 'Massangam', 47, 8, 'Noun', 'Ouest'),
(295, 'Mbouda', 41, 8, 'Bamboutos', 'Ouest'),
(296, 'Njimom', 47, 8, 'Noun', 'Ouest'),
(297, 'Nkong-Zem', 48, 8, 'Menoua', 'Ouest'),
(298, 'Penka-Michel', 48, 8, 'Menoua', 'Ouest'),
(299, 'Santchou', 48, 8, 'Menoua', 'Ouest'),
(300, 'Tonga', 46, 8, 'Ndé', 'Ouest'),
(301, 'Akom II', 49, 9, 'Océan', 'Sud'),
(302, 'Ambam', 50, 9, 'Vallée-du-Ntem', 'Sud'),
(303, 'Bengbis', 51, 9, 'Dja-et-Lobo', 'Sud'),
(304, 'Bipindi', 49, 9, 'Océan', 'Sud'),
(305, 'Biwong-Bane', 52, 9, 'Mvila', 'Sud'),
(306, 'Biwong-Bulu', 52, 9, 'Mvila', 'Sud'),
(307, 'Campo', 49, 9, 'Océan', 'Sud'),
(308, 'Djoum', 51, 9, 'Dja-et-Lobo', 'Sud'),
(309, 'Ebolowa Ier', 52, 9, 'Mvila', 'Sud'),
(310, 'Ebolowa IIe', 52, 9, 'Mvila', 'Sud'),
(311, 'Efoulan', 52, 9, 'Mvila', 'Sud'),
(312, 'Kribi Ier', 49, 9, 'Océan', 'Sud'),
(313, 'Kribi IIe', 49, 9, 'Océan', 'Sud'),
(314, 'Kyé-Ossi', 50, 9, 'Vallée-du-Ntem', 'Sud'),
(315, 'Lokoundjé', 49, 9, 'Océan', 'Sud'),
(316, 'Lolodorf', 49, 9, 'Océan', 'Sud'),
(317, 'Ma\'an', 50, 9, 'Vallée-du-Ntem', 'Sud'),
(318, 'Mengong', 52, 9, 'Mvila', 'Sud'),
(319, 'Meyomessala', 15, 9, 'Mfoundi', 'Sud'),
(320, 'Meyomessi', 15, 9, 'Mfoundi', 'Sud'),
(321, 'Mintom', 51, 9, 'Dja-et-Lobo', 'Sud'),
(322, 'Mvangan', 52, 9, 'Mvila', 'Sud'),
(323, 'Mvengue', 49, 9, 'Océan', 'Sud'),
(324, 'Ngoulemakong', 52, 9, 'Mvila', 'Sud'),
(325, 'Niete', 49, 9, 'Océan', 'Sud'),
(326, 'Olamze', 50, 9, 'Vallée-du-Ntem', 'Sud'),
(327, 'Oveng', 51, 9, 'Dja-et-Lobo', 'Sud'),
(328, 'Sangmélima', 51, 9, 'Dja-et-Lobo', 'Sud'),
(329, 'Zoétélé', 51, 9, 'Dja-et-Lobo', 'Sud'),
(330, 'Akwaya', 53, 10, 'Manyu', 'Sud-Ouest'),
(331, 'Alou', 54, 10, 'Lebialem', 'Sud-Ouest'),
(332, 'Bamusso', 55, 10, 'Ndian', 'Sud-Ouest'),
(333, 'Bangem', 56, 10, 'Koupé-Manengouba', 'Sud-Ouest'),
(334, 'Buéa', 57, 10, 'Fako', 'Sud-Ouest'),
(335, 'Dikome-Balue', 55, 10, 'Ndian', 'Sud-Ouest'),
(336, 'Ekondo-Titi', 55, 10, 'Ndian', 'Sud-Ouest'),
(337, 'Eyumodjock', 53, 10, 'Manyu', 'Sud-Ouest'),
(338, 'Idabato', 55, 10, 'Ndian', 'Sud-Ouest'),
(339, 'Isanguele', 55, 10, 'Ndian', 'Sud-Ouest'),
(340, 'Kombo-Abedimo', 55, 10, 'Ndian', 'Sud-Ouest'),
(341, 'Kombo-Idinti', 55, 10, 'Ndian', 'Sud-Ouest'),
(342, 'Konye', 58, 10, 'Meme', 'Sud-Ouest'),
(343, 'Kumba Ier', 58, 10, 'Meme', 'Sud-Ouest'),
(344, 'Kumba IIe', 58, 10, 'Meme', 'Sud-Ouest'),
(345, 'Kumba IIIe', 58, 10, 'Meme', 'Sud-Ouest'),
(346, 'Limbé Ier', 57, 10, 'Fako', 'Sud-Ouest'),
(347, 'Limbé IIe', 57, 10, 'Fako', 'Sud-Ouest'),
(348, 'Limbé IIIe', 57, 10, 'Fako', 'Sud-Ouest'),
(349, 'Mamfé', 53, 10, 'Manyu', 'Sud-Ouest'),
(350, 'Mbonge', 58, 10, 'Meme', 'Sud-Ouest'),
(351, 'Menji', 54, 10, 'Lebialem', 'Sud-Ouest'),
(352, 'Mundemba', 55, 10, 'Ndian', 'Sud-Ouest'),
(353, 'Muyuka', 57, 10, 'Fako', 'Sud-Ouest'),
(354, 'Nguti', 56, 10, 'Koupé-Manengouba', 'Sud-Ouest'),
(355, 'Tiko', 57, 10, 'Fako', 'Sud-Ouest'),
(356, 'Toko', 55, 10, 'Ndian', 'Sud-Ouest'),
(357, 'Tombel', 56, 10, 'Koupé-Manengouba', 'Sud-Ouest'),
(358, 'Upper Bayang', 53, 10, 'Manyu', 'Sud-Ouest'),
(359, 'Wabane', 54, 10, 'Lebialem', 'Sud-Ouest'),
(360, 'West Coast', 57, 10, 'Fako', 'Sud-Ouest');

-- --------------------------------------------------------

--
-- Structure de la table `banques`
--

CREATE TABLE `banques` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `siege` varchar(255) DEFAULT NULL,
  `address` text,
  `microfinance` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `banques`
--

INSERT INTO `banques` (`id`, `name`, `siege`, `address`, `microfinance`) VALUES
(1, 'Access Bank', 'Douala', 'Téléphones : (+234) 12 71 2005 / (+234) 18 77 1496', 0),
(2, 'Afriland First Bank (AFB)', 'Yaoundé', 'B.P. 11 834, Téléphone : 222 22 30 68', 0),
(3, 'Banco Nacional de Guinea Ecuatorial (BANGE)', 'Yaoundé', 'Téléphones : (+240) 333 09 95 76 / (+240) 333 09 95 61', 0),
(4, 'Banque Atlantique Cameroun (BACM)', 'Douala', 'B.P. 2 933, Téléphone : 233 42 10 66', 0),
(5, 'Banque Camerounaise des Petites et Moyennes Entreprises (BC-PME)', 'Douala', 'B.P. 12 962, Téléphones : 222 51 03 03 / 681 58 21 00', 0),
(6, 'Banque Gabonaise pour le Financement International (BGFIBANK)', 'Douala', 'B.P. 660, Téléphone : 233 42 64 64', 0),
(7, 'Banque Internationale du Cameroun pour l’Epargne et le Crédit (BICEC)', 'Douala', 'B.P. 1 925, Téléphone : 233 42 85 76', 0),
(8, 'Citibank Cameroun', 'Douala', 'B.P. 4 571, Téléphone : 233 42 42 72', 0),
(9, 'Commercial Bank-Cameroun (CBC)', 'Douala', 'B.P. 4 004, Téléphone : 233 42 02 02', 0),
(10, 'Crédit Communautaire d’Afrique – Bank (CCA-BANK)', 'Yaoundé', 'B.P. 30 388, Téléphones : 222 22 84 77 / 222 22 13 87', 0),
(11, 'Ecobank Cameroun (ECOBANK)', 'Douala', 'B.P. 582, Téléphone : 233 43 82 50', 0),
(12, 'La Régionale Bank', 'Yaoundé', 'B.P. 30 145, Téléphone +237 222 22 02 39 / 222 22 66 55', 0),
(13, 'National Financial Credit-Bank (NFC-Bank)', 'Yaoundé', 'B.P. 6 578, Téléphone : 222 20 28 23', 0),
(14, 'Société Commerciale de Banque-Cameroun (SCB-Cameroun)', 'Douala', 'B.P. 300, Téléphone : 233 43 53 00', 0),
(15, 'Société Générale Cameroun (SGC)', 'Douala', 'B.P. 4 042, Téléphone : 233 50 18 18', 0),
(16, 'Standard Chatered Bank Cameroon (SCBC)', 'Douala', 'B.P. 1 784, Téléphone : 233 43 52 00', 0),
(17, 'Union Bank of Cameroon (UBC)', 'Douala', 'B.P. 15 569, Téléphone : 233 36 23 14', 0),
(18, 'United Bank for Africa (UBA)', 'Douala', 'B.P. 2 088, Téléphone : 233 43 36 39', 0),
(19, 'Africa Golden Bank (AGB)', 'Douala', 'BP.10169, Téléphone : 694222847', 0),
(20, 'Crédit Foncier du Cameroun (CFC)', 'Yaoundé', 'B.P. 1 531, Téléphone : 222 23 52 17', 1),
(21, 'PRO-PME Financement S.A.', 'Douala', 'B.P. 2373, Téléphones : 233 42 31 03 / 677 50 08 13', 1),
(22, 'Société Camerounaise de Crédit Automobile SOCCA (Alios Finance)', 'Douala', 'B.P. 554, Téléphone : 233 50 23 00', 1),
(23, 'Société Camerounaise d’Equipement (SCE)', 'Yaoundé', 'B.P. 178, Téléphone : 222 23 38 64', 1),
(24, 'Société de Recouvrement des Créances du Cameroun (SRC)', 'Yaoundé', 'B.P. 11 911, Téléphones : 222 22 09 11 / 222 22 37 39', 1),
(25, 'Société Nationale d’Investissement (SNI)', 'Yaoundé', 'B.P. 423, Téléphone : 222 22 44 22 / 222 23 40 95 / 222 23 10 61', 1),
(26, 'Wafacash Central Africa S.A. (WCA)', 'Douala', 'B.P. 1 362, Téléphone : 243 08 65 69', 1);

-- --------------------------------------------------------

--
-- Structure de la table `branches`
--

CREATE TABLE `branches` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `choices`
--

CREATE TABLE `choices` (
  `id` int UNSIGNED NOT NULL,
  `valeur` varchar(255) DEFAULT NULL,
  `note` double DEFAULT '0',
  `critere_id` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `choices`
--

INSERT INTO `choices` (`id`, `valeur`, `note`, `critere_id`, `created_at`, `updated_at`) VALUES
(1, 'Très significatif', 1, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(2, 'Significatif', 2, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(3, 'Bon', 3, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(4, 'Plutôt Bon', 4, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(5, 'Moyen', 5, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(6, 'Plutôt Moyen', 6, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(7, 'Faible', 7, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(8, 'Limité', 8, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(9, 'Mauvais', 9, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(10, 'Très Mauvais', 10, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(11, 'très bonne A', 1, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(12, 'très bonne B', 2, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(13, 'bonne', 3, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(14, 'moyenne', 4, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(15, 'faible', 5, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(16, 'plutôt en stagnation', 6, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(17, 'plutôt en baisse', 7, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(18, 'en baisse sensible', 8, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(19, 'en forte baisse', 9, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(20, 'secteur en difficulté ou en déclin', 10, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(21, 'non', 1, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(22, ' non ou très peu ', 2, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(23, 'peu', 3, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(24, 'légèrement', 4, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(25, 'modérement B', 5, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(26, 'modérement A', 6, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(27, 'fortement B', 7, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(28, 'fortement A', 8, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(29, 'très fortement B', 9, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(30, 'très fortement A', 10, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(31, 'extremement bien positionné', 1, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(32, 'très bien positionné', 2, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(33, 'plutot bien positionné', 3, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(34, 'assez bien positionné', 4, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(35, 'moyennement positionné A', 5, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(36, 'moyennement positionné B', 6, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(37, 'plutôt mal positionné et/ou étroit', 7, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(38, 'mal positionné et/ou  très étroit', 8, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(39, 'renouvellement non assuré', 9, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(40, 'obsolescence marquée', 10, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(41, 'très modérée', 1, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(42, 'modérée', 2, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(43, 'plutôt faible', 3, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(44, 'plutot sensible', 4, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(45, 'assez sensible', 5, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(46, 'sensible', 6, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(47, 'assez forte', 7, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(48, 'forte', 8, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(49, 'très forte', 9, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(50, 'extremement forte', 10, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(51, 'très élevéés A', 1, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(52, 'très élevéés B', 2, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(53, 'élevées', 3, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(54, 'assez élevées', 4, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(55, ' plutôt faibles', 5, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(56, ' faibles ', 6, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(57, 'très faibles A', 7, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(58, 'très faibles B', 8, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(59, 'inexistentes A', 9, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(60, 'inexistentes B', 10, 6, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(61, 'très bonne A', 1, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(62, ' très bonne B', 2, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(63, 'bonne A', 3, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(64, 'bonne B', 4, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(65, 'moyenne à moyen terme', 5, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(66, 'limitée à moyen terme', 6, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(67, 'plutot faible', 7, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(68, 'faible', 8, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(69, 'très faible', 9, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(70, 'extrement faible', 10, 7, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(71, 'excellente', 1, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(72, 'très bonne', 2, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(73, 'bonne', 3, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(74, 'assez bonne', 4, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(75, 'moyenne', 5, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(76, 'plutôt moyenne', 6, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(77, 'mauvaise', 7, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(78, 'limitée', 8, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(79, 'très mauvaise A', 9, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(80, 'très mauvaise B', 10, 8, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(81, 'excellente', 1, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(82, 'très bonne', 2, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(83, 'bonne', 3, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(84, 'assez bonne', 4, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(85, 'moyenne', 5, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(86, 'plutôt moyenne', 6, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(87, 'mauvaise', 7, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(88, 'limitée', 8, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(89, 'très mauvaise A', 9, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(90, 'très mauvaise B', 10, 9, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(91, 'excellente', 1, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(92, 'très bonne', 2, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(93, 'bonne', 3, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(94, 'assez bonne', 4, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(95, 'moyenne', 5, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(96, 'plutôt moyenne', 6, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(97, 'mauvaise', 7, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(98, 'limitée', 8, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(99, 'très mauvaise A', 9, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(100, 'très mauvaise B', 10, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(101, 'excellente', 1, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(102, 'très bonne', 2, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(103, 'bonne', 3, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(104, 'assez bonne', 4, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(105, 'moyenne', 5, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(106, 'plutôt moyenne', 6, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(107, 'mauvaise', 7, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(108, 'limitée', 8, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(109, 'très mauvaise A', 9, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(110, 'très mauvaise B', 10, 11, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(111, 'excellente', 1, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(112, 'très bonne', 2, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(113, 'bonne', 3, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(114, 'assez bonne', 4, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(115, 'moyenne', 5, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(116, 'plutôt moyenne', 6, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(117, 'mauvaise', 7, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(118, 'limitée', 8, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(119, 'très mauvaise A', 9, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(120, 'très mauvaise B', 10, 12, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(121, 'importants,bien adaptés', 1, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(122, 'importants, adaptés', 2, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(123, 'assez importants', 3, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(124, 'suffisants', 4, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(125, 'plutôt faibles', 5, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(126, 'faibles', 6, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(127, 'insuffisants A', 7, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(128, 'insuffisants B', 8, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(129, 'très insuffisants A', 9, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(130, 'très insuffisants B', 10, 13, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(131, 'excellente', 1, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(132, 'très bonne', 2, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(133, 'bonne', 3, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(134, 'assez bonne', 4, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(135, 'moyenne', 5, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(136, 'plutôt faible', 6, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(137, 'faible', 7, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(138, 'limité', 8, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(139, 'très limitée', 9, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(140, 'inexistente', 10, 14, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(141, 'très fiables dans l\'ensemble', 1, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(142, 'plutôt fiables A', 2, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(143, 'plutôt fiables B', 3, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(144, 'fiables', 4, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(145, 'moyennement fiables', 5, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(146, 'pas toufours fiables', 6, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(147, 'peu fiables', 7, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(148, 'peu ou pas fiables ou non communiquées', 8, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(149, 'pas fiables A', 9, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(150, 'pas fiables B', 10, 15, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(151, 'excellente', 1, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(152, 'très bonne', 2, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(153, 'bonne', 3, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(154, 'assez bonne', 4, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(155, 'moyenne', 5, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(156, 'limitée', 6, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(157, 'plutôt faible', 7, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(158, 'faible', 8, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(159, 'mauvaise', 9, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(160, 'très mauvaise', 10, 16, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(161, 'Ne se pose pas du tout', 1, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(162, 'Ne se pose pas', 2, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(163, 'Garantie par le cadre juridique et fonctionnel', 3, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(164, 'Garantie par le cadre juridique ', 4, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(165, 'Assurée depuis un certain temps (Moyen)', 5, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(166, 'Assurée depuis quelque temps (Court)', 6, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(167, 'se pose à moyen terme, relève non prévue', 7, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(168, 'se pose à court terme,relève non assurée', 8, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(169, 'problème aigue de succession', 9, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(170, 'gestion par administrateur ', 10, 17, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(171, 'très fiable', 1, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(172, 'fiable', 2, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(173, 'plutôt fiable A', 3, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(174, 'plutôt fiable B', 4, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(175, 'moyennement fiable', 5, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(176, 'pas toujours fiable', 6, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(177, 'peu fiable', 7, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(178, 'peu ou pas fiable', 8, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(179, 'pas fiable A', 9, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(180, 'pas fiable B', 10, 31, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(181, 'excellente', 1, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(182, 'très bonne', 2, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(183, 'bonne', 3, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(184, 'assez bonne', 4, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(185, 'moyenne', 5, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(186, 'plutôt moyenne', 6, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(187, 'mauvaise', 7, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(188, 'limitée', 8, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(189, 'très mauvaise A', 9, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(190, 'très mauvaise B', 10, 32, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(191, 'très facile en permanence', 1, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(192, 'facile en conjoncture normale A', 2, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(193, 'facile en conjoncture normale B', 3, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(194, 'limité en conjocture normale A', 4, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(195, 'limité en conjocture normale B', 5, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(196, 'incertain mais envisageable', 6, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(197, 'difficile mais possible', 7, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(198, 'très peu probable', 8, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(199, 'non pas d\'appui A', 9, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(200, 'non pas d\'appui B', 10, 33, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(201, 'oui, rapidement et importants', 1, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(202, 'oui, assez rapidement et assez  importants', 2, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(203, 'oui, sans delai A', 3, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(204, 'oui, sans delai B', 4, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(205, 'Surface financière limitée', 5, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(206, 'Surface financière faible', 6, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(207, 'Surface financière très faible', 7, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(208, 'Patrimoine insignifiant', 8, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(209, 'Non, pas patrimoine personnel', 9, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(210, 'Non, pas de patrimoine du tout', 10, 34, '2024-12-05 12:31:05', '2024-12-05 12:31:05');

-- --------------------------------------------------------

--
-- Structure de la table `composantes`
--

CREATE TABLE `composantes` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `programme_id` int NOT NULL DEFAULT '0',
  `organisme_id` int NOT NULL DEFAULT '0',
  `banque_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `composantes`
--

INSERT INTO `composantes` (`id`, `name`, `type`, `programme_id`, `organisme_id`, `banque_id`) VALUES
(8, 'Fonds fiduciaire pour l\'aide au commerce', 'Appuis financiers', 2, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `criteres`
--

CREATE TABLE `criteres` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `criteres`
--

INSERT INTO `criteres` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ACTIVITE', '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(2, 'GESTION ET STRATEGIE', '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(3, 'FINANCES', '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(4, 'QUALITE DE L\'INFORMATION FINANCIERE ET GARANTIES', '2024-12-05 12:31:05', '2024-12-05 12:31:05');

-- --------------------------------------------------------

--
-- Structure de la table `critere_programme_ponderations`
--

CREATE TABLE `critere_programme_ponderations` (
  `id` int UNSIGNED NOT NULL,
  `programme_id` int DEFAULT '0',
  `critere_id` int DEFAULT '0',
  `ponderation` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delegation_pouvoirs`
--

CREATE TABLE `delegation_pouvoirs` (
  `id` bigint UNSIGNED NOT NULL,
  `seuil_engagements_max` decimal(18,2) NOT NULL,
  `profil_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `delegation_pouvoirs`
--

INSERT INTO `delegation_pouvoirs` (`id`, `seuil_engagements_max`, `profil_id`, `created_at`, `updated_at`) VALUES
(1, 1000000.00, 9, '2026-04-23 18:22:26', '2026-04-23 18:22:26');

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abb` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int NOT NULL DEFAULT '0',
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departements`
--

INSERT INTO `departements` (`id`, `name`, `abb`, `region_id`, `region`, `created_at`, `updated_at`) VALUES
(1, 'Mayo-Banyo', NULL, 1, NULL, NULL, NULL),
(2, 'Vina', NULL, 1, NULL, NULL, NULL),
(3, 'Mbéré', NULL, 1, NULL, NULL, NULL),
(4, 'Faro-et-Déo', NULL, 1, NULL, NULL, NULL),
(5, 'Djerem', NULL, 1, NULL, NULL, NULL),
(6, 'Méfou-et-Afamba', NULL, 2, NULL, NULL, NULL),
(7, 'Nyong-et-So\'o', NULL, 2, NULL, NULL, NULL),
(8, 'Méfou-et-Akono', NULL, 2, NULL, NULL, NULL),
(9, 'Nyong-et-Mfoumou', NULL, 2, NULL, NULL, NULL),
(10, 'Mbam-et-Inoubou', NULL, 2, NULL, NULL, NULL),
(11, 'Lekié', NULL, 2, NULL, NULL, NULL),
(12, 'Haute-Sanaga', NULL, 2, NULL, NULL, NULL),
(13, 'Nyong-et-Kéllé', NULL, 2, NULL, NULL, NULL),
(14, 'Mbam-et-Kim', NULL, 2, NULL, NULL, NULL),
(15, 'Mfoundi', NULL, 2, NULL, NULL, NULL),
(16, 'Haut-Nyong', NULL, 2, NULL, NULL, NULL),
(17, 'Kadey', NULL, 3, NULL, NULL, NULL),
(18, 'Lom-et-Djérem', NULL, 3, NULL, NULL, NULL),
(19, 'Boumba-et-Ngoko', NULL, 3, NULL, NULL, NULL),
(20, 'Logone-et-Chari', NULL, 4, NULL, NULL, NULL),
(21, 'Diamaré', NULL, 4, NULL, NULL, NULL),
(22, 'Mayo-Tsanaga', NULL, 4, NULL, NULL, NULL),
(23, 'Mayo-Danay', NULL, 4, NULL, NULL, NULL),
(24, 'Mayo-Kani', NULL, 4, NULL, NULL, NULL),
(25, 'Mayo-Sava', NULL, 4, NULL, NULL, NULL),
(26, 'Moungo', NULL, 5, NULL, NULL, NULL),
(27, 'Sanaga-Maritime', NULL, 5, NULL, NULL, NULL),
(28, 'Wouri', NULL, 5, NULL, NULL, NULL),
(29, 'Nkam', NULL, 5, NULL, NULL, NULL),
(30, 'Bénoué', NULL, 6, NULL, NULL, NULL),
(31, 'Faro', NULL, 6, NULL, NULL, NULL),
(32, 'Mayo-Louti', NULL, 6, NULL, NULL, NULL),
(33, 'Mayo-Rey', NULL, 6, NULL, NULL, NULL),
(34, 'Donga-Mantung', NULL, 7, NULL, NULL, NULL),
(35, 'Momo', NULL, 7, NULL, NULL, NULL),
(36, 'Ngo-Ketunjia', NULL, 7, NULL, NULL, NULL),
(37, 'Mezam', NULL, 7, NULL, NULL, NULL),
(38, 'Boyo', NULL, 7, NULL, NULL, NULL),
(39, 'Menchum', NULL, 7, NULL, NULL, NULL),
(40, 'Bui', NULL, 7, NULL, NULL, NULL),
(41, 'Bamboutos', NULL, 8, NULL, NULL, NULL),
(42, 'Haut-Nkam', NULL, 8, NULL, NULL, NULL),
(43, 'Mifi', NULL, 8, NULL, NULL, NULL),
(44, 'Hauts-Plateaux', NULL, 8, NULL, NULL, NULL),
(45, 'Koung-Khi', NULL, 8, NULL, NULL, NULL),
(46, 'Ndé', NULL, 8, NULL, NULL, NULL),
(47, 'Noun', NULL, 8, NULL, NULL, NULL),
(48, 'Menoua', NULL, 8, NULL, NULL, NULL),
(49, 'Océan', NULL, 9, NULL, NULL, NULL),
(50, 'Vallée-du-Ntem', NULL, 9, NULL, NULL, NULL),
(51, 'Dja-et-Lobo', NULL, 9, NULL, NULL, NULL),
(52, 'Mvila', NULL, 9, NULL, NULL, NULL),
(53, 'Manyu', NULL, 10, NULL, NULL, NULL),
(54, 'Lebialem', NULL, 10, NULL, NULL, NULL),
(55, 'Ndian', NULL, 10, NULL, NULL, NULL),
(56, 'Koupé-Manengouba', NULL, 10, NULL, NULL, NULL),
(57, 'Fako', NULL, 10, NULL, NULL, NULL),
(58, 'Meme', NULL, 10, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` int UNSIGNED NOT NULL,
  `entreprise_id` int DEFAULT NULL,
  `programme_id` int UNSIGNED DEFAULT NULL,
  `analyste_id` int NOT NULL DEFAULT '0',
  `gestionnaire_id` int NOT NULL DEFAULT '0',
  `agence_id` int NOT NULL DEFAULT '0',
  `representation_id` int NOT NULL DEFAULT '0',
  `donnees_generales` text,
  `analyse_ensemble` text,
  `analyse_financiere` text,
  `appuis` text,
  `analyse_risque` text,
  `analyse_rentabilite` text,
  `conclusions_analyste` text,
  `conclusions_gestionnaire` text,
  `conclusions_ca` text,
  `conclusions_ca_saved_at` timestamp NULL DEFAULT NULL,
  `conclusions_ca_saved_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(100) DEFAULT NULL,
  `exploitation_avis_credit` text,
  `exploitation_avis_credit_at` timestamp NULL DEFAULT NULL,
  `exploitation_avis_credit_user_id` bigint UNSIGNED DEFAULT NULL,
  `exploitation_engagements_decision` varchar(32) DEFAULT NULL,
  `exploitation_engagements_decision_at` timestamp NULL DEFAULT NULL,
  `exploitation_engagements_decision_user_id` bigint UNSIGNED DEFAULT NULL,
  `exploitation_engagements_decision_comment` text,
  `exploitation_analyste_assigned_at` timestamp NULL DEFAULT NULL,
  `exploitation_analyste_assigned_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `exploitation_analyste_transmitted_to_exploitation_at` datetime DEFAULT NULL,
  `exploitation_analyste_transmitted_to_exploitation_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_instruction_submitted_at` timestamp NULL DEFAULT NULL,
  `juridique_instruction_submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_analyste_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_analyste_assigned_at` timestamp NULL DEFAULT NULL,
  `juridique_analyste_assigned_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_analyste_avis` longtext,
  `juridique_analyste_submitted_to_reju_at` timestamp NULL DEFAULT NULL,
  `juridique_analyste_submitted_to_reju_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_responsable_avis` longtext,
  `juridique_submitted_to_engagements_at` timestamp NULL DEFAULT NULL,
  `juridique_submitted_to_engagements_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_analyste_credit_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_analyste_credit_assigned_at` timestamp NULL DEFAULT NULL,
  `reng_analyste_credit_assigned_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_contre_analyse` longtext,
  `reng_analyste_credit_avis` longtext,
  `reng_etat_engagements_client` longtext,
  `reng_analyste_credit_submitted_at` timestamp NULL DEFAULT NULL,
  `reng_analyste_credit_submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_responsable_avis` longtext,
  `reng_responsable_avis_at` timestamp NULL DEFAULT NULL,
  `reng_responsable_avis_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_submitted_to_risques_at` timestamp NULL DEFAULT NULL,
  `reng_submitted_to_risques_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_analyste_risques_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_analyste_risques_assigned_at` timestamp NULL DEFAULT NULL,
  `rerx_analyste_risques_assigned_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_analyse_risques` longtext,
  `rerx_analyste_risques_avis` longtext,
  `rerx_analyste_risques_submitted_at` timestamp NULL DEFAULT NULL,
  `rerx_analyste_risques_submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_responsable_avis` longtext,
  `rerx_responsable_avis_at` timestamp NULL DEFAULT NULL,
  `rerx_responsable_avis_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_submitted_to_direction_at` timestamp NULL DEFAULT NULL,
  `rerx_submitted_to_direction_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `chef_filiere_submitted_to_agence_at` timestamp NULL DEFAULT NULL,
  `chef_filiere_submitted_to_agence_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_agence_validated_at` timestamp NULL DEFAULT NULL,
  `instruction_agence_validated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_agence_rejected_at` timestamp NULL DEFAULT NULL,
  `instruction_agence_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_agence_reject_motif` text,
  `exploitation_analyste_instruction_avis` longtext,
  `exploitation_analyste_instruction_avis_saved_at` timestamp NULL DEFAULT NULL,
  `instruction_grille_last_edited_at` timestamp NULL DEFAULT NULL,
  `instruction_grille_last_edited_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_analyste_avis_saved_at` timestamp NULL DEFAULT NULL,
  `juridique_analyste_avis_saved_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_responsable_avis_at` timestamp NULL DEFAULT NULL,
  `juridique_responsable_avis_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `engagements_sollicites_total` decimal(18,2) DEFAULT NULL,
  `engagements_en_cours_total` decimal(18,2) DEFAULT NULL,
  `instruction_agence_closing_note` text,
  `instruction_agence_ca_avis` longtext,
  `instruction_agence_ca_avis_saved_at` timestamp NULL DEFAULT NULL,
  `instruction_agence_ca_avis_saved_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_ca_transmitted_to_exploitation_at` timestamp NULL DEFAULT NULL,
  `instruction_ca_transmitted_to_exploitation_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `exploitation_af_breves_donnees_client` longtext,
  `exploitation_af_analyse_critique_ensemble` longtext,
  `exploitation_af_analyse_financiere_client` longtext,
  `exploitation_af_appuis_proposes` longtext,
  `exploitation_af_analyse_risque_remboursement` longtext,
  `exploitation_af_rentabilite_relation` longtext,
  `exploitation_af_conclusions_recommandations` longtext,
  `instruction_closure_validated_at` timestamp NULL DEFAULT NULL,
  `instruction_closure_validated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_closure_rejected_at` timestamp NULL DEFAULT NULL,
  `instruction_closure_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_closure_reject_motif` text,
  `instruction_closure_note` text,
  `exploitation_analyste_rejected_at` timestamp NULL DEFAULT NULL,
  `exploitation_analyste_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `exploitation_analyste_reject_motif` text,
  `juridique_analyste_rejected_at` timestamp NULL DEFAULT NULL,
  `juridique_analyste_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_analyste_reject_motif` text,
  `reng_analyste_credit_rejected_at` timestamp NULL DEFAULT NULL,
  `reng_analyste_credit_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `reng_analyste_credit_reject_motif` text,
  `rerx_analyste_risques_rejected_at` timestamp NULL DEFAULT NULL,
  `rerx_analyste_risques_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `rerx_analyste_risques_reject_motif` text,
  `juridique_rejected_to_exploitation_at` timestamp NULL DEFAULT NULL,
  `juridique_rejected_to_exploitation_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `juridique_rejected_to_exploitation_motif` text,
  `engagements_rejected_to_juridique_at` timestamp NULL DEFAULT NULL,
  `engagements_rejected_to_juridique_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `engagements_rejected_to_juridique_motif` text,
  `risques_rejected_to_engagements_at` timestamp NULL DEFAULT NULL,
  `risques_rejected_to_engagements_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `risques_rejected_to_engagements_motif` text,
  `direction_rejected_to_risques_at` timestamp NULL DEFAULT NULL,
  `direction_rejected_to_risques_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `direction_rejected_to_risques_motif` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`id`, `entreprise_id`, `programme_id`, `analyste_id`, `gestionnaire_id`, `agence_id`, `representation_id`, `donnees_generales`, `analyse_ensemble`, `analyse_financiere`, `appuis`, `analyse_risque`, `analyse_rentabilite`, `conclusions_analyste`, `conclusions_gestionnaire`, `conclusions_ca`, `conclusions_ca_saved_at`, `conclusions_ca_saved_by_user_id`, `created_at`, `updated_at`, `active`, `token`, `exploitation_avis_credit`, `exploitation_avis_credit_at`, `exploitation_avis_credit_user_id`, `exploitation_engagements_decision`, `exploitation_engagements_decision_at`, `exploitation_engagements_decision_user_id`, `exploitation_engagements_decision_comment`, `exploitation_analyste_assigned_at`, `exploitation_analyste_assigned_by_user_id`, `exploitation_analyste_transmitted_to_exploitation_at`, `exploitation_analyste_transmitted_to_exploitation_by_user_id`, `juridique_instruction_submitted_at`, `juridique_instruction_submitted_by_user_id`, `juridique_analyste_user_id`, `juridique_analyste_assigned_at`, `juridique_analyste_assigned_by_user_id`, `juridique_analyste_avis`, `juridique_analyste_submitted_to_reju_at`, `juridique_analyste_submitted_to_reju_by_user_id`, `juridique_responsable_avis`, `juridique_submitted_to_engagements_at`, `juridique_submitted_to_engagements_by_user_id`, `reng_analyste_credit_user_id`, `reng_analyste_credit_assigned_at`, `reng_analyste_credit_assigned_by_user_id`, `reng_contre_analyse`, `reng_analyste_credit_avis`, `reng_etat_engagements_client`, `reng_analyste_credit_submitted_at`, `reng_analyste_credit_submitted_by_user_id`, `reng_responsable_avis`, `reng_responsable_avis_at`, `reng_responsable_avis_by_user_id`, `reng_submitted_to_risques_at`, `reng_submitted_to_risques_by_user_id`, `rerx_analyste_risques_user_id`, `rerx_analyste_risques_assigned_at`, `rerx_analyste_risques_assigned_by_user_id`, `rerx_analyse_risques`, `rerx_analyste_risques_avis`, `rerx_analyste_risques_submitted_at`, `rerx_analyste_risques_submitted_by_user_id`, `rerx_responsable_avis`, `rerx_responsable_avis_at`, `rerx_responsable_avis_by_user_id`, `rerx_submitted_to_direction_at`, `rerx_submitted_to_direction_by_user_id`, `chef_filiere_submitted_to_agence_at`, `chef_filiere_submitted_to_agence_by_user_id`, `instruction_agence_validated_at`, `instruction_agence_validated_by_user_id`, `instruction_agence_rejected_at`, `instruction_agence_rejected_by_user_id`, `instruction_agence_reject_motif`, `exploitation_analyste_instruction_avis`, `exploitation_analyste_instruction_avis_saved_at`, `instruction_grille_last_edited_at`, `instruction_grille_last_edited_by_user_id`, `juridique_analyste_avis_saved_at`, `juridique_analyste_avis_saved_by_user_id`, `juridique_responsable_avis_at`, `juridique_responsable_avis_by_user_id`, `engagements_sollicites_total`, `engagements_en_cours_total`, `instruction_agence_closing_note`, `instruction_agence_ca_avis`, `instruction_agence_ca_avis_saved_at`, `instruction_agence_ca_avis_saved_by_user_id`, `instruction_ca_transmitted_to_exploitation_at`, `instruction_ca_transmitted_to_exploitation_by_user_id`, `exploitation_af_breves_donnees_client`, `exploitation_af_analyse_critique_ensemble`, `exploitation_af_analyse_financiere_client`, `exploitation_af_appuis_proposes`, `exploitation_af_analyse_risque_remboursement`, `exploitation_af_rentabilite_relation`, `exploitation_af_conclusions_recommandations`, `instruction_closure_validated_at`, `instruction_closure_validated_by_user_id`, `instruction_closure_rejected_at`, `instruction_closure_rejected_by_user_id`, `instruction_closure_reject_motif`, `instruction_closure_note`, `exploitation_analyste_rejected_at`, `exploitation_analyste_rejected_by_user_id`, `exploitation_analyste_reject_motif`, `juridique_analyste_rejected_at`, `juridique_analyste_rejected_by_user_id`, `juridique_analyste_reject_motif`, `reng_analyste_credit_rejected_at`, `reng_analyste_credit_rejected_by_user_id`, `reng_analyste_credit_reject_motif`, `rerx_analyste_risques_rejected_at`, `rerx_analyste_risques_rejected_by_user_id`, `rerx_analyste_risques_reject_motif`, `juridique_rejected_to_exploitation_at`, `juridique_rejected_to_exploitation_by_user_id`, `juridique_rejected_to_exploitation_motif`, `engagements_rejected_to_juridique_at`, `engagements_rejected_to_juridique_by_user_id`, `engagements_rejected_to_juridique_motif`, `risques_rejected_to_engagements_at`, `risques_rejected_to_engagements_by_user_id`, `risques_rejected_to_engagements_motif`, `direction_rejected_to_risques_at`, `direction_rejected_to_risques_by_user_id`, `direction_rejected_to_risques_motif`) VALUES
(31, 261, 1, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-19 19:44:34', '2026-04-21 06:56:32', 0, 'ecde8f26898476b1ce37d59753822ed2e7309799', '<p>Ceci est mon avis a propos de ce dossier très important:</p><ol><li>Le dossier est prometteur</li><li>Le porteur de projet a encore beaucoup d\'effort a fournir</li><li>Étudier tous les contours de la question</li></ol>', '2026-04-20 19:56:39', 8, 'accord', '2026-04-20 19:58:20', 8, 'Dossier prêt a passer au pole jurique', '2026-04-20 10:47:51', 8, '2026-04-20 20:35:51', 9, '2026-04-20 19:58:38', 8, 14, '2026-04-20 22:52:01', 4, '<p>Ceci est mon avis en tant qu\'analyste juridique:</p><p>Le dossier est bon&nbsp;</p><p><br></p>', '2026-04-20 22:54:52', 14, '<p>Ceci est mon analyse en tant que <u><i><b>responsable juridique.</b></i></u>&nbsp;</p><ul><li>une</li><li>deux</li><li>trois</li><li>quatre (4)</li><li><br></li><li><br></li></ul>', '2026-04-21 06:11:21', 4, 13, '2026-04-21 06:13:40', 10, '<p>Ceci est une contre analyse que je produis en<b> tant qu\'analyste de crédit</b></p><p><b><br></b></p>', '<p>Et ceci est mon avis en tant qu\'analyste de credit</p><p>Le dossier est interessant</p><p>Le client semble solvable</p>', NULL, '2026-04-21 06:53:42', 13, '<p>Mon avis en tant que responsable est que je partage en tout point ce qui a été dit par l\'analyste de credit</p>', NULL, NULL, '2026-04-21 06:56:32', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 263, 1, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 13:02:55', '2026-04-21 14:41:13', 0, 'a1ea9f21c5dda542664899e23aeb3fbf1f04f866', '<p>lorem up sum&nbsp;</p><p>mon avis en tant responsable exploitation</p>', '2026-04-21 14:18:20', 8, 'accord', '2026-04-21 14:21:33', 8, 'Je decide valider le dossier des engagements', '2026-04-21 14:05:28', 8, '2026-04-21 15:14:33', 9, '2026-04-21 14:21:58', 8, 14, '2026-04-21 14:25:40', 4, '<p>Mon avis en tant qu\'analyste juridique</p>', '2026-04-21 14:26:58', 14, '<p>Mon avis en tant que responsable juridique</p>', '2026-04-21 14:29:16', 4, 13, '2026-04-21 14:32:18', 10, '<p>Ma contre-analyste concernant ce dossier</p>', '<p>Mon avis en tant qu\'analyste credit</p>', NULL, '2026-04-21 14:34:10', 13, '<p>Mon avis en tant que responsable des engagements</p>', NULL, NULL, '2026-04-21 14:35:35', 10, 12, '2026-04-21 14:37:46', 11, '<p>Mkn analyses des risques sur ce dossier&nbsp;</p>', '<p>Mon avis sur ce dossier</p>', '2026-04-21 14:39:08', 12, '<p>Mon avis en tant que responsable risques</p>', NULL, NULL, '2026-04-21 14:41:13', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 263, NULL, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 21:23:49', '2026-04-22 06:56:41', 0, 'ce6afff157d94e0c8d3f40c7326afe3e52fa429f', '<p>Ici mon avis en tant que responsable exploitation</p>', '2026-04-22 06:09:26', 8, 'accord', '2026-04-22 06:09:58', 8, 'Je valide la décision de credit', '2026-04-22 05:48:29', 8, '2026-04-22 07:07:20', 9, '2026-04-22 06:10:11', 8, 14, '2026-04-22 06:11:47', 4, '<p>Ceci<b> est mon avis en tan</b>t qu\'analyste juridique</p>', '2026-04-22 06:54:04', 14, '<p>Ceci est<b> mon&nbsp; avi</b>s en tant que responsable juridique</p><ul><li>un</li><li>deux</li><li>trois</li></ul><h2>Un grand sous titre</h2>', '2026-04-22 06:56:41', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 21:23:49', 6, '2026-04-21 21:25:47', 7, NULL, NULL, NULL, '<p>Ceci est l\'analyse financier:</p><ol><li>un premier point</li><li>un deuxième</li><li>un troisième</li></ol><p><b>Du texte en gras</b></p>', '2026-04-22 06:07:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 261, NULL, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-23 18:26:18', '2026-04-23 22:45:37', 0, 'bb5e3878885cbe432d7b8bc1471f33520b588707', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-23 19:52:47', 8, '2026-04-23 23:45:37', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-23 18:26:18', 6, '2026-04-23 19:31:59', 7, NULL, NULL, NULL, '<h6 class=\"small text-uppercase text-muted mb-2\">Brèves données générales actualisées sur le client</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ipsam nobis dolore amet aliquam sequi maiores error corrupti quasi porro reprehenderit aspernatur, autem officia ad vitae laborum, voluptate iusto assumenda.</span></div><div><span style=\"color: #292929;\">Ad omnis laudantium tenetur natus id. Perferendis, facilis praesentium quia, laudantium voluptatibus nostrum aliquid in repellendus soluta qui cumque corporis sapiente dolorem sint, molestias deleniti? Omnis non possimus quibusdam quas.</span></div><div><span style=\"color: #292929;\">Error earum accusamus hic accusantium optio aut voluptate libero fuga, officiis dolorum eius aspernatur, assumenda aperiam quas, possimus velit culpa dolorem ducimus totam eligendi doloremque ex! Explicabo qui deleniti modi.</span></div><div><span style=\"color: #292929;\">Dignissimos doloribus, sunt cupiditate unde officia nostrum, repellendus ullam rerum placeat sed totam molestiae alias nesciunt? Nesciunt rem, natus suscipit, consequatur sit, repudiandae praesentium tempora repellat doloribus quas at eaque!</span></div></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse critique d’ensemble</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet, consectetur adip<b>isicing elit. Quod lauda</b>ntium, numquam eaque dicta, soluta tempora amet </span></div><ul><li><span style=\"color: #292929;\">nihil consequuntur quasi accusamus autem voluptas repellendus </span></li><li><span style=\"color: #292929;\">sapiente quia assumenda sed quam iure asperiores?</span></li></ul><div><span style=\"color: #292929;\">Id fugit ex quod, exercitationem delectus facere consectetur minus </span></div><div><span style=\"color: #292929;\">quia alias necessitatibus repudiandae distinctio veniam nesciunt, debitis sint, </span></div><div><span style=\"color: #292929;\">vitae aliquid eos illum facilis cum odit quibusdam. Suscipit quas enim accusantium?</span></div></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse financière du client</h6><div class=\"mb-4 rich-text-af-section\"><div><span style=\"color: #292929;\"><b>Repellendus repellat sed neque laudantium repreh</b>enderit voluptates dicta iste, eligendi ab necessitatibus perferendis debitis id?</span></div><div><span style=\"color: #292929;\">Rerum, dignissimos iste numquam, nostrum totam ipsam excepturi accusantium impedit architecto aperiam eveniet laborum ipsum cumque voluptatem. Ipsum velit ex nisi illo, eum rem, reprehenderit unde necessitatibus laborum itaque tempore.</span></div><div><span style=\"color: #292929;\">Ipsum voluptatum eius inventore. Sed numquam quam voluptatem officiis nam. Necessitatibus, eveniet iusto ducimus dolorum animi non perspiciatis numquam, blanditiis delectus repellendus earum tempora fugit officia culpa minima vitae assumenda!</span></div><div><span style=\"color: #292929;\">Est ipsum quos, ratione distinctio dignissimos dolores quae repellendus, praesentium iure molestias accusantium ipsam blanditiis, consectetur voluptatibus earu</span></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Appuis financiers et non financiers proposés</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ipsam nobis dolore amet aliquam sequi maiores error corrupti quasi porro reprehenderit aspernatur, autem officia ad vitae laborum, voluptate iusto assumenda.</span></div><div><span style=\"color: #292929;\">Ad omnis laudantium tenetur natus id. Perferendis, facilis praesentium quia, laudantium voluptatibus nostrum aliquid in repellendus soluta qui cumque corporis sapiente dolorem sint, molestias deleniti? Omnis non possimus quibusdam quas.</span></div><div><span style=\"color: #292929;\">Error earum accusamus hic accusantium optio aut voluptate libero fuga, officiis dolorum eius aspernatur, assumenda aperiam quas, possimus velit culpa dolorem ducimus totam eligendi doloremque ex! Explicabo qui deleniti modi.</span></div><div><span style=\"color: #292929;\">Dignissimos doloribus, sunt cupiditate unde officia nostrum, repellendus ullam rerum placeat sed totam molestiae alias nesciunt? Nesciunt rem, natus suscipit, consequatur sit, repudiandae praesentium tempora repellat doloribus quas at eaque!</span></div></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse du risque et de la capacité de remboursement du client</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur nostrum nam itaque. Ab suscipit, quidem qui repudiandae excepturi saepe eum debitis. A dicta sequi nostrum aliquid sint expedita perspiciatis ex?</span></div><div><span style=\"color: #292929;\">Nemo nostrum officia veniam libero atque minima minus odio illum eum sed itaque voluptates nihil sunt, at cupiditate fugit quod, quisquam eligendi rerum impedit. Animi necessitatibus quidem assumenda odio nulla!</span></div><div><span style=\"color: #292929;\">Magni dolor asperiores vitae praesentium ea ducimus inventore facere consectetur. Iure doloremque dignissimos nisi sed a cumque illum odio dicta eveniet! Rem inventore reprehenderit delectus ullam porro, qui tempora dolores?</span></div><div><span style=\"color: #292929;\">Optio perferendis voluptatum ipsa error nemo, assumenda nam quo, dolore labore blanditiis magnam, doloremque saepe necessitatibus temporibus laboriosam laborum in. Aperiam accusantium totam nostrum reiciendis facilis sed. Odio, ducimus voluptatum?</span></div><div><span style=\"color: #292929;\">Repellendus fugiat unde architecto ipsam minus commodi magnam debitis veritatis hic ratione reiciendis aperiam, harum quae ut eaque delectus dolores. Odio, blanditiis. Sint illum non facilis enim. Rerum, placeat consectetur!</span></div><div><span style=\"color: #292929;\">Eius illum itaque laborum atque vel rerum quam ratione ea. Sint placeat nesciunt, qui ex laboriosam autem odio, dignissimos numquam porro eum distinctio et in facere dolores, sapiente molestias alias!</span></div></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Rentabilité de la relation pour l’établissement</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Id odio, blanditiis porro soluta provident molestias. Repellendus repellat sed neque laudantium reprehenderit voluptates dicta iste, eligendi ab necessitatibus perferendis debitis id?</span></div><div><span style=\"color: #292929;\">Rerum, dignissimos iste numquam, nostrum totam ipsam excepturi accusantium impedit architecto aperiam eveniet laborum ipsum cumque voluptatem. Ipsum velit ex nisi illo, eum rem, reprehenderit unde necessitatibus laborum itaque tempore.</span></div><div><span style=\"color: #292929;\">Ipsum voluptatum eius inventore. Sed numquam quam voluptatem officiis nam. Necessitatibus, eveniet iusto ducimus dolorum animi non perspiciatis numquam, blanditiis delectus repellendus earum tempora fugit officia culpa minima vitae assumenda!</span></div><div><span style=\"color: #292929;\">Est ipsum quos, ratione distinctio dignissimos dolores quae repellendus, praesentium iure molestias accusantium ipsam blanditiis, consectetur voluptatibus earum expedita non maxime. Eveniet dolores, accusamus incidunt delectus adipisci consequuntur minima nobis?</span></div></div><p><br></p></div><h6 class=\"small text-uppercase text-muted mb-2\">Conclusions motivées, recommandations de l’analyste financier</h6><div class=\"mb-4 rich-text-af-section\"><div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\"><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, quam, expedita </b></span></div><ol><li><span style=\"color: #292929;\">asperiores voluptas doloremque aut sunt accusamus hic dicta aspernatur numquam ex soluta.</span></li><li><span style=\"color: #292929;\"> Voluptatum ducimus excepturi delectus commodi, perferendis facere?</span></li></ol><div><span style=\"color: #292929;\">Saepe, aut beatae libero et ratione necessitatibus minima assumenda doloremque nihil, tempora voluptatem labore illo eos temporibus expedita cupiditate facilis. Magnam dolorum voluptatum expedita rem dicta natus aliquam, tempore officiis.</span></div><div><span style=\"color: #292929;\">Explicabo aut vel labore pariatur officiis doloremque iure nulla laudantium eligendi exercitationem quae iste est soluta repudiandae maiores velit cumque, eum minus illum ipsum. Eos quam quibusdam culpa molestiae commodi?</span></div><div><span style=\"color: #292929;\">Nihil eius eveniet nam, veniam enim animi qui placeat, mollitia eaque omnis alias iure, facere voluptatibus delectus exercitationem ipsam! Quae ea temporibus, id minima magnam magni nobis consequuntur rerum itaque?</span></div><div><span style=\"color: #292929;\">Eius porro temporibus non saepe facere odio. Qui repudiandae distinctio obcaecati maiores quia sed nostrum velit, itaque est? Sed dicta accusamus excepturi laboriosam repellat quaerat soluta ea eveniet sequi fugiat.</span></div></div><p><br></p></div>', '2026-04-23 22:45:37', NULL, NULL, NULL, NULL, NULL, NULL, 8500000.00, 4000000.00, 'Je suis d\'accord', 'Ceci est mon avis en tant que chef d\'agence. Je trouve le dossier <b>acceptable et recevable</b>', '2026-04-23 19:47:34', 7, '2026-04-23 19:51:19', 7, '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ipsam nobis dolore amet aliquam sequi maiores error corrupti quasi porro reprehenderit aspernatur, autem officia ad vitae laborum, voluptate iusto assumenda.</span></div><div><span style=\"color: #292929;\">Ad omnis laudantium tenetur natus id. Perferendis, facilis praesentium quia, laudantium voluptatibus nostrum aliquid in repellendus soluta qui cumque corporis sapiente dolorem sint, molestias deleniti? Omnis non possimus quibusdam quas.</span></div><div><span style=\"color: #292929;\">Error earum accusamus hic accusantium optio aut voluptate libero fuga, officiis dolorum eius aspernatur, assumenda aperiam quas, possimus velit culpa dolorem ducimus totam eligendi doloremque ex! Explicabo qui deleniti modi.</span></div><div><span style=\"color: #292929;\">Dignissimos doloribus, sunt cupiditate unde officia nostrum, repellendus ullam rerum placeat sed totam molestiae alias nesciunt? Nesciunt rem, natus suscipit, consequatur sit, repudiandae praesentium tempora repellat doloribus quas at eaque!</span></div></div><p><br></p>', '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet, consectetur adip<b>isicing elit. Quod lauda</b>ntium, numquam eaque dicta, soluta tempora amet </span></div><ul><li><span style=\"color: #292929;\">nihil consequuntur quasi accusamus autem voluptas repellendus </span></li><li><span style=\"color: #292929;\">sapiente quia assumenda sed quam iure asperiores?</span></li></ul><div><span style=\"color: #292929;\">Id fugit ex quod, exercitationem delectus facere consectetur minus </span></div><div><span style=\"color: #292929;\">quia alias necessitatibus repudiandae distinctio veniam nesciunt, debitis sint, </span></div><div><span style=\"color: #292929;\">vitae aliquid eos illum facilis cum odit quibusdam. Suscipit quas enim accusantium?</span></div></div><p><br></p>', '<div><span style=\"color: #292929;\"><b>Repellendus repellat sed neque laudantium repreh</b>enderit voluptates dicta iste, eligendi ab necessitatibus perferendis debitis id?</span></div><div><span style=\"color: #292929;\">Rerum, dignissimos iste numquam, nostrum totam ipsam excepturi accusantium impedit architecto aperiam eveniet laborum ipsum cumque voluptatem. Ipsum velit ex nisi illo, eum rem, reprehenderit unde necessitatibus laborum itaque tempore.</span></div><div><span style=\"color: #292929;\">Ipsum voluptatum eius inventore. Sed numquam quam voluptatem officiis nam. Necessitatibus, eveniet iusto ducimus dolorum animi non perspiciatis numquam, blanditiis delectus repellendus earum tempora fugit officia culpa minima vitae assumenda!</span></div><div><span style=\"color: #292929;\">Est ipsum quos, ratione distinctio dignissimos dolores quae repellendus, praesentium iure molestias accusantium ipsam blanditiis, consectetur voluptatibus earu</span></div><p><br></p>', '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ipsam nobis dolore amet aliquam sequi maiores error corrupti quasi porro reprehenderit aspernatur, autem officia ad vitae laborum, voluptate iusto assumenda.</span></div><div><span style=\"color: #292929;\">Ad omnis laudantium tenetur natus id. Perferendis, facilis praesentium quia, laudantium voluptatibus nostrum aliquid in repellendus soluta qui cumque corporis sapiente dolorem sint, molestias deleniti? Omnis non possimus quibusdam quas.</span></div><div><span style=\"color: #292929;\">Error earum accusamus hic accusantium optio aut voluptate libero fuga, officiis dolorum eius aspernatur, assumenda aperiam quas, possimus velit culpa dolorem ducimus totam eligendi doloremque ex! Explicabo qui deleniti modi.</span></div><div><span style=\"color: #292929;\">Dignissimos doloribus, sunt cupiditate unde officia nostrum, repellendus ullam rerum placeat sed totam molestiae alias nesciunt? Nesciunt rem, natus suscipit, consequatur sit, repudiandae praesentium tempora repellat doloribus quas at eaque!</span></div></div><p><br></p>', '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur nostrum nam itaque. Ab suscipit, quidem qui repudiandae excepturi saepe eum debitis. A dicta sequi nostrum aliquid sint expedita perspiciatis ex?</span></div><div><span style=\"color: #292929;\">Nemo nostrum officia veniam libero atque minima minus odio illum eum sed itaque voluptates nihil sunt, at cupiditate fugit quod, quisquam eligendi rerum impedit. Animi necessitatibus quidem assumenda odio nulla!</span></div><div><span style=\"color: #292929;\">Magni dolor asperiores vitae praesentium ea ducimus inventore facere consectetur. Iure doloremque dignissimos nisi sed a cumque illum odio dicta eveniet! Rem inventore reprehenderit delectus ullam porro, qui tempora dolores?</span></div><div><span style=\"color: #292929;\">Optio perferendis voluptatum ipsa error nemo, assumenda nam quo, dolore labore blanditiis magnam, doloremque saepe necessitatibus temporibus laboriosam laborum in. Aperiam accusantium totam nostrum reiciendis facilis sed. Odio, ducimus voluptatum?</span></div><div><span style=\"color: #292929;\">Repellendus fugiat unde architecto ipsam minus commodi magnam debitis veritatis hic ratione reiciendis aperiam, harum quae ut eaque delectus dolores. Odio, blanditiis. Sint illum non facilis enim. Rerum, placeat consectetur!</span></div><div><span style=\"color: #292929;\">Eius illum itaque laborum atque vel rerum quam ratione ea. Sint placeat nesciunt, qui ex laboriosam autem odio, dignissimos numquam porro eum distinctio et in facere dolores, sapiente molestias alias!</span></div></div><p><br></p>', '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Id odio, blanditiis porro soluta provident molestias. Repellendus repellat sed neque laudantium reprehenderit voluptates dicta iste, eligendi ab necessitatibus perferendis debitis id?</span></div><div><span style=\"color: #292929;\">Rerum, dignissimos iste numquam, nostrum totam ipsam excepturi accusantium impedit architecto aperiam eveniet laborum ipsum cumque voluptatem. Ipsum velit ex nisi illo, eum rem, reprehenderit unde necessitatibus laborum itaque tempore.</span></div><div><span style=\"color: #292929;\">Ipsum voluptatum eius inventore. Sed numquam quam voluptatem officiis nam. Necessitatibus, eveniet iusto ducimus dolorum animi non perspiciatis numquam, blanditiis delectus repellendus earum tempora fugit officia culpa minima vitae assumenda!</span></div><div><span style=\"color: #292929;\">Est ipsum quos, ratione distinctio dignissimos dolores quae repellendus, praesentium iure molestias accusantium ipsam blanditiis, consectetur voluptatibus earum expedita non maxime. Eveniet dolores, accusamus incidunt delectus adipisci consequuntur minima nobis?</span></div></div><p><br></p>', '<div style=\"color: #292929;background-color: #ffffff;font-family: Menlo, Monaco, \'Courier New\', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre;\"><div><span style=\"color: #292929;\"><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, quam, expedita </b></span></div><ol><li><span style=\"color: #292929;\">asperiores voluptas doloremque aut sunt accusamus hic dicta aspernatur numquam ex soluta.</span></li><li><span style=\"color: #292929;\"> Voluptatum ducimus excepturi delectus commodi, perferendis facere?</span></li></ol><div><span style=\"color: #292929;\">Saepe, aut beatae libero et ratione necessitatibus minima assumenda doloremque nihil, tempora voluptatem labore illo eos temporibus expedita cupiditate facilis. Magnam dolorum voluptatum expedita rem dicta natus aliquam, tempore officiis.</span></div><div><span style=\"color: #292929;\">Explicabo aut vel labore pariatur officiis doloremque iure nulla laudantium eligendi exercitationem quae iste est soluta repudiandae maiores velit cumque, eum minus illum ipsum. Eos quam quibusdam culpa molestiae commodi?</span></div><div><span style=\"color: #292929;\">Nihil eius eveniet nam, veniam enim animi qui placeat, mollitia eaque omnis alias iure, facere voluptatibus delectus exercitationem ipsam! Quae ea temporibus, id minima magnam magni nobis consequuntur rerum itaque?</span></div><div><span style=\"color: #292929;\">Eius porro temporibus non saepe facere odio. Qui repudiandae distinctio obcaecati maiores quia sed nostrum velit, itaque est? Sed dicta accusamus excepturi laboriosam repellat quaerat soluta ea eveniet sequi fugiat.</span></div></div><p><br></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 265, NULL, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-24 15:01:45', '2026-04-25 08:33:05', 0, 'c860522175a6e0dd82f9954029e9c9c464be18cd', '<p>mon avis est favorable pour la suite</p>', '2026-04-24 15:27:23', 8, 'accord', '2026-04-24 15:27:55', 8, 'ok', '2026-04-24 15:13:34', 8, '2026-04-24 16:24:00', 9, '2026-04-25 08:17:12', 8, 14, '2026-04-25 08:23:06', 4, '<p><b>Ceci est mon avis en tant que responsable juridique.&nbsp;</b></p><p>Je trouve le dossier très intéressant et conforme a toutes les exigences en terme de :</p><ul><li>Les pièces juridiques&nbsp;</li><li>L’authenticité des pièces soumises</li></ul>', '2026-04-25 08:26:27', 14, '<p>Ceci est mon avis en que véritable responsable juridique de la <b>banque</b></p>', '2026-04-25 08:33:05', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-24 15:01:45', 6, '2026-04-24 15:04:34', 7, NULL, NULL, NULL, '<h6 class=\"small text-uppercase text-muted mb-2\">Brèves données générales actualisées sur le client</h6><div class=\"mb-4 rich-text-af-section\"><p>ghsdghds</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse critique d’ensemble</h6><div class=\"mb-4 rich-text-af-section\"><p>bnhjjkjk</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse financière du client</h6><div class=\"mb-4 rich-text-af-section\"><p>&nbsp;iuiuoippopo</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Appuis financiers et non financiers proposés</h6><div class=\"mb-4 rich-text-af-section\"><p>fdfdfkdf</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse du risque et de la capacité de remboursement du client</h6><div class=\"mb-4 rich-text-af-section\"><p>&nbsp;ghhjjjkjkj</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Rentabilité de la relation pour l’établissement</h6><div class=\"mb-4 rich-text-af-section\"><p>&nbsp;gjhjhkjkj</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Conclusions motivées, recommandations de l’analyste financier</h6><div class=\"mb-4 rich-text-af-section\"><p>&nbsp;yjhhkjk</p></div>', '2026-04-24 15:24:00', NULL, NULL, '2026-04-25 08:26:27', 14, '2026-04-25 08:32:46', 4, 120000000.00, 7500000.00, 'Je suis ok', NULL, NULL, NULL, NULL, NULL, '<p>ghsdghds</p>', '<p>bnhjjkjk</p>', '<p>&nbsp;iuiuoippopo</p>', '<p>fdfdfkdf</p>', '<p>&nbsp;ghhjjjkjkj</p>', '<p>&nbsp;gjhjhkjkj</p>', '<p>&nbsp;yjhhkjk</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 265, NULL, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-30 12:46:18', '2026-04-30 14:00:30', 0, '84962be486bb4c39491fa2593e565ce6a83c9e4b', '<p>Un petit avis concernant ce dossier d\'intruction</p>', '2026-04-30 13:30:52', 8, 'accord', '2026-04-30 13:31:19', 8, 'petit commentaire de plus', '2026-04-30 13:00:30', 8, '2026-04-30 14:16:08', 9, '2026-04-30 13:31:32', 8, 14, '2026-04-30 13:34:55', 4, '<p>Mon avis en tant qu\'analyste juridique</p>', '2026-04-30 13:37:11', 14, '<p>Mon avis en tant responsable juridique</p>', '2026-04-30 13:38:45', 4, 13, '2026-04-30 13:42:22', 10, '<p>Ma contre analyse&nbsp;</p><ol><li>anomalie&nbsp;</li><li>anomalie</li><li>etc...</li></ol>', '<p>Mon avis en tant qu\'analyste credit</p>', NULL, '2026-04-30 13:44:57', 13, '<p>Mon avis en tant responsable des engagements</p>', '2026-04-30 13:45:56', 10, '2026-04-30 13:46:08', 10, 12, '2026-04-30 13:47:56', 11, '<p>Petite analyse des risques ici ...</p><ol><li>risque 1</li><li>risque 2</li><li>etc....</li></ol>', '<p>Mon avis en tant qu\'analyste risque</p>', '2026-04-30 13:49:46', 12, '<p>Mon avis en tant responsable risque</p>', '2026-04-30 13:50:49', 11, '2026-04-30 13:51:07', 11, '2026-04-30 12:46:18', 6, '2026-04-30 12:51:07', 7, NULL, NULL, NULL, '<h6 class=\"small text-uppercase text-muted mb-2\">Brèves données générales actualisées sur le client</h6><div class=\"mb-4 rich-text-rendered\"><p>Le client est intéressant</p><ul><li>un</li><li>deux</li><li>trois&nbsp;</li></ul></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse critique d’ensemble</h6><div class=\"mb-4 rich-text-rendered\"><p>Je saisis l\'analyse d\'ensemble</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse financière du client</h6><div class=\"mb-4 rich-text-rendered\"><p>Ici l\'analyse financière&nbsp;</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Appuis financiers et non financiers proposés</h6><div class=\"mb-4 rich-text-rendered\"><p>Je propose ici les appuis</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse du risque et de la capacité de remboursement du client</h6><div class=\"mb-4 rich-text-rendered\"><p>J’évalue le risque ici</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Rentabilité de la relation pour l’établissement</h6><div class=\"mb-4 rich-text-rendered\"><p>Ici la rentabilité&nbsp;</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Conclusions motivées, recommandations de l’analyste financier</h6><div class=\"mb-4 rich-text-rendered\"><p>Petite conclusion</p></div>', '2026-04-30 13:16:08', NULL, NULL, '2026-04-30 13:37:11', 14, '2026-04-30 13:38:31', 4, 65000000.00, 40000000.00, 'Note du CA, ok okoko', '<p>Mon avis en tant chef d\'agence</p><p><b>une petite liste&nbsp;</b></p><ol><li>un&nbsp;</li><li>deux</li><li>trois</li></ol>', '2026-04-30 12:52:21', 7, '2026-04-30 12:52:42', 7, '<p>Le client est intéressant</p><ul><li>un</li><li>deux</li><li>trois&nbsp;</li></ul>', '<p>Je saisis l\'analyse d\'ensemble</p>', '<p>Ici l\'analyse financière&nbsp;</p>', '<p>Je propose ici les appuis</p>', '<p>J’évalue le risque ici</p>', '<p>Ici la rentabilité&nbsp;</p>', '<p>Petite conclusion</p>', '2026-04-30 14:00:30', 15, NULL, NULL, NULL, 'Petite note finale pour clôturer ce dossier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 266, NULL, 9, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-06 12:37:25', '2026-05-06 13:00:30', 0, '6ab2500900cda689cbca72b351577b8601a841b2', '<p>Je decide rejeter a ce stade</p>', '2026-05-06 13:00:13', 8, 'rejet', '2026-05-06 13:00:30', 8, 'non complet', '2026-05-06 12:54:42', 8, '2026-05-06 13:56:13', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-06 12:52:10', 6, '2026-05-06 12:53:37', 7, NULL, NULL, NULL, '<h6 class=\"small text-uppercase text-muted mb-2\">Brèves données générales actualisées sur le client</h6><div class=\"mb-4 rich-text-rendered\"><p>jfjekjfle</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse critique d’ensemble</h6><div class=\"mb-4 rich-text-rendered\"><p>klrkler</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse financière du client</h6><div class=\"mb-4 rich-text-rendered\"><p>rkelkerl</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Appuis financiers et non financiers proposés</h6><div class=\"mb-4 rich-text-rendered\"><p>rklkerl</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Analyse du risque et de la capacité de remboursement du client</h6><div class=\"mb-4 rich-text-rendered\"><p>rklekerl</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Rentabilité de la relation pour l’établissement</h6><div class=\"mb-4 rich-text-rendered\"><p>rekller</p></div><h6 class=\"small text-uppercase text-muted mb-2\">Conclusions motivées, recommandations de l’analyste financier</h6><div class=\"mb-4 rich-text-rendered\"><p>klreklrelk</p></div>', '2026-05-06 12:56:13', NULL, NULL, NULL, NULL, NULL, NULL, 35000000.00, 13000000.00, 'Je valide ce dossier', NULL, NULL, NULL, NULL, NULL, '<p>jfjekjfle</p>', '<p>klrkler</p>', '<p>rkelkerl</p>', '<p>rklkerl</p>', '<p>rklekerl</p>', '<p>rekller</p>', '<p>klreklrelk</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `dossier_analyse_critiques`
--

CREATE TABLE `dossier_analyse_critiques` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `synthese` longtext COLLATE utf8mb4_unicode_ci,
  `statut` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_analyse_critiques`
--

INSERT INTO `dossier_analyse_critiques` (`id`, `entreprise_id`, `token`, `synthese`, `statut`, `created_at`, `updated_at`) VALUES
(1, 261, '040d4e4d74e0547e6a6ea3a0f3989761f1c04181', NULL, 'brouillon', '2026-04-18 18:32:12', '2026-04-18 18:32:12'),
(2, 263, '0c01828cb6356c03aa9d3270ec791c9c49f6e006', NULL, 'brouillon', '2026-04-21 12:16:13', '2026-04-21 12:16:13'),
(3, 265, 'edd515927fb47b6b1b9021e6b498f9bad04d82a5', NULL, 'brouillon', '2026-04-24 13:59:24', '2026-04-24 13:59:24'),
(4, 266, 'e9985c54aa3e2a0c20eee80f71a39964feb47bbd', NULL, 'brouillon', '2026-04-29 15:31:50', '2026-04-29 15:31:50'),
(5, 267, '1bbea1484aae8e3ca4e20c5d221739709774c162', NULL, 'brouillon', '2026-05-06 10:15:24', '2026-05-06 10:15:24');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_entree_relations`
--

CREATE TABLE `dossier_entree_relations` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `analyse_strategique` longtext COLLATE utf8mb4_unicode_ci,
  `analyse_operationnelle` longtext COLLATE utf8mb4_unicode_ci,
  `analyse_eligibilite` longtext COLLATE utf8mb4_unicode_ci,
  `identification_besoins` longtext COLLATE utf8mb4_unicode_ci,
  `besoin_financement` tinyint(1) NOT NULL DEFAULT '0',
  `besoin_accompagnement` tinyint(1) NOT NULL DEFAULT '0',
  `besoin_structuration` tinyint(1) NOT NULL DEFAULT '0',
  `qualification_notes` longtext COLLATE utf8mb4_unicode_ci,
  `qualification_completed_at` timestamp NULL DEFAULT NULL,
  `qualification_user_id` bigint UNSIGNED DEFAULT NULL,
  `programmes_submitted_at` timestamp NULL DEFAULT NULL,
  `programmes_submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_validation_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `instruction_validated_at` timestamp NULL DEFAULT NULL,
  `instruction_validated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `qualification_validated_by_agence_at` timestamp NULL DEFAULT NULL,
  `qualification_validated_by_agence_user_id` bigint UNSIGNED DEFAULT NULL,
  `qualification_rejected_by_agence_at` timestamp NULL DEFAULT NULL,
  `qualification_rejected_by_agence_user_id` bigint UNSIGNED DEFAULT NULL,
  `qualification_reject_motif` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `instruction_bundle_submitted_at` timestamp NULL DEFAULT NULL,
  `instruction_bundle_submitted_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_bundle_validated_at` timestamp NULL DEFAULT NULL,
  `instruction_bundle_validated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_bundle_dossier_id` int UNSIGNED DEFAULT NULL,
  `instruction_bundle_rejected_at` timestamp NULL DEFAULT NULL,
  `instruction_bundle_rejected_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `instruction_bundle_reject_motif` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_entree_relations`
--

INSERT INTO `dossier_entree_relations` (`id`, `entreprise_id`, `token`, `statut`, `submitted_at`, `submitted_by_user_id`, `analyse_strategique`, `analyse_operationnelle`, `analyse_eligibilite`, `identification_besoins`, `besoin_financement`, `besoin_accompagnement`, `besoin_structuration`, `qualification_notes`, `qualification_completed_at`, `qualification_user_id`, `programmes_submitted_at`, `programmes_submitted_by_user_id`, `instruction_validation_status`, `instruction_validated_at`, `instruction_validated_by_user_id`, `qualification_validated_by_agence_at`, `qualification_validated_by_agence_user_id`, `qualification_rejected_by_agence_at`, `qualification_rejected_by_agence_user_id`, `qualification_reject_motif`, `created_at`, `updated_at`, `instruction_bundle_submitted_at`, `instruction_bundle_submitted_by_user_id`, `instruction_bundle_validated_at`, `instruction_bundle_validated_by_user_id`, `instruction_bundle_dossier_id`, `instruction_bundle_rejected_at`, `instruction_bundle_rejected_by_user_id`, `instruction_bundle_reject_motif`) VALUES
(1, 261, 'db45e8af1fb9b01ed05145f2cab2e670bc360408', 'qualification_validee_agence', '2026-04-18 22:05:57', 2, '<p>Ceci est une petite an<b>alyse stratégiqu</b>e pour le dossier de ce client</p>', '<p>Ceci est une petite analyse opérationnelle pour le dossier du client alliages technologies&nbsp;</p>', 'Ici se trouve la section liée aux critères d\'eligibilite&nbsp;', '<p>Nous voici dans la section identification et notes pour compléter notre qualification</p>', 1, 1, 0, '<p>Ici sont lister les notes de fin</p><ol><li>note a</li><li>note b</li><li>note c</li><li>etc ...</li></ol><p><b>C\'est la fin.</b></p>', '2026-04-19 19:01:18', 6, '2026-04-19 19:02:08', 6, 'qualification_validee_agence', NULL, NULL, '2026-04-19 19:37:00', 7, NULL, NULL, NULL, '2026-04-19 15:07:33', '2026-04-19 19:37:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 263, 'c41a24bfb51f95b10f85404f8fd8538bb4f0eb40', 'qualification_validee_agence', '2026-04-21 12:35:18', 2, '<p>analyse strategique</p>', '<p>Petite analyse operationnelle</p>', '<p>Petite saisie sur l\'eligibilite</p>', '<p>Quelques besoins:</p><ul><li>premier besoin</li><li>deuxieme</li><li>troisieme</li></ul>', 1, 0, 1, '<p>Quelques notes de qualification</p>', '2026-04-21 12:59:14', 6, '2026-04-21 12:59:32', 6, 'qualification_validee_agence', NULL, NULL, '2026-04-21 13:01:17', 7, NULL, NULL, NULL, '2026-04-21 12:51:23', '2026-04-21 20:08:13', '2026-04-21 20:08:13', 6, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 265, '556d4bb932099bf56883e0571f3c417b2a0afdee', 'qualification_validee_agence', '2026-04-24 13:56:18', 2, '<p>gjfdhjfd hfdjhfd nfdknk</p>', '<p>fdjfd fdnnfdknfd</p>', '<p>fdkjdfk fdknkndf nfkdnkfd nfkndf</p>', '<p>fdjdfjkjfd kfndnfd nfdnkdf ndfkndkf nkdfnknfdk ndfknkdfn</p>', 0, 1, 1, '<p>hrjrh brknkrnrk nreknkre</p>', '2026-04-24 14:49:41', 6, '2026-04-24 14:51:50', 6, 'qualification_validee_agence', NULL, NULL, '2026-04-24 14:55:30', 7, NULL, NULL, NULL, '2026-04-24 14:41:18', '2026-04-24 14:55:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 266, 'f6ea077b0733778efd527401ef38476e6bcb54d8', 'qualification_validee_agence', '2026-05-06 10:28:53', 2, '<p>analyse strategique ici</p>', '<p>Analyse op ici apres refus</p>', '<p>Elig ici apres refus</p>', '<p>Identif.&nbsp; apres refus</p>', 1, 1, 0, '<p>Notes de struct. apres refus</p>', '2026-05-06 12:34:43', 6, '2026-05-06 12:34:50', 6, 'qualification_validee_agence', NULL, NULL, '2026-05-06 12:36:12', 7, NULL, NULL, NULL, '2026-05-06 10:33:32', '2026-05-06 12:36:12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `dossier_entree_relation_programmes`
--

CREATE TABLE `dossier_entree_relation_programmes` (
  `id` bigint UNSIGNED NOT NULL,
  `dossier_entree_relation_id` bigint UNSIGNED NOT NULL,
  `programme_id` int NOT NULL,
  `type_appui` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'financier',
  `statut` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'propose',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `instruction_dossier_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_entree_relation_programmes`
--

INSERT INTO `dossier_entree_relation_programmes` (`id`, `dossier_entree_relation_id`, `programme_id`, `type_appui`, `statut`, `notes`, `submitted_at`, `validated_at`, `instruction_dossier_id`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 'mixte', 'valide', NULL, NULL, '2026-04-19 19:44:34', 31, '2026-04-19 19:44:34', '2026-04-19 19:44:34'),
(5, 2, 1, 'mixte', 'valide', NULL, NULL, '2026-04-21 13:02:55', 32, '2026-04-21 13:02:55', '2026-04-21 13:02:55'),
(6, 2, 2, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 1 500 000 XAF ; non financier : 3 000 000 XAF', '2026-04-21 21:23:49', '2026-04-21 21:25:47', 35, '2026-04-21 20:08:13', '2026-04-21 21:25:47'),
(7, 2, 3, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 6 000 000 XAF ; non financier : 5 000 000 XAF', '2026-04-21 21:23:49', '2026-04-21 21:25:47', 35, '2026-04-21 20:08:13', '2026-04-21 21:25:47'),
(8, 1, 3, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 5 000 000 XAF ; non financier : 3 500 000 XAF', '2026-04-23 18:26:18', '2026-04-23 19:31:59', 36, '2026-04-23 18:26:18', '2026-04-23 19:31:59'),
(9, 3, 1, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 25 000 000 XAF ; non financier : 3 000 000 XAF', '2026-04-24 15:01:45', '2026-04-24 15:04:34', 37, '2026-04-24 15:01:45', '2026-04-24 15:04:34'),
(10, 3, 2, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 7 000 000 XAF ; non financier : 1 000 000 XAF', '2026-04-24 15:01:45', '2026-04-24 15:04:34', 37, '2026-04-24 15:01:45', '2026-04-24 15:04:34'),
(11, 3, 3, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 5 000 000 XAF ; non financier : 3 000 000 XAF', '2026-04-30 12:46:18', '2026-04-30 12:51:07', 38, '2026-04-30 12:46:18', '2026-04-30 12:51:07'),
(12, 4, 1, 'mixte', 'valide', 'Budgets d\'appui proposés — financier : 120 000 000 XAF ; non financier : 20 000 000 XAF', '2026-05-06 12:52:10', '2026-05-06 12:53:37', 39, '2026-05-06 12:37:25', '2026-05-06 12:53:37');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_instruction_programmes`
--

CREATE TABLE `dossier_instruction_programmes` (
  `id` bigint UNSIGNED NOT NULL,
  `dossier_id` int UNSIGNED NOT NULL,
  `programme_id` int NOT NULL,
  `budget_appui_financier` decimal(15,2) NOT NULL DEFAULT '0.00',
  `budget_appui_non_financier` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_instruction_programmes`
--

INSERT INTO `dossier_instruction_programmes` (`id`, `dossier_id`, `programme_id`, `budget_appui_financier`, `budget_appui_non_financier`, `sort_order`, `created_at`, `updated_at`) VALUES
(3, 35, 2, 1500000.00, 3000000.00, 0, '2026-04-21 21:23:49', '2026-04-21 21:23:49'),
(4, 35, 3, 6000000.00, 5000000.00, 1, '2026-04-21 21:23:49', '2026-04-21 21:23:49'),
(5, 36, 3, 5000000.00, 3500000.00, 0, '2026-04-23 18:26:18', '2026-04-23 18:26:18'),
(6, 37, 1, 25000000.00, 3000000.00, 0, '2026-04-24 15:01:45', '2026-04-24 15:01:45'),
(7, 37, 2, 7000000.00, 1000000.00, 1, '2026-04-24 15:01:45', '2026-04-24 15:01:45'),
(8, 38, 3, 5000000.00, 3000000.00, 0, '2026-04-30 12:46:18', '2026-04-30 12:46:18'),
(10, 39, 1, 120000000.00, 20000000.00, 0, '2026-05-06 12:52:10', '2026-05-06 12:52:10');

-- --------------------------------------------------------

--
-- Structure de la table `elements_constitutifs_types`
--

CREATE TABLE `elements_constitutifs_types` (
  `id` int NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `elements_constitutifs_types`
--

INSERT INTO `elements_constitutifs_types` (`id`, `name`, `active`) VALUES
(1, 'Dossier juridique et administratif', 1),
(2, 'Dossier financier', 1),
(3, 'Dossier Pouvoirs et Signatures', 1),
(4, 'Dossier des garanties', 1),
(5, 'Dossierdesuividescautionsreçues', 1),
(6, 'Dossier de suivi des relations avec les parties prenantes', 1),
(7, 'Dossierdesengagements', 1),
(8, 'Dossier des correspondances avec les partenaires financiers', 1),
(9, 'Dossier des réclamations adressées aux partenaires financiers', 1),
(10, 'Dossier Divers', 1);

-- --------------------------------------------------------

--
-- Structure de la table `engagements`
--

CREATE TABLE `engagements` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `niveau` int DEFAULT '0',
  `parent_id` int DEFAULT '0',
  `is_title` tinyint(1) DEFAULT '1',
  `is_leaf` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `engagements`
--

INSERT INTO `engagements` (`id`, `name`, `niveau`, `parent_id`, `is_title`, `is_leaf`, `created_at`, `updated_at`) VALUES
(1, 'EMPRUNTS BANCAIRES', 0, 0, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(2, 'INVESTISSEMENTS DIRECTS', 0, 0, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(3, 'COMPENSATION DES BIENS ET SERVICES', 0, 0, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(4, 'GARANTIES', 0, 0, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(5, 'Crédits courants en FCFA', 1, 1, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(6, 'Crédits à Moyen et long termes en FCFA', 1, 1, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(7, 'Engagements par signature en FCFA', 1, 1, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(8, 'Nature des investissements en FCFA', 1, 2, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(9, 'Nature et montant des produits compensés en FCFA', 1, 3, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(10, 'Nature des garanties en FCFA', 1, 4, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(11, 'Nature des garanties en FCFA', 1, 4, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(12, 'Mobilisation de créances', 2, 5, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(13, 'Facilités de caisse', 2, 5, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(14, 'Engagements par signature donnés à l\'entreprise', 2, 7, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(15, 'Engagements par signature reçus de l\'entreprise', 2, 7, 1, 0, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(16, 'Escompte Effets', 3, 12, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(17, 'Affacturage', 3, 12, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(18, 'Autres', 3, 12, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(19, 'Découvert', 3, 13, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(20, 'Crédit spot', 3, 13, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(21, 'Crédit amortissable', 3, 13, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(22, 'Autres', 3, 13, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(23, 'Emprunt à moyen terme', 2, 6, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(24, 'Emprunt à long terme', 2, 6, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(25, 'Crédit-bail', 2, 6, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(26, 'Engagements de financement', 3, 14, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(27, 'Engagements de garantie', 3, 14, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(28, 'Engagements sur titre', 3, 14, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(29, 'Engagements de financement', 3, 15, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(30, 'Engagements de garantie dont hypothèque', 3, 15, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(31, 'Engagements sur titre', 3, 15, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(32, 'Prise de participation', 2, 8, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(33, 'Capital-risque', 2, 8, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(34, 'Obligations', 2, 8, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(35, 'Subventions d\'investissements', 2, 8, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(36, 'Marchandises produites localement', 2, 9, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(37, 'Marchandises importées', 2, 9, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(38, 'Produits fabriqués (localement)', 2, 9, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(39, 'Services', 2, 9, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(40, 'Cautionnement mutuel', 2, 10, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(41, 'Garantie hypothécaire', 2, 10, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(42, 'Garantie souveraine', 2, 10, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(43, 'Garantie assurance', 2, 10, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(44, 'Contregarantie bancaire', 2, 10, 0, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05');

-- --------------------------------------------------------

--
-- Structure de la table `engagement_entreprises`
--

CREATE TABLE `engagement_entreprises` (
  `id` int UNSIGNED NOT NULL,
  `entreprise_id` int DEFAULT '0',
  `banque_id` int DEFAULT '0',
  `engagement_id` int DEFAULT '0',
  `montant` double DEFAULT '0',
  `encours_montant` double DEFAULT '0',
  `encours_part` double DEFAULT '0',
  `encours_impaye` double DEFAULT '0',
  `encours_dt_validite` date DEFAULT NULL,
  `sollicite_montant` double DEFAULT '0',
  `sollicite_part` double DEFAULT '0',
  `sollicite_dt_validite` date DEFAULT NULL,
  `total` double DEFAULT '0',
  `nb_part` double DEFAULT '0',
  `dt_validite` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `engagement_entreprises`
--

INSERT INTO `engagement_entreprises` (`id`, `entreprise_id`, `banque_id`, `engagement_id`, `montant`, `encours_montant`, `encours_part`, `encours_impaye`, `encours_dt_validite`, `sollicite_montant`, `sollicite_part`, `sollicite_dt_validite`, `total`, `nb_part`, `dt_validite`, `created_at`, `updated_at`) VALUES
(14, 261, 2, 16, 0, 40000, 0, 12000, '2026-04-24', 50000, 0, '2026-04-24', 0, 0, NULL, '2026-04-21 07:59:56', '2026-04-21 07:59:56');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rccm` varchar(20) DEFAULT NULL,
  `niu` varchar(20) DEFAULT NULL,
  `cnps` varchar(20) DEFAULT NULL,
  `mm_phone` varchar(20) DEFAULT NULL,
  `taille` varchar(20) DEFAULT NULL,
  `caractere` enum('Formel','Informel') DEFAULT NULL,
  `forme_id` int DEFAULT NULL,
  `systeme` enum('Normal','Minimal') NOT NULL,
  `capital` double DEFAULT NULL,
  `chiffre_affaire` double DEFAULT NULL,
  `ressources_propres` double DEFAULT NULL,
  `total_actif` double DEFAULT NULL,
  `nb_personnel` int DEFAULT NULL,
  `nb_personnel_permanent` int DEFAULT NULL,
  `nb_personnel_saisonier` int DEFAULT NULL,
  `personnel_permanent` tinyint(1) NOT NULL DEFAULT '0',
  `personnel_saisonier` tinyint(1) NOT NULL DEFAULT '0',
  `personnel_mixte` tinyint(1) NOT NULL DEFAULT '0',
  `dt_creation` date DEFAULT NULL,
  `dt_start` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `manager` varchar(155) DEFAULT NULL,
  `manager_contact` varchar(255) DEFAULT NULL,
  `manager_sexe` enum('Homme','Femme') DEFAULT NULL,
  `manager_niveau` enum('Supérieur','Secondaire','Primaire','Sans niveau') DEFAULT NULL,
  `manager_promoteur` tinyint(1) DEFAULT NULL,
  `manager_dtn` date DEFAULT NULL,
  `produit_id` int DEFAULT NULL,
  `produit_year_start` int DEFAULT NULL,
  `filiere_id` int NOT NULL DEFAULT '0',
  `branche_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `gestionnaire_id` int NOT NULL DEFAULT '0',
  `agence_id` int NOT NULL DEFAULT '0',
  `representation_id` int NOT NULL DEFAULT '0',
  `region_id` int DEFAULT NULL,
  `departement_id` int DEFAULT NULL,
  `arrondissement_id` int DEFAULT NULL,
  `village_ou_quartier` varchar(255) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `village_id` int NOT NULL DEFAULT '0',
  `quartier_id` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `prospect` tinyint(1) NOT NULL DEFAULT '0',
  `programme_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `producteur_id` int NOT NULL DEFAULT '0',
  `verger_id` int NOT NULL DEFAULT '0',
  `cooperative_id` int NOT NULL DEFAULT '0',
  `prospect_submitted_at` timestamp NULL DEFAULT NULL,
  `juridique_avis` text,
  `juridique_avis_at` timestamp NULL DEFAULT NULL,
  `juridique_avis_user_id` int UNSIGNED DEFAULT NULL,
  `conformite_avis` text,
  `conformite_avis_at` timestamp NULL DEFAULT NULL,
  `conformite_avis_user_id` int UNSIGNED DEFAULT NULL,
  `promu_client_at` timestamp NULL DEFAULT NULL,
  `promu_client_user_id` int UNSIGNED DEFAULT NULL,
  `prospect_rejected_at` timestamp NULL DEFAULT NULL,
  `prospect_rejected_user_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `name`, `rccm`, `niu`, `cnps`, `mm_phone`, `taille`, `caractere`, `forme_id`, `systeme`, `capital`, `chiffre_affaire`, `ressources_propres`, `total_actif`, `nb_personnel`, `nb_personnel_permanent`, `nb_personnel_saisonier`, `personnel_permanent`, `personnel_saisonier`, `personnel_mixte`, `dt_creation`, `dt_start`, `email`, `phone`, `manager`, `manager_contact`, `manager_sexe`, `manager_niveau`, `manager_promoteur`, `manager_dtn`, `produit_id`, `produit_year_start`, `filiere_id`, `branche_id`, `user_id`, `gestionnaire_id`, `agence_id`, `representation_id`, `region_id`, `departement_id`, `arrondissement_id`, `village_ou_quartier`, `latitude`, `longitude`, `village_id`, `quartier_id`, `created_at`, `updated_at`, `token`, `prospect`, `programme_id`, `parent_id`, `producteur_id`, `verger_id`, `cooperative_id`, `prospect_submitted_at`, `juridique_avis`, `juridique_avis_at`, `juridique_avis_user_id`, `conformite_avis`, `conformite_avis_at`, `conformite_avis_user_id`, `promu_client_at`, `promu_client_user_id`, `prospect_rejected_at`, `prospect_rejected_user_id`) VALUES
(261, 'Alliages Technologies', 'RC/YAO/2025/B/120', 'M1236723787293A', '6833232442', '653432224', 'PETITE', 'Formel', 4, 'Minimal', 500000, 430000, 210000, 340000, 4, 3, 1, 0, 1, 0, '2026-01-12', '2026-03-02', 'info@alt.cm', '655326562', 'Essomba Clement', '662388238', 'Homme', 'Secondaire', 1, '2008-04-14', 14, 4, 0, 0, 2, 2, 1, 1, 2, 12, 49, NULL, '3.456433', '12.3348943', 0, 0, '2026-04-18 19:29:17', '2026-04-19 16:07:33', '26f8b55e2877e8bdafb8baed865d4fb65b516bee', 0, 0, 0, 0, 0, 0, '2026-04-18 22:05:57', '<p>Ceci est mo<i>n avis sur </i>ce dossier</p><p>C<u>ette </u><u>partie<b> est</b></u><b><u> p</u>artiellement en gras</b></p><p>Ceci est une liste:</p><ul><li>une</li><li>deux</li></ul><p><b><br></b></p>', '2026-04-19 13:41:08', 4, '<p>Ceci est l\'avis du responsable conformite.</p><p>ci-dessous quelques points d\'attention:</p><ul><li>point 1</li><li>point 2</li><li>point3</li><li>point 4</li></ul>', '2026-04-19 13:59:56', 5, '2026-04-19 15:07:33', 7, NULL, NULL),
(262, 'Angara Solutions', 'RC/YAO/2025/B/120', 'M1238904590', '56768787887989', '658938292', 'MOYENNE', 'Formel', 1, 'Normal', 1000000, 10000000, 15000000, 20000000, 5, 3, 2, 0, 0, 1, '2019-08-23', '2022-01-01', 'info@walama', '696784743', 'Eloundou Ngah', '677735635625363', 'Homme', 'Supérieur', 1, '1987-04-19', 458, 5, 0, 0, 2, 2, 1, 1, 2, 15, 85, 'Longkak', '3.456432', '11.4345455', 0, 0, '2026-04-19 06:31:56', '2026-04-29 15:01:23', '7636ac202c3338a497b84e1757fe4ea1985f1783', 1, 0, 0, 0, 0, 0, '2026-04-29 14:01:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(263, 'Angara certifications', 'RC/YAO/2024/B/123', 'M123456789012B', '6676238832', '676787996', 'PETITE', 'Formel', 1, 'Normal', 60000000, 45000000, 300000, 5000000, 5, 3, 2, 0, 0, 1, '2009-02-20', '2015-03-12', NULL, '664838743', 'Eloundou Ngah', '687823773', 'Homme', 'Supérieur', 1, '2007-05-23', 11, 5, 0, 0, 2, 2, 1, 1, 2, 15, 85, 'Mballa II', NULL, NULL, 0, 0, '2026-04-21 12:56:56', '2026-04-21 13:51:23', '6b0947b06a88d4783971422836f2deedf5382a14', 0, 0, 0, 0, 0, 0, '2026-04-21 12:35:18', '<p>Ceci<b> est mo</b>n avis</p><ul><li>Un&nbsp;</li><li>deux</li><li>trois</li></ul>', '2026-04-21 12:36:24', 4, '<p><b><u>Avis de le conformité.</u></b></p><ol><li>un</li><li>deux</li><li>trois</li><li>quatre&nbsp;</li></ol>', '2026-04-21 12:38:14', 5, '2026-04-21 12:51:23', 7, NULL, NULL),
(264, 'Walama', 'RC/YAO/2025/B/189', 'M1234567890134', '6768237823', '656789832', 'GRANDE', 'Formel', 1, 'Normal', 1000000, 60000000, 120000000, 514000000, 15, 10, 5, 0, 0, 1, '2010-06-12', '2019-02-01', 'contact@walama.cm', '678986765', 'Hamed Soho', '653232123', 'Homme', 'Supérieur', 0, '1980-05-30', 8, 5, 0, 0, 2, 2, 1, 1, 1, 5, 15, 'Tidjem', '3.456432', '12.3348943', 0, 0, '2026-04-23 17:35:18', '2026-04-24 01:46:04', '2b42499e19a8f6488e943b034d454475bf4a4213', 1, 0, 0, 0, 0, 0, '2026-04-24 00:46:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(265, 'GIC LE MANIOC PLUS', 'RC/YAO/2024/B/139', 'M1238904590', '5463764387834', '654736747', 'MOYENNE', 'Formel', 1, 'Normal', 4500000, 4200000, 2300000, 3200000, 30, 24, 6, 0, 0, 1, '2022-09-11', '2024-06-12', NULL, '667843783', 'KAMGA ARNAUD', '656567878', 'Homme', 'Supérieur', 1, '1994-04-10', 8, 7, 0, 0, 2, 2, 1, 1, 2, 11, 41, 'LOBO', '3.456432', '12.3348943', 0, 0, '2026-04-24 14:37:33', '2026-04-24 15:41:18', '214793ca0de5af00e57006879ef81754cd8ef401', 0, 0, 0, 0, 0, 0, '2026-04-24 13:56:18', '<p><b>un petit texte en gras</b></p><p><b>une liste:</b></p><ul><li><b>un&nbsp;</b></li><li>deux</li><li>trois</li></ul><p><br></p>', '2026-04-24 13:59:24', 4, '<p>Mon avis en tant q<b>ue respons</b>able conformité&nbsp;</p>', '2026-04-24 14:35:13', 5, '2026-04-24 14:41:18', 7, NULL, NULL),
(266, 'CTECH', 'RC/YAO/2024/B/123', 'M1238904590', '56768787887989', '658938292', 'GRANDE', 'Formel', 6, 'Normal', 500000, 12000000, 10000000, 30000000, 7, 4, 3, 0, 0, 1, '2020-09-23', '2023-10-20', 'info@alt.cm', '683934344', 'Waffo Alain', '64387434943', 'Homme', 'Supérieur', 1, '1990-11-12', 16, 3, 0, 0, 2, 2, 1, 1, 5, 28, 179, 'MAKEPE', '3.456432', '12.3348943', 0, 0, '2026-04-29 15:11:54', '2026-05-06 11:33:32', 'd7fd0901e50314afff53ab15359d235556947614', 0, 0, 0, 0, 0, 0, '2026-05-06 10:28:53', '<p>mon avis en tant resp. jur.</p>', '2026-05-06 10:32:26', 4, '<p>mon avis en tant que resp. conf.</p>', '2026-05-06 10:31:18', 5, '2026-05-06 10:33:32', 7, NULL, NULL),
(267, 'EBOUTANG', 'RC/YAO/2024/B/159', 'M1234567890135', '5463764387834', '676787996', 'MOYENNE', 'Formel', 6, 'Normal', 700000, 5700000, 4300000, 5450000, 8, 4, 4, 0, 0, 1, '2020-09-23', '2024-09-21', 'contact@eboutang.cm', '664838743', 'remi ayayos', '67489389', 'Homme', 'Supérieur', 1, '1980-08-12', 9, 6, 0, 0, 2, 2, 1, 1, 3, 19, 122, NULL, '3.456433', '12.3348943', 0, 0, '2026-05-06 11:13:12', '2026-05-06 11:17:28', '21860f69fd4b1b5b8a282139ac5f06e3b684a4a3', 0, 0, 0, 0, 0, 0, '2026-05-06 10:14:05', '<p>Mon avis en tant responsable juridique</p>', '2026-05-06 10:15:24', 4, '<p>Mon avis en tant que responsable conformite</p>', '2026-05-06 10:16:09', 5, NULL, NULL, '2026-05-06 10:17:28', 7);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises_elements_constitutifs`
--

CREATE TABLE `entreprises_elements_constitutifs` (
  `id` int NOT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `uri` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprises_elements_constitutifs`
--

INSERT INTO `entreprises_elements_constitutifs` (`id`, `entreprise_id`, `type_id`, `uri`, `active`, `created_at`, `updated_at`, `token`) VALUES
(7, 265, 10, 'elements_constitutifs/94ce40d6ef6028f018b10a16f00e97fbb7ce44cc.pdf', 1, NULL, NULL, '94ce40d6ef6028f018b10a16f00e97fbb7ce44cc');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_appuis`
--

CREATE TABLE `entreprise_appuis` (
  `id` int NOT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `service_id` int NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise_appuis`
--

INSERT INTO `entreprise_appuis` (`id`, `entreprise_id`, `service_id`, `sequence`) VALUES
(153, 261, 28, 1),
(154, 261, 6, 1),
(155, 261, 2, 1),
(156, 261, 1, 1),
(157, 263, 23, 1),
(158, 263, 27, 1),
(159, 263, 18, 1),
(160, 263, 6, 1),
(161, 263, 2, 1),
(162, 263, 1, 1),
(163, 264, 14, 1),
(164, 264, 18, 1),
(165, 264, 17, 1),
(166, 264, 31, 1),
(167, 264, 21, 1),
(168, 264, 2, 1),
(169, 264, 3, 1),
(170, 264, 5, 1),
(171, 265, 14, 1),
(172, 265, 31, 1),
(173, 265, 30, 1),
(174, 265, 1, 1),
(175, 265, 5, 1),
(176, 265, 10, 1),
(177, 265, 4, 1),
(178, 265, 6, 1),
(188, 262, 28, 1),
(189, 262, 26, 1),
(190, 262, 17, 1),
(191, 262, 29, 1),
(192, 262, 20, 1),
(193, 262, 7, 1),
(194, 262, 1, 1),
(195, 262, 4, 1),
(196, 262, 9, 1),
(203, 267, 28, 1),
(204, 267, 26, 1),
(205, 267, 17, 1),
(206, 267, 6, 1),
(207, 267, 2, 1),
(208, 267, 1, 1),
(209, 266, 27, 1),
(210, 266, 15, 1),
(211, 266, 14, 1),
(212, 266, 18, 1),
(213, 266, 31, 1),
(214, 266, 6, 1),
(215, 266, 2, 1),
(216, 266, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_critere_avis`
--

CREATE TABLE `entreprise_critere_avis` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `critere_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `avis` longtext COLLATE utf8mb4_unicode_ci,
  `saved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise_critere_avis`
--

INSERT INTO `entreprise_critere_avis` (`id`, `entreprise_id`, `critere_id`, `user_id`, `avis`, `saved_at`, `created_at`, `updated_at`) VALUES
(1, 266, 2, 2, '<p>Un avis sur la stratégie&nbsp;</p>', '2026-04-29 16:18:22', '2026-04-29 15:18:22', '2026-04-29 15:18:22'),
(2, 266, 4, 2, '<p>Un avis sur la <b>qualité de l\'i</b>nformation</p><ol><li>un</li><li>deux</li><li>trois</li></ol>', '2026-04-29 16:20:57', '2026-04-29 15:20:57', '2026-04-29 15:20:57'),
(3, 266, 1, 2, '<p>Un avis sur l’activité&nbsp;</p>', '2026-04-29 16:21:37', '2026-04-29 15:21:37', '2026-04-29 15:21:37');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_equipe_membres`
--

CREATE TABLE `entreprise_equipe_membres` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `entreprise_site_id` bigint UNSIGNED DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau_etude` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `associe` tinyint(1) NOT NULL DEFAULT '0',
  `dirigeant` tinyint(1) NOT NULL DEFAULT '0',
  `cni_numero` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cni_expire_at` date DEFAULT NULL,
  `cni_fichier_id` int UNSIGNED DEFAULT NULL,
  `divers` longtext COLLATE utf8mb4_unicode_ci,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `updated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise_equipe_membres`
--

INSERT INTO `entreprise_equipe_membres` (`id`, `entreprise_id`, `entreprise_site_id`, `nom`, `prenom`, `telephone`, `email`, `date_naissance`, `fonction`, `niveau_etude`, `specialite`, `associe`, `dirigeant`, `cni_numero`, `cni_expire_at`, `cni_fichier_id`, `divers`, `created_by_user_id`, `updated_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 265, 1, 'ESSAMA', 'Appolinaire', '6956873283', 'a.essama@gmail.com', '1990-04-11', 'Comptable', 'Supérieur', 'Sciences de gestion', 1, 1, '689823923676237', '2027-02-22', 7, 'Quelques informations complémentaires concernant ce membre', 2, 2, '2026-04-29 13:06:24', '2026-04-29 13:06:24');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_piece_exigibles`
--

CREATE TABLE `entreprise_piece_exigibles` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `piece_exigible_definition_id` bigint UNSIGNED NOT NULL,
  `fichier_id` int DEFAULT NULL,
  `provided_at` timestamp NULL DEFAULT NULL,
  `provided_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise_piece_exigibles`
--

INSERT INTO `entreprise_piece_exigibles` (`id`, `entreprise_id`, `piece_exigible_definition_id`, `fichier_id`, `provided_at`, `provided_by_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 261, 1, 1, '2026-04-18 21:47:42', 2, NULL, '2026-04-18 21:47:42', '2026-04-18 21:47:42'),
(2, 263, 1, 2, '2026-04-21 12:01:11', 2, NULL, '2026-04-21 12:01:11', '2026-04-21 12:01:11'),
(3, 265, 1, 5, '2026-04-24 13:39:26', 2, NULL, '2026-04-24 13:39:26', '2026-04-24 13:39:26'),
(4, 266, 1, 8, '2026-04-29 15:22:08', 2, NULL, '2026-04-29 15:22:08', '2026-04-29 15:22:08'),
(5, 266, 2, 9, '2026-04-29 15:22:26', 2, NULL, '2026-04-29 15:22:26', '2026-04-29 15:22:26');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_produits`
--

CREATE TABLE `entreprise_produits` (
  `id` int NOT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `produit_id` int NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise_produits`
--

INSERT INTO `entreprise_produits` (`id`, `entreprise_id`, `produit_id`, `sequence`) VALUES
(71, 261, 134, 2),
(72, 261, 6, 2),
(73, 263, 134, 2),
(74, 263, 1, 2),
(75, 263, 4, 2),
(76, 264, 134, 2),
(77, 264, 356, 2),
(78, 264, 6, 2),
(79, 264, 109, 2),
(80, 265, 3, 2),
(81, 265, 7, 2),
(82, 265, 9, 2),
(85, 262, 1, 2),
(86, 262, 4, 2),
(89, 267, 134, 2),
(90, 267, 5, 2),
(91, 267, 6, 2),
(92, 267, 7, 2),
(93, 266, 10, 2),
(94, 266, 8, 2),
(95, 266, 319, 2),
(96, 266, 191, 2),
(97, 266, 253, 2);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_sites`
--

CREATE TABLE `entreprise_sites` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` bigint UNSIGNED NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `departement_id` bigint UNSIGNED DEFAULT NULL,
  `arrondissement_id` bigint UNSIGNED DEFAULT NULL,
  `village_id` bigint UNSIGNED DEFAULT NULL,
  `quartier_id` bigint UNSIGNED DEFAULT NULL,
  `village_ou_quartier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `divers` longtext COLLATE utf8mb4_unicode_ci,
  `created_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `updated_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise_sites`
--

INSERT INTO `entreprise_sites` (`id`, `entreprise_id`, `libelle`, `region_id`, `departement_id`, `arrondissement_id`, `village_id`, `quartier_id`, `village_ou_quartier`, `latitude`, `longitude`, `telephone`, `email`, `divers`, `created_by_user_id`, `updated_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 265, 'Site principal', 2, 12, 49, NULL, NULL, 'Obapi', NULL, NULL, '67982398932', NULL, NULL, 2, 2, '2026-04-29 13:02:31', '2026-04-29 13:02:31'),
(2, 266, 'Direction generale', 5, 28, 179, NULL, NULL, NULL, NULL, NULL, '678278733', 'contact@ctech.cm', NULL, 2, 2, '2026-04-29 14:12:50', '2026-04-29 14:12:50');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_types`
--

CREATE TABLE `entreprise_types` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `individuelle` tinyint(1) NOT NULL DEFAULT '0',
  `formel` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise_types`
--

INSERT INTO `entreprise_types` (`id`, `name`, `individuelle`, `formel`) VALUES
(1, 'Négoce', 1, 1),
(2, 'Artisanale', 1, 1),
(3, 'Service', 1, 1),
(4, 'Société en nom collectif  (SNC)', 0, 1),
(5, 'Société en commandite simple (SCS)', 0, 1),
(6, 'Groupement d’intérêt économique (GIE)', 0, 1),
(7, 'Société anonyme (SA)', 0, 1),
(8, 'Société par actions simplifiée (SAS)', 0, 1),
(9, 'Société en commandite par actions (SCA)', 0, 1),
(10, 'Société à responsabilité limitée (SARL)', 0, 1),
(11, 'Société à responsabilité limitée unipersonnelle (SARLU)', 0, 1),
(12, 'Société coopérative avec conseil d’administration (COOP CA)', 0, 1),
(13, 'Société coopérative simplifiée (SCOOPS)', 0, 1),
(14, 'Activité Génératrice de Revenus (AGR)', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fichiers`
--

CREATE TABLE `fichiers` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `dossier_id` int UNSIGNED DEFAULT NULL,
  `type_id` int NOT NULL DEFAULT '0',
  `token` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `uploaded_by_user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fichiers`
--

INSERT INTO `fichiers` (`id`, `name`, `original_name`, `entreprise_id`, `dossier_id`, `type_id`, `token`, `uploaded_at`, `uploaded_by_user_id`) VALUES
(1, 'pieces_exigibles/8ebb61ef172472bb8b98a6418e102d43ce30e59c.pdf', NULL, 261, NULL, 0, '8ebb61ef172472bb8b98a6418e102d43ce30e59c', NULL, NULL),
(2, 'pieces_exigibles/fb3f0a345ccbf282299edd2db8f0903ca1ba3a8c.pdf', NULL, 263, NULL, 0, 'fb3f0a345ccbf282299edd2db8f0903ca1ba3a8c', NULL, NULL),
(3, 'dossiers_pieces/1cfbfc4a7195eaf6ec7207bdbb52ca80d5e7c629.pdf', 'CAHIER DES CHARGES TECHNIQUE ADAPTATION ANGARA-1.pdf', 261, 36, 5, '1cfbfc4a7195eaf6ec7207bdbb52ca80d5e7c629', '2026-04-23 20:36:39', 7),
(4, 'dossiers_pieces/d85fd2d2a6b3e06b1bba2704265018e63f1f5a5c.pdf', 'DentaFlow — Présentation Ordre des Dentistes.pdf', 261, 36, 4, 'd85fd2d2a6b3e06b1bba2704265018e63f1f5a5c', '2026-04-23 21:13:28', 8),
(5, 'pieces_exigibles/685310d8e26934bef18aff19caebdfacdee408e7.pdf', NULL, 265, NULL, 0, '685310d8e26934bef18aff19caebdfacdee408e7', NULL, NULL),
(6, 'dossiers_pieces/f31373cd25b2284daaf92433794b009be799ac0f.pdf', 'gestionnaire-prospects_2026-04-24_014314.pdf', 265, 37, 2, 'f31373cd25b2284daaf92433794b009be799ac0f', '2026-04-24 15:11:47', 8),
(7, 'cni/a91a4eb8eb4ba931961a5570f59c1ed68826195a.png', NULL, 265, NULL, 0, 'a91a4eb8eb4ba931961a5570f59c1ed68826195a', NULL, NULL),
(8, 'pieces_exigibles/4dbe75f65d28922551a6695f4ef04dcecb10078b.pdf', NULL, 266, NULL, 0, '4dbe75f65d28922551a6695f4ef04dcecb10078b', NULL, NULL),
(9, 'pieces_exigibles/cb2b372203c096d8eb13b7c88f125611ad3c4da1.pdf', NULL, 266, NULL, 0, 'cb2b372203c096d8eb13b7c88f125611ad3c4da1', NULL, NULL),
(10, 'dossiers_pieces/4f949606a80555c7a91ab30a3fb7b936e2b0689a.pdf', 'fiche-prospect-Angara-Solutions.pdf', 265, 38, 2, '4f949606a80555c7a91ab30a3fb7b936e2b0689a', '2026-04-30 13:17:20', 9);

-- --------------------------------------------------------

--
-- Structure de la table `fichiers_types`
--

CREATE TABLE `fichiers_types` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fichiers_types`
--

INSERT INTO `fichiers_types` (`id`, `name`, `active`) VALUES
(1, 'Dossier juridique et administratif', 1),
(2, 'Dossier financier', 1),
(3, 'Dossier Pouvoirs et Signatures', 1),
(4, 'Dossier des garanties', 1),
(5, 'Dossier de suivi des cautions reçues', 0),
(6, 'Dossier de suivi des relations avec les parties prenantes', 1),
(7, 'Dossier des engagements', 1),
(8, 'Dossier des correspondances avec les partenaires financiers', 1),
(9, 'Dossier des réclamations adressées aux partenaires financiers', 1),
(10, 'Dossier Divers', 1);

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

CREATE TABLE `filieres` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `_id` int NOT NULL DEFAULT '0',
  `branche_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `filieres`
--

INSERT INTO `filieres` (`id`, `name`, `code`, `_id`, `branche_id`) VALUES
(1, 'Céréales', '001001', 1, 0),
(2, 'Racines et tubercules ', '001002', 7, 0),
(3, 'Bananes ', '001003', 13, 0),
(4, 'Produits du palmier à huile ', '001004', 16, 0),
(5, 'Produits d’autres cultures  oléagineuses ', '001005', 18, 0),
(6, 'Coton ', '001006', 22, 0),
(7, 'Fruits et légumes (n.c les fruits sauvages et la banane) ', '001007', 24, 0),
(8, 'Plants, fleurs et autres produits de l\'horticulture ', '001008', 32, 0),
(9, 'Produits de la culture du cacao ', '001009', 34, 0),
(10, 'Café et thé ', '001010', 36, 0),
(11, 'Autres produits cultivés n.c.a. ', '001011', 40, 0),
(12, 'Produits de l’égrenage et des autres services de soutien à l\'agriculture ', '001012', 47, 0),
(13, 'Produits de l\'élevage de bovins', '002001', 51, 0),
(14, 'Produits de l\'élevage des petits ruminants ', '002002', 54, 0),
(15, 'Produits de l’élevage des porcins', '002003', 59, 0),
(16, 'Produits de l’élevage de la volaille ', '002004', 61, 0),
(17, 'Produits de l’élevage des équins et asins ', '002005', 66, 0),
(18, 'Produits de l’apiculture ', '002006', 69, 0),
(19, 'Produits de l’élevage des  animaux n.c.a. ', '002007', 72, 0),
(20, 'Produits de la chasse et du piégeage ', '002008', 76, 0),
(21, 'Services de soutien à l\'élevage ', '002009', 80, 0),
(22, 'Produits de la sylviculture et de l\'exploitation forestière', '003001', 84, 0),
(23, 'Services de conservation des forêts et aires protégés ', '003002', 89, 0),
(24, 'Services de soutien à la sylviculture et à l\'exploitation forestière ', '003003', 91, 0),
(25, 'Produits de la pêche (artisanale, industrielle), de la pisciculture et de l’aquaculture', '004000', 94, 0),
(26, 'Services de soutien à l\'extraction d\'hydrocarbures et d’autres produits énergétiques ', '005002', 99, 0),
(27, 'Services de soutien aux industries extractives ', '005003', 101, 0),
(28, 'Produits de l\'extraction d\'hydrocarbures', '005001', 103, 0),
(29, 'Services de soutien à l\'extraction d\'hydrocarbures et d’autres produits énergétiques ', '005002', 107, 0),
(30, 'Services de soutien aux industries extractives ', '005003', 109, 0),
(31, 'Produits de l\'extraction de minerais métalliques', '6001', 111, 0),
(32, 'Produits de l\'extraction de minéraux non métalliques', '6002', 115, 0),
(33, 'Services de soutien aux industries minières', '6003', 120, 0),
(34, 'Produits de transformation et de conservation de viande et produits dérivés', '7001', 122, 0),
(35, 'Produits de la transformation et de la conservation du poisson et des produits de la pêche', '7002', 130, 0),
(36, 'mollusques congelés, surgelés ou en conserves', '', 134, 0),
(37, 'Services de soutien à l’industrie de la viande', '7003', 138, 0),
(38, 'Farines de céréales', '8001', 144, 0),
(39, 'Produits de la préparation du riz', '8002', 149, 0),
(40, 'Produits amylacés', '8003', 152, 0),
(41, 'Produits à base de cacao', '9001', 158, 0),
(42, 'Produits du décorticage et de la transformation du café', '9002', 164, 0),
(43, 'Produits de la préparation et de la conservation du thé', '9003', 169, 0),
(44, 'Sucre et mélasses', '9004', 171, 0),
(45, 'Huiles brutes et tourteaux', '10001', 174, 0),
(46, 'Huiles raffinées, margarines et matières grasses', '10002', 180, 0),
(47, 'Provende et aliments pour animaux', '10003', 188, 0),
(48, 'Pain, biscuits et pâtisserie', '11001', 191, 0),
(49, 'Pâtes alimentaires', '11002', 195, 0),
(50, 'Produits laitiers', '12001', 197, 0),
(51, 'Produits de la transformation et de la conservation des fruits, légumes et autres produits alimentaires', '12002', 202, 0),
(52, 'Bière et malt', '13001', 207, 0),
(53, 'Autres boissons alcoolisées', '13002', 210, 0),
(54, 'Boissons non alcoolisées et eaux minérales', '13003', 214, 0),
(55, 'Produits à base de tabac', '14000', 217, 0),
(56, 'Opérations sous-traitées intervenant dans la fabrication de produits textiles', '15000000', 220, 0),
(57, 'Fibres et fils textiles', '15001', 221, 0),
(58, 'Tissus et services d’ennoblissement', '15002', 225, 0),
(59, 'Autres produits textiles non vestimentaires', '15003', 229, 0),
(60, 'Articles d\'habillement (sauf chaussures)', '15004', 234, 0),
(61, 'Opérations sous-traitées intervenant dans la fabrication de cuir, articles de voyages et de chaussures', '16000000', 237, 0),
(62, 'Produits du cuir et articles en cuir', '16001', 238, 0),
(63, 'Chaussures, y compris chaussures en caoutchouc et en plastique', '16002', 241, 0),
(64, 'Opérations sous-traitées intervenant dans le travail du bois et la fabrication d’articles en bois', '17000000', 243, 0),
(65, 'Produits du Sciage et traitement du bois', '17001', 244, 0),
(66, 'Feuilles de placages, contreplaqués et panneaux', '17002', 247, 0),
(67, 'Produits en bois assemblés, articles en bois, liège, vannerie et sparterie', '17003', 249, 0),
(68, 'Papier, carton et articles en papier ou en carton', '18001', 253, 0),
(69, 'Produits imprimés ou reproduits', '18002', 259, 0),
(70, 'Produits du raffinage du pétrole et de la Cokéfaction', '19000', 266, 0),
(71, 'Produits chimiques de base', '20001', 277, 0),
(72, 'Savons, parfums, détergents et produits d\'entretien', '20002', 283, 0),
(73, 'Produits pharmaceutiques', '20003', 287, 0),
(74, 'Autres produits chimiques', '20004', 290, 0),
(75, 'Caoutchouc sec', '21001', 295, 0),
(76, 'Articles en caoutchouc', '21002', 297, 0),
(77, 'Articles en matières plastiques (sauf chaussures)', '21003', 299, 0),
(78, 'Ciment', '22001', 302, 0),
(79, 'Autres produits minéraux non métalliques', '22002', 304, 0),
(80, 'Produits métallurgiques de base et ouvrages en métaux', '23000', 311, 0),
(81, 'Machines, appareils électriques et matériels n.c.a.', '24000', 321, 0),
(82, 'Equipements et appareils audiovisuels et de communication, instruments médicaux, de précision, d\'optique et d\'horlogerie', '25000', 330, 0),
(83, 'Opérations sous-traitées intervenant dans la fabrication de matériels de transport', '26000000', 333, 0),
(84, 'Véhicules routiers', '26001', 334, 0),
(85, 'Autres matériels de transport', '26002', 339, 0),
(86, 'Meubles', '27001', 344, 0),
(87, 'Produits des industries manufacturières n.c.a.', '27002', 347, 0),
(88, 'Travaux de réparation des machines et équipements professionnels', '28001', 352, 0),
(89, 'optiques', '', 356, 0),
(90, 'Travaux d\'Installation des machines et équipements industriels', '28002', 359, 0),
(91, 'Electricité et supports énergétiques', '29001', 361, 0),
(92, 'Gaz, biocarburants et autres supports énergétiques d’origine non fossile', '29002', 363, 0),
(93, 'Service de Captage, traitement et distribution d’eau', '30001', 366, 0),
(94, 'Service de collecte et traitement des eaux usées', '30002', 369, 0),
(95, 'Service de collecte, traitement et élimination des déchets  solide; Service de récupération', '30003', 371, 0),
(96, 'Service de dépollution', '30004', 375, 0),
(97, 'Travaux de préparation de sites et de construction d\'ouvrages de génie civil et bâtiments', '31001', 377, 0),
(98, 'Travaux d\'installation', '31002', 382, 0),
(99, 'Travaux de finition', '31003', 386, 0),
(100, 'Vente de véhicules automobiles et de motocycles', '32001', 390, 0),
(101, 'Services d\'entretien et réparation de véhicule automobile et de motocycles', '32002', 393, 0),
(102, 'Vente de pièces détachées et d’accessoires pour automobile/motocycle', '32003', 396, 0),
(103, 'Vente en gros de produits agricoles bruts et d\'animaux vivants', '32004', 399, 0),
(104, 'Vente en gros de produits alimentaires, boissons et tabacs manufacturés', '32005', 401, 0),
(105, 'Vente en gros de matériaux de construction, quincaillerie et fournitures pour plomberie', '32006', 403, 0),
(106, 'Autres vente en gros', '32007', 405, 0),
(107, 'Vente en détail en magasin non spécialisé', '32008', 408, 0),
(108, 'Vente en détail de produits alimentaires, boissons et tabacs manufacturés', '32009', 410, 0),
(109, 'Vente en détail de matériaux de construction, quincaillerie et fournitures pour plomberie', '32010', 412, 0),
(110, 'Autres ventes en détail en magasin spécialisé', '32011', 414, 0),
(111, 'Vente en détail de biens d’occasion', '32012', 416, 0),
(112, 'Vente en détail hors magasin', '32013', 418, 0),
(113, 'Autres ventes en détail hors magasin', '32014', 420, 0),
(114, 'Hébergement', '33001', 422, 0),
(115, 'Services de restaurants et des débits de boissons et cafés', '33002', 424, 0),
(116, 'Services de Transports ferroviaires', '34001', 427, 0),
(117, 'Service de transport par taxis et motos', '34002', 430, 0),
(118, 'Autres Services de transports routiers de voyageurs', '34003', 433, 0),
(119, 'Services de transports routiers de marchandises', '34004', 437, 0),
(120, 'Autres Services de transports', '34005', 439, 0),
(121, 'Service d’entreposage, services auxiliaires des transports', '34006', 443, 0),
(122, 'Services de postes et courrier', '34007', 446, 0),
(123, 'Services d\'édition', '35001', 448, 0),
(124, 'Produit des activités audio et vidéo', '35002', 451, 0),
(125, 'Service de programmation télévisuelle et de radiodiffusion', '35003', 454, 0),
(126, 'Services de Télécommunication', '35004', 457, 0),
(127, 'Produits et services informatiques : conseil, programmation', '35005', 459, 0),
(128, 'Service de fourniture d’informations', '35006', 462, 0),
(129, 'Services d’intermédiation monétaire et financière (sauf micro finances)', '36001', 465, 0),
(130, 'Services d\'assurance (sauf sécurité sociale)', '36002', 469, 0),
(131, 'Services d\'auxiliaires financiers et d\'assurance', '36003', 472, 0),
(132, 'Services de Micro-finances', '36004', 475, 0),
(133, 'Services de location immobilière', '37001', 477, 0),
(134, 'Autres services immobiliers', '37002', 479, 0),
(135, 'Services administratifs et d’appui aux entreprises', '38001', 481, 0),
(136, 'Services  de recherchedéveloppement en sciences physiques et naturelles', '38002', 483, 0),
(137, 'Services  de recherchedéveloppement en sciences humaines et sociales', '38003', 485, 0),
(138, 'Services juridiques et comptables', '38004', 487, 0),
(139, 'Services vétérinaires', '38005', 489, 0),
(140, 'Autres services spécialisées, scientifiques et techniques', '38006', 491, 0),
(141, 'Services d\'administration publique générale et services fournis à l\'ensemble de la collectivité', '39001', 499, 0),
(142, 'Administration publique générale, économique  et sociale', '39010001', 500, 0),
(143, 'Service de sécurité sociale obligatoire', '39002', 502, 0),
(144, 'Service d’enseignement', '40000', 505, 0),
(145, 'Services pour la santé humaine', '41001', 510, 0),
(146, 'Services d’hébergement médico-social et social', '41002', 515, 0),
(147, 'Services fournis par les organisations associatives', '42001', 517, 0),
(148, 'Services récréatifs, culturels et sportifs', '42002', 519, 0),
(149, 'Services personnels', '42003', 521, 0),
(150, 'Services domestiques', '42004', 523, 0),
(151, 'Services de réparation des ordinateurs, des équipements de communication et réseautage', '42005', 525, 0),
(152, 'Réparation de biens personnels et domestiques', '42006', 527, 0),
(153, 'Services des organisations et organismes extraterritoriaux', '43000', 529, 0);

-- --------------------------------------------------------

--
-- Structure de la table `formes_juridiques`
--

CREATE TABLE `formes_juridiques` (
  `id` int NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `individuelle` tinyint(1) NOT NULL DEFAULT '0',
  `formel` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `formes_juridiques`
--

INSERT INTO `formes_juridiques` (`id`, `name`, `individuelle`, `formel`) VALUES
(1, 'SARL', 0, 1),
(2, 'ENTREPRISE INDIVIDUELLE', 1, 1),
(3, 'COOPERATIVE', 0, 1),
(4, 'GIC', 0, 1),
(5, 'SA', 0, 1),
(6, 'SURL', 0, 1),
(7, 'AGR', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `indicateurs`
--

CREATE TABLE `indicateurs` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `indicateurs`
--

INSERT INTO `indicateurs` (`id`, `name`, `type_id`) VALUES
(1, 'Nombre total de PME bénéficiaires des appuis non-financiers', 1),
(2, 'Coût total des appuis non-financiers accordés aux PME', 1),
(3, 'Coût et Nombre de chèques services accordés aux PME bénéficiaires', 1),
(4, 'Coût et Nombre de PME formalisées et accompagnées', 1),
(5, 'Coût et Nombre de PME bénéficiaires des appuis non-financiers par type de prestation', 1),
(6, 'Coût et Nombre de bénéficiaires des appuis non-financiers par Région, Département et Commune', 1),
(7, 'Valeur cumulée des actifs des PME bénéficiaires des appuis non-financiers à l’entrée en relation', 1),
(8, 'Valeur cumulée des actifs des PME bénéficiaires des appuis non-financiers à l’entrée en relation par type de prestation', 1),
(9, 'Valeur cumulée des actifs des PME bénéficiaires des appuis non-financiers à l’entrée en relation, par type de prestation et par Région, Département et Commune', 1),
(10, 'Chiffre d\'affaires cumulé des PME bénéficiaires des appuis non-financiers à l’entrée en relation par type de prestation', 1),
(11, 'Chiffre d\'affaires cumulé des PME bénéficiaires des appuis non-financiers à l’entrée en relation, par type de prestation et par Région, Département et Commune', 1),
(12, 'Nombre d’employés des PME accompagnées à l’entrée en relation', 1),
(13, 'Variation en année N+1 du Nombre d’employés des PME accompagnées', 1),
(14, 'Nombre de Banques et EMF partenaires sélectionnés', 2),
(15, 'Nombre de produits financiers (adaptés aux besoins des PME) développés par les Banques et EMF partenaires', 2),
(16, 'Nombre de produits financiers (adaptés) offerts aux PME par les Banques et EMF partenaires', 2),
(17, 'Nombre de nouvelles PME entrant dans le portefeuille des Banques et EMF grâce aux activités de facilitation-médiation', 2),
(18, 'Montant cumulé des concours Court Terme aux PME par les Banques et EMF', 2),
(19, 'Montant cumulé des concours Moyen Terme aux PME par les Banques et EMF', 2),
(20, 'Montant cumulé des concours Long Terme aux PME par les Banques et EMF', 2),
(21, 'Montant cumulé des concours Court Terme aux PME par les Banques et EMF, par type de crédit', 2),
(22, 'Montant cumulé des concours Moyen Terme aux PME par les Banques et EMF, par type de crédit', 2),
(23, 'Montant cumulé des concours Long Terme aux PME par les Banques et EMF, par type de crédit', 2),
(24, 'Subventions accordées aux Banques et EMF pour développer des produits adaptés ou répondant aux besoins des PME', 2),
(25, 'Nombre de PME ayant bénéficié des produits financiers offerts par les Banques et EMF partenaires', 2),
(26, 'Nombre de Banque et EMF accompagnés qui ont réalisé leur autoévaluation en matière de performance sociale, protection de la clientèle et gouvernance ', 2),
(27, 'Nombre de Banque et EMF qui ont mis en œuvre des actions correctives après leur autoévaluation en matière de performance sociale, protection de la clientèle et gouvernance', 2),
(28, 'Nombre de Banque et EMF dont les résultats et ratios prudentiels sont améliorés suite à l’accompagnement', 2),
(29, 'Coût et Nombre d\'entreprises et d’organisations intermédiaires accompagnées par Partenaire', 3),
(30, 'Coût et Nombre de personnes formées par Partenaire', 3),
(31, 'Nombre d’employés des PME accompagnées par le Prestataire à l’entrée en relation', 3),
(32, 'Variation en année N+1 du Nombre d’employés des PME accompagnées par le Prestataire', 3),
(33, 'Valeur cumulée des actifs des PME bénéficiaires des appuis non-financiers à l’entrée en relation par Partenaire', 3),
(34, 'Chiffre d\'affaires cumulé des PME bénéficiaires des appuis non-financiers à l’entrée en relation par Partenaire', 3),
(35, 'Montant cumulé des concours accordés aux PME par Partenaire', 3),
(36, 'Montant cumulé des impayés sur les concours accordés aux PME par Partenaire', 3),
(37, 'Variation en année N+1 de la Valeur cumulée des actifs des PME bénéficiaires des appuis non-financiers à l’entrée en relation par Partenaire', 3),
(38, 'Variation en année N+1 du Chiffre d\'affaires cumulé des PME bénéficiaires des appuis non-financiers à l’entrée en relation par Partenaire', 3),
(39, 'Variation en année N+1 du Montant cumulé des concours accordés aux PME par partenaire', 3),
(40, 'Variation en année N+1 du Montant cumulé des impayés sur les concours accordés aux PME par Partenaire\r\n', 3);

-- --------------------------------------------------------

--
-- Structure de la table `indicateurs_financiers`
--

CREATE TABLE `indicateurs_financiers` (
  `id` int UNSIGNED NOT NULL,
  `ca` double DEFAULT '0',
  `marge_commerciale` double DEFAULT '0',
  `va` double DEFAULT '0',
  `ebe` double DEFAULT '0',
  `resultat_expl` double DEFAULT '0',
  `resultat_fin` double DEFAULT '0',
  `resultat_ao` double DEFAULT '0',
  `resultat_hao` double DEFAULT '0',
  `resultat_net` double DEFAULT '0',
  `val_compt_cci` double DEFAULT '0',
  `prod_cci` double DEFAULT '0',
  `revenus_fin` double DEFAULT '0',
  `gains_change` double DEFAULT '0',
  `transf_charges_fin` double DEFAULT '0',
  `prod_hao` double DEFAULT '0',
  `transf_charges_hao` double DEFAULT '0',
  `frais_fin` double DEFAULT '0',
  `pertes_change` double DEFAULT '0',
  `participation` double DEFAULT '0',
  `impots_resultats` double DEFAULT '0',
  `distrib_divid` double DEFAULT '0',
  `capitaux_propres_res_assim` double DEFAULT '0',
  `dettes_fin` double DEFAULT '0',
  `actif_immo` double DEFAULT '0',
  `actif_circulant_expl` double DEFAULT '0',
  `passif_circulant_expl` double DEFAULT '0',
  `actif_circulant_hao` double DEFAULT '0',
  `passif_circulant_hao` double DEFAULT '0',
  `controle_treso_net` double DEFAULT '0',
  `flux_treso_act_op` double DEFAULT '0',
  `flux_treso_act_invest` double DEFAULT '0',
  `flux_treso_act_fin` double DEFAULT '0',
  `endettement_fin_brut` double DEFAULT '0',
  `treso_actif` double DEFAULT '0',
  `stock_moyen` double DEFAULT '0',
  `cout_prod_vendu` double DEFAULT '0',
  `ratio_endet_global` double DEFAULT '0',
  `couverture_frais_fin` double DEFAULT '0',
  `solvabilite_glob_rx_liq` double DEFAULT '0',
  `liquidite_generale` double DEFAULT '0',
  `rentab_eco` double DEFAULT '0',
  `rentab_fin` double DEFAULT '0',
  `capacite_endettement` double DEFAULT '0',
  `delai_client` double DEFAULT '0',
  `delai_fournisseur` double DEFAULT '0',
  `rentab_eco_ratio` double DEFAULT '0',
  `rentab_fin_ratio` double DEFAULT '0',
  `bilan_row_23` double DEFAULT '0',
  `bilan_row_24` double DEFAULT '0',
  `dossier_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `parent_id` int DEFAULT '0',
  `annee` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `indicateurs_financiers`
--

INSERT INTO `indicateurs_financiers` (`id`, `ca`, `marge_commerciale`, `va`, `ebe`, `resultat_expl`, `resultat_fin`, `resultat_ao`, `resultat_hao`, `resultat_net`, `val_compt_cci`, `prod_cci`, `revenus_fin`, `gains_change`, `transf_charges_fin`, `prod_hao`, `transf_charges_hao`, `frais_fin`, `pertes_change`, `participation`, `impots_resultats`, `distrib_divid`, `capitaux_propres_res_assim`, `dettes_fin`, `actif_immo`, `actif_circulant_expl`, `passif_circulant_expl`, `actif_circulant_hao`, `passif_circulant_hao`, `controle_treso_net`, `flux_treso_act_op`, `flux_treso_act_invest`, `flux_treso_act_fin`, `endettement_fin_brut`, `treso_actif`, `stock_moyen`, `cout_prod_vendu`, `ratio_endet_global`, `couverture_frais_fin`, `solvabilite_glob_rx_liq`, `liquidite_generale`, `rentab_eco`, `rentab_fin`, `capacite_endettement`, `delai_client`, `delai_fournisseur`, `rentab_eco_ratio`, `rentab_fin_ratio`, `bilan_row_23`, `bilan_row_24`, `dossier_id`, `user_id`, `parent_id`, `annee`, `created_at`, `updated_at`) VALUES
(55, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 31, 0, 0, 2022, '2026-04-20 11:25:36', '2026-04-20 11:25:36'),
(56, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 31, 0, 0, 2023, '2026-04-20 11:25:36', '2026-04-20 11:25:36'),
(57, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 32, 0, 0, 2022, '2026-04-21 14:08:44', '2026-04-21 14:08:44'),
(58, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 32, 0, 0, 2023, '2026-04-21 14:08:44', '2026-04-21 14:08:44'),
(59, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 35, 0, 0, 2022, '2026-04-22 06:03:54', '2026-04-22 06:03:54'),
(60, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 35, 0, 0, 2023, '2026-04-22 06:03:54', '2026-04-22 06:03:54'),
(61, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 36, 0, 0, 2023, '2026-04-23 22:17:05', '2026-04-23 22:17:05'),
(62, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 36, 0, 0, 2024, '2026-04-23 22:17:05', '2026-04-23 22:17:05'),
(63, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 37, 0, 0, 2022, '2026-04-24 15:18:02', '2026-04-24 15:18:02'),
(64, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 37, 0, 0, 2023, '2026-04-24 15:18:02', '2026-04-24 15:18:02'),
(65, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 38, 0, 0, 2022, '2026-04-30 13:10:15', '2026-04-30 13:10:15'),
(66, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 38, 0, 0, 2023, '2026-04-30 13:10:15', '2026-04-30 13:10:15');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liens`
--

CREATE TABLE `liens` (
  `id` int NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liens`
--

INSERT INTO `liens` (`id`, `name`) VALUES
(1, 'Familial'),
(2, 'Professionnel'),
(3, 'Associatif'),
(4, 'Amical');

-- --------------------------------------------------------

--
-- Structure de la table `localites`
--

CREATE TABLE `localites` (
  `id` int NOT NULL,
  `commune` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `departement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `localites`
--

INSERT INTO `localites` (`id`, `commune`, `departement`, `region`) VALUES
(1, 'Bankim', 'Mayo-Banyo', 'Adamaoua'),
(2, 'Banyo', 'Mayo-Banyo', 'Adamaoua'),
(3, 'Belel', 'Vina', 'Adamaoua'),
(4, 'Dir', 'Mbéré', 'Adamaoua'),
(5, 'Djohong', 'Mbéré', 'Adamaoua'),
(6, 'Galim-Tignère', 'Faro-et-Déo', 'Adamaoua'),
(7, 'Kontcha', 'Faro-et-Déo', 'Adamaoua'),
(8, 'Martap', 'Vina', 'Adamaoua'),
(9, 'Mayo-Baléo', 'Faro-et-Déo', 'Adamaoua'),
(10, 'Mayo-Darlé', 'Mayo-Banyo', 'Adamaoua'),
(11, 'Mbe', 'Vina', 'Adamaoua'),
(12, 'Meiganga', 'Mbéré', 'Adamaoua'),
(13, 'Nganha', 'Vina', 'Adamaoua'),
(14, 'Ngaoui', 'Mbéré', 'Adamaoua'),
(15, 'Ngaoundal', 'Djerem', 'Adamaoua'),
(16, 'Ngaoundéré Ier', 'Vina', 'Adamaoua'),
(17, 'Ngaoundéré IIe', 'Vina', 'Adamaoua'),
(18, 'Ngaoundéré IIIe', 'Vina', 'Adamaoua'),
(19, 'Nyambaka', 'Vina', 'Adamaoua'),
(20, 'Tibati', 'Djerem', 'Adamaoua'),
(21, 'Tignère', 'Faro-et-Déo', 'Adamaoua'),
(22, 'Afanloum', 'Méfou-et-Afamba', 'Centre'),
(23, 'Akoeman', 'Nyong-et-So\'o', 'Centre'),
(24, 'Akono', 'Méfou-et-Akono', 'Centre'),
(25, 'Akonolinga', 'Nyong-et-Mfoumou', 'Centre'),
(26, 'Awaé', 'Méfou-et-Afamba', 'Centre'),
(27, 'Ayos', 'Nyong-et-Mfoumou', 'Centre'),
(28, 'Bafia', 'Mbam-et-Inoubou', 'Centre'),
(29, 'Batchenga', 'Lekié', 'Centre'),
(30, 'Bibey', 'Haute-Sanaga', 'Centre'),
(31, 'Bikok', 'Méfou-et-Akono', 'Centre'),
(32, 'Biyouha', 'Nyong-et-Kéllé', 'Centre'),
(33, 'Bokito', 'Mbam-et-Inoubou', 'Centre'),
(34, 'Bondjock', 'Nyong-et-Kéllé', 'Centre'),
(35, 'Bot-Makak', 'Nyong-et-Kéllé', 'Centre'),
(36, 'Deuk', 'Mbam-et-Inoubou', 'Centre'),
(37, 'Dibang', 'Nyong-et-Kéllé', 'Centre'),
(38, 'Dzeng', 'Nyong-et-So\'o', 'Centre'),
(39, 'Ebebda', 'Lekié', 'Centre'),
(40, 'Edzendouan', 'Méfou-et-Afamba', 'Centre'),
(41, 'Elig-Mfomo', 'Lekié', 'Centre'),
(42, 'Endom', 'Nyong-et-Mfoumou', 'Centre'),
(43, 'Éséka', 'Nyong-et-Kéllé', 'Centre'),
(44, 'Esse', 'Méfou-et-Afamba', 'Centre'),
(45, 'Evodoula', 'Lekié', 'Centre'),
(46, 'Kiiki', 'Mbam-et-Inoubou', 'Centre'),
(47, 'Kobdombo', 'Nyong-et-Mfoumou', 'Centre'),
(48, 'Kon-Yambetta', 'Mbam-et-Inoubou', 'Centre'),
(49, 'Lembe-Yezoum', 'Haute-Sanaga', 'Centre'),
(50, 'Lobo', 'Lekié', 'Centre'),
(51, 'Makak', 'Nyong-et-Kéllé', 'Centre'),
(52, 'Makénéné', 'Mbam-et-Inoubou', 'Centre'),
(53, 'Matomb', 'Nyong-et-Kéllé', 'Centre'),
(54, 'Mbalmayo', 'Nyong-et-So\'o', 'Centre'),
(55, 'Mbandjock', 'Haute-Sanaga', 'Centre'),
(56, 'Mbangassina', 'Mbam-et-Kim', 'Centre'),
(57, 'Mbankomo', 'Méfou-et-Akono', 'Centre'),
(58, 'Mengang', 'Nyong-et-Mfoumou', 'Centre'),
(59, 'Mengueme', 'Nyong-et-So\'o', 'Centre'),
(60, 'Messondo', 'Nyong-et-Kéllé', 'Centre'),
(61, 'Mfou', 'Méfou-et-Afamba', 'Centre'),
(62, 'Minta', 'Haute-Sanaga', 'Centre'),
(63, 'Monatélé', 'Lekié', 'Centre'),
(64, 'Nanga-Eboko', 'Haute-Sanaga', 'Centre'),
(65, 'Ndikiniméki', 'Mbam-et-Inoubou', 'Centre'),
(66, 'Ngambè-Tikar', 'Mbam-et-Kim', 'Centre'),
(67, 'Ngog-Mapubi', 'Nyong-et-Kéllé', 'Centre'),
(68, 'Ngomedzap', 'Nyong-et-So\'o', 'Centre'),
(69, 'Ngoro', 'Mbam-et-Kim', 'Centre'),
(70, 'Ngoumou', 'Méfou-et-Akono', 'Centre'),
(71, 'Ngui-Bassal', 'Nyong-et-Kéllé', 'Centre'),
(72, 'Nitoukou', 'Mbam-et-Inoubou', 'Centre'),
(73, 'Nkolafamba', 'Méfou-et-Afamba', 'Centre'),
(74, 'Nkolmetet', 'Nyong-et-So\'o', 'Centre'),
(75, 'Nkoteng', 'Haute-Sanaga', 'Centre'),
(76, 'Nsem', 'Haute-Sanaga', 'Centre'),
(77, 'Ntui', 'Mbam-et-Kim', 'Centre'),
(78, 'Obala', 'Lekié', 'Centre'),
(79, 'Okola', 'Lekié', 'Centre'),
(80, 'Olanguina', 'Méfou-et-Afamba', 'Centre'),
(81, 'Ombessa', 'Mbam-et-Inoubou', 'Centre'),
(82, 'Sa\'a', 'Lekié', 'Centre'),
(83, 'Soa', 'Méfou-et-Afamba', 'Centre'),
(84, 'Yaoundé Ier', 'Mfoundi', 'Centre'),
(85, 'Yaoundé IIe', 'Mfoundi', 'Centre'),
(86, 'Yaoundé IIIe', 'Mfoundi', 'Centre'),
(87, 'Yaoundé IVe', 'Mfoundi', 'Centre'),
(88, 'Yaoundé Ve', 'Mfoundi', 'Centre'),
(89, 'Yaoundé VIe', 'Mfoundi', 'Centre'),
(90, 'Yaoundé VIIe', 'Mfoundi', 'Centre'),
(91, 'Yoko', 'Mbam-et-Kim', 'Centre'),
(92, 'Abong-Mbang', 'Haut-Nyong', 'Est'),
(93, 'Angossas', 'Haut-Nyong', 'Est'),
(94, 'Atok', 'Haut-Nyong', 'Est'),
(95, 'Batouri', 'Kadey', 'Est'),
(96, 'Bélabo', 'Lom-et-Djérem', 'Est'),
(97, 'Bertoua Ier', 'Lom-et-Djérem', 'Est'),
(98, 'Bertoua IIe', 'Lom-et-Djérem', 'Est'),
(99, 'Bétaré-Oya', 'Lom-et-Djérem', 'Est'),
(100, 'Diang', 'Lom-et-Djérem', 'Est'),
(101, 'Dimako', 'Haut-Nyong', 'Est'),
(102, 'Doumaintang', 'Haut-Nyong', 'Est'),
(103, 'Doumé', 'Haut-Nyong', 'Est'),
(104, 'Gari-Gombo', 'Boumba-et-Ngoko', 'Est'),
(105, 'Garoua-Boulaï', 'Lom-et-Djérem', 'Est'),
(106, 'Kentzou', 'Kadey', 'Est'),
(107, 'Kette', 'Kadey', 'Est'),
(108, 'Lomié', 'Haut-Nyong', 'Est'),
(109, 'Mandjou', 'Lom-et-Djérem', 'Est'),
(110, 'Mbang', 'Kadey', 'Est'),
(111, 'Mboma', 'Haut-Nyong', 'Est'),
(112, 'Messamena', 'Haut-Nyong', 'Est'),
(113, 'Messok', 'Haut-Nyong', 'Est'),
(114, 'Mindourou', 'Haut-Nyong', 'Est'),
(115, 'Moloundou', 'Boumba-et-Ngoko', 'Est'),
(116, 'Ndelele', 'Kadey', 'Est'),
(117, 'Ngoura', 'Lom-et-Djérem', 'Est'),
(118, 'Ngoyla', 'Haut-Nyong', 'Est'),
(119, 'Nguelebok', 'Kadey', 'Est'),
(120, 'Nguelemendouka', 'Haut-Nyong', 'Est'),
(121, 'Ouli', 'Kadey', 'Est'),
(122, 'Salapoumbé', 'Boumba-et-Ngoko', 'Est'),
(123, 'Somalomo', 'Haut-Nyong', 'Est'),
(124, 'Yokadouma', 'Boumba-et-Ngoko', 'Est'),
(125, 'Blangoua', 'Logone-et-Chari', 'Extrême-Nord'),
(126, 'Bogo', 'Diamaré', 'Extrême-Nord'),
(127, 'Bourrha', 'Mayo-Tsanaga', 'Extrême-Nord'),
(128, 'Dargala', 'Diamaré', 'Extrême-Nord'),
(129, 'Darak', 'Diamaré', 'Extrême-Nord'),
(130, 'Datcheka', 'Mayo-Danay', 'Extrême-Nord'),
(131, 'Dziguilao', 'Mayo-Kani', 'Extrême-Nord'),
(132, 'Fotokol', 'Logone-et-Chari', 'Extrême-Nord'),
(133, 'Gazawa', 'Diamaré', 'Extrême-Nord'),
(134, 'Gobo', 'Mayo-Danay', 'Extrême-Nord'),
(135, 'Goulfey', 'Logone-et-Chari', 'Extrême-Nord'),
(136, 'Guémé', 'Mayo-Danay', 'Extrême-Nord'),
(137, 'Guéré', 'Mayo-Danay', 'Extrême-Nord'),
(138, 'Guidiguis', 'Mayo-Kani', 'Extrême-Nord'),
(139, 'Hile-Alifa', 'Logone-et-Chari', 'Extrême-Nord'),
(140, 'Hina', 'Mayo-Tsanaga', 'Extrême-Nord'),
(141, 'Kaélé', 'Mayo-Kani', 'Extrême-Nord'),
(142, 'Kai-Kai', 'Mayo-Danay', 'Extrême-Nord'),
(143, 'Kalfou', 'Mayo-Danay', 'Extrême-Nord'),
(144, 'Kar-Hay', 'Mayo-Danay', 'Extrême-Nord'),
(145, 'Kolofata', 'Mayo-Sava', 'Extrême-Nord'),
(146, 'Kousséri', 'Logone-et-Chari', 'Extrême-Nord'),
(147, 'Koza', 'Mayo-Tsanaga', 'Extrême-Nord'),
(148, 'Logone-Birni', 'Logone-et-Chari', 'Extrême-Nord'),
(149, 'Maga', 'Mayo-Danay', 'Extrême-Nord'),
(150, 'Makary', 'Logone-et-Chari', 'Extrême-Nord'),
(151, 'Maroua Ier', 'Diamaré', 'Extrême-Nord'),
(152, 'Maroua IIe', 'Diamaré', 'Extrême-Nord'),
(153, 'Maroua IIIe', 'Diamaré', 'Extrême-Nord'),
(154, 'Meri', 'Diamaré', 'Extrême-Nord'),
(155, 'Mindif', 'Mayo-Kani', 'Extrême-Nord'),
(156, 'Mogodé', 'Mayo-Tsanaga', 'Extrême-Nord'),
(157, 'Mokolo', 'Mayo-Tsanaga', 'Extrême-Nord'),
(158, 'Mora', 'Mayo-Sava', 'Extrême-Nord'),
(159, 'Moulvoudaye', 'Mayo-Kani', 'Extrême-Nord'),
(160, 'Moutourwa', 'Mayo-Kani', 'Extrême-Nord'),
(161, 'Mozogo', 'Mayo-Tsanaga', 'Extrême-Nord'),
(162, 'Ndoukoula', 'Diamaré', 'Extrême-Nord'),
(163, 'Petté', 'Diamaré', 'Extrême-Nord'),
(164, 'Soulédé-Roua', 'Mayo-Tsanaga', 'Extrême-Nord'),
(165, 'Tchati-Bali', 'Mayo-Danay', 'Extrême-Nord'),
(166, 'Tokombéré', 'Mayo-Sava', 'Extrême-Nord'),
(167, 'Touloum', 'Mayo-Kani', 'Extrême-Nord'),
(168, 'Waza', 'Logone-et-Chari', 'Extrême-Nord'),
(169, 'Wina', 'Mayo-Danay', 'Extrême-Nord'),
(170, 'Yagoua', 'Mayo-Danay', 'Extrême-Nord'),
(171, 'Zina', 'Logone-et-Chari', 'Extrême-Nord'),
(172, 'Baré', 'Moungo', 'Littoral'),
(173, 'Bonaléa', 'Moungo', 'Littoral'),
(174, 'Dibamba', 'Sanaga-Maritime', 'Littoral'),
(175, 'Dibombari', 'Moungo', 'Littoral'),
(176, 'Dizangué', 'Sanaga-Maritime', 'Littoral'),
(177, 'Douala Ier', 'Wouri', 'Littoral'),
(178, 'Douala IIe', 'Wouri', 'Littoral'),
(179, 'Douala IIIe', 'Wouri', 'Littoral'),
(180, 'Douala IVe', 'Wouri', 'Littoral'),
(181, 'Douala Ve', 'Wouri', 'Littoral'),
(182, 'Douala VIe', 'Wouri', 'Littoral'),
(183, 'Ebone', 'Moungo', 'Littoral'),
(184, 'Édéa Ier', 'Sanaga-Maritime', 'Littoral'),
(185, 'Édéa IIe', 'Sanaga-Maritime', 'Littoral'),
(186, 'Loum', 'Moungo', 'Littoral'),
(187, 'Manjo', 'Moungo', 'Littoral'),
(188, 'Massock-Songloulou', 'Sanaga-Maritime', 'Littoral'),
(189, 'Mbanga', 'Moungo', 'Littoral'),
(190, 'Melong', 'Moungo', 'Littoral'),
(191, 'Mombo', 'Moungo', 'Littoral'),
(192, 'Mouanko', 'Sanaga-Maritime', 'Littoral'),
(193, 'Ndobian', 'Nkam', 'Littoral'),
(194, 'Ndom', 'Sanaga-Maritime', 'Littoral'),
(195, 'Ngambe', 'Sanaga-Maritime', 'Littoral'),
(196, 'Ngwei', 'Sanaga-Maritime', 'Littoral'),
(197, 'Nkondjock', 'Nkam', 'Littoral'),
(198, 'Nkongsamba Ier', 'Moungo', 'Littoral'),
(199, 'Nkongsamba IIe', 'Moungo', 'Littoral'),
(200, 'Nkongsamba IIIe', 'Moungo', 'Littoral'),
(201, 'Nyanon', 'Sanaga-Maritime', 'Littoral'),
(202, 'Penja', 'Moungo', 'Littoral'),
(203, 'Pouma', 'Sanaga-Maritime', 'Littoral'),
(204, 'Yabassi', 'Nkam', 'Littoral'),
(205, 'Yingui', 'Nkam', 'Littoral'),
(206, 'Barndaké', 'Bénoué', 'Nord'),
(207, 'Bashéo', 'Bénoué', 'Nord'),
(208, 'Beka', 'Faro', 'Nord'),
(209, 'Bibemi', 'Bénoué', 'Nord'),
(210, 'Dembo', 'Bénoué', 'Nord'),
(211, 'Figuil', 'Mayo-Louti', 'Nord'),
(212, 'Garoua Ier', 'Bénoué', 'Nord'),
(213, 'Garoua IIe', 'Bénoué', 'Nord'),
(214, 'Garoua IIIe', 'Bénoué', 'Nord'),
(215, 'Gashiga', 'Bénoué', 'Nord'),
(216, 'Guider', 'Mayo-Louti', 'Nord'),
(217, 'Lagdo', 'Bénoué', 'Nord'),
(218, 'Madingring', 'Mayo-Rey', 'Nord'),
(219, 'Mayo-Oulo', 'Mayo-Louti', 'Nord'),
(220, 'Ngong', 'Bénoué', 'Nord'),
(221, 'Pitoa', 'Bénoué', 'Nord'),
(222, 'Poli', 'Faro', 'Nord'),
(223, 'Rey-Bouba', 'Mayo-Rey', 'Nord'),
(224, 'Tcholliré', 'Mayo-Rey', 'Nord'),
(225, 'Touboro', 'Mayo-Rey', 'Nord'),
(226, 'Touroua', 'Bénoué', 'Nord'),
(227, 'Ako', 'Donga-Mantung', 'Nord-Ouest'),
(228, 'Andek', 'Momo', 'Nord-Ouest'),
(229, 'Babessi', 'Ngo-Ketunjia', 'Nord-Ouest'),
(230, 'Bafut', 'Mezam', 'Nord-Ouest'),
(231, 'Bali', 'Mezam', 'Nord-Ouest'),
(232, 'Balikumbat', 'Ngo-Ketunjia', 'Nord-Ouest'),
(233, 'Bamenda Ier', 'Mezam', 'Nord-Ouest'),
(234, 'Bamenda IIe', 'Mezam', 'Nord-Ouest'),
(235, 'Bamenda IIIe', 'Mezam', 'Nord-Ouest'),
(236, 'Batibo', 'Momo', 'Nord-Ouest'),
(237, 'Belo', 'Boyo', 'Nord-Ouest'),
(238, 'Benakuma', 'Menchum', 'Nord-Ouest'),
(239, 'Elak-Oku', 'Bui', 'Nord-Ouest'),
(240, 'Fonfuka', 'Boyo', 'Nord-Ouest'),
(241, 'Fundong', 'Boyo', 'Nord-Ouest'),
(242, 'Furu-Awa', 'Menchum', 'Nord-Ouest'),
(243, 'Jakiri', 'Bui', 'Nord-Ouest'),
(244, 'Kumbo', 'Bui', 'Nord-Ouest'),
(245, 'Mbengwi', 'Momo', 'Nord-Ouest'),
(246, 'Mbiame', 'Bui', 'Nord-Ouest'),
(247, 'Misaje', 'Donga-Mantung', 'Nord-Ouest'),
(248, 'Ndop', 'Ngo-Ketunjia', 'Nord-Ouest'),
(249, 'Ndu', 'Donga-Mantung', 'Nord-Ouest'),
(250, 'Njikwa', 'Momo', 'Nord-Ouest'),
(251, 'Njinikom', 'Boyo', 'Nord-Ouest'),
(252, 'Nkambé', 'Donga-Mantung', 'Nord-Ouest'),
(253, 'Nkor', 'Bui', 'Nord-Ouest'),
(254, 'Nkum', 'Bui', 'Nord-Ouest'),
(255, 'Nwa', 'Donga-Mantung', 'Nord-Ouest'),
(256, 'Santa', 'Mezam', 'Nord-Ouest'),
(257, 'Tubah', 'Mezam', 'Nord-Ouest'),
(258, 'Widikum-Boffe', 'Momo', 'Nord-Ouest'),
(259, 'Wum', 'Menchum', 'Nord-Ouest'),
(260, 'Zhoa', 'Menchum', 'Nord-Ouest'),
(261, 'Babadjou', 'Bamboutos', 'Ouest'),
(262, 'Bafang', 'Haut-Nkam', 'Ouest'),
(263, 'Bafoussam Ier', 'Mifi', 'Ouest'),
(264, 'Bafoussam IIe', 'Mifi', 'Ouest'),
(265, 'Bafoussam IIIe', 'Mifi', 'Ouest'),
(266, 'Baham', 'Hauts-Plateaux', 'Ouest'),
(267, 'Bakou', 'Haut-Nkam', 'Ouest'),
(268, 'Bamendjou', 'Hauts-Plateaux', 'Ouest'),
(269, 'Bana', 'Haut-Nkam', 'Ouest'),
(270, 'Bandja', 'Haut-Nkam', 'Ouest'),
(271, 'Bandjoun', 'Koung-Khi', 'Ouest'),
(272, 'Bangangté', 'Ndé', 'Ouest'),
(273, 'Banka', 'Haut-Nkam', 'Ouest'),
(274, 'Bangou', 'Hauts-Plateaux', 'Ouest'),
(275, 'Bangourain', 'Noun', 'Ouest'),
(276, 'Banwa', 'Haut-Nkam', 'Ouest'),
(277, 'Bassamba', 'Ndé', 'Ouest'),
(278, 'Batcham', 'Bamboutos', 'Ouest'),
(279, 'Batié', 'Hauts-Plateaux', 'Ouest'),
(280, 'Bayangam', 'Koung-Khi', 'Ouest'),
(281, 'Bazou', 'Ndé', 'Ouest'),
(282, 'Demdeng', 'Koung-Khi', 'Ouest'),
(283, 'Dschang', 'Menoua', 'Ouest'),
(284, 'Fokoué', 'Menoua', 'Ouest'),
(285, 'Fongo-Tongo', 'Menoua', 'Ouest'),
(286, 'Foumban', 'Noun', 'Ouest'),
(287, 'Foumbot', 'Noun', 'Ouest'),
(288, 'Galim', 'Bamboutos', 'Ouest'),
(289, 'Kekem', 'Haut-Nkam', 'Ouest'),
(290, 'Kouoptamo', 'Noun', 'Ouest'),
(291, 'Koutaba', 'Noun', 'Ouest'),
(292, 'Magba', 'Noun', 'Ouest'),
(293, 'Malentouen', 'Noun', 'Ouest'),
(294, 'Massangam', 'Noun', 'Ouest'),
(295, 'Mbouda', 'Bamboutos', 'Ouest'),
(296, 'Njimom', 'Noun', 'Ouest'),
(297, 'Nkong-Zem', 'Menoua', 'Ouest'),
(298, 'Penka-Michel', 'Menoua', 'Ouest'),
(299, 'Santchou', 'Menoua', 'Ouest'),
(300, 'Tonga', 'Ndé', 'Ouest'),
(301, 'Akom II', 'Océan', 'Sud'),
(302, 'Ambam', 'Vallée-du-Ntem', 'Sud'),
(303, 'Bengbis', 'Dja-et-Lobo', 'Sud'),
(304, 'Bipindi', 'Océan', 'Sud'),
(305, 'Biwong-Bane', 'Mvila', 'Sud'),
(306, 'Biwong-Bulu', 'Mvila', 'Sud'),
(307, 'Campo', 'Océan', 'Sud'),
(308, 'Djoum', 'Dja-et-Lobo', 'Sud'),
(309, 'Ebolowa Ier', 'Mvila', 'Sud'),
(310, 'Ebolowa IIe', 'Mvila', 'Sud'),
(311, 'Efoulan', 'Mvila', 'Sud'),
(312, 'Kribi Ier', 'Océan', 'Sud'),
(313, 'Kribi IIe', 'Océan', 'Sud'),
(314, 'Kyé-Ossi', 'Vallée-du-Ntem', 'Sud'),
(315, 'Lokoundjé', 'Océan', 'Sud'),
(316, 'Lolodorf', 'Océan', 'Sud'),
(317, 'Ma\'an', 'Vallée-du-Ntem', 'Sud'),
(318, 'Mengong', 'Mvila', 'Sud'),
(319, 'Meyomessala', 'Mfoundi', 'Sud'),
(320, 'Meyomessi', 'Mfoundi', 'Sud'),
(321, 'Mintom', 'Dja-et-Lobo', 'Sud'),
(322, 'Mvangan', 'Mvila', 'Sud'),
(323, 'Mvengue', 'Océan', 'Sud'),
(324, 'Ngoulemakong', 'Mvila', 'Sud'),
(325, 'Niete', 'Océan', 'Sud'),
(326, 'Olamze', 'Vallée-du-Ntem', 'Sud'),
(327, 'Oveng', 'Dja-et-Lobo', 'Sud'),
(328, 'Sangmélima', 'Dja-et-Lobo', 'Sud'),
(329, 'Zoétélé', 'Dja-et-Lobo', 'Sud'),
(330, 'Akwaya', 'Manyu', 'Sud-Ouest'),
(331, 'Alou', 'Lebialem', 'Sud-Ouest'),
(332, 'Bamusso', 'Ndian', 'Sud-Ouest'),
(333, 'Bangem', 'Koupé-Manengouba', 'Sud-Ouest'),
(334, 'Buéa', 'Fako', 'Sud-Ouest'),
(335, 'Dikome-Balue', 'Ndian', 'Sud-Ouest'),
(336, 'Ekondo-Titi', 'Ndian', 'Sud-Ouest'),
(337, 'Eyumodjock', 'Manyu', 'Sud-Ouest'),
(338, 'Idabato', 'Ndian', 'Sud-Ouest'),
(339, 'Isanguele', 'Ndian', 'Sud-Ouest'),
(340, 'Kombo-Abedimo', 'Ndian', 'Sud-Ouest'),
(341, 'Kombo-Idinti', 'Ndian', 'Sud-Ouest'),
(342, 'Konye', 'Meme', 'Sud-Ouest'),
(343, 'Kumba Ier', 'Meme', 'Sud-Ouest'),
(344, 'Kumba IIe', 'Meme', 'Sud-Ouest'),
(345, 'Kumba IIIe', 'Meme', 'Sud-Ouest'),
(346, 'Limbé Ier', 'Fako', 'Sud-Ouest'),
(347, 'Limbé IIe', 'Fako', 'Sud-Ouest'),
(348, 'Limbé IIIe', 'Fako', 'Sud-Ouest'),
(349, 'Mamfé', 'Manyu', 'Sud-Ouest'),
(350, 'Mbonge', 'Meme', 'Sud-Ouest'),
(351, 'Menji', 'Lebialem', 'Sud-Ouest'),
(352, 'Mundemba', 'Ndian', 'Sud-Ouest'),
(353, 'Muyuka', 'Fako', 'Sud-Ouest'),
(354, 'Nguti', 'Koupé-Manengouba', 'Sud-Ouest'),
(355, 'Tiko', 'Fako', 'Sud-Ouest'),
(356, 'Toko', 'Ndian', 'Sud-Ouest'),
(357, 'Tombel', 'Koupé-Manengouba', 'Sud-Ouest'),
(358, 'Upper Bayang', 'Manyu', 'Sud-Ouest'),
(359, 'Wabane', 'Lebialem', 'Sud-Ouest'),
(360, 'West Coast', 'Fako', 'Sud-Ouest');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2016_01_01_000000_add_voyager_user_fields', 2),
(5, '2016_01_01_000000_create_data_types_table', 2),
(6, '2016_05_19_173453_create_menu_table', 2),
(7, '2016_10_21_190000_create_roles_table', 2),
(8, '2016_10_21_190000_create_settings_table', 2),
(9, '2016_11_30_135954_create_permission_table', 2),
(10, '2016_11_30_141208_create_permission_role_table', 2),
(11, '2016_12_26_201236_data_types__add__server_side', 2),
(12, '2017_01_13_000000_add_route_to_menu_items_table', 2),
(13, '2017_01_14_005015_create_translations_table', 2),
(14, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 2),
(15, '2017_03_06_000000_add_controller_to_data_types_table', 2),
(16, '2017_04_21_000000_add_order_to_data_rows_table', 2),
(17, '2017_07_05_210000_add_policyname_to_data_types_table', 2),
(18, '2017_08_05_000000_add_group_to_settings_table', 2),
(19, '2017_11_26_013050_add_user_role_relationship', 2),
(20, '2017_11_26_015000_create_user_roles_table', 2),
(21, '2018_03_11_000000_add_user_settings', 3),
(22, '2018_03_14_000000_add_details_to_data_types_table', 3),
(23, '2018_03_16_000000_make_settings_value_nullable', 3),
(24, '2019_08_19_000000_create_failed_jobs_table', 3),
(25, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(26, '2024_12_16_144106_create_permission_tables', 4),
(27, '2025_03_10_090000_create_historical_foundation_tables', 5),
(28, '2025_03_10_091000_create_postes_table', 5),
(29, '2025_03_10_091500_align_users_with_historical_schema', 5),
(30, '2025_03_10_092000_create_historical_enterprise_core_tables', 5),
(31, '2025_03_10_093000_create_historical_instruction_and_questionnaire_tables', 5),
(32, '2025_03_11_100001_create_evaluation_frameworks_table', 5),
(33, '2025_03_11_100002_create_evaluation_categories_table', 5),
(34, '2025_03_11_100003_create_evaluation_indicators_table', 5),
(35, '2025_03_11_100004_create_evaluation_score_thresholds_table', 5),
(36, '2025_03_11_100005_create_evaluation_settings_table', 5),
(37, '2025_03_11_100006_create_entreprise_evaluation_profiles_table', 5),
(38, '2025_03_11_100007_create_dossier_esg_evaluations_table', 5),
(39, '2025_03_11_100008_create_dossier_esg_evaluation_items_table', 5),
(40, '2025_06_04_180325_create_notifications_table', 5),
(41, '2025_07_21_165044_create_jobs_table', 5),
(42, '2026_03_19_105313_add_conclusions_ca_to_dossiers_table', 5),
(43, '2026_04_17_120000_add_prospect_workflow_columns_to_entreprises_table', 5),
(44, '2026_04_18_140000_add_promotion_client_columns_to_entreprises_table', 5),
(45, '2026_04_18_200000_bcpme_dossier_eer_analyse_critique_pieces_tables', 5),
(46, '2026_04_18_230000_drop_spatie_permission_tables', 6),
(47, '2026_04_18_230100_drop_legacy_role_tables_after_spatie_removal', 7),
(48, '2026_04_18_230200_drop_esg_feature_tables', 8),
(49, '2026_04_18_230300_drop_unwanted_historical_and_voyager_tables', 9),
(50, '2026_04_18_235500_make_prospect_fields_nullable_on_entreprises_table', 10),
(51, '2026_04_19_000100_add_geo_and_turnover_fields_to_entreprises_table', 11),
(52, '2026_04_19_140000_add_prospect_rejection_columns_to_entreprises_table', 12),
(53, '2026_04_19_160000_add_qualification_agence_validation_to_eer', 13),
(54, '2026_04_19_000200_add_village_ou_quartier_to_entreprises_table', 14),
(55, '2026_04_19_090000_add_commentaire_to_tiers_table', 14),
(56, '2026_04_19_120000_seed_bc_pme_profils_referential', 14),
(57, '2026_04_19_130000_add_profil_chef_filiere_24', 14),
(58, '2026_04_19_160000_add_exploitation_workflow_to_dossiers_table', 15),
(59, '2026_04_19_170000_add_exploitation_analyste_assignment_audit_to_dossiers_table', 16),
(60, '2026_04_20_120000_add_exploitation_instruction_submission_to_dossiers_table', 17),
(61, '2026_04_20_150000_add_juridique_instruction_submission_to_dossiers_table', 18),
(62, '2026_04_20_160000_add_juridique_analyste_workflow_to_dossiers_table', 19),
(63, '2026_04_20_170000_add_reng_analyste_credit_workflow_to_dossiers_table', 20),
(64, '2026_04_21_120000_add_rerx_risques_analyst_workflow_to_dossiers_table', 20),
(65, '2026_04_19_200000_instruction_dossier_multi_programme', 21),
(66, '2026_04_19_160000_instruction_bundle_reject_columns', 22),
(67, '2026_04_22_100000_add_chef_filiere_submission_to_dossiers_table', 23),
(68, '2026_04_22_110000_null_programme_id_on_dossiers_with_instruction_lignes', 23),
(69, '2026_04_22_120000_add_instruction_agence_decision_to_dossiers', 23),
(70, '2026_04_19_210000_add_exploitation_analyste_instruction_avis_to_dossiers_table', 24),
(71, '2026_04_23_100000_create_organisation_entites_table', 25),
(72, '2026_04_23_110000_add_organisation_fields_to_users_table', 25),
(73, '2026_04_23_120000_add_audit_fields_to_dossiers_table', 26),
(74, '2026_04_23_130000_add_more_audit_fields_to_dossiers_table', 27),
(75, '2026_04_23_150000_drop_individual_from_entreprises_table', 28),
(76, '2026_04_24_100000_instruction_delegation_pouvoir', 28),
(77, '2026_04_24_150000_add_instruction_agence_ca_avis_to_dossiers_table', 29),
(78, '2026_04_24_160000_add_instruction_ca_transmitted_to_exploitation_to_dossiers', 30),
(79, '2026_04_23_140000_add_dossier_audit_to_fichiers_table', 31),
(80, '2026_04_25_100000_add_exploitation_af_instruction_sections_to_dossiers_table', 32),
(81, '2026_04_25_200000_rename_analyste_instruction_transmission_columns_on_dossiers', 32),
(82, '2026_04_23_140000_add_qualification_rejection_by_agence_to_eer', 33),
(83, '2026_04_24_170000_add_active_to_fichiers_types_table', 34),
(84, '2026_04_26_100000_add_instruction_closure_fields_to_dossiers', 34),
(85, '2026_04_26_120000_remove_legacy_agent_programme_profil', 35),
(86, '2026_04_29_130000_create_entreprise_sites_table', 36),
(87, '2026_04_29_131000_create_entreprise_equipe_membres_table', 36),
(88, '2026_04_29_132000_add_cni_fields_to_entreprise_equipe_membres_table', 37),
(89, '2026_04_29_160000_create_entreprise_critere_avis_table', 38),
(90, '2026_05_06_100000_add_pole_analyste_reject_columns_to_dossiers', 39),
(91, '2026_05_06_120000_add_inter_pole_reject_columns_to_dossiers', 40);

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

CREATE TABLE `niveaux` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `niveaux`
--

INSERT INTO `niveaux` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'NON SCOLARISE(E)', 1, NULL, NULL),
(2, 'PRIMAIRE', 1, NULL, NULL),
(3, 'SECONDAIRE', 1, NULL, NULL),
(4, 'UNIVERSITAIRE', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `organisation_entites`
--

CREATE TABLE `organisation_entites` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_prefix` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `organisation_entites`
--

INSERT INTO `organisation_entites` (`id`, `key`, `name`, `type`, `route_prefix`, `active`, `created_at`, `updated_at`) VALUES
(1, 'pole_exploitation', 'Pôle exploitation', 'pole', 'respexp', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(2, 'pole_juridique', 'Pôle juridique', 'pole', 'juridique', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(3, 'pole_engagements', 'Pôle engagements', 'pole', 'reng', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(4, 'pole_risques', 'Pôle risques', 'pole', 'rerx', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(5, 'direction_generale', 'Direction générale', 'direction_generale', 'dg', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(6, 'conseil_administration', 'Conseil d’administration', 'conseil_administration', NULL, 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(7, 'audit_interne', 'Audit interne', 'audit_ci', 'respaud', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56'),
(8, 'controle_interne', 'Contrôle interne', 'audit_ci', 'respci', 1, '2026-04-23 07:06:56', '2026-04-23 07:06:56');

-- --------------------------------------------------------

--
-- Structure de la table `organismes`
--

CREATE TABLE `organismes` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `abb` varchar(20) DEFAULT NULL,
  `type_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `pay_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `organismes`
--

INSERT INTO `organismes` (`id`, `name`, `abb`, `type_id`, `parent_id`, `pay_id`) VALUES
(1, 'Banque africaine de développement (BAD)', 'BAD', 1, 0, 0),
(2, 'Le Fonds africain de développement (FAD)', 'FAD', 1, 0, 1),
(3, 'Fonds fiduciaire multi-donateurs pour la gouvernance', NULL, 1, 0, 1),
(4, 'Fonds fiduciaire pour l\'aide au commerce', NULL, 1, 0, 1),
(5, 'Fonds fiduciaire multi-donateurs pour la microfinance', NULL, 1, 0, 1),
(6, 'Fonds fiduciaire pour l\'adaptation au changement climatique', NULL, 1, 0, 1),
(7, 'Banque mondiale', NULL, 1, 0, 0),
(8, 'Fonds monétaire international (FMI)', 'FMI', 1, 0, 0),
(9, 'Banque chinoise de développement', NULL, 1, 0, 0),
(10, 'Programme de Développement des Nations Unies (PNUD)', NULL, 2, 0, 0),
(11, 'Organisation de coopération et de développement économiques (OCDE)', NULL, 2, 0, 0),
(12, 'Programme d’aide publique au développement (APD)', NULL, 2, 0, 11),
(13, 'Plateforme de l\'OCDE sur le financement durable des PME', NULL, 2, 0, 0),
(14, 'Organisation des Nations Unies pour l\'alimentation et l\'agriculture (FAO)', 'FAO', 2, 0, 0),
(15, 'Fonds international de développement agricole (FIDA)', 'FIDA', 2, 0, 0),
(16, 'Programme alimentaire mondial (PAM)', 'PAM', 2, 0, 0),
(17, 'Agence américaine pour le développement international (USAID)', 'USAID', 3, 0, 0),
(18, 'Bureau des affaires étrangères, du Commonwealth et du développement (FCDO) du Royaume-Uni', 'FCDO', 3, 0, 0),
(19, 'Agence française de développement (AFD)', 'AFD', 3, 0, 0),
(20, 'Banque allemande de développement (KfW)', 'KFW', 3, 0, 0),
(21, 'GIZ (Deutsche Gesellschaft für Internationale Zusammenarbeit)', 'GIZ', 3, 0, 0),
(22, 'Agence suédoise de coopération internationale pour le développement (Sida)', 'SIDA', 3, 0, 0),
(23, 'Agence norvégienne de coopération pour le développement (Norad)', 'NORAD', 3, 0, 0),
(24, 'Agence finlandaise pour le développement (Finnida)', 'FINNIDA', 3, 0, 0),
(25, 'Agence canadienne de développement international (ACDI)\r\n', 'ACDI', 3, 0, 0),
(26, 'Agence japonaise de coopération internationale (JICA)', 'JICA', 3, 0, 0),
(27, 'Corporation financière internationale (IFC)', 'IFC', 4, 0, 0),
(28, 'Banque africaine d\'import-export (Afreximbank)', 'Afreximbank', 4, 0, 0),
(29, 'Banque de développement de l\'Afrique australe (DBSA) ', 'DBSA', 5, 0, 0),
(30, 'Banque de développement de l\'Afrique de l\'Est (EADB)', 'EADB', 5, 0, 0),
(31, 'Helios Investment Partners', '', 6, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `persons`
--

CREATE TABLE `persons` (
  `id` int NOT NULL,
  `name` varchar(155) DEFAULT NULL,
  `niu` varchar(22) DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text,
  `token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `persons`
--

INSERT INTO `persons` (`id`, `name`, `niu`, `user_id`, `email`, `phone`, `address`, `token`, `created_at`, `updated_at`) VALUES
(14, 'Olama remy', '789830332', 2, 'r.olama@gmail.com', '6588932032', 'Quelque part dans la ville de Doula', '887f6ea8f0d65631cc192c14581c21f3c03216e2', NULL, NULL),
(15, 'KAMLA', '6736283833', 2, 'info@abc.cm', '678798988', 'Quartier Golf, Yaounde', '1d82c7bf542ef633d728451587bcac453a101c3b', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `piece_exigible_definitions`
--

CREATE TABLE `piece_exigible_definitions` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `piece_exigible_definitions`
--

INSERT INTO `piece_exigible_definitions` (`id`, `label`, `description`, `sort_order`, `active`, `created_at`, `updated_at`) VALUES
(1, 'RCCM', 'Le registre de commerce', 0, 1, '2026-04-18 11:47:00', '2026-04-18 11:47:00'),
(2, 'NIU', 'Le numéro d\'identification unique', 1, 1, '2026-04-18 11:47:46', '2026-04-18 11:47:46');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int NOT NULL,
  `code` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int NOT NULL DEFAULT '0',
  `filiere_id` int NOT NULL DEFAULT '0',
  `branche_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `code`, `name`, `parent_id`, `filiere_id`, `branche_id`) VALUES
(1, '001001', 'Céréales', 0, 0, 0),
(2, '001001001', 'Blé ', 1, 0, 0),
(3, '001001002', 'Maïs sec ', 1, 0, 0),
(4, '001001003', 'Mil, sorgho et fonio ', 1, 0, 0),
(5, '001001004', 'Riz paddy ', 1, 0, 0),
(6, '001001005', 'Autres céréales ', 1, 0, 0),
(7, '001002', 'Racines et tubercules ', 0, 0, 0),
(8, '001002001', 'Manioc frais ', 7, 0, 0),
(9, '001002002', 'Manioc séché ( y.c le manioc sous forme de cossette) ', 7, 0, 0),
(10, '001002003', 'Macabo et Taro ', 7, 0, 0),
(11, '001002004', 'pomme de   terre ', 7, 0, 0),
(12, '001002005', 'Autres racines et tubercules ', 7, 0, 0),
(13, '001003', 'Bananes ', 0, 0, 0),
(14, '001003001', 'Bananes plantains ', 13, 0, 0),
(15, '001003002', 'Bananes douces ', 13, 0, 0),
(16, '001004', 'Produits du palmier à huile ', 0, 0, 0),
(17, '001004000', 'Noix de palme ', 16, 0, 0),
(18, '001005', 'Produits d’autres cultures  oléagineuses ', 0, 0, 0),
(19, '001005001', 'Arachides graines ', 18, 0, 0),
(20, '001005002', 'Soja ', 18, 0, 0),
(21, '001005003', 'Autres plantes oléagineuses ', 18, 0, 0),
(22, '001006', 'Coton ', 0, 0, 0),
(23, '001006000', 'Coton brut ', 22, 0, 0),
(24, '001007', 'Fruits et légumes (n.c les fruits sauvages et la banane) ', 0, 0, 0),
(25, '001007001', 'Haricots doliques et niébé ', 24, 0, 0),
(26, '001007002', 'Autres légumineuses et légumes secs ', 24, 0, 0),
(27, '001007003', 'Epices et condiments ', 24, 0, 0),
(28, '001007004', 'Légumes, feuilles et champignons ', 24, 0, 0),
(29, '001007005', 'Agrumes ', 24, 0, 0),
(30, '001007006', 'Ananas ', 24, 0, 0),
(31, '001007007', 'Autres fruits ', 24, 0, 0),
(32, '001008', 'Plants, fleurs et autres produits de l\'horticulture ', 0, 0, 0),
(33, '001008000', 'Plants, fleurs et autres produits de l’horticulture ', 32, 0, 0),
(34, '001009', 'Produits de la culture du cacao ', 0, 0, 0),
(35, '001009000', 'Fèves de cacao séchées ', 34, 0, 0),
(36, '001010', 'Café et thé ', 0, 0, 0),
(37, '001010001', 'Grains de café arabica dépulpés et séchés ', 36, 0, 0),
(38, '001010002', 'Grains de café robusta séchés ', 36, 0, 0),
(39, '001010003', 'Thé ', 36, 0, 0),
(40, '001011', 'Autres produits cultivés n.c.a. ', 0, 0, 0),
(41, '001011001', 'Tabac brut y compris tabac séché ', 40, 0, 0),
(42, '001011002', 'Latex y compris caoutchouc brut ', 40, 0, 0),
(43, '001011003', 'Canne à sucre y compris betterave à sucre ', 40, 0, 0),
(44, '001011004', 'Plantes aromatiques ou médicinales ', 40, 0, 0),
(45, '001011005', 'Noix de kola, bitter kola, autres stupéfiants n.c.a ', 40, 0, 0),
(46, '001011006', 'Produits végétaux cultivés n.c.a. ', 40, 0, 0),
(47, '001012', 'Produits de l’égrenage et des autres services de soutien à l\'agriculture ', 0, 0, 0),
(48, '001012001', 'Coton fibre (coton égrené) ', 47, 0, 0),
(49, '001012002', 'Graine de coton ', 47, 0, 0),
(50, '001012003', 'Autres services de soutien à l\'agriculture', 47, 0, 0),
(51, '002001', 'Produits de l\'élevage de bovins', 0, 0, 0),
(52, '002001001', 'Bovins sur pieds ', 51, 0, 0),
(53, '002001002', 'Lait de vache brut ', 51, 0, 0),
(54, '002002', 'Produits de l\'élevage des petits ruminants ', 0, 0, 0),
(55, '002002001', 'Ovins sur pieds  ', 54, 0, 0),
(56, '002002002', 'Caprins sur pieds ', 54, 0, 0),
(57, '002002003', 'Lait brut de brebis, lait brut de chèvres ', 54, 0, 0),
(58, '002002004', 'Laine et poils bruts d’ovins ', 54, 0, 0),
(59, '002003', 'Produits de l’élevage des porcins', 0, 0, 0),
(60, '002003000', 'Porcins sur pieds ', 59, 0, 0),
(61, '002004', 'Produits de l’élevage de la volaille ', 0, 0, 0),
(62, '002004001', 'Poulets ', 61, 0, 0),
(63, '002004002', 'Autres volailles (pigeons, dindes, oies, Pintades, canards, etc) ', 61, 0, 0),
(64, '002004003', 'Œufs de poule ', 61, 0, 0),
(65, '002004004', 'Œufs d’autres volailles ', 61, 0, 0),
(66, '002005', 'Produits de l’élevage des équins et asins ', 0, 0, 0),
(67, '002005001', 'Equins  ', 66, 0, 0),
(68, '002005002', 'Asins et mulets ', 66, 0, 0),
(69, '002006', 'Produits de l’apiculture ', 0, 0, 0),
(70, '002006001', 'Miel  ', 69, 0, 0),
(71, '002006002', 'Cire d’abeilles et gelée royale ', 69, 0, 0),
(72, '002007', 'Produits de l’élevage des  animaux n.c.a. ', 0, 0, 0),
(73, '002007001', 'Produits de l’élevage dit non conventionnel ', 72, 0, 0),
(74, '002007002', 'Animaux de compagnies ', 72, 0, 0),
(75, '002007003', 'Autres animaux et autres produits d’animaux n.c.a ', 72, 0, 0),
(76, '002008', 'Produits de la chasse et du piégeage ', 0, 0, 0),
(77, '002008001', 'Gibier frais, fumé ou séché ', 76, 0, 0),
(78, '002008002', 'Trophées d’animaux de la chasse (plumes, ivoire, peau, ', 76, 0, 0),
(79, '002008003', 'Services annexes à la chasse ', 76, 0, 0),
(80, '002009', 'Services de soutien à l\'élevage ', 0, 0, 0),
(81, '002009001', 'service d’extraction et d’insémination artificielle de bovins ', 80, 0, 0),
(82, '002009002', 'services de gardiennage et de conduite de troupeaux ', 80, 0, 0),
(83, '002009003', 'autres services de soutien à l\'élevage', 80, 0, 0),
(84, '003001', 'Produits de la sylviculture et de l\'exploitation forestière', 0, 0, 0),
(85, '003001001', 'Produits de la sylviculture sur pied ', 84, 0, 0),
(86, '003001002', 'Pépinières forestières ', 84, 0, 0),
(87, '003001003', 'Produits de l\'exploitation forestière ', 84, 0, 0),
(88, '003001004', 'Produits de la cueillette et produits forestiers poussant à l\'état sauvage ', 84, 0, 0),
(89, '003002', 'Services de conservation des forêts et aires protégés ', 0, 0, 0),
(90, '003002000', 'Crédit carbone ', 89, 0, 0),
(91, '003003', 'Services de soutien à la sylviculture et à l\'exploitation forestière ', 0, 0, 0),
(92, '003003001', 'Services annexes à la sylviculture ', 91, 0, 0),
(93, '003003002', 'Services annexes à l\'exploitation forestière', 91, 0, 0),
(94, '004000', 'Produits de la pêche (artisanale, industrielle), de la pisciculture et de l’aquaculture', 0, 0, 0),
(95, '004000001', 'Poissons frais ', 94, 0, 0),
(96, '004000002', 'Crustacés, mollusques et autres produits de la pêche et de l’aquaculture ', 94, 0, 0),
(97, '004000003', 'Services annexes à la pêche et à l’aquaculture', 94, 0, 0),
(98, '005001003', 'Autres produits énergétiques ', 103, 0, 0),
(99, '005002', 'Services de soutien à l\'extraction d\'hydrocarbures et d’autres produits énergétiques ', 0, 0, 0),
(100, '005002000', 'Services de soutien à l\'extraction d\'hydrocarbures ', 107, 0, 0),
(101, '005003', 'Services de soutien aux industries extractives ', 0, 0, 0),
(102, '005003000', 'Services de soutien aux industries extractives', 109, 0, 0),
(103, '005001', 'Produits de l\'extraction d\'hydrocarbures', 0, 0, 0),
(104, '005001001', 'Pétrole brut ', 103, 0, 0),
(105, '005001002', 'Gaz naturel ', 103, 0, 0),
(106, '005001003', 'Autres produits énergétiques ', 103, 0, 0),
(107, '005002', 'Services de soutien à l\'extraction d\'hydrocarbures et d’autres produits énergétiques ', 0, 0, 0),
(108, '005002000', 'Services de soutien à l\'extraction d\'hydrocarbures ', 107, 0, 0),
(109, '005003', 'Services de soutien aux industries extractives ', 0, 0, 0),
(110, '005003000', 'Services de soutien aux industries extractives', 109, 0, 0),
(111, '6001', 'Produits de l\'extraction de minerais métalliques', 0, 0, 0),
(112, '6001001', 'Bauxite', 111, 0, 0),
(113, '6001002', 'Minerais de fer', 111, 0, 0),
(114, '6001003', 'Autres minerais métalliques', 111, 0, 0),
(115, '6002', 'Produits de l\'extraction de minéraux non métalliques', 0, 0, 0),
(116, '6002001', 'Gypse et pierre à ciment’(n.c. clinkers)', 115, 0, 0),
(117, '6002002', 'Sel (sel gemme, sel de mer, sel de table, saumure)', 115, 0, 0),
(118, '6002003', 'Diamants industriels et abrasifs naturels', 115, 0, 0),
(119, '6002004', 'Autres minéraux non métalliques', 115, 0, 0),
(120, '6003', 'Services de soutien aux industries minières', 0, 0, 0),
(121, '6003000', 'Services de soutien aux industries minières', 120, 0, 0),
(122, '7001', 'Produits de transformation et de conservation de viande et produits dérivés', 0, 0, 0),
(123, '7001001', 'Viande de bovins', 122, 0, 0),
(124, '7001002', 'Viande d\'ovins et de caprins', 122, 0, 0),
(125, '7001003', 'Viande de porc', 122, 0, 0),
(126, '7001004', 'Volailles abattues', 122, 0, 0),
(127, '7001005', 'Autres viandes de boucherie et produits d\'abatage', 122, 0, 0),
(128, '7001006', 'Cuir et peaux brutes', 122, 0, 0),
(129, '7001007', 'Conserves à base de viande', 122, 0, 0),
(130, '7002', 'Produits de la transformation et de la conservation du poisson et des produits de la pêche', 0, 0, 0),
(131, '7002001', 'Poissons frais congelés et surgelés', 130, 0, 0),
(132, '7002002', 'Poissons séchés, salés, fumés', 130, 0, 0),
(133, '7002003', 'coquillages, crustacés et', 130, 0, 0),
(134, '', 'mollusques congelés, surgelés ou en conserves', 0, 0, 0),
(135, '7002004', 'crustacés et mollusques salés, fumés, séchés', 130, 0, 0),
(136, '7002005', 'farine de poisson', 130, 0, 0),
(137, '7002006', 'Conserves à base de poisson', 130, 0, 0),
(138, '7003', 'Services de soutien à l’industrie de la viande', 0, 0, 0),
(139, '7003001', 'Services d’abattage et d’éviscération des animaux de boucherie (bovins, ovins et caprins, porcins etc.)', 138, 0, 0),
(140, '7003002', 'Services de lavage des animaux de viande de boucherie (bovins, ovins et caprins, porcins etc.)', 138, 0, 0),
(141, '7003003', 'Service de mise en quartier  des animaux de viande de boucherie (bovins, ovins et caprins, porcins etc.)', 138, 0, 0),
(142, '7003004', 'Service d’abattage de la volaille', 138, 0, 0),
(143, '7003005', 'Services de plumage et d’éviscération de la volaille', 138, 0, 0),
(144, '8001', 'Farines de céréales', 0, 0, 0),
(145, '8001001', 'Farine de blé (Froment)', 144, 0, 0),
(146, '8001002', 'Farine de maïs', 144, 0, 0),
(147, '8001003', 'Farine de tubercules, de légume et d\'autres céréales', 144, 0, 0),
(148, '8001004', 'Céréales autrement transformées', 144, 0, 0),
(149, '8002', 'Produits de la préparation du riz', 0, 0, 0),
(150, '8002001', 'Riz décortiqué', 149, 0, 0),
(151, '8002002', 'Brisure de riz', 149, 0, 0),
(152, '8003', 'Produits amylacés', 0, 0, 0),
(153, '8003001', 'Farine de manioc', 152, 0, 0),
(154, '8003002', 'Tapioca', 152, 0, 0),
(155, '8003003', 'Bâton de manioc', 152, 0, 0),
(156, '8003004', 'Produits de l\'hydrolyse de l\'amidon', 152, 0, 0),
(157, '8003005', 'Autres produits à base de manioc', 152, 0, 0),
(158, '9001', 'Produits à base de cacao', 0, 0, 0),
(159, '9001001', 'Cacao en masse', 158, 0, 0),
(160, '9001002', 'Beurre de cacao', 158, 0, 0),
(161, '9001003', 'Poudre ou tourteaux de cacao, sucrés ou non', 158, 0, 0),
(162, '9001004', 'chocolats et préparation à base de chocolats', 158, 0, 0),
(163, '9001005', 'Confiseries diverses', 158, 0, 0),
(164, '9002', 'Produits du décorticage et de la transformation du café', 0, 0, 0),
(165, '9002001', 'Café décortiqué arabica', 164, 0, 0),
(166, '9002002', 'Café décortiqué robusta', 164, 0, 0),
(167, '9002003', 'Café torréfié', 164, 0, 0),
(168, '9002004', 'Café conditionné', 164, 0, 0),
(169, '9003', 'Produits de la préparation et de la conservation du thé', 0, 0, 0),
(170, '9003000', 'Thé conditionné', 169, 0, 0),
(171, '9004', 'Sucre et mélasses', 0, 0, 0),
(172, '9004001', 'Sucre', 171, 0, 0),
(173, '9004002', 'Mélasses', 171, 0, 0),
(174, '10001', 'Huiles brutes et tourteaux', 0, 0, 0),
(175, '10001001', 'Huile brute de palme', 174, 0, 0),
(176, '10001002', 'Noix de palmiste', 174, 0, 0),
(177, '10001003', 'Huile brute de coton', 174, 0, 0),
(178, '10001004', 'Huile brute de maïs et autres huiles brutes', 174, 0, 0),
(179, '10001005', 'Tourteaux', 174, 0, 0),
(180, '10002', 'Huiles raffinées, margarines et matières grasses', 0, 0, 0),
(181, '10002001', 'Huile raffinée de palme', 180, 0, 0),
(182, '10002002', 'Huile raffinée de palmiste', 180, 0, 0),
(183, '10002003', 'Huile raffinée de coton', 180, 0, 0),
(184, '10002004', 'Huile raffinée d\'arachide', 180, 0, 0),
(185, '10002005', 'Huile raffinée de maïs', 180, 0, 0),
(186, '10002006', 'Autres huiles raffinées végétales et animales', 180, 0, 0),
(187, '10002007', 'Margarine et matières grasses diverses', 180, 0, 0),
(188, '10003', 'Provende et aliments pour animaux', 0, 0, 0),
(189, '10003001', 'Provende et autres aliments pour animaux', 188, 0, 0),
(190, '10003002', 'fourrages déshydratés', 188, 0, 0),
(191, '11001', 'Pain, biscuits et pâtisserie', 0, 0, 0),
(192, '11001001', 'Pain et pâtisserie fraiche', 191, 0, 0),
(193, '11001002', 'Biscuits et biscottes', 191, 0, 0),
(194, '11001003', 'Beignets de tout genre', 191, 0, 0),
(195, '11002', 'Pâtes alimentaires', 0, 0, 0),
(196, '11002000', 'Pâtes alimentaires', 195, 0, 0),
(197, '12001', 'Produits laitiers', 0, 0, 0),
(198, '12001001', 'Lait', 197, 0, 0),
(199, '12001002', 'Crème de lait, lait fermenté, yaourts et desserts lactés frais', 197, 0, 0),
(200, '12001003', 'Beurre et fromage', 197, 0, 0),
(201, '12001004', 'Glaces, sucettes et autres produits laitiers', 197, 0, 0),
(202, '12002', 'Produits de la transformation et de la conservation des fruits, légumes et autres produits alimentaires', 0, 0, 0),
(203, '12002001', 'Jus de fruits et légumes', 202, 0, 0),
(204, '12002002', 'Produits des préparations et conserves des fruits et légumes', 202, 0, 0),
(205, '12002003', 'Condiments et assaisonnements', 202, 0, 0),
(206, '12002004', 'Produits alimentaires divers', 202, 0, 0),
(207, '13001', 'Bière et malt', 0, 0, 0),
(208, '13001001', 'Bière', 207, 0, 0),
(209, '13001002', 'Malt', 207, 0, 0),
(210, '13002', 'Autres boissons alcoolisées', 0, 0, 0),
(211, '13002001', 'Vins et champagnes', 210, 0, 0),
(212, '13002002', 'Autres boissons alcoolisées modernes', 210, 0, 0),
(213, '13003003', 'Boissons alcoolisées artisanales', 214, 0, 0),
(214, '13003', 'Boissons non alcoolisées et eaux minérales', 0, 0, 0),
(215, '13003001', 'Eaux minérales', 214, 0, 0),
(216, '13003002', 'Boissons non alcoolisées diverses', 214, 0, 0),
(217, '14000', 'Produits à base de tabac', 0, 0, 0),
(218, '14000001', 'Cigares et cigarettes', 217, 0, 0),
(219, '14000002', 'Autres produits à base de tabac', 217, 0, 0),
(220, '15000000', 'Opérations sous-traitées intervenant dans la fabrication de produits textiles', 0, 0, 0),
(221, '15001', 'Fibres et fils textiles', 0, 0, 0),
(222, '15001001', 'Fibres de coton préparé', 221, 0, 0),
(223, '15001002', 'Fils de coton', 221, 0, 0),
(224, '15001003', 'Fibres et fils d\'autres textiles', 221, 0, 0),
(225, '15002', 'Tissus et services d’ennoblissement', 0, 0, 0),
(226, '15002001', 'Tissus de coton', 225, 0, 0),
(227, '15002002', 'Tissus d\'autres textiles', 225, 0, 0),
(228, '15002003', 'Services d’ennoblissement textile', 225, 0, 0),
(229, '15003', 'Autres produits textiles non vestimentaires', 0, 0, 0),
(230, '15003001', 'Linge de maison, articles d\'ameublement et de literie', 229, 0, 0),
(231, '15003002', 'Tapis et moquettes', 229, 0, 0),
(232, '15003003', 'Ficelles, filets et cordages', 229, 0, 0),
(233, '15003004', 'Autres articles confectionnés en textiles', 229, 0, 0),
(234, '15004', 'Articles d\'habillement (sauf chaussures)', 0, 0, 0),
(235, '15004001', 'Vêtements en textiles', 234, 0, 0),
(236, '15004002', 'Vêtements en cuir et fourrures', 234, 0, 0),
(237, '16000000', 'Opérations sous-traitées intervenant dans la fabrication de cuir, articles de voyages et de chaussures', 0, 0, 0),
(238, '16001', 'Produits du cuir et articles en cuir', 0, 0, 0),
(239, '16001001', 'Cuirs et peaux travaillés', 238, 0, 0),
(240, '16001002', 'Articles de voyages et de maroquinerie', 238, 0, 0),
(241, '16002', 'Chaussures, y compris chaussures en caoutchouc et en plastique', 0, 0, 0),
(242, '16002000', 'Chaussures et articles chaussants', 241, 0, 0),
(243, '17000000', 'Opérations sous-traitées intervenant dans le travail du bois et la fabrication d’articles en bois', 0, 0, 0),
(244, '17001', 'Produits du Sciage et traitement du bois', 0, 0, 0),
(245, '17001001', 'Bois sciés', 244, 0, 0),
(246, '17001002', 'Bois profilés, traités et autres sous-produits du bois', 244, 0, 0),
(247, '17002', 'Feuilles de placages, contreplaqués et panneaux', 0, 0, 0),
(248, '17002000', 'Placages, contre-plaqués et panneaux à base de bois', 247, 0, 0),
(249, '17003', 'Produits en bois assemblés, articles en bois, liège, vannerie et sparterie', 0, 0, 0),
(250, '17003001', 'Charpentes et menuiseries de bâtiment en bois', 249, 0, 0),
(251, '17003002', 'Emballages et objets divers en bois', 249, 0, 0),
(252, '17003003', 'Ouvrages en liège, vannerie ou sparterie', 249, 0, 0),
(253, '18001', 'Papier, carton et articles en papier ou en carton', 0, 0, 0),
(254, '18001001', 'Pâte à papier', 253, 0, 0),
(255, '18001002', 'Papiers à usage graphique et articles de papeterie', 253, 0, 0),
(256, '18001003', 'Papiers kraft et domestiques, articles à usage sanitaire ou domestique', 253, 0, 0),
(257, '18001004', 'Papiers et cartons élaborés; Autres papiers et cartons', 253, 0, 0),
(258, '18001005', 'Emballages en papier ou en carton et autres articles en papier ou en carton', 253, 0, 0),
(259, '18002', 'Produits imprimés ou reproduits', 0, 0, 0),
(260, '18002001', 'Livres, journaux et revues périodiques', 259, 0, 0),
(261, '18002002', 'Autres produits imprimés', 259, 0, 0),
(262, '18002003', 'Papeterie scolaire et commerciale', 259, 0, 0),
(263, '18002004', 'Travaux d\'impression de la presse et autres produits de l’imprimerie', 259, 0, 0),
(264, '18002005', 'Travaux de façonnage, de finition, de reliure travaux de composition et de photogravures', 259, 0, 0),
(265, '18002006', 'Travaux de reproduction des supports électroniques, d\'enregistrements audio vidéo et informatique', 259, 0, 0),
(266, '19000', 'Produits du raffinage du pétrole et de la Cokéfaction', 0, 0, 0),
(267, '19000001', 'Essences', 266, 0, 0),
(268, '19000002', 'Kérosène (pétrole lampant)', 266, 0, 0),
(269, '19000003', 'Carburéacteurs (Jet A1)', 266, 0, 0),
(270, '19000004', 'Gazoles', 266, 0, 0),
(271, '19000005', 'Fuel lourd', 266, 0, 0),
(272, '19000006', 'Fuel léger', 266, 0, 0),
(273, '19000007', 'Autres produits pétroliers raffinés liquides', 266, 0, 0),
(274, '19000008', 'Produits pétroliers raffinés gazeux', 266, 0, 0),
(275, '19000009', 'Graisses lubrifiantes, coke de pétrole et bitumes', 266, 0, 0),
(276, '19000010', 'Cokes et goudrons ; produits des industries nucléaires', 266, 0, 0),
(277, '20001', 'Produits chimiques de base', 0, 0, 0),
(278, '20001001', 'Gaz industriels', 277, 0, 0),
(279, '20001002', 'Produits chimiques inorganiques de base', 277, 0, 0),
(280, '20001003', 'Produits chimiques organiques de base', 277, 0, 0),
(281, '20001004', 'Produits azotés et engrais', 277, 0, 0),
(282, '20001005', 'Matières plastiques et caoutchouc synthétique', 277, 0, 0),
(283, '20002', 'Savons, parfums, détergents et produits d\'entretien', 0, 0, 0),
(284, '20002001', 'Savons et détergents', 283, 0, 0),
(285, '20002002', 'Glycérine, agents tensioactifs et produits d\'entretien ménager', 283, 0, 0),
(286, '20002003', 'Parfums et produits de toilette', 283, 0, 0),
(287, '20003', 'Produits pharmaceutiques', 0, 0, 0),
(288, '20003001', 'Produits de l’industrie pharmaceutique', 287, 0, 0),
(289, '20003002', 'Médicaments traditionnels', 287, 0, 0),
(290, '20004', 'Autres produits chimiques', 0, 0, 0),
(291, '20004001', 'Produits agrochimiques', 290, 0, 0),
(292, '20004002', 'Peintures, vernis, adjuvants, encres d\'imprimerie', 290, 0, 0),
(293, '20004003', 'Allumettes, articles pyrotechniques, produits pour la photographie et autres produits', 290, 0, 0),
(294, '20004004', 'Fibres artificielles ou synthétiques', 290, 0, 0),
(295, '21001', 'Caoutchouc sec', 0, 0, 0),
(296, '21001000', 'Caoutchouc sec', 295, 0, 0),
(297, '21002', 'Articles en caoutchouc', 0, 0, 0),
(298, '21002000', 'Pneumatiques et ouvrages en caoutchouc', 297, 0, 0),
(299, '21003', 'Articles en matières plastiques (sauf chaussures)', 0, 0, 0),
(300, '21003001', 'Tubes, profilés, plaques et autres éléments en matière plastique pour la construction', 299, 0, 0),
(301, '21003002', 'Emballages et articles divers en matière plastique', 299, 0, 0),
(302, '22001', 'Ciment', 0, 0, 0),
(303, '22001000', 'Ciment', 302, 0, 0),
(304, '22002', 'Autres produits minéraux non métalliques', 0, 0, 0),
(305, '22002001', 'Chaux et plâtre', 304, 0, 0),
(306, '22002002', 'Verre et articles en verres', 304, 0, 0),
(307, '22002003', 'Produits céramiques', 304, 0, 0),
(308, '22002004', 'Matériaux et ouvrages en ciment, en béton en plâtre ou en pierre', 304, 0, 0),
(309, '22002005', 'Produits minéraux non métalliques n.c.a', 304, 0, 0),
(310, '23000000', 'Opérations sous-traités intervenant dans la fabrication de produits métallurgiques de base et d’ouvrages en métaux', 311, 0, 0),
(311, '23000', 'Produits métallurgiques de base et ouvrages en métaux', 0, 0, 0),
(312, '23000001', 'Produits sidérurgiques et de première transformation', 311, 0, 0),
(313, '23000002', 'Tubes et tuyaux en fonte ou en acier', 311, 0, 0),
(314, '23000003', 'Produits de la transformation de l\'acier', 311, 0, 0),
(315, '23000004', 'Métaux précieux', 311, 0, 0),
(316, '23000005', 'Alumine', 311, 0, 0),
(317, '23000006', 'Aluminium et demi-produits en aluminium', 311, 0, 0),
(318, '23000007', 'Autres métaux non ferreux', 311, 0, 0),
(319, '23000008', 'Ouvrages en métaux n.c.a.', 311, 0, 0),
(320, '24000000', 'Opérations sous-traitées intervenant dans la fabrication d’appareils électriques et de matériels n.c.a.', 321, 0, 0),
(321, '24000', 'Machines, appareils électriques et matériels n.c.a.', 0, 0, 0),
(322, '24000001', 'Equipements mécaniques et machines d’usage général', 321, 0, 0),
(323, '24000002', 'Machines agricoles et machines outils', 321, 0, 0),
(324, '24000003', 'Autres machines d’usage spécifique ; armes et munitions', 321, 0, 0),
(325, '24000004', 'Appareils domestiques', 321, 0, 0),
(326, '24000005', 'Machines de bureau et matériel informatique', 321, 0, 0),
(327, '24000006', 'Accumulateurs et piles électriques', 321, 0, 0),
(328, '24000007', 'Autres machines et matériels électriques n.c.a', 321, 0, 0),
(329, '25000000', 'Opérations sous-traitées intervenant dans la fabrication d’équipements et instruments médicaux, de précision, d\'optique et d\'horlogerie d’appareils audiovisuels et de communication, instruments médicaux, de précision, d\'optique et d\'horlogerie', 330, 0, 0),
(330, '25000', 'Equipements et appareils audiovisuels et de communication, instruments médicaux, de précision, d\'optique et d\'horlogerie', 0, 0, 0),
(331, '25000001', 'Equipements, appareils audiovisuels et de communication', 330, 0, 0),
(332, '25000002', 'Instruments médicaux, de précision, d’optique et d’horlogerie', 330, 0, 0),
(333, '26000000', 'Opérations sous-traitées intervenant dans la fabrication de matériels de transport', 0, 0, 0),
(334, '26001', 'Véhicules routiers', 0, 0, 0),
(335, '26001001', 'Véhicules automobiles', 334, 0, 0),
(336, '26001002', 'Carrosseries et remorques, pièces et accessoires pour véhicules automobiles (et leurs moteurs)', 334, 0, 0),
(337, '26001003', 'Motocycles, cycles et équipements pour cycles', 334, 0, 0),
(338, '26001004', 'Autres véhicules routiers', 334, 0, 0),
(339, '26002', 'Autres matériels de transport', 0, 0, 0),
(340, '26002001', 'Matériel de transport naval, entretien et réparation naval', 339, 0, 0),
(341, '26002002', 'Matériel ferroviaire roulant', 339, 0, 0),
(342, '26002003', 'Matériels aéronautique et spatial, Travaux d’entretien et réparation des aéronefs', 339, 0, 0),
(343, '26002004', 'Matériels de transport n.c.a.', 339, 0, 0),
(344, '27001', 'Meubles', 0, 0, 0),
(345, '27001001', 'Meubles en bois', 344, 0, 0),
(346, '27001002', 'Autres meubles et prestations connexes à l\'ameublement, sommiers et matelas', 344, 0, 0),
(347, '27002', 'Produits des industries manufacturières n.c.a.', 0, 0, 0),
(348, '27002001', 'Pièces de monnaies et bijoux', 347, 0, 0),
(349, '27002002', 'Instruments de musique', 347, 0, 0),
(350, '27002003', 'Articles de sport; jeux et jouets', 347, 0, 0),
(351, '27002004', 'Articles manufacturés n.c.a', 347, 0, 0),
(352, '28001', 'Travaux de réparation des machines et équipements professionnels', 0, 0, 0),
(353, '28001001', 'Travaux de réparation d’ouvrage en métaux', 352, 0, 0),
(354, '28001002', 'Travaux de réparation des machines et équipements mécaniques', 352, 0, 0),
(355, '28001003', 'Travaux de réparation de matériels électroniques et', 352, 0, 0),
(356, '', 'optiques', 0, 0, 0),
(357, '28001004', 'Travaux de réparation de matériel électriques', 352, 0, 0),
(358, '28001005', 'Travaux de réparation et la maintenance navale, aéronautique et ferroviaire', 352, 0, 0),
(359, '28002', 'Travaux d\'Installation des machines et équipements industriels', 0, 0, 0),
(360, '28002000', 'Travaux d’installation des machines et équipements industriels', 359, 0, 0),
(361, '29001', 'Electricité et supports énergétiques', 0, 0, 0),
(362, '29001000', 'Electricité', 361, 0, 0),
(363, '29002', 'Gaz, biocarburants et autres supports énergétiques d’origine non fossile', 0, 0, 0),
(364, '29002001', 'Gaz raffiné distribué, Biogaz et biocarburants et autres agrocarburants', 363, 0, 0),
(365, '29002002', 'Glace hydrique et autres supports énergétiques d’origine non fossile', 363, 0, 0),
(366, '30001', 'Service de Captage, traitement et distribution d’eau', 0, 0, 0),
(367, '30001001', 'Eau captée et traitée', 366, 0, 0),
(368, '30001002', 'Eau distribuée', 366, 0, 0),
(369, '30002', 'Service de collecte et traitement des eaux usées', 0, 0, 0),
(370, '30002000', 'Eau usées collectées et traitées', 369, 0, 0),
(371, '30003', 'Service de collecte, traitement et élimination des déchets  solide; Service de récupération', 0, 0, 0),
(372, '30003001', 'Déchets collectés', 371, 0, 0),
(373, '30003002', 'Déchets traités et éliminés', 371, 0, 0),
(374, '30003003', 'Matériaux récupérés', 371, 0, 0),
(375, '30004', 'Service de dépollution', 0, 0, 0),
(376, '30004000', 'Service de dépollution', 375, 0, 0),
(377, '31001', 'Travaux de préparation de sites et de construction d\'ouvrages de génie civil et bâtiments', 0, 0, 0),
(378, '31001001', 'Travaux de Préparation des sites', 377, 0, 0),
(379, '31001002', 'Travaux de construction de logements', 377, 0, 0),
(380, '31001003', 'Travaux de construction des Bâtiments non résidentiels', 377, 0, 0),
(381, '31001004', 'Autres travaux de construction', 377, 0, 0),
(382, '31002', 'Travaux d\'installation', 0, 0, 0),
(383, '31002001', 'Travaux d\'installation  électrique', 382, 0, 0),
(384, '31002002', 'Travaux d’installation de plomberie', 382, 0, 0),
(385, '31002003', 'Autres travaux d’installation', 382, 0, 0),
(386, '31003', 'Travaux de finition', 0, 0, 0),
(387, '31003001', 'Travaux de finition et revêtement de sol', 386, 0, 0),
(388, '31003002', 'Travaux  de finition vitrerie, plâtrerie', 386, 0, 0),
(389, '31003003', 'Autres travaux de finition n.c.a.', 386, 0, 0),
(390, '32001', 'Vente de véhicules automobiles et de motocycles', 0, 0, 0),
(391, '32001001', 'Vente de véhicule automobile', 390, 0, 0),
(392, '32001002', 'Vente de motocycle', 390, 0, 0),
(393, '32002', 'Services d\'entretien et réparation de véhicule automobile et de motocycles', 0, 0, 0),
(394, '32002001', 'Services d\'entretien et réparation de véhicule automobile', 393, 0, 0),
(395, '32002002', 'Services d\'entretien et réparation de motocycles', 393, 0, 0),
(396, '32003', 'Vente de pièces détachées et d’accessoires pour automobile/motocycle', 0, 0, 0),
(397, '32003001', 'Vente de pièces détachées et d’accessoires pour automobile,', 396, 0, 0),
(398, '32003002', 'Vente de pièces détachées et d’accessoires pour motocycles', 396, 0, 0),
(399, '32004', 'Vente en gros de produits agricoles bruts et d\'animaux vivants', 0, 0, 0),
(400, '32004000', 'Vente en gros de produits agricoles bruts et d\'animaux vivants y.c. bois bruts', 399, 0, 0),
(401, '32005', 'Vente en gros de produits alimentaires, boissons et tabacs manufacturés', 0, 0, 0),
(402, '32005000', 'Vente en gros de produits alimentaires, boissons et tabacs manufacturés', 401, 0, 0),
(403, '32006', 'Vente en gros de matériaux de construction, quincaillerie et fournitures pour plomberie', 0, 0, 0),
(404, '32006000', 'Vente en gros de matériaux de construction, quincaillerie et fournitures pour plomberie', 403, 0, 0),
(405, '32007', 'Autres vente en gros', 0, 0, 0),
(406, '32007001', 'Service des intermédiaires du commerce de gros ;', 405, 0, 0),
(407, '32007002', 'Vente en gros de produits ou de biens de consommation non alimentaires n.c.a.;', 405, 0, 0),
(408, '32008', 'Vente en détail en magasin non spécialisé', 0, 0, 0),
(409, '32008000', 'Vente en détail en magasin non spécialisé', 408, 0, 0),
(410, '32009', 'Vente en détail de produits alimentaires, boissons et tabacs manufacturés', 0, 0, 0),
(411, '32009000', 'Vente en détail de produits alimentaires, boissons et tabacs manufacturés', 410, 0, 0),
(412, '32010', 'Vente en détail de matériaux de construction, quincaillerie et fournitures pour plomberie', 0, 0, 0),
(413, '32010000', 'Vente en détail de matériaux de construction, quincaillerie et fournitures pour plomberie', 412, 0, 0),
(414, '32011', 'Autres ventes en détail en magasin spécialisé', 0, 0, 0),
(415, '32011000', 'Autres vente en détail en magasin spécialisé', 414, 0, 0),
(416, '32012', 'Vente en détail de biens d’occasion', 0, 0, 0),
(417, '32012000', 'Vente en détail de biens d’occasion', 416, 0, 0),
(418, '32013', 'Vente en détail hors magasin', 0, 0, 0),
(419, '32013000', 'Vente en détail hors magasin', 418, 0, 0),
(420, '32014', 'Autres ventes en détail hors magasin', 0, 0, 0),
(421, '32014000', 'Autres vente en détail hors magasin', 420, 0, 0),
(422, '33001', 'Hébergement', 0, 0, 0),
(423, '33001000', 'Services d’hébergement', 422, 0, 0),
(424, '33002', 'Services de restaurants et des débits de boissons et cafés', 0, 0, 0),
(425, '33002001', 'Services de restaurants', 424, 0, 0),
(426, '33002002', 'Services des débits de boissons et cafés', 424, 0, 0),
(427, '34001', 'Services de Transports ferroviaires', 0, 0, 0),
(428, '34001001', 'Services de transport ferroviaire passagers', 427, 0, 0),
(429, '34001002', 'Services de transport ferroviaire marchandises', 427, 0, 0),
(430, '34002', 'Service de transport par taxis et motos', 0, 0, 0),
(431, '34002001', 'Services de transport de voyageurs par taxis', 430, 0, 0),
(432, '34002002', 'Services de transport de voyageurs par taxi-motos', 430, 0, 0),
(433, '34003', 'Autres Services de transports routiers de voyageurs', 0, 0, 0),
(434, '34003001', 'Services de transports routiers urbains de voyageurs', 433, 0, 0),
(435, '34003002', 'Services de transports routiers interurbains de voyageurs', 433, 0, 0),
(436, '34003003', 'Autres services de transports routiers n.c.a', 433, 0, 0),
(437, '34004', 'Services de transports routiers de marchandises', 0, 0, 0),
(438, '34004000', 'Services de transports routiers de marchandises', 437, 0, 0),
(439, '34005', 'Autres Services de transports', 0, 0, 0),
(440, '34005001', 'Services de transports par eau', 439, 0, 0),
(441, '34005002', 'Services de transports aériens', 439, 0, 0),
(442, '34005003', 'Service de transports par conduit', 439, 0, 0),
(443, '34006', 'Service d’entreposage, services auxiliaires des transports', 0, 0, 0),
(444, '34006001', 'Services de manutention, d\'entreposage et des infrastructures de transports', 443, 0, 0),
(445, '34006002', 'Services d’ organisation du transport de fret', 443, 0, 0),
(446, '34007', 'Services de postes et courrier', 0, 0, 0),
(447, '34007000', 'Services de postes et de courrier', 446, 0, 0),
(448, '35001', 'Services d\'édition', 0, 0, 0),
(449, '35001001', 'Services d\'édition de livres et périodiques et autres services d’édition', 448, 0, 0),
(450, '35001002', 'Services d\'édition de logiciels', 448, 0, 0),
(451, '35002', 'Produit des activités audio et vidéo', 0, 0, 0),
(452, '35002001', 'Produits des activités cinématographiques et de programmation télévisuelle', 451, 0, 0),
(453, '35002002', 'Enregistrements sonores et produits musicaux édités', 451, 0, 0),
(454, '35003', 'Service de programmation télévisuelle et de radiodiffusion', 0, 0, 0),
(455, '35003001', 'Services de radiodiffusion', 454, 0, 0),
(456, '35003002', 'Services de programmation de télévision et télédiffusion', 454, 0, 0),
(457, '35004', 'Services de Télécommunication', 0, 0, 0),
(458, '35004000', 'Services de Télécommunication', 457, 0, 0),
(459, '35005', 'Produits et services informatiques : conseil, programmation', 0, 0, 0),
(460, '35005001', 'Produits de la programmation informatique', 459, 0, 0),
(461, '35005002', 'Services de conseil et autres Services informatiques', 459, 0, 0),
(462, '35006', 'Service de fourniture d’informations', 0, 0, 0),
(463, '35006001', 'Services de traitement de données, hébergement et activités connexes ; services de portails Internet', 462, 0, 0),
(464, '35006002', 'Autres services de fourniture d\'information', 462, 0, 0),
(465, '36001', 'Services d’intermédiation monétaire et financière (sauf micro finances)', 0, 0, 0),
(466, '36001001', 'Services d\'intermédiation monétaire et financière', 465, 0, 0),
(467, '36001002', 'Service de fonds de placements, holdings et services financiers similaires', 465, 0, 0),
(468, '36001003', 'Services de crédit et autres intermédiations non monétaires', 465, 0, 0),
(469, '36002', 'Services d\'assurance (sauf sécurité sociale)', 0, 0, 0),
(470, '36002001', 'Services d’assurance-vie et caisses de retraite', 469, 0, 0),
(471, '36002002', 'Services d’assurance-dommages et réassurance', 469, 0, 0),
(472, '36003', 'Services d\'auxiliaires financiers et d\'assurance', 0, 0, 0),
(473, '36003001', 'Services d’auxiliaires financiers, hors assurance et caisses de retraite', 472, 0, 0),
(474, '36003002', 'Service de gestion de fonds pour tiers', 472, 0, 0),
(475, '36004', 'Services de Micro-finances', 0, 0, 0),
(476, '36004000', 'Services de Micro-finance.', 475, 0, 0),
(477, '37001', 'Services de location immobilière', 0, 0, 0),
(478, '37001000', 'Services de location immobilière', 477, 0, 0),
(479, '37002', 'Autres services immobiliers', 0, 0, 0),
(480, '37002000', 'Autres services immobiliers', 479, 0, 0),
(481, '38001', 'Services administratifs et d’appui aux entreprises', 0, 0, 0),
(482, '38001000', 'Services administratifs et d’appui aux entreprises', 481, 0, 0),
(483, '38002', 'Services  de recherchedéveloppement en sciences physiques et naturelles', 0, 0, 0),
(484, '38002000', 'Services  de recherchedéveloppement en sciences physiques et naturelles', 483, 0, 0),
(485, '38003', 'Services  de recherchedéveloppement en sciences humaines et sociales', 0, 0, 0),
(486, '38003000', 'Services  de recherchedéveloppement en sciences physiques et naturelles', 485, 0, 0),
(487, '38004', 'Services juridiques et comptables', 0, 0, 0),
(488, '38004000', 'Services juridiques et comptables', 487, 0, 0),
(489, '38005', 'Services vétérinaires', 0, 0, 0),
(490, '38005000', 'Services vétérinaires', 489, 0, 0),
(491, '38006', 'Autres services spécialisées, scientifiques et techniques', 0, 0, 0),
(492, '38006001', 'Services d\'architecture, d\'ingénierie et techniques', 491, 0, 0),
(493, '38006002', 'Services de contrôles et d’analyses techniques', 491, 0, 0),
(494, '38006003', 'Services photographiques', 491, 0, 0),
(495, '38006004', 'Services des Sièges Sociaux ; Conseil En Gestion', 491, 0, 0),
(496, '38006005', 'Services de publicité, études de marché et sondage', 491, 0, 0),
(497, '38006006', 'Services spécialisés de design', 491, 0, 0),
(498, '38006007', 'Autres services spécialisées, scientifiques et techniques n.c.a.', 491, 0, 0),
(499, '39001', 'Services d\'administration publique générale et services fournis à l\'ensemble de la collectivité', 0, 0, 0),
(500, '39010001', 'Administration publique générale, économique  et sociale', 0, 0, 0),
(501, '39001002', 'Services de prérogative publique', 499, 0, 0),
(502, '39002', 'Service de sécurité sociale obligatoire', 0, 0, 0),
(503, '39002001', 'Service des prestations maladie, maternité et invalidité', 502, 0, 0),
(504, '39002002', 'Autres services de sécurité sociale obligatoire', 502, 0, 0),
(505, '40000', 'Service d’enseignement', 0, 0, 0),
(506, '40000001', 'Services d’enseignement préprimaire et primaire', 505, 0, 0),
(507, '40000002', 'Services d’enseignement secondaire', 505, 0, 0),
(508, '40000003', 'Services d’enseignement supérieur et post-secondaire non supérieur', 505, 0, 0),
(509, '40000004', 'Autres services d’enseignement', 505, 0, 0),
(510, '41001', 'Services pour la santé humaine', 0, 0, 0),
(511, '41001001', 'Services hospitaliers', 510, 0, 0),
(512, '41001002', 'Services des médecins et des dentistes', 510, 0, 0),
(513, '41001003', 'Services paramédicaux et de soutien', 510, 0, 0),
(514, '41001004', 'Autres services pour la santé humaine', 510, 0, 0),
(515, '41002', 'Services d’hébergement médico-social et social', 0, 0, 0),
(516, '41002000', 'Services d’hébergement médicosocial et social', 515, 0, 0),
(517, '42001', 'Services fournis par les organisations associatives', 0, 0, 0),
(518, '42001000', 'Services des organisations associatives', 517, 0, 0),
(519, '42002', 'Services récréatifs, culturels et sportifs', 0, 0, 0),
(520, '42002000', 'Services récréatifs, culturels et sportifs', 519, 0, 0),
(521, '42003', 'Services personnels', 0, 0, 0),
(522, '42003000', 'Services personnels', 521, 0, 0),
(523, '42004', 'Services domestiques', 0, 0, 0),
(524, '42004000', 'Services domestiques (cuisinière, jardiniers, répétiteurs, chauffeurs, femme de ménage)', 523, 0, 0),
(525, '42005', 'Services de réparation des ordinateurs, des équipements de communication et réseautage', 0, 0, 0),
(526, '42005000', 'Réparation des ordinateurs et des équipements de communication', 525, 0, 0),
(527, '42006', 'Réparation de biens personnels et domestiques', 0, 0, 0),
(528, '42006000', 'Réparation de biens personnels et domestiques', 527, 0, 0),
(529, '43000', 'Services des organisations et organismes extraterritoriaux', 0, 0, 0),
(530, '43000000', 'Services des organisations et organismes extraterritoriaux', 529, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

CREATE TABLE `profils` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abb` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` int NOT NULL DEFAULT '0',
  `metier` tinyint(1) NOT NULL DEFAULT '1',
  `programme` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profils`
--

INSERT INTO `profils` (`id`, `name`, `abb`, `niveau`, `metier`, `programme`, `active`) VALUES
(1, 'Super Administrateur', 'Super Ad.', 0, 0, 0, 1),
(2, 'PRESIDENT DU CONSEIL D\'ADMINISTRATION', 'PCA', 0, 1, 0, 1),
(3, 'ADMINISTRATEUR', 'ADM', 0, 1, 0, 1),
(4, 'DIRECTEUR GENERAL', 'DG', 1, 1, 0, 1),
(5, 'DIRECTEUR GENERAL ADJOINT', 'DGA', 1, 1, 0, 1),
(6, 'RESPONSABLE EXPLOITATION', 'RESP-EXP', 2, 1, 0, 1),
(7, 'RESPONSABLE AUDIT INTERNE', 'RESP-AUD', 2, 1, 0, 1),
(8, 'RESPONSABLE CONTROLE INTERNE', 'RESP-CI', 2, 1, 0, 1),
(9, 'RESPONSABLE ENGAGEMENTS', 'RENG', 2, 1, 0, 1),
(10, 'RESPONSABLE JURIDIQUE', 'REJU', 2, 1, 0, 1),
(11, 'RESPONSABLE CONFORMITE', 'RECONF', 2, 1, 0, 1),
(12, 'RESPONSABLE RISQUES', 'RERX', 2, 1, 0, 1),
(14, 'RESPONSABLE REGIONAL', 'RESP-REG', 3, 1, 0, 1),
(15, 'CHEF D\'AGENCE', 'CA', 3, 1, 0, 1),
(16, 'GESTIONNAIRE', 'GEST', 4, 1, 0, 1),
(17, 'ANALYSTE FINANCIER EXPLOITATION', 'AFE', 4, 1, 0, 1),
(18, 'ANALYSTE RISQUES', 'ANRX', 4, 1, 0, 1),
(19, 'ANALYSTE JURIDIQUE', 'ANJ', 4, 1, 0, 1),
(20, 'ANALYSTE CREDIT', 'ANCDT', 4, 1, 0, 1),
(21, 'ANALYSTE CONFORMITE', 'ANCONF', 4, 1, 0, 1),
(22, 'AUDITEUR', 'AUD', 4, 1, 0, 1),
(23, 'CONTROLEUR', 'CONT', 4, 1, 0, 1),
(24, 'CHEF DE FILIERE', 'CHFIL', 4, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `programmes`
--

CREATE TABLE `programmes` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `convention` varchar(30) DEFAULT NULL,
  `dt_sig_conv` date DEFAULT NULL,
  `signataire` varchar(200) DEFAULT NULL,
  `budget` double DEFAULT '0',
  `budget_anf` double NOT NULL DEFAULT '0',
  `budget_af` double NOT NULL DEFAULT '0',
  `budget_coord` double NOT NULL DEFAULT '0',
  `type_pp` varchar(100) DEFAULT NULL,
  `type_pm` varchar(100) DEFAULT NULL,
  `dt_start` date DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `programmes`
--

INSERT INTO `programmes` (`id`, `name`, `convention`, `dt_sig_conv`, `signataire`, `budget`, `budget_anf`, `budget_af`, `budget_coord`, `type_pp`, `type_pm`, `dt_start`, `contact`, `user_id`, `active`, `created_at`, `updated_at`, `token`) VALUES
(1, 'PIISAH', '5676327/MINADER', '2020-01-01', 'MINADER', 0, 2500000000, 5200000000, 1000000000, 'Homme-Femme-Jeune', 'GRANDE-MOYENNE-PETITE-TRES PETITE', '2025-01-01', 'AMADOU HAMAD - 69782738832', 1, 1, '2026-04-19 18:45:07', '2026-04-19 18:45:07', 'af348beb8917aea15b55e719a65e5f4812f6c390'),
(2, 'TRANSFAGRI', '67384898494', '2019-05-21', 'MINEPAT', 0, 960000000, 4467000000, 750000000, 'Femme-Personnes vulnérables', 'GRANDE-MOYENNE-PETITE', '2024-01-01', 'Le coordo', 1, 1, '2026-04-21 20:29:08', '2026-04-21 20:29:08', '4f006a7f50fbe71f7e5ca9cb17658793b446843e'),
(3, 'PURATOS', '56236237823', '2022-07-09', 'ELESYST', 0, 1000000000, 2500000000, 400000000, 'Homme-Femme-Jeune-Personnes vulnérables', 'PETITE-TRES PETITE-COOPERATIVE-ASSOCIATION', '2023-06-11', 'Eloundoun NGAH ERIC', 1, 1, '2026-04-21 20:39:57', '2026-04-21 20:39:57', '63495495adc898b693f51fb99f79f852be2a51ba');

-- --------------------------------------------------------

--
-- Structure de la table `programme_appuis`
--

CREATE TABLE `programme_appuis` (
  `id` int NOT NULL,
  `programme_id` int NOT NULL DEFAULT '0',
  `service_id` int NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `programme_appuis`
--

INSERT INTO `programme_appuis` (`id`, `programme_id`, `service_id`, `sequence`) VALUES
(30, 1, 14, 1),
(31, 1, 15, 1),
(32, 1, 17, 1),
(33, 1, 20, 1),
(34, 1, 1, 1),
(35, 1, 3, 1),
(36, 1, 9, 1),
(44, 2, 14, 1),
(45, 2, 17, 1),
(46, 2, 19, 1),
(47, 2, 18, 1),
(48, 2, 15, 1),
(49, 2, 22, 1),
(50, 2, 25, 1),
(51, 2, 28, 1),
(52, 2, 2, 1),
(53, 2, 1, 1),
(54, 2, 3, 1),
(55, 3, 17, 1),
(56, 3, 18, 1),
(57, 3, 19, 1),
(58, 3, 21, 1),
(59, 3, 4, 1),
(60, 3, 9, 1),
(61, 3, 11, 1);

-- --------------------------------------------------------

--
-- Structure de la table `programme_indicateurs`
--

CREATE TABLE `programme_indicateurs` (
  `id` int NOT NULL,
  `programme_id` int NOT NULL DEFAULT '0',
  `indicateur_id` int NOT NULL DEFAULT '0',
  `attente` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `programme_indicateurs`
--

INSERT INTO `programme_indicateurs` (`id`, `programme_id`, `indicateur_id`, `attente`) VALUES
(5, 2, 3, '45'),
(6, 1, 13, '45');

-- --------------------------------------------------------

--
-- Structure de la table `programme_organismes`
--

CREATE TABLE `programme_organismes` (
  `id` int NOT NULL,
  `programme_id` int NOT NULL DEFAULT '0',
  `organisme_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `programme_organismes`
--

INSERT INTO `programme_organismes` (`id`, `programme_id`, `organisme_id`) VALUES
(16, 2, 2),
(17, 2, 5),
(18, 2, 13),
(19, 3, 12),
(20, 3, 14);

-- --------------------------------------------------------

--
-- Structure de la table `programme_produits`
--

CREATE TABLE `programme_produits` (
  `id` int NOT NULL,
  `programme_id` int NOT NULL DEFAULT '0',
  `produit_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `programme_produits`
--

INSERT INTO `programme_produits` (`id`, `programme_id`, `produit_id`) VALUES
(17, 1, 7),
(18, 1, 22),
(19, 1, 51),
(20, 1, 59),
(21, 1, 61),
(27, 2, 7),
(28, 2, 18),
(29, 2, 36),
(30, 2, 47),
(31, 3, 158);

-- --------------------------------------------------------

--
-- Structure de la table `quartiers`
--

CREATE TABLE `quartiers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `departement_id` int NOT NULL DEFAULT '0',
  `arrondissement_id` int NOT NULL DEFAULT '0',
  `latitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int NOT NULL DEFAULT '0',
  `semaine` int NOT NULL DEFAULT '0',
  `moi_id` int NOT NULL DEFAULT '0',
  `annee` int NOT NULL DEFAULT '0',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `critere_id` int NOT NULL DEFAULT '0',
  `sous_critere_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `name`, `active`, `critere_id`, `sous_critere_id`) VALUES
(1, 'AQ1/ les  produits ou services s’adressent t’ils uniquement au marché local ? Si oui, comment est délimitée la zone de vente ?', 1, 0, 1),
(2, 'AQ2/Quelle est la situation de la concurrence dans le secteur d’activité?', 1, 0, 1),
(3, 'AQ3 /l’entreprise a-t-elle des partenaires d’affaires dans ce secteur d’activité ?', 1, 0, 1),
(4, 'BQ1/L’état actuel des environnements politique, social, législatif, du pays garantit-il le plein essor de votre activité ?\r\n', 1, 0, 2),
(5, 'CQ1/ l’entreprise connait-elle des périodes d’inactivité? A quoi  cela est dû ?', 1, 0, 3),
(6, 'CQ2/Les prix des matières premières sur le marché varient-ils ? Si oui, à quelle fréquence ?\r\n', 1, 0, 3),
(7, 'CQ3/ Les prix des  produits sur le marché varient-ils ? Si oui, à quelle fréquence ?\r\n', 1, 0, 3),
(8, 'DQ1/ En moyenne, quelle proportion des produits mis à la vente sont écoulés par an? (entreprises industrielles et commerciales)\r\n', 1, 0, 4),
(9, 'DQ2/ En moyenne, quel est le niveau d’atteinte des objectifs de vente annuels ? (entreprises de services)\r\n', 1, 0, 4),
(10, 'DQ3/ les produits ou services sont-ils adaptés aux attentes des clients ?', 1, 0, 4),
(11, 'EQ1/ Quelle est la participation moyenne au chiffre d\'affaire des principaux  clients ?', 1, 0, 5),
(12, 'EQ2/Parmi eux, existe-t-il un qui représente plus de 10% du chiffre d\'affaire ? Si oui, combien ?', 1, 0, 5),
(13, 'EQ3/Existe-t-il une politique de proximité et de fidélisation de la clientèle phare ?, (Critère d’ajustement des deux questions précédentes)\"', 1, 0, 5),
(14, 'FQ1/Quel est le volume annuel des pertes financières associées aux contraintes  administratives et fiscales dans l’exercice  de l’activité ?', 1, 0, 6),
(15, 'FQ2/Quel est le volume annuel des pertes financières liées au déficit infrastructurel ? (défaut de voies de transport, défaut de moyens de transport adéquats, etc.)\"', 1, 0, 6),
(16, 'FQ3/Quel est le niveau de disponibilité des matières premières ?', 1, 0, 6),
(17, 'GQ1/ Existe-t-il des perspectives favorables en ce qui concerne ce secteur d’activité ? (réglementation, plans de développement, contraintes administratives et fiscales, subventionnements) Si oui, lesquelles ? Sinon, pourquoi ?\r\n', 1, 0, 7),
(18, 'HQ1/ le dirigeant propriétaire a-t-il  des connaissances précises dans les domaines suivants ?,Comptabilité ,Fiscalité,Finance,management', 1, 0, 8),
(19, 'HQ2/ le dirigeant propriétaire est-il marié ? Si oui, depuis combien de temps ?', 1, 0, 8),
(20, 'HQ3/ Des valeurs ont-elles été adopté pour gouverner le fonctionnement de l’entreprise ? Si oui, lesquelles ? Sinon, pourquoi ?', 1, 0, 8),
(21, 'HQ4/ Depuis combien de temps le dirigeant propriétaire est impliqué dans  l’entrepreneuriat ?\r\nPs : L’analyste cherchera à s’enquérir de l’historique des autres entreprises du répondant (entreprises à succès, en faillite, pérennes, etc.)', 1, 0, 8),
(22, 'HQ5/ le dirigeant propriétaire a-t-il  déjà occupé un poste de responsabilité au sein d’une entreprise/d’un projet  autre que la(les) siennes(s) ? Si  oui, pendant combien de temps ?', 1, 0, 8),
(23, 'HQ6/Quel est le volume moyen annuel des revenus  d’affaires dégagé par ses autres entreprises  le cas échéant ?\"', 1, 0, 8),
(24, 'HQ7/Etes-vous un croyant ? Si oui, votre foi vous aide-t-elle dans la conduite de vos activités ? Sinon, pensez-vous que cela impacte vos activités ?\r\n', 1, 0, 8),
(25, 'IQ1/Existe-t-il des processus, des procédures et un organigramme bien définis au sein de votre entreprise? Si oui, sont-elles appliquées dans la gestion quotidienne de l’entreprise? Sinon, comment sont prises les décisions de gestion?\r\nPs : L’analyste devra effectuer un audit organisationnel avant de sélectionner la réponse', 1, 0, 9),
(26, 'JQ1/Un mécanisme de mise à jour des processus et procédures en fonction de l’évolution des activités a-t-il été mis en place ? Si oui, quel est son mode de fonctionnement (outils de reporting, d’analyse et d’identification des risques)? Sinon, pourquoi ?\r\n', 1, 0, 10),
(27, 'JQ2/ l’entreprise utilise-t-elle  un logiciel professionnel de gestion intégrant les fonctions principales existantes ? Si oui, quels sont les modules pris en compte ? Sinon, présentez les modules pris en compte.Ps : L’analyste devra considérer les modules « Comptabilité » et « Ventes » comme modules de base\"', 1, 0, 10),
(28, 'JQ3/L’identification des responsabilités et des droits d’accès à l’information  par poste est-elle encadrée ? Si oui, par quel(s) moyen(s) ? Sinon, pourquoi ?', 1, 0, 10),
(29, 'JQ4/Existe-t-il un système de sauvegarde des informations (numérique et/ou physique) au sein de votre entreprise? Si oui, comment fonctionne-t-il ? Sinon, pourquoi ?\r\n◻Oui◻ Non', 1, 0, 10),
(30, 'JQ5/Existe-t-il un mécanisme de suivi et de continuité des activités en cas de dysfonctionnement majeur du système informatique ? Si oui, quel est son mode de fonctionnement ? Sinon, pourquoi ?\r\n', 1, 0, 10),
(31, 'KQ1/ l’entreprise a-t’elle l’habitude de mener  des actions commerciales ? Si oui, quel est le  taux moyen  de conversion prospects/clients suite auxdites actions? Sinon, pourquoi ?', 1, 0, 11),
(32, 'KQ2/ Des mécanismes vous permettant de suivre les besoins de vos clients ont-ils été mis en place dans votre entreprise ? Si oui, lesquels ? Sinon, pourquoi ?\r\n', 1, 0, 11),
(33, 'KQ3/Depuis combien de temps l’entreprise a ses  principaux clients en portefeuille ?\r\n◻ [01 an ; 03 ans [  ◻ [03 ans ;  05 ans [    ◻ au moins 5 ans            ', 1, 0, 11),
(34, 'KQ4/L’entreprise dispose t’elled’ outils de communication pour se faire connaître ? Si oui, lesquels (radio, télévision, panneaux publicitaires, caissons lumineux, mailing, flyers, pancartes, etc.) ? Sinon, pourquoi ?', 1, 0, 11),
(35, 'LQ1 : L’entreprise effectue t’elle une planification des besoins en ressources humaines ? Si oui, comment ? Sinon, pourquoi ?', 1, 0, 12),
(36, 'LQ2 : Une politique de gestion des carrières a-t’elle été implémentée au sein de l’entreprise ? Si oui, comment s’implémente-t-elle ? Sinon, pourquoi ?', 1, 0, 12),
(37, 'LQ3/Existe-t-il au sein de l’entreprise un mécanisme d’évaluation périodique du personnel ? Si oui, quel est son mode de fonctionnement ? Sinon, pourquoi ?\"', 1, 0, 12),
(38, 'LQ4/ Les employés de l’entreprise,  poursuivent-ils un plan de formation continue ? Si oui, quel est-il ? Sinon, pourquoi ?', 1, 0, 12),
(39, 'LQ5/ Quel est le niveau moyen de qualification de la main-d’œuvre, en rapport avec la nature de l’ activité de l’entreprise ?\r\nPs : L’analyste devra se baser sur les documents liés aux profils de  postes', 1, 0, 12),
(40, ' LQ6 : Une politique de maintenance des équipements a-t-elle été adoptée au sein de votre entreprise ? Si oui, quelle est-elle ? Sinon, pourquoi ?', 1, 0, 12),
(41, 'LQ7 : Quel est le taux d’utilisation des capacités de production des équipements de l’entreprise ? ', 1, 0, 12),
(42, 'LQ8 : Quelle est la qualité des équipements  (fonction de la durée de vie, marques, achat neuf ou occasion, nombre moyen d’interruption de l’activité pour maintenance, les performances et capacité de production, l’impact sur la réduction des couts)   ?   Ps : un diagnostic exhaustif devra être fait avec justificatifs   \r\n', 1, 0, 12),
(43, 'MQ1/ L’entreprise dispose t’elle d’un plan d’investissement formel et correspondant aux besoins exprimés par le projet de développement de l’entreprise ? ', 1, 0, 13),
(44, 'MQ2/les investissements consentis  permettent-ils à l’entreprise d’atteindre ses objectifs de performance (écart prévision réalisation) \"', 1, 0, 13),
(45, 'MQ3/l’entreprise dispose-t-elle d’une ligne directrice régissant l’acquisition de ses équipements (à la pointe de la technologie, neuf ou occasion) ? Si oui, quelle est-elle ? Sinon, pourquoi ?', 1, 0, 13),
(46, 'MQ4/ le personnel de l’entreprise est-il régulièrement formé sur la l’utilisation optimale des outils d’exploitation.', 1, 0, 13),
(47, 'NQ1/L’entreprise dispose t’elle d’un plan  budgétaire annuel   bien établi ? Si oui, par quelle la méthode de conception ? Sinon, pourquoi ?\r\n', 1, 0, 14),
(48, 'NQ2/L’entreprise évalue t’elle les écarts budgétaires (prévisions réalisation)\r\n', 1, 0, 14),
(49, 'NQ3/L’entreprise réalise-t-elle une  comptabilité analytique  régulière ? Si oui, comment ? Sinon, pourquoi ?\r\n', 1, 0, 14),
(50, 'NQ4/L’entreprise a-t-elle optimisée l’efficacité de ses processus opérationnels ? Si oui, comment ? Sinon, pourquoi ?\"', 1, 0, 14),
(51, 'NQ5/La  politique d’approvisionnement permet-elle à l’entreprise de mettre en comparaison les fournisseurs pour effectuer des achats au meilleur prix ?\"', 1, 0, 14),
(52, 'OQ1/les différents choix stratégiques que le(s) dirigeant(s) propriétaire(s)  a/ont effectué  pour mettre en place l’entreprise et permettre son développement sont-elles en cohérence avec les moyens mis en œuvre, la nature de l’activité, et l’environnement dans lequel l’entreprise évolue ?\r\nPs : l’analyste doit évaluer les la vision d’entreprise du dirigeant et les comparer avec la situation fonctionnelle de l’entreprise. La réponse à cette question implique au préalable la réalisation d’un diagnostic des options stratégiques et la description faite par le dirigeant, de sa vision d’entreprise.', 1, 0, 15),
(53, 'PQ1/L’entreprise récence combien de cas de démission par an (nombre de démission/total employé) ?\"', 1, 0, 16),
(54, 'PQ2/Les employés disposent-ils majoritairement d’un contrat de travail dument rédigé?\"', 1, 0, 16),
(55, 'PQ3/Les employés sous contrats  bénéficient-ils des assurances nécessaires (prévoyance sociale et autres) ?', 1, 0, 16),
(56, 'PQ4/L’entreprise a-t-elle défini et implémenté des procédures formelles relatives à la résolution des conflits au travail ?\"', 1, 0, 16),
(57, 'PQ5/Les employés sont-ils globalement satisfaits de leur traitement salarial ?', 1, 0, 16),
(58, 'PQ6/Les employés ont-ils à leur disposition les moyens techniques nécessaire pour travailler efficacement ?', 1, 0, 16),
(59, 'PQ7/Les Horaires et volumes de travail permettent-ils à l’employé de s’épanouir dans la réalisation de ses taches', 1, 0, 16),
(60, 'QQ1/ En cas d’incapacité du dirigeant propriétaire à assumer sa fonction (décès, invalidité, absence prolongée) :\r\nExiste-t-il un mécanisme d’alternance garantissant la continuité des activités de votre entreprise ?\r\nPs il faudra pour répondre à la question, joindre la description du mécanisme et s’assurer de son efficacité\r\n', 1, 0, 17),
(61, 'QQ2/L’entreprise a-t-elle désigné un ayant droit disposant des compétences requises pour assurer la direction?\r\n', 1, 0, 17),
(62, 'RQ1/Quelle est la fréquence de communication des prévisions par la direction \r\n', 1, 0, 18),
(63, 'RQ2/Quel est le taux moyen de réalisation des prévisions \r\n', 1, 0, 18),
(64, 'RQ3/Quelle est la croissance moyenne  du CA (déflaté) de l’entreprise d’une année à l’autre et la nature de son évolution \r\nPs : moyenne évaluée sur 2 à 5 ans, voir plus en fonction de l’ancienneté de l’entreprise et de la pertinence de l’analyse. Si l’entreprise est nouvelle. On peut se baser uniquement sur les tendances prévisionnelles', 1, 0, 18),
(65, 'RQ4/Quelle est la croissance moyenne  des charges de l’entreprise d’une année à l’autre et la nature de son évolution\r\nPs : moyenne évaluée sur 2 à 5 ans, voir plus en fonction de l’ancienneté de l’entreprise et de la pertinence de l’analyse. Si l’entreprise est nouvelle. On peut se baser uniquement sur les tendances prévisionnelles\r\nPS : ', 1, 0, 18),
(66, 'SQ1/L’entreprise dispose t’elle d’un service de la comptabilité permanent. ?', 1, 0, 19),
(67, 'SQ2/Les états financiers produits respectent ils la déontologie et la règlementation comptable en vigueur, Ps. Un rapport d’expertise comptable pourrait être complémentaire à l’évaluation  faite par l’analyste  pour soutenir la réponse ', 1, 0, 19),
(68, 'SQ3/Les résultats publiés dans  états financiers sont-ils cohérents avec les capacités de production réelles de l’entreprise et son volume de clientèle.', 1, 0, 19),
(69, 'TQ1/Que représente la valeur de l’entreprise par rapport à la surface financière globale du ou des dirigeant propriétaires?\"', 1, 0, 20),
(70, 'TQ2/Le dirigeants propriétaires sont-ils disposés  à cautionner l’entreprise,  par leur  patrimoine  hors entreprise afin de soutenir son développement même  en cas de difficulté ?', 1, 0, 20),
(71, 'UQ1/Les actifs  de l’entreprise peuvent-ils  servir de garanties satisfaisantes (en qualité, quantité, valeur) en cas de nécessité\r\nPs : un diagnostic des actifs visant à estimer la valeur des actifs devra être réalisé avant de répondre à cette question\r\n', 1, 0, 21);

-- --------------------------------------------------------

--
-- Structure de la table `questions_answers`
--

CREATE TABLE `questions_answers` (
  `id` int NOT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `choice_id` int NOT NULL DEFAULT '0',
  `value` varchar(10) DEFAULT NULL,
  `question_id` int NOT NULL DEFAULT '0',
  `sous_critere_id` int NOT NULL DEFAULT '0',
  `critere_id` int NOT NULL DEFAULT '0',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions_answers`
--

INSERT INTO `questions_answers` (`id`, `entreprise_id`, `choice_id`, `value`, `question_id`, `sous_critere_id`, `critere_id`, `created_at`, `updated_at`) VALUES
(522, 261, 2, '5', 1, 1, 1, '2026-04-18', '2026-04-18'),
(523, 261, 11, '2', 2, 1, 1, '2026-04-18', '2026-04-18'),
(524, 261, 14, '2', 3, 1, 1, '2026-04-18', '2026-04-18'),
(525, 261, 20, '2', 4, 2, 1, '2026-04-18', '2026-04-18'),
(526, 261, 27, '3', 5, 3, 1, '2026-04-18', '2026-04-18'),
(527, 261, 31, '1', 6, 3, 1, '2026-04-18', '2026-04-18'),
(528, 261, 39, '3', 7, 3, 1, '2026-04-18', '2026-04-18'),
(529, 261, 45, '3', 8, 4, 1, '2026-04-18', '2026-04-18'),
(530, 261, 51, '3', 9, 4, 1, '2026-04-18', '2026-04-18'),
(531, 261, 57, '3', 10, 4, 1, '2026-04-18', '2026-04-18'),
(532, 261, 62, '2', 11, 5, 1, '2026-04-18', '2026-04-18'),
(533, 261, 73, '3', 12, 5, 1, '2026-04-18', '2026-04-18'),
(534, 261, 81, '0', 13, 5, 1, '2026-04-18', '2026-04-18'),
(535, 261, 84, '5', 14, 6, 1, '2026-04-18', '2026-04-18'),
(536, 261, 91, '4', 15, 6, 1, '2026-04-18', '2026-04-18'),
(537, 261, 96, '5', 16, 6, 1, '2026-04-18', '2026-04-18'),
(538, 261, 103, '3', 17, 7, 1, '2026-04-18', '2026-04-18'),
(539, 261, 109, '3', 18, 8, 2, '2026-04-18', '2026-04-18'),
(540, 261, 115, '3', 19, 8, 2, '2026-04-18', '2026-04-18'),
(541, 261, 120, '2', 20, 8, 2, '2026-04-18', '2026-04-18'),
(542, 261, 126, '2', 21, 8, 2, '2026-04-18', '2026-04-18'),
(543, 261, 133, '3', 22, 8, 2, '2026-04-18', '2026-04-18'),
(544, 261, 138, '2', 23, 8, 2, '2026-04-18', '2026-04-18'),
(545, 261, 144, '2', 24, 8, 2, '2026-04-18', '2026-04-18'),
(546, 261, 157, '3', 26, 10, 2, '2026-04-18', '2026-04-18'),
(547, 261, 162, '2', 27, 10, 2, '2026-04-18', '2026-04-18'),
(548, 261, 168, '2', 28, 10, 2, '2026-04-18', '2026-04-18'),
(549, 261, 175, '3', 29, 10, 2, '2026-04-18', '2026-04-18'),
(550, 261, 181, '3', 30, 10, 2, '2026-04-18', '2026-04-18'),
(551, 261, 186, '2', 31, 11, 2, '2026-04-18', '2026-04-18'),
(552, 261, 193, '3', 32, 11, 2, '2026-04-18', '2026-04-18'),
(553, 261, 199, '3', 33, 11, 2, '2026-04-18', '2026-04-18'),
(554, 261, 206, '4', 34, 11, 2, '2026-04-18', '2026-04-18'),
(555, 261, 211, '3', 35, 12, 2, '2026-04-18', '2026-04-18'),
(556, 261, 216, '2', 36, 12, 2, '2026-04-18', '2026-04-18'),
(557, 261, 223, '3', 37, 12, 2, '2026-04-18', '2026-04-18'),
(558, 261, 230, '4', 38, 12, 2, '2026-04-18', '2026-04-18'),
(559, 261, 234, '2', 39, 12, 2, '2026-04-18', '2026-04-18'),
(560, 261, 242, '4', 40, 12, 2, '2026-04-18', '2026-04-18'),
(561, 261, 247, '3', 41, 12, 2, '2026-04-18', '2026-04-18'),
(562, 261, 252, '2', 42, 12, 2, '2026-04-18', '2026-04-18'),
(563, 261, 258, '2', 43, 13, 2, '2026-04-18', '2026-04-18'),
(564, 261, 263, '2', 44, 13, 2, '2026-04-18', '2026-04-18'),
(565, 261, 268, '2', 45, 13, 2, '2026-04-18', '2026-04-18'),
(566, 261, 274, '3', 46, 13, 2, '2026-04-18', '2026-04-18'),
(567, 261, 339, '1', 62, 18, 4, '2026-04-18', '2026-04-18'),
(568, 261, 344, '2', 63, 18, 4, '2026-04-18', '2026-04-18'),
(569, 261, 348, '1', 64, 18, 4, '2026-04-18', '2026-04-18'),
(570, 261, 355, '1', 65, 18, 4, '2026-04-18', '2026-04-18'),
(571, 261, 374, '2', 69, 20, 4, '2026-04-18', '2026-04-18'),
(572, 261, 379, '3', 70, 20, 4, '2026-04-18', '2026-04-18'),
(573, 261, 381, '1', 71, 21, 4, '2026-04-18', '2026-04-18'),
(574, 263, 2, '5', 1, 1, 1, '2026-04-21', '2026-04-21'),
(575, 263, 11, '2', 2, 1, 1, '2026-04-21', '2026-04-21'),
(576, 263, 14, '2', 3, 1, 1, '2026-04-21', '2026-04-21'),
(577, 263, 21, '3', 4, 2, 1, '2026-04-21', '2026-04-21'),
(578, 263, 27, '3', 5, 3, 1, '2026-04-21', '2026-04-21'),
(579, 263, 46, '4', 8, 4, 1, '2026-04-21', '2026-04-21'),
(580, 263, 52, '4', 9, 4, 1, '2026-04-21', '2026-04-21'),
(581, 263, 57, '3', 10, 4, 1, '2026-04-21', '2026-04-21'),
(582, 263, 62, '2', 11, 5, 1, '2026-04-21', '2026-04-21'),
(583, 263, 72, '2', 12, 5, 1, '2026-04-21', '2026-04-21'),
(584, 263, 82, '0', 13, 5, 1, '2026-04-21', '2026-04-21'),
(585, 263, 103, '3', 17, 7, 1, '2026-04-21', '2026-04-21'),
(586, 263, 108, '2', 18, 8, 2, '2026-04-21', '2026-04-21'),
(587, 263, 115, '3', 19, 8, 2, '2026-04-21', '2026-04-21'),
(588, 263, 123, '5', 20, 8, 2, '2026-04-21', '2026-04-21'),
(589, 263, 126, '2', 21, 8, 2, '2026-04-21', '2026-04-21'),
(590, 263, 133, '3', 22, 8, 2, '2026-04-21', '2026-04-21'),
(591, 263, 138, '2', 23, 8, 2, '2026-04-21', '2026-04-21'),
(592, 263, 147, '5', 24, 8, 2, '2026-04-21', '2026-04-21'),
(593, 263, 150, '2', 25, 9, 2, '2026-04-21', '2026-04-21'),
(594, 263, 156, '2', 26, 10, 2, '2026-04-21', '2026-04-21'),
(595, 263, 162, '2', 27, 10, 2, '2026-04-21', '2026-04-21'),
(596, 263, 169, '3', 28, 10, 2, '2026-04-21', '2026-04-21'),
(597, 263, 176, '4', 29, 10, 2, '2026-04-21', '2026-04-21'),
(598, 263, 181, '3', 30, 10, 2, '2026-04-21', '2026-04-21'),
(599, 263, 335, '3', 60, 17, 2, '2026-04-21', '2026-04-21'),
(600, 263, 336, '1', 61, 17, 2, '2026-04-21', '2026-04-21'),
(601, 263, 340, '2', 62, 18, 4, '2026-04-21', '2026-04-21'),
(602, 263, 344, '2', 63, 18, 4, '2026-04-21', '2026-04-21'),
(603, 263, 352, '3', 64, 18, 4, '2026-04-21', '2026-04-21'),
(604, 263, 359, '3', 65, 18, 4, '2026-04-21', '2026-04-21'),
(605, 263, 362, '2', 66, 19, 4, '2026-04-21', '2026-04-21'),
(606, 263, 366, '2', 67, 19, 4, '2026-04-21', '2026-04-21'),
(607, 263, 371, '3', 68, 19, 4, '2026-04-21', '2026-04-21'),
(608, 263, 374, '2', 69, 20, 4, '2026-04-21', '2026-04-21'),
(609, 263, 378, '2', 70, 20, 4, '2026-04-21', '2026-04-21'),
(610, 263, 382, '2', 71, 21, 4, '2026-04-21', '2026-04-21'),
(611, 263, 85, '4', 14, 6, 1, '2026-04-21', '2026-04-21'),
(612, 263, 91, '4', 15, 6, 1, '2026-04-21', '2026-04-21'),
(613, 263, 97, '4', 16, 6, 1, '2026-04-21', '2026-04-21'),
(614, 263, 186, '2', 31, 11, 2, '2026-04-21', '2026-04-21'),
(615, 263, 192, '2', 32, 11, 2, '2026-04-21', '2026-04-21'),
(616, 263, 198, '2', 33, 11, 2, '2026-04-21', '2026-04-21'),
(617, 263, 204, '2', 34, 11, 2, '2026-04-21', '2026-04-21'),
(618, 263, 211, '3', 35, 12, 2, '2026-04-21', '2026-04-21'),
(619, 263, 217, '3', 36, 12, 2, '2026-04-21', '2026-04-21'),
(620, 263, 222, '2', 37, 12, 2, '2026-04-21', '2026-04-21'),
(621, 263, 228, '2', 38, 12, 2, '2026-04-21', '2026-04-21'),
(622, 263, 237, '5', 39, 12, 2, '2026-04-21', '2026-04-21'),
(623, 263, 240, '2', 40, 12, 2, '2026-04-21', '2026-04-21'),
(624, 263, 249, '5', 41, 12, 2, '2026-04-21', '2026-04-21'),
(625, 263, 251, '1', 42, 12, 2, '2026-04-21', '2026-04-21'),
(626, 263, 258, '2', 43, 13, 2, '2026-04-21', '2026-04-21'),
(627, 263, 263, '2', 44, 13, 2, '2026-04-21', '2026-04-21'),
(628, 263, 268, '2', 45, 13, 2, '2026-04-21', '2026-04-21'),
(629, 263, 272, '1', 46, 13, 2, '2026-04-21', '2026-04-21'),
(630, 265, 3, '4', 1, 1, 1, '2026-04-24', '2026-04-24'),
(631, 265, 8, '5', 2, 1, 1, '2026-04-24', '2026-04-24'),
(632, 265, 17, '5', 3, 1, 1, '2026-04-24', '2026-04-24'),
(633, 265, 20, '2', 4, 2, 1, '2026-04-24', '2026-04-24'),
(634, 265, 27, '3', 5, 3, 1, '2026-04-24', '2026-04-24'),
(635, 265, 32, '2', 6, 3, 1, '2026-04-24', '2026-04-24'),
(636, 265, 41, '5', 7, 3, 1, '2026-04-24', '2026-04-24'),
(637, 265, 44, '2', 8, 4, 1, '2026-04-24', '2026-04-24'),
(638, 265, 50, '2', 9, 4, 1, '2026-04-24', '2026-04-24'),
(639, 265, 56, '2', 10, 4, 1, '2026-04-24', '2026-04-24'),
(640, 265, 62, '2', 11, 5, 1, '2026-04-24', '2026-04-24'),
(641, 265, 72, '2', 12, 5, 1, '2026-04-24', '2026-04-24'),
(642, 265, 82, '0', 13, 5, 1, '2026-04-24', '2026-04-24'),
(643, 265, 84, '5', 14, 6, 1, '2026-04-24', '2026-04-24'),
(644, 265, 90, '5', 15, 6, 1, '2026-04-24', '2026-04-24'),
(645, 265, 96, '5', 16, 6, 1, '2026-04-24', '2026-04-24'),
(646, 265, 210, '2', 35, 12, 2, '2026-04-24', '2026-04-24'),
(647, 265, 217, '3', 36, 12, 2, '2026-04-24', '2026-04-24'),
(648, 265, 222, '2', 37, 12, 2, '2026-04-24', '2026-04-24'),
(649, 265, 279, '3', 47, 14, 2, '2026-04-24', '2026-04-24'),
(650, 265, 283, '3', 48, 14, 2, '2026-04-24', '2026-04-24'),
(651, 265, 286, '2', 49, 14, 2, '2026-04-24', '2026-04-24'),
(652, 265, 290, '2', 50, 14, 2, '2026-04-24', '2026-04-24'),
(653, 265, 293, '1', 51, 14, 2, '2026-04-24', '2026-04-24'),
(654, 265, 373, '1', 69, 20, 4, '2026-04-24', '2026-04-24'),
(655, 265, 378, '2', 70, 20, 4, '2026-04-24', '2026-04-24'),
(656, 265, 151, '3', 25, 9, 2, '2026-04-24', '2026-04-24'),
(657, 265, 188, '4', 31, 11, 2, '2026-04-24', '2026-04-24'),
(658, 265, 193, '3', 32, 11, 2, '2026-04-24', '2026-04-24'),
(659, 265, 201, '5', 33, 11, 2, '2026-04-24', '2026-04-24'),
(660, 265, 204, '2', 34, 11, 2, '2026-04-24', '2026-04-24'),
(661, 265, 228, '2', 38, 12, 2, '2026-04-24', '2026-04-24'),
(662, 265, 237, '5', 39, 12, 2, '2026-04-24', '2026-04-24'),
(663, 265, 240, '2', 40, 12, 2, '2026-04-24', '2026-04-24'),
(664, 265, 248, '4', 41, 12, 2, '2026-04-24', '2026-04-24'),
(665, 265, 254, '4', 42, 12, 2, '2026-04-24', '2026-04-24'),
(666, 265, 334, '2', 60, 17, 2, '2026-04-24', '2026-04-24'),
(667, 265, 337, '2', 61, 17, 2, '2026-04-24', '2026-04-24'),
(668, 265, 340, '2', 62, 18, 4, '2026-04-24', '2026-04-24'),
(669, 265, 345, '3', 63, 18, 4, '2026-04-24', '2026-04-24'),
(670, 265, 348, '1', 64, 18, 4, '2026-04-24', '2026-04-24'),
(671, 265, 358, '3', 65, 18, 4, '2026-04-24', '2026-04-24'),
(672, 265, 361, '1', 66, 19, 4, '2026-04-24', '2026-04-24'),
(673, 265, 366, '2', 67, 19, 4, '2026-04-24', '2026-04-24'),
(674, 265, 370, '2', 68, 19, 4, '2026-04-24', '2026-04-24'),
(675, 265, 381, '1', 71, 21, 4, '2026-04-24', '2026-04-24'),
(676, 266, 2, '5', 1, 1, 1, '2026-04-29', '2026-04-29'),
(677, 266, 11, '2', 2, 1, 1, '2026-04-29', '2026-04-29'),
(678, 266, 15, '3', 3, 1, 1, '2026-04-29', '2026-04-29'),
(679, 266, 20, '2', 4, 2, 1, '2026-04-29', '2026-04-29'),
(680, 266, 27, '3', 5, 3, 1, '2026-04-29', '2026-04-29'),
(681, 266, 33, '3', 6, 3, 1, '2026-04-29', '2026-04-29'),
(682, 266, 39, '3', 7, 3, 1, '2026-04-29', '2026-04-29'),
(683, 266, 44, '2', 8, 4, 1, '2026-04-29', '2026-04-29'),
(684, 266, 51, '3', 9, 4, 1, '2026-04-29', '2026-04-29'),
(685, 266, 58, '4', 10, 4, 1, '2026-04-29', '2026-04-29'),
(686, 266, 62, '2', 11, 5, 1, '2026-04-29', '2026-04-29'),
(687, 266, 73, '3', 12, 5, 1, '2026-04-29', '2026-04-29'),
(688, 266, 82, '0', 13, 5, 1, '2026-04-29', '2026-04-29'),
(689, 266, 85, '4', 14, 6, 1, '2026-04-29', '2026-04-29'),
(690, 266, 90, '5', 15, 6, 1, '2026-04-29', '2026-04-29'),
(691, 266, 97, '4', 16, 6, 1, '2026-04-29', '2026-04-29'),
(692, 266, 103, '3', 17, 7, 1, '2026-04-29', '2026-04-29'),
(693, 266, 108, '2', 18, 8, 2, '2026-04-29', '2026-04-29'),
(694, 266, 115, '3', 19, 8, 2, '2026-04-29', '2026-04-29'),
(695, 266, 122, '4', 20, 8, 2, '2026-04-29', '2026-04-29'),
(696, 266, 127, '3', 21, 8, 2, '2026-04-29', '2026-04-29'),
(697, 266, 133, '3', 22, 8, 2, '2026-04-29', '2026-04-29'),
(698, 266, 138, '2', 23, 8, 2, '2026-04-29', '2026-04-29'),
(699, 266, 144, '2', 24, 8, 2, '2026-04-29', '2026-04-29'),
(700, 266, 151, '3', 25, 9, 2, '2026-04-29', '2026-04-29'),
(701, 266, 156, '2', 26, 10, 2, '2026-04-29', '2026-04-29'),
(702, 266, 162, '2', 27, 10, 2, '2026-04-29', '2026-04-29'),
(703, 266, 169, '3', 28, 10, 2, '2026-04-29', '2026-04-29'),
(704, 266, 175, '3', 29, 10, 2, '2026-04-29', '2026-04-29'),
(705, 266, 181, '3', 30, 10, 2, '2026-04-29', '2026-04-29'),
(706, 266, 187, '3', 31, 11, 2, '2026-04-29', '2026-04-29'),
(707, 266, 195, '5', 32, 11, 2, '2026-04-29', '2026-04-29'),
(708, 266, 199, '3', 33, 11, 2, '2026-04-29', '2026-04-29'),
(709, 266, 206, '4', 34, 11, 2, '2026-04-29', '2026-04-29'),
(710, 266, 210, '2', 35, 12, 2, '2026-04-29', '2026-04-29'),
(711, 266, 217, '3', 36, 12, 2, '2026-04-29', '2026-04-29'),
(712, 266, 223, '3', 37, 12, 2, '2026-04-29', '2026-04-29'),
(713, 266, 229, '3', 38, 12, 2, '2026-04-29', '2026-04-29'),
(714, 266, 236, '4', 39, 12, 2, '2026-04-29', '2026-04-29'),
(715, 266, 240, '2', 40, 12, 2, '2026-04-29', '2026-04-29'),
(716, 266, 247, '3', 41, 12, 2, '2026-04-29', '2026-04-29'),
(717, 266, 252, '2', 42, 12, 2, '2026-04-29', '2026-04-29'),
(718, 266, 259, '3', 43, 13, 2, '2026-04-29', '2026-04-29'),
(719, 266, 264, '3', 44, 13, 2, '2026-04-29', '2026-04-29'),
(720, 266, 269, '3', 45, 13, 2, '2026-04-29', '2026-04-29'),
(721, 266, 274, '3', 46, 13, 2, '2026-04-29', '2026-04-29'),
(722, 266, 278, '2', 47, 14, 2, '2026-04-29', '2026-04-29'),
(723, 266, 282, '2', 48, 14, 2, '2026-04-29', '2026-04-29'),
(724, 266, 286, '2', 49, 14, 2, '2026-04-29', '2026-04-29'),
(725, 266, 290, '2', 50, 14, 2, '2026-04-29', '2026-04-29'),
(726, 266, 294, '2', 51, 14, 2, '2026-04-29', '2026-04-29'),
(727, 266, 301, '5', 52, 15, 2, '2026-04-29', '2026-04-29'),
(728, 266, 315, '3', 55, 16, 2, '2026-04-29', '2026-04-29'),
(729, 266, 318, '2', 56, 16, 2, '2026-04-29', '2026-04-29'),
(730, 266, 323, '3', 57, 16, 2, '2026-04-29', '2026-04-29'),
(731, 266, 326, '2', 58, 16, 2, '2026-04-29', '2026-04-29'),
(732, 266, 331, '3', 59, 16, 2, '2026-04-29', '2026-04-29'),
(733, 266, 334, '2', 60, 17, 2, '2026-04-29', '2026-04-29'),
(734, 266, 337, '2', 61, 17, 2, '2026-04-29', '2026-04-29'),
(735, 266, 341, '3', 62, 18, 4, '2026-04-29', '2026-04-29'),
(736, 266, 345, '3', 63, 18, 4, '2026-04-29', '2026-04-29'),
(737, 266, 349, '1', 64, 18, 4, '2026-04-29', '2026-04-29'),
(738, 266, 355, '1', 65, 18, 4, '2026-04-29', '2026-04-29'),
(739, 266, 363, '3', 66, 19, 4, '2026-04-29', '2026-04-29'),
(740, 266, 367, '3', 67, 19, 4, '2026-04-29', '2026-04-29'),
(741, 266, 370, '2', 68, 19, 4, '2026-04-29', '2026-04-29'),
(742, 266, 375, '3', 69, 20, 4, '2026-04-29', '2026-04-29'),
(743, 266, 378, '2', 70, 20, 4, '2026-04-29', '2026-04-29'),
(744, 266, 383, '3', 71, 21, 4, '2026-04-29', '2026-04-29');

-- --------------------------------------------------------

--
-- Structure de la table `questions_choices`
--

CREATE TABLE `questions_choices` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(10) DEFAULT NULL,
  `question_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions_choices`
--

INSERT INTO `questions_choices` (`id`, `name`, `value`, `question_id`) VALUES
(1, 'Oui (Limitée à la ville …)', '6', 1),
(2, 'Oui (Limitée à la région …)', '5', 1),
(3, 'Oui (Tout le pays)', '4', 1),
(4, 'Non (Sous régional)', '3', 1),
(5, 'Non (Continental)', '2', 1),
(6, 'Non (Mondial)', '1', 1),
(7, 'Très forte', '6', 2),
(8, 'Forte', '5', 2),
(9, 'Moyenne', '4', 2),
(10, 'Faible', '3', 2),
(11, 'Très faible', '2', 2),
(12, 'Inexistante', '1', 2),
(13, 'Oui plusieurs et important', '1', 3),
(14, 'Oui plusieurs', '2', 3),
(15, 'Oui assez', '3', 3),
(16, 'Peu', '4', 3),
(17, 'Très peu', '5', 3),
(18, 'Non', '6', 3),
(19, 'Très bonne tendance (impact très positif sur l’activité)', '1 ', 4),
(20, 'Bonne tendance (impact positif sur l’activité)', '2', 4),
(21, 'Tendance insignifiante (faible impact positif sur l’activité)', '3', 4),
(22, 'Tendance insignifiante (faible impact négatif sur l’activité)', '4', 4),
(23, 'Mauvaise tendance (impact négatif sur l’activité)', '5', 4),
(24, 'Très mauvaise tendance   (impact très négatif sur l’activité)', '6', 4),
(25, 'Activité permanente', '1', 5),
(26, 'Très peu cyclique', '2', 5),
(27, 'Peu cyclique (arguments et commentaires positifs)', '3', 5),
(28, 'Peu cyclique (arguments et commentaires négatifs)', '4', 5),
(29, 'Très cyclique (arguments et commentaires positifs)', '5', 5),
(30, 'Très cyclique  (arguments et commentaires négatifs)', '6', 5),
(31, 'Absence de variation', '1 ', 6),
(32, 'Très faible variation', '2', 6),
(33, 'Faible variation (arguments et commentaires positifs)', '3', 6),
(34, 'Faible variation  (arguments et commentaires négatifs)', '4', 6),
(35, 'Forte variation (arguments et commentaires positifs)', '5', 6),
(36, 'Forte variation  (arguments et commentaires négatifs)', '6', 6),
(37, 'Absence de variation', '1 ', 7),
(38, 'Très faible variation', '2', 7),
(39, 'Faible variation (arguments et commentaires positifs)', '3', 7),
(40, 'Faible variation  (arguments et commentaires négatifs)', '4', 7),
(41, 'Forte variation (arguments et commentaires positifs)', '5', 7),
(42, 'Forte variation  (arguments et commentaires négatifs)', '6', 7),
(43, '[85% ; 100%]', '1 ', 8),
(44, '[65% ; 85% [', '2', 8),
(45, '[45% ; 65% [', '3', 8),
(46, '[25% ; 45% [', '4', 8),
(47, '[15% ; 25% [', '5', 8),
(48, '[1% ; 15% [', '6', 8),
(49, 'Plus de 100 %', '1 ', 9),
(50, '[75% ; 100% [', '2', 9),
(51, '[50% ; 75% [', '3', 9),
(52, '[25% ; 50% [', '4', 9),
(53, '[1% ; 25% [', '5', 9),
(54, 'Absence de prévisions', '6', 9),
(55, 'Très régulièrement', '1 ', 10),
(56, 'Régulièrement', '2', 10),
(57, 'Régulièrement (en cas d’expression de besoins des clients)', '3', 10),
(58, 'Rarement', '4', 10),
(59, 'Très rarement', '5', 10),
(60, 'Jamais', '6', 10),
(61, 'Au plus 20% (+)', '1 ', 11),
(62, 'Au plus 20% (-)', '2', 11),
(63, '[20% ; 30% [(+)', '3', 11),
(64, '[20% ; 30% [(-)', '4', 11),
(65, '[30% ; 40% [(+)', '5', 11),
(66, '[30% ; 40% [(-)', '6', 11),
(67, '[40% ; 50% [(+)', '7', 11),
(68, '[40% ; 50% [(-)', '8', 11),
(69, 'Plus de 50% (+)', '9', 11),
(70, 'Plus de 50% (-)', '10', 11),
(71, 'Non (+)', '1', 12),
(72, 'Non  (-)', '2', 12),
(73, 'Oui/1 (+)', '3', 12),
(74, 'Oui/1 (-)', '4', 12),
(75, 'Oui/2 (+)', '5', 12),
(76, 'Oui/2 (-)', '6', 12),
(77, 'Oui/3 (+)', '7', 12),
(78, 'Oui/3 (-)', '8', 12),
(79, 'Oui/Entre 3 et 8 (+)', '9', 12),
(80, 'Oui/Entre 3 et 8  (-)', '10', 12),
(81, 'Oui', '+', 13),
(82, 'Non', '-', 13),
(83, 'Elevé et contraignant', '6', 14),
(84, 'Elevé mais acceptable', '5', 14),
(85, 'Moyen et contraignant', '4', 14),
(86, 'Moyen mais acceptable', '3', 14),
(87, 'Faible', '2', 14),
(88, 'Très faible', '1', 14),
(89, 'Elevé et contraignant', '6', 15),
(90, 'Elevé mais acceptable', '5', 15),
(91, 'Moyen et contraignant', '4', 15),
(92, 'Moyen mais acceptable', '3', 15),
(93, 'Faible', '2', 15),
(94, 'Très faible', '1', 15),
(95, 'Insignifiant', '6', 16),
(96, 'Très faible', '5', 16),
(97, 'Faible', '4', 16),
(98, 'Moyen', '3', 16),
(99, 'Elevé', '2', 16),
(100, 'Très élevé', '1', 16),
(101, 'Très bonne visibilité (arguments et commentaires très pertinents)', '1 ', 17),
(102, 'Bonne visibilité (arguments et commentaires pertinents)', '2', 17),
(103, 'Visibilité moyenne (arguments et commentaires acceptables)', '3', 17),
(104, 'Faible visibilité (arguments et commentaires peu pertinents)', '4', 17),
(105, 'Très faible visibilité (arguments et commentaires très peu pertinents)', '5', 17),
(106, 'Aucune visibilité', '6', 17),
(107, '4 occurrences de Oui (niveau de connaissances avancé)', '1 ', 18),
(108, '4 occurrences de Oui (niveau de connaissances moyen)', '2', 18),
(109, '2 occurrences de Oui (niveau de connaissances avancé)', '3', 18),
(110, '2  occurrences de Oui (niveau de connaissances moyen)', '4', 18),
(111, '1 occurrence de oui', '5', 18),
(112, 'Pas de connaissance', '6', 18),
(113, 'Plus de 20 ans', '1 ', 19),
(114, 'Entre 10 et 20 ans', '2', 19),
(115, 'Entre 5 et 10 ans', '3', 19),
(116, 'Entre 2 et 5 ans', '4', 19),
(117, 'Moins de 2 ans', '5', 19),
(118, 'Célibataire', '6', 19),
(119, 'Oui/arguments et commentaires très pertinents', '1 ', 20),
(120, 'Oui /arguments et commentaires pertinents', '2', 20),
(121, 'Oui/ arguments et commentaires peu pertinents', '3', 20),
(122, 'Non/arguments et commentaires pertinents', '4', 20),
(123, 'Non /arguments et commentaires peu pertinent', '5', 20),
(124, 'Non  /aucun commentaire', '6', 20),
(125, 'Confirmé + (plus de 20 ans)', '1 ', 21),
(126, 'Confirmé -  (entre 15 et 20 ans)', '2', 21),
(127, 'Avancé + (entre 10 et 15 ans)', '3', 21),
(128, 'Avancé - (entre 5 et 10 ans)', '4', 21),
(129, 'Intermédiaire (entre 2 et 5 ans)', '5', 21),
(130, 'Débutant (moins de 2 ans)', '6', 21),
(131, 'Plus de  20 ans', '1 ', 22),
(132, 'Entre 10 et 20 ans', '2', 22),
(133, 'Entre 5 et 10 ans', '3', 22),
(134, 'Entre 2 et 5 ans', '4', 22),
(135, 'Moins de 2 ans', '5', 22),
(136, 'Aucune expérience antérieure', '6', 22),
(137, 'Très élevé (plus de 100 000 000)', '1 ', 23),
(138, 'Elevé (entre 25 000 000 et 100 000 000)', '2', 23),
(139, 'Moyen (entre 5 000 000 et 25 000 000) ', '3', 23),
(140, 'Faible (entre 1 000 000 et 5 000 000)', '4', 23),
(141, 'Très faible (entre 100 000 et 1 000 000)', '5', 23),
(142, 'Pas d’autres entreprises', '6', 23),
(143, 'Oui/arguments et commentaires très pertinents', '1 ', 24),
(144, 'Oui/arguments et commentaires pertinents', '2', 24),
(145, 'Oui/arguments et commentaires peu pertinents', '3', 24),
(146, 'Non /arguments et commentaires pertinents', '4', 24),
(147, 'Non/arguments et commentaires peu pertinents', '5', 24),
(148, 'Non/aucun commentaire', '6', 24),
(149, 'Oui/arguments et commentaires très pertinents', '1 ', 25),
(150, 'Oui/arguments et commentaires pertinents ', '2', 25),
(151, 'Oui/ arguments et commentaires peu pertinents', '3', 25),
(152, 'Non/arguments et commentaires pertinents', '4', 25),
(153, 'Non/arguments et commentaires peu pertinents', '5', 25),
(154, 'Non/aucun commentaire', '6', 25),
(155, 'Oui/arguments et commentaires très pertinents', '1 ', 26),
(156, 'Oui/arguments et commentaires pertinents', '2', 26),
(157, 'Oui/arguments et commentaires peu pertinents', '3', 26),
(158, 'Non/arguments et commentaires pertinents', '4', 26),
(159, 'Non/arguments et commentaires peu pertinents', '5', 26),
(160, 'Non/aucun commentaire', '6', 26),
(161, 'Oui/ensemble des modules de base  + ensemble des modules additionnels', '1 ', 27),
(162, 'Oui/ensemble des modules de base + quelques modules additionnels', '2', 27),
(163, 'Oui/ensemble des modules de base pris en compte', '3', 27),
(164, 'Non/quantité considérable de modules pris en compte', '4', 27),
(165, 'Non/faible quantité de modules pris en compte', '5', 27),
(166, 'Non/aucun module pris en compte', '6', 27),
(167, 'Oui/arguments et commentaires très pertinents', '1 ', 28),
(168, 'Oui/arguments et commentaires pertinents', '2', 28),
(169, 'Oui/arguments et commentaires peu pertinents', '3', 28),
(170, 'Non/arguments et commentaires pertinents', '4', 28),
(171, 'Non/arguments et commentaires peu pertinents', '5', 28),
(172, 'Non/aucun commentaire', '6', 28),
(173, 'Oui/arguments et commentaires très pertinents', '1 ', 29),
(174, 'Oui/arguments et commentaires pertinents', '2', 29),
(175, 'Oui/arguments et commentaires peu pertinents', '3', 29),
(176, 'Non/arguments et commentaires pertinents', '4', 29),
(177, 'Non/arguments et commentaires peu pertinents', '5', 29),
(178, 'Non/aucun commentaire', '6', 29),
(179, 'Oui/arguments et commentaires très pertinents', '1 ', 30),
(180, 'Oui/arguments et commentaires pertinents', '2', 30),
(181, 'Oui/arguments et commentaires peu pertinents', '3', 30),
(182, 'Non/arguments et commentaires pertinents', '4', 30),
(183, 'Non/arguments et commentaires peu pertinents', '5', 30),
(184, 'Non/aucun commentaire', '6', 30),
(185, 'Oui/plus de 50%', '1 ', 31),
(186, 'Oui/entre 20 et 50%', '2', 31),
(187, 'Oui (entre 10 et 20%)', '3', 31),
(188, 'Oui (moins de 10%)', '4', 31),
(189, 'Non/arguments et commentaires peu pertinents', '5', 31),
(190, 'Non/aucun commentaire', '6', 31),
(191, 'Oui/arguments et commentaires très pertinents', '1 ', 32),
(192, 'Oui/arguments et commentaires pertinents', '2', 32),
(193, 'Oui/arguments et commentaires peu pertinents', '3', 32),
(194, 'Non/arguments et commentaires pertinents', '4', 32),
(195, 'Non/arguments et commentaires peu pertinents', '5', 32),
(196, 'Non/aucun commentaire', '6', 32),
(197, 'Plus de  5 ans', '1 ', 33),
(198, ' 5 ans', '2', 33),
(199, '4 ans', '3', 33),
(200, '3 ans', '4', 33),
(201, '2 ans', '5', 33),
(202, '1 an', '6', 33),
(203, 'Oui/arguments et commentaires très pertinents', '1 ', 34),
(204, 'Oui/arguments et commentaires pertinents', '2', 34),
(205, 'Oui/arguments et commentaires peu pertinents', '3', 34),
(206, 'Non/arguments et commentaires pertinents', '4', 34),
(207, 'Non /arguments et commentaires peu pertinents', '5', 34),
(208, 'Non/aucun commentaire', '6', 34),
(209, 'Oui/arguments et commentaires très pertinents', '1 ', 35),
(210, 'Oui/arguments et commentaires pertinents', '2', 35),
(211, 'Oui/arguments et commentaires peu pertinents', '3', 35),
(212, 'Non/arguments et commentaires pertinents', '4', 35),
(213, 'Non /arguments et commentaires peu pertinents', '5', 35),
(214, 'Non/aucun commentaire', '6', 35),
(215, 'Oui/arguments et commentaires très pertinents', '1 ', 36),
(216, 'Oui/arguments et commentaires pertinents', '2', 36),
(217, 'Oui/arguments et commentaires peu pertinents', '3', 36),
(218, 'Non/arguments et commentaires pertinents', '4', 36),
(219, 'Non/arguments et commentaires peu pertinents', '5', 36),
(220, 'Non/aucun commentaire', '6', 36),
(221, 'Oui/arguments et commentaires très pertinents', '1 ', 37),
(222, 'Oui/arguments et commentaires pertinents', '2', 37),
(223, 'Oui/arguments et commentaires peu pertinents', '3', 37),
(224, 'Non/arguments et commentaires pertinents', '4', 37),
(225, 'Non /arguments et commentaires peu pertinents', '5', 37),
(226, 'Non/aucun commentaire', '6', 37),
(227, 'Oui/arguments et commentaires très pertinents', '1 ', 38),
(228, 'Oui/arguments et commentaires pertinents', '2', 38),
(229, 'Oui/arguments et commentaires peu pertinents', '3', 38),
(230, 'Non/arguments et commentaires pertinents', '4', 38),
(231, 'Non /arguments et commentaires peu pertinents', '5', 38),
(232, 'Non/aucun commentaire', '6', 38),
(233, 'Très forte qualification', '1 ', 39),
(234, 'Forte qualification', '2', 39),
(235, 'Qualification de base', '3', 39),
(236, 'Faible qualification', '4', 39),
(237, 'Très faible qualification', '5', 39),
(238, 'Aucune qualification particulière', '6', 39),
(239, 'Oui /arguments et commentaires très pertinents', '1 ', 40),
(240, 'Oui /arguments et commentaires pertinents', '2', 40),
(241, 'Oui/arguments et commentaires peu pertinents', '3', 40),
(242, 'Non/arguments et commentaires pertinents', '4', 40),
(243, 'Non /arguments et commentaires peu pertinents', '5', 40),
(244, 'Non /aucun commentaire', '6', 40),
(245, 'Plus de 80%', '1', 41),
(246, 'Entre 70 et 80%', '2', 41),
(247, 'Entre 60 et 70%', '3', 41),
(248, 'Entre 50 et 60%', '4', 41),
(249, 'Entre 30 et 50%', '5', 41),
(250, 'Moins de 30%', '6', 41),
(251, 'Excellente ', '1 ', 42),
(252, 'Très bonne', '2', 42),
(253, 'Bonne', '3', 42),
(254, 'Moyenne', '4', 42),
(255, 'Mauvaise', '5', 42),
(256, 'Très mauvaise', '6', 42),
(257, 'Oui /Plan d’investissement régulièrement mis à jour et correspondant au mieux aux besoins exprimés par le projet de développement de l’entreprise', '1', 43),
(258, 'Oui /Plan d’investissement correspondant aux besoins exprimés par le  projet de développement de l’entreprise', '2', 43),
(259, 'Oui/Plan d’investissement informel mais correspondant aux besoins exprimés par le projet de développement de l’entreprise ', '3', 43),
(260, 'Oui/Plan d’investissement informel mais ne correspondant pas aux besoins exprimés par le projet de développement de l’entreprise ', '4', 43),
(261, 'Non/aucun plan d’investissement ', '5', 43),
(262, 'Oui / les investissements consentis  permettent à l’entreprise d’obtenir des résultats dépassant les objectifs de performance fixés', '1 ', 44),
(263, 'Oui/ les investissements consentis  permettent à l’entreprise d’atteindre ses objectifs de performance', '2', 44),
(264, 'Oui / les investissements consentis  permettent à l’entreprise d’obtenir des résultats proches des objectifs de performance prévus.', '3', 44),
(265, 'Non / les investissements consentis ne  permettent pas à l’entreprise d’atteindre ses objectifs de performance', '4', 44),
(266, 'Non / les investissements consentis sont insignifiants ', '5', 44),
(267, 'Oui /arguments et commentaires très pertinents', '1 ', 45),
(268, 'Oui /arguments et commentaires pertinents', '2', 45),
(269, 'Oui /arguments et commentaires peu pertinents', '3', 45),
(270, 'Non/ mais  arguments et commentaires pertinents', '4', 45),
(271, 'Non  arguments et commentaires pas pertinents', '5', 45),
(272, 'Oui/le personnel bénéficie d’une formation adaptée ', '1 ', 46),
(273, 'Oui/ le personnel bénéficie d’une formation adaptée', '2', 46),
(274, 'Moyen  mais acceptable', '3', 46),
(275, 'Moyen et contraignant', '4', 46),
(276, 'Elevé mais acceptable', '5', 46),
(277, 'Oui/ le plan budgétaire est bien établi et tient compte du besoin de maitrise des coûts', '1 ', 47),
(278, 'Oui/ le plan budgétaire tient compte du besoin de maitrise des coûts', '2', 47),
(279, 'Oui/ mais le plan budgétaire est informel ', '3', 47),
(280, 'Non/ l’entreprise ne dispose d’aucun plan budgétaire', '4', 47),
(281, ' Oui /une analyse mensuelle  des écarts  budgétaire est réalisée ', '1', 48),
(282, 'Oui /une analyse  des écarts  budgétaire est réalisée plusieurs fois par ans', '2', 48),
(283, 'Oui/ mais rarement ', '3', 48),
(284, 'Non / n’effectue pas d’analyse des écarts budgétaires', '4', 48),
(285, '   Oui/ la comptabilité analytique est régulière et contribue efficacement à la maitrise des dépenses faites', '1', 49),
(286, 'Oui /la comptabilité analytique est peu régulière mais contribue tout de même à la maitrise des dépenses faites', '2', 49),
(287, 'Non/ l’entreprise ne dispose pas d’une comptabilité analytique mais a mis en place d’autres outils lui permettant de suivre distinctement ses dépenses et recettes', '3', 49),
(288, 'Non / l’entreprise ne dispose d’aucun suivi de la ligne analytique', '4', 49),
(289, 'Oui /arguments et commentaires très pertinents', '1 ', 50),
(290, 'Oui/ arguments et commentaires peu pertinents', '2', 50),
(291, 'Non / mais arguments et commentaires positifs', '3', 50),
(292, 'Non ', '4', 50),
(293, 'Oui / la politique d’approvisionnement participe très bien à la maitrise des coûts d’achat', '1', 51),
(294, 'Oui / la politique d’approvisionnement permet d’effectuer les achats aux meilleurs prix', '2', 51),
(295, 'Non/ mais l’entreprise  essai d’obtenir les meilleurs prix', '3', 51),
(296, 'Non l’entreprise n’a mis en place aucune politique d’approvisionnement efficace', '4', 51),
(297, 'Oui  très cohérent', '1', 52),
(298, 'Oui  cohérant', '2', 52),
(299, 'Oui plutôt cohérent', '3', 52),
(300, 'Oui moyennement cohérent', '4', 52),
(301, 'Non pas toujours cohérent', '5', 52),
(302, 'Non peu cohérent', '6', 52),
(303, 'Non peu ou pas cohérent', '7', 52),
(304, 'Pas du tout cohérent\r\n', '8', 52),
(305, 'Moins de 2%', '1', 53),
(306, 'Entre 2% et 5%', '2', 53),
(307, 'Entre 5% et 10%', '3', 53),
(308, 'Plus de 10%', '4', 53),
(309, 'Oui/ absolument tous les employés', '1', 54),
(310, 'Oui/la majorité', '2', 54),
(311, 'Seulement quelques-uns', '3', 54),
(312, 'Aucun d’entre eux', '4', 54),
(313, 'Oui/ les employés sont tous  couverts au maximum', '1', 55),
(314, 'Oui/ les employés bénéficient uniquement de la prévoyance sociale', '2', 55),
(315, 'Non / juste quelques-uns en bénéficient', '3', 55),
(316, 'Non rien de prévue', '4', 55),
(317, 'Oui/ l’entreprise a implémenté des procédures de résolution de conflits efficaces', '1', 56),
(318, 'Oui/ une politique de résolution des conflits est plutôt effectives', '2', 56),
(319, 'Non/ mais l’entreprise arrive a résoudre certains conflits', '3', 56),
(320, 'Non/ il n’y a aucune procédure de résolution des conflits', '4', 56),
(321, 'Oui/ très satisfait', '1', 57),
(322, 'Oui/ juste moyennement', '2', 57),
(323, 'Non/ mais accepte la situation', '3', 57),
(324, 'Non/ absolument pas', '4', 57),
(325, 'Oui tous les moyens techniques nécessaires sont mis à leur disposition', '1', 58),
(326, 'Oui/ le personnel dispose du minimum pour travailler', '2', 58),
(327, 'Non/ les moyens techniques sont insatisfaisants', '3', 58),
(328, 'Non/ les moyens techniques existants sont complètement inadaptés', '4', 58),
(329, 'Oui / les taches et horaires sont plutôt flexibles', '1', 59),
(330, 'Oui/ les emplois de temps sont chargés mais acceptables', '2', 59),
(331, 'Non/ les taches et horaires sont plutôt surchargés', '3', 59),
(332, 'Non/ le personnel travail dans des conditions insupportables', '4', 59),
(333, 'Oui/ toutes les mesures sont prises pour que l’entreprise assure en permanence ses activités', '1', 60),
(334, 'Oui / juste des mesures temporaires', '2', 60),
(335, 'Non rien de prévu', '3', 60),
(336, 'Oui l’ayant droit dispose des compétences nécessaires', '1', 61),
(337, 'Oui mais l’ayant droit ne dispose pas des compétences nécessaires', '2', 61),
(338, 'Non rien de prévu\r\n', '3', 61),
(339, 'Tous les six mois', '1', 62),
(340, 'Chaque année', '2', 62),
(341, 'Rarement', '3', 62),
(342, 'jamais', '4', 62),
(343, 'Plus de 90%', '1', 63),
(344, 'Entre 70 et 90%', '2', 63),
(345, 'Entre 50% et 70%', '3', 63),
(346, 'Mois de 50%', '4', 63),
(347, 'Croissance plus de 15%  stable', '1', 64),
(348, 'Croissance plus de 15%  instable', '1,3', 64),
(349, 'Croissance entre 5% et 15 % stable', '1,8', 64),
(350, 'Croissance entre 5% et 15 % instable', '2', 64),
(351, 'Croissance entre 0% et 5% stable', '3', 64),
(352, 'Croissance entre 0% et 5% instable', '3,5', 64),
(353, 'Pas de croissance', '4', 64),
(354, 'Pas de croissance', '1', 65),
(355, 'Croissance entre 0% et 5% stable', '1,3', 65),
(356, 'Croissance entre 0% et 5% instable', '1,8', 65),
(357, 'Croissance entre 5% et 10 % stable', '2', 65),
(358, 'Croissance entre 5% et 10 % instable', '3', 65),
(359, 'Croissance plus de 10%  stable', '3,5', 65),
(360, 'Croissance plus de 10%  instable', '4', 65),
(361, 'Oui /   très qualifié', '1', 66),
(362, 'Oui, qualification moyenne', '2', 66),
(363, 'Non mais bénéficie des services d’un prestataire externe', '3', 66),
(364, 'Aucun service comptable ou comptable non qualifié', '4', 66),
(365, 'Oui/ au maximum', '1', 67),
(366, 'Oui/ malgré quelques réserves', '2', 67),
(367, 'Non pas vraiment', '3', 67),
(368, 'Non les états produits  sont totalement imaginaires', '4', 67),
(369, 'Oui/ au maximum', '1', 68),
(370, 'Oui/ malgré quelques réserves', '2', 68),
(371, 'Non pas vraiment', '3', 68),
(372, 'Non les états produits  sont totalement imaginaires', '4', 68),
(373, 'Moins de 20%', '1', 69),
(374, 'Entre 20 et 50%', '2', 69),
(375, 'Entre 50 ET 80%', '3', 69),
(376, 'Plus de 80%', '4', 69),
(377, 'Oui/ au maximum', '1', 70),
(378, 'Oui/ malgré quelques réserves', '2', 70),
(379, 'Non pas vraiment', '3', 70),
(380, 'Absolument pas', '4', 70),
(381, 'Oui/ au maximum', '1', 71),
(382, 'Oui/ moyennement ', '2', 71),
(383, 'Non pas vraiment', '3', 71),
(384, 'Absolument pas', '4', 71);

-- --------------------------------------------------------

--
-- Structure de la table `questions_sous_criteres`
--

CREATE TABLE `questions_sous_criteres` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `critere_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions_sous_criteres`
--

INSERT INTO `questions_sous_criteres` (`id`, `name`, `critere_id`) VALUES
(1, 'A/Positionnement concurrentiel sur le marché', 1),
(2, 'B/Tendance structurelle du marché', 1),
(3, 'C/Marchés cycliques ou à prix volatils', 1),
(4, 'D/Portefeuille produit', 1),
(5, 'E/Pression liée à la clientèle', 1),
(6, 'F/Barrières à l\'entrée ', 1),
(7, 'G/Visibilité sur le secteur', 1),
(8, 'H/Qualité du profil du dirigeant', 2),
(9, 'I/Qualité de l’organisation', 2),
(10, 'J/Qualité du système de contrôle interne', 2),
(11, 'K/Qualité de la Stratégie commerciale et marketing\"', 2),
(12, 'L/Qualité des Moyens humains et techniques\"', 2),
(13, 'M/Adéquation des investissements', 2),
(14, 'N/Maîtrise des coûts de production', 2),
(15, 'O/Cohérence des  options stratégiques', 2),
(16, 'P/Qualité des relations sociales', 2),
(17, 'Q/Succession des Dirigeants\"', 2),
(18, 'R/Fiabilité des prévisions / Evolution produits & charges\"', 4),
(19, 'S/Représentation fidèle réalité comptable et financière/ Transparence des transactions', 4),
(20, 'T/Implication des dirigeants / Surface financière des promoteurs', 4),
(21, 'U/Actifs cessibles', 4);

-- --------------------------------------------------------

--
-- Structure de la table `questions_sous_criteres_`
--

CREATE TABLE `questions_sous_criteres_` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `critere_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abb` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representation_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `regions`
--

INSERT INTO `regions` (`id`, `name`, `abb`, `representation_id`) VALUES
(1, 'Adamaoua', NULL, 1),
(2, 'Centre', NULL, 2),
(3, 'Est', NULL, 2),
(4, 'Extrême-Nord', NULL, 1),
(5, 'Littoral', NULL, 3),
(6, 'Nord', NULL, 1),
(7, 'Nord-Ouest', NULL, 3),
(8, 'Ouest', NULL, 3),
(9, 'Sud', NULL, 2),
(10, 'Sud-Ouest', NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

CREATE TABLE `reponses` (
  `id` int UNSIGNED NOT NULL,
  `dossier_id` int DEFAULT '0',
  `critere_id` int DEFAULT '0',
  `choice_id` int DEFAULT '0',
  `note` int DEFAULT '0',
  `ponderation` double DEFAULT NULL,
  `value` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `dossier_id`, `critere_id`, `choice_id`, `note`, `ponderation`, `value`, `created_at`, `updated_at`) VALUES
(224, 31, 1, 3, 3, 1.5, 0.045, '2026-04-20 11:26:55', '2026-04-20 11:26:55'),
(225, 31, 32, 183, 3, 5, 0.15, '2026-04-20 11:27:06', '2026-04-20 11:27:06'),
(226, 31, 6, 53, 3, 1.5, 0.045, '2026-04-20 11:27:16', '2026-04-20 11:27:16'),
(227, 31, 33, 192, 2, 5, 0.1, '2026-04-20 11:27:29', '2026-04-20 11:27:29'),
(228, 31, 14, 133, 3, 3, 0.09, '2026-04-20 11:29:17', '2026-04-20 11:29:17'),
(229, 31, 2, 17, 7, 1, 0.07, '2026-04-20 11:29:26', '2026-04-20 11:29:26'),
(230, 31, 3, 27, 7, 1, 0.07, '2026-04-20 11:29:37', '2026-04-20 11:29:37'),
(231, 31, 4, 38, 8, 2, 0.16, '2026-04-20 11:29:47', '2026-04-20 11:29:47'),
(232, 31, 5, 47, 7, 2, 0.14, '2026-04-20 11:29:58', '2026-04-20 11:29:58'),
(233, 31, 7, 67, 7, 1, 0.07, '2026-04-20 11:30:07', '2026-04-20 11:30:07'),
(234, 32, 1, 2, 2, 1.5, 0.03, '2026-04-21 14:13:51', '2026-04-21 14:13:51'),
(235, 32, 8, 73, 3, 4, 0.12, '2026-04-21 14:14:00', '2026-04-21 14:14:00'),
(236, 32, 9, 83, 3, 1, 0.03, '2026-04-21 14:14:20', '2026-04-21 14:14:20'),
(237, 32, 12, 112, 2, 2, 0.04, '2026-04-21 14:14:29', '2026-04-21 14:14:29'),
(238, 35, 1, 3, 3, 1.5, 0.045, '2026-04-22 06:06:43', '2026-04-22 06:06:43'),
(239, 35, 9, 83, 3, 1, 0.03, '2026-04-22 06:06:52', '2026-04-22 06:06:52'),
(240, 35, 11, 105, 5, 1, 0.05, '2026-04-22 06:07:03', '2026-04-22 06:07:03'),
(241, 35, 3, 22, 2, 1, 0.02, '2026-04-22 06:07:13', '2026-04-22 06:07:13'),
(242, 35, 32, 186, 6, 5, 0.3, '2026-04-22 06:07:51', '2026-04-22 06:07:51'),
(243, 37, 3, 24, 4, 1, 0.04, '2026-04-24 15:19:55', '2026-04-24 15:19:55'),
(244, 37, 1, 2, 2, 1.5, 0.03, '2026-04-24 15:20:08', '2026-04-24 15:20:08'),
(245, 37, 32, 183, 3, 5, 0.15, '2026-04-24 15:20:53', '2026-04-24 15:20:53'),
(246, 38, 1, 2, 2, 1.5, 0.03, '2026-04-30 13:10:55', '2026-04-30 13:10:55'),
(247, 38, 2, 14, 4, 1, 0.04, '2026-04-30 13:11:10', '2026-04-30 13:11:10'),
(248, 38, 3, 23, 3, 1, 0.03, '2026-04-30 13:11:21', '2026-04-30 13:11:21'),
(249, 38, 32, 184, 4, 5, 0.2, '2026-04-30 13:11:37', '2026-04-30 13:11:37'),
(250, 38, 12, 112, 2, 2, 0.04, '2026-04-30 13:11:50', '2026-04-30 13:11:50'),
(251, 38, 4, 32, 2, 2, 0.04, '2026-04-30 13:12:55', '2026-04-30 13:12:55'),
(252, 38, 6, 53, 3, 1.5, 0.045, '2026-04-30 13:13:07', '2026-04-30 13:13:07'),
(253, 38, 15, 142, 2, 3, 0.06, '2026-04-30 13:13:21', '2026-04-30 13:13:21'),
(254, 38, 34, 201, 1, 10, 0.1, '2026-04-30 13:13:35', '2026-04-30 13:13:35');

-- --------------------------------------------------------

--
-- Structure de la table `representations`
--

CREATE TABLE `representations` (
  `id` int NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `representations`
--

INSERT INTO `representations` (`id`, `name`, `active`) VALUES
(1, 'DIRECTION GRAND NORD', 1),
(2, 'DIRECTION GRAND SUD', 1),
(3, 'DIRECTION GRAND OUEST', 1);

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `type_id` int NOT NULL DEFAULT '0',
  `approche_id` int NOT NULL DEFAULT '0',
  `financier` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `type_id`, `approche_id`, `financier`) VALUES
(1, 'Formation professionnelle certifiante', '• Formation de niveau maîtrise en compétences dans des Centres de Formation Professionnelle Sectoriels (CFPS) agréés avec Centres d\'évaluation, Analyse des niveaux, Tests/examens ; ', 1, 1, 0),
(2, 'Formation aux Métiers dans les filières structurées', 'Formation pratique de base dans des Centres de Formation aux Métiers (CFM) pour des activités bien ciblées (métiers de la boucherie, de la boulangerie, de la construction de piscine, de la charcuterie, etc.) dans des centres spécialisés agréés ;', 1, 1, 0),
(3, 'Service de renforcement des Institutions Financières (IF) et de renforcement des liens avec les TPE/PMEAA', '• Formation coaching des IF • Formation sur les services financiers innovants\r\n• Formation des IF partenaires sur les services adaptés.', 2, 1, 0),
(4, 'Services facilitant l’accès au marché', '• Coaching des processus de contractualisation avec les agro-industries ou la grande distribution ; • Formation des commerciaux ; • Participation aux tests d’innovation ; • Conception des supports de communication (étiquettes, kakemono, dépliants, carte de visite, catalogue, etc.) ; • Appui à l’amélioration des systèmes de conditionnement et de transport.', 2, 1, 0),
(5, 'Service facilitant les projets collaboratifs /partenariat', '• Coaching de projet ; • Soutien aux expériences pilotes ; • Services renforçant les liens avec les OP et entre les OP.', 2, 2, 0),
(6, '\r\nServices d’implémentation de la démarche qualité', '• Formation sur les bonnes pratiques d’hygiène et les bonnes pratiques de production ; • Appui au processus de certification (bio, commerce équitable, global gap, IG etc.) ; • Coaching des projets.', 3, 3, 0),
(7, 'Appui à la diversification et développement', '• Coaching des projets financés.', 3, 3, 0),
(8, 'Services facilitant la création des TPE', '• Formation et accompagnement par les incubateurs', 3, 4, 0),
(9, 'Services facilitant la formalisation des entreprises', '• Accompagnement par les CGA\r\n• Accompagnement par les CAPME et CFCE\r\n', 4, 4, 0),
(10, 'Services d’assistance à la tenue de comptabilité et aux déclarations fiscales', '• Assistance par les CGA\r\n• Assistance par les experts comptables et experts fiscaux\r\n', 4, 4, 0),
(11, 'Services d’instruction des dossiers de financement et de notation financière', '• Assistance par les sociétés de notation financière\r\n• Assistance par les experts financiers et cabinets spécialisés\r\n• Assistance par les sociétés de bourse', 4, 4, 0),
(12, 'Formations collectives pour les dirigeants, les employés et les OP, suivi des activités de coaching', '• Démarche qualité ; • Formations techniques.', 5, 4, 0),
(13, 'Services facilitant l’accès aux crédits', '• Etude de faisabilité/business plan ; • Manuel de procédures de gestion ; • Formation en gestion (relations avec la banque, le fisc, éducation financière etc.) ; • Coaching des stratégies de mobilisation des garanties ; ', 5, 3, 0),
(14, 'Crédits courants', 'Mobilisation de créances\r\nEscompte Effets\r\nAffacturage\r\nAutres\r\nFacilités de caisse\r\nDécouvert\r\nCrédit spot\r\nCrédit amortissable\r\nAutres ', 6, 1, 1),
(15, 'Crédits à moyen et long termes', 'Emprunt à moyen terme\r\nEmprunt à long terme\r\nCrédit-bail\r\nLocation d’équipement', 6, 1, 1),
(16, 'Prêt d’honneur', 'Emprunt sans intérêts (Prêts sans intérêts ni garanties que vous vous engagerez à rembourser \"sur l\'honneur\")', 6, 3, 1),
(17, 'Financement des produits', 'Crédit du négociant\r\nCrédit du fournisseur d’intrants \r\nCrédit de l’entreprise de commercialisation\r\nFinancement des entreprises dominantes', 7, 3, 1),
(18, 'Financement des créances', 'Financement des créances commerciales \r\nFactoring \r\nContrat de vente à forfait', 7, 3, 1),
(19, 'Nantissement des actifs physiques', 'Récépissés d’entreposage \r\nAccords de mise en pension \r\nLeasing financier (crédit-bail)', 7, 3, 1),
(20, 'Produits d’atténuation des risques', 'Assurance \r\nContrats à terme \r\nOpérations à terme', 7, 3, 1),
(21, 'Renforcements financiers', 'Instruments de titrisation\r\nGarantie des prêts \r\nFinancement des entreprises communes', 7, 3, 1),
(22, 'Prise de participation', 'Entrée dans le capital par souscription sous seing privé (Associé ou Actionnaire) \r\nAchat de titres de sociétés déjà émis sur le marché financier ', 8, 3, 1),
(23, 'Capital-risque', 'Entrée dans le capital par souscription sous seing privé (Associé ou Actionnaire) \r\nCouplage capital et compte-courant associé', 8, 3, 1),
(24, 'Obligations', 'Emprunt émis par une entreprise', 8, 3, 1),
(25, 'Subventions d\'investissements', 'Ressources financières versés à l\'entreprise à titre définitif', 8, 3, 1),
(26, 'Crédit inter-entreprises', '• Somme des crédits que les entreprises s\'accordent entre elles afin de se donner des délais de paiement fournisseurs et des délais de paiement clients.', 9, 3, 1),
(27, 'Compensation de biens et services entre entreprises', '• Echange de biens ou de services qui sont payés, en tout ou partie, par échange avec d\'autres biens ou de services, plutôt que de l\'argent.', 9, 3, 1),
(28, 'Cautionnement mutuel', '• Mutualisation des risques pour garantir les prêts de l\'ensemble des adhérents de la SCM-PME sur la base d’un fonds de garantie.', 10, 2, 1),
(29, 'Garantie hypothécaire', '• Garantie réelle (hypothèque, nantissement) qui va porter sur un actif foncier ou sur un bien immobilier. ', 10, 3, 1),
(30, 'Garantie souveraine', '• Contrat par lequel l’État s’engage envers le préteur (le bailleur de fonds) à satisfaire l’obligation de l’emprunteur (débiteur) si celui-ci ne la satisfait pas par lui-même.\r\nGarantie solidaire : Engagement ferme dont la mise en jeu est automatique.\r\nGarantie simple : Engagement ferme dont la mise en jeu n’est pas automatique.', 10, 3, 1),
(31, 'Garantie assurance', '• Garantie destinée aux entreprises de toutes tailles (TPE, PME et grands comptes) pour garantir les impayés.', 10, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '1',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sme_notes`
--

CREATE TABLE `sme_notes` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `mention` varchar(255) DEFAULT NULL,
  `note` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sous_criteres`
--

CREATE TABLE `sous_criteres` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `critere_id` int DEFAULT NULL,
  `sequence` int DEFAULT NULL,
  `default` double DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sous_criteres`
--

INSERT INTO `sous_criteres` (`id`, `name`, `critere_id`, `sequence`, `default`, `created_at`, `updated_at`) VALUES
(1, 'positionnement concurentiel sur le marché', 1, 1, 1.5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(2, 'Tendance structurelle du marché	', 1, 2, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(3, 'Marchés cycliques ou à prix volatils', 1, 3, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(4, 'Portefeuille produit', 1, 4, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(5, 'Pression liée à la clientèle', 1, 5, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(6, 'Barrière à l\'entrée', 1, 6, 1.5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(7, 'Visibilité du secteur', 1, 7, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(8, 'Qualité du profil du drigeant', 2, 8, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(9, 'Qualité de l\'organisation', 2, 9, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(10, 'Qualité du système de contrôle interne', 2, 10, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(11, 'Qualité de la Stratégie commerciale et marketing', 2, 11, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(12, 'Qualité des Moyens humains et techniques', 2, 12, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(13, 'Adéquation des investissements / Impact sur l\'emploi', 2, 13, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(14, 'Maîtrise des coûts de productivité / Impact sur la création des richesses', 2, 14, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(15, 'Cohérence des options stratégiques / Impact sur l\'environnement	', 2, 15, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(16, 'Qualité des relations sociales', 2, 16, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(17, 'Succession des Dirigeants', 2, 17, 1, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(18, 'Dynamique de l\'Equilibre Financier (FR/BFR)', 3, 18, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(19, 'Ratio d\'endettement global ((Actifs - Capitaux propres)/ Actifs) <1', 3, 19, 3, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(20, 'Capacité de  Remboursement (DLMT/CAFG)', 3, 20, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(21, 'Couverture des frais financiers (FTAO/FF)', 3, 21, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(22, 'Solvabilité globale  Risque liquidatif (Ressources Propres /Total Bilan)', 3, 22, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(23, 'Liquidité générale (Ratio de FR= Actifs circulants/ Passifs circulants)>1', 3, 23, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(24, 'Rentabilité d\'exploitation par rapport au CA (EBE/ CA)>30%', 3, 24, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(25, 'Rentabilité économique (REX/ACTIF)>10%', 3, 25, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(26, 'Rentabilité Financière (RN/Capitaux propres)>10%', 3, 26, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(27, 'Capacité d\'endettement (Ressources Propres/Dettes structurelles)>1', 3, 27, 4, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(28, 'Délai Client  ((créances client /CA)*365)', 3, 28, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(29, 'Délai Fournisseur ((dettes fournisseurs /Achats à crédit)*365)', 3, 29, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(30, 'Délai d\'Ecoulement des stocks ((stock moyen/Coûts des produits vendus)*365 )', 3, 30, 2, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(31, 'Fiabilité des prévisions / Evolution des produits & charges', 4, 31, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(32, 'Représentation fidèle réalité  comptable et financière/ Transparence des transactions', 4, 32, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(33, 'Implication du dirigeant / Surface financière des promoteurs', 4, 33, 5, '2024-12-05 12:31:05', '2024-12-05 12:31:05'),
(34, 'Qualité des garanties & Actifs cessibles', 4, 34, 10, '2024-12-05 12:31:05', '2024-12-05 12:31:05');

-- --------------------------------------------------------

--
-- Structure de la table `tailles`
--

CREATE TABLE `tailles` (
  `id` int NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tailles`
--

INSERT INTO `tailles` (`id`, `name`) VALUES
(1, 'Grande'),
(2, 'Moyenne'),
(3, 'Petite'),
(4, 'Très Petite'),
(5, 'Coopérative'),
(6, 'Association');

-- --------------------------------------------------------

--
-- Structure de la table `tiers`
--

CREATE TABLE `tiers` (
  `id` int NOT NULL,
  `person_id` int NOT NULL DEFAULT '0',
  `lien` varchar(50) DEFAULT NULL,
  `commentaire` text,
  `company_id` int NOT NULL DEFAULT '0',
  `banque_id` int NOT NULL DEFAULT '0',
  `entreprise_id` int NOT NULL DEFAULT '0',
  `client` tinyint(1) NOT NULL DEFAULT '0',
  `fournisseur` tinyint(1) NOT NULL DEFAULT '0',
  `bank` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tiers`
--

INSERT INTO `tiers` (`id`, `person_id`, `lien`, `commentaire`, `company_id`, `banque_id`, `entreprise_id`, `client`, `fournisseur`, `bank`) VALUES
(21, 14, 'Associatif', NULL, 0, 0, 261, 0, 0, 0),
(22, 0, 'Partenaire', NULL, 262, 0, 261, 0, 0, 0),
(23, 0, 'Client', NULL, 261, 0, 263, 0, 0, 0),
(24, 15, 'Amical', NULL, 0, 0, 265, 0, 0, 0),
(25, 0, 'Client', NULL, 262, 0, 265, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `Torganismes`
--

CREATE TABLE `Torganismes` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Torganismes`
--

INSERT INTO `Torganismes` (`id`, `name`) VALUES
(1, 'Banques multilatérales de développement'),
(2, 'Organisations de développement multilatérales'),
(3, 'Agences de développement bilatérales'),
(4, 'Institutions de développement du secteur privé'),
(5, 'Banques de développement régionales'),
(6, 'Fonds d’investissement privées');

-- --------------------------------------------------------

--
-- Structure de la table `tservices`
--

CREATE TABLE `tservices` (
  `id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `financier` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tservices`
--

INSERT INTO `tservices` (`id`, `name`, `financier`) VALUES
(1, 'Formation Professionnelle : technique et Métier', 0),
(2, 'Formation Commerciale et Managériale', 0),
(3, 'Appuis étatiques', 0),
(4, 'Services Spécialisés', 0),
(5, 'Services aux Entreprises', 0),
(6, 'Emprunts bancaires', 1),
(7, 'Financement des Entreprises agricoles', 1),
(8, 'Investissements directs', 1),
(9, 'Compensation de biens et services', 1),
(10, 'Garanties', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `photo_uri` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agence_id` int NOT NULL DEFAULT '0',
  `organisation_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organisation_entite_id` bigint UNSIGNED DEFAULT NULL,
  `representation_id` int NOT NULL DEFAULT '0',
  `programme_id` int NOT NULL DEFAULT '0',
  `cooperative_id` int NOT NULL DEFAULT '0',
  `secteur_id` int NOT NULL DEFAULT '0',
  `banque_id` int NOT NULL DEFAULT '0',
  `poste_id` int NOT NULL DEFAULT '0',
  `departement_id` int NOT NULL DEFAULT '0',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entrepot_id` int NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` longtext COLLATE utf8mb4_unicode_ci,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `role_id`, `photo_uri`, `agence_id`, `organisation_type`, `organisation_entite_id`, `representation_id`, `programme_id`, `cooperative_id`, `secteur_id`, `banque_id`, `poste_id`, `departement_id`, `phone`, `entrepot_id`, `email`, `email_verified_at`, `avatar`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `token`, `permissions`, `settings`, `created_at`, `updated_at`, `active`, `remember_token`) VALUES
(1, 'Haisenheim', 1, 'profil/gfhgjdhhjdshgftehfgh.jpeg', 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 'clementessomba@gmail.com', NULL, 'users/default.png', '$2y$12$pRF.vQQiiwU4FxoEAOY3POSu6smhLgG/D5ar1sED5PTmH/OOQcSVy', NULL, NULL, NULL, 'gfhgjdhhjdshgftehfgh', NULL, NULL, '2024-08-27 09:36:45', '2025-01-06 12:16:53', 1, NULL),
(2, 'NGOAH Armelle', 16, 'profil/b43e4118bc4a23701e5f084c6abab1c458fdfdcd.png', 1, 'agence', NULL, 1, 0, 0, 0, 0, 0, 0, '6633532466', 0, 'a.ngoah43@angara.com', NULL, 'users/default.png', '$2y$12$pRF.vQQiiwU4FxoEAOY3POSu6smhLgG/D5ar1sED5PTmH/OOQcSVy', NULL, NULL, NULL, 'b43e4118bc4a23701e5f084c6abab1c458fdfdcd', NULL, NULL, '2024-12-12 16:10:45', '2026-04-23 15:29:18', 1, NULL),
(3, 'ELOUNDOU Francis', 16, NULL, 1, 'agence', NULL, 1, 0, 0, 0, 0, 0, 0, '6799649451', 0, 'f.eloundou73@angara.com', NULL, 'users/default.png', '$2y$12$pRF.vQQiiwU4FxoEAOY3POSu6smhLgG/D5ar1sED5PTmH/OOQcSVy', NULL, NULL, NULL, 'ad7d9a00f7ab0deef9795b06157e02bdc3276357', NULL, NULL, '2025-01-05 15:03:56', '2026-04-23 07:12:17', 1, NULL),
(4, 'Raphael Alima', 10, NULL, 0, 'entite', 2, 0, 0, 0, 0, 0, 0, 0, '64532456632', 0, 'r.alima@angara.com', NULL, 'users/default.png', '$2y$12$4soFsk0lGK4fj1NaX2KCxuZnTV9QCr9m9DYAGiDkhxCQFU3b3hcw6', NULL, NULL, NULL, '70db8b4bbc503dd00925a00e69b1383dfea621ab', NULL, NULL, '2026-04-18 11:51:17', '2026-04-23 07:12:17', 1, NULL),
(5, 'NOLA ARMAND', 11, NULL, 0, 'entite', 1, 0, 0, 0, 0, 0, 0, 0, '67238989', 0, 'a.nola@angara.com', NULL, 'users/default.png', '$2y$12$sPerVup59HlhSLOZ01/gVuF6rjchGbBeeCHH.3Ma0M/qdtmCfkaLq', NULL, NULL, NULL, '0870ff28225db1db9ce586480cdcf7ae0221291b', NULL, NULL, '2026-04-19 08:01:44', '2026-04-23 07:12:17', 1, NULL),
(6, 'FOTSO SOP HENRY', 24, NULL, 1, 'agence', NULL, 0, 0, 0, 0, 0, 0, 0, '6967872823', 0, 'sh.fotso@angara.com', NULL, 'users/default.png', '$2y$12$WIHC8mgFCiIueLiX3cbl.O51rqDeqyMV0H4O6xQg25qyku44mR.lK', NULL, NULL, NULL, '56b13989d41536217533f2d31165edd0209cda1a', NULL, NULL, '2026-04-19 08:04:08', '2026-04-23 07:12:17', 1, NULL),
(7, 'Hamad Youssef', 15, NULL, 1, 'agence', NULL, 0, 0, 0, 0, 0, 0, 0, '6583934344', 0, 'h.youssef@angara.com', NULL, 'users/default.png', '$2y$12$hdiUIpoUY1SeTip.UW4k5.dtp/65mcTuYrFT2ml.bENlpCdzL3mS6', NULL, NULL, NULL, '91025fe724eebc0f818ba7134893d0855fc89672', NULL, NULL, '2026-04-19 14:05:47', '2026-04-23 07:12:17', 1, NULL),
(8, 'SOMO PIERRE', 6, NULL, 0, 'entite', 1, 0, 0, 0, 0, 0, 0, 0, '67545432453', 0, 'p.somo@angara.com', NULL, 'users/default.png', '$2y$12$zQ05N7tywhSSvzXz9ZOCve11yNHua8.Bs0YteUPX3XU30iyjRwxb.', NULL, NULL, NULL, '66e9ac0a04930af7b0e01414b751c830d6d42462', NULL, NULL, '2026-04-20 03:21:13', '2026-04-23 07:12:17', 1, NULL),
(9, 'SAMA ARSENE', 17, 'profil/4edf47f688c4c3f0c3e086c9b9f0b480cea46fd7.png', 0, 'entite', 1, 0, 0, 0, 0, 0, 0, 0, '696536787', 0, 'a.sama@angara.com', NULL, 'users/default.png', '$2y$12$CkDlczBHBUoSYNntYyPxQuSTCsc6Jn5gv9F7YjLI7Sd5bY1YZ.DHC', NULL, NULL, NULL, '4edf47f688c4c3f0c3e086c9b9f0b480cea46fd7', NULL, NULL, '2026-04-20 03:22:30', '2026-04-23 15:01:21', 1, NULL),
(10, 'TAMO PIERRE', 9, NULL, 0, 'entite', 3, 0, 0, 0, 0, 0, 0, 0, '6873293233', 0, 'p.tamo@angara.com', NULL, 'users/default.png', '$2y$12$3lLVRvu/7hgwzRPGpYU/7e.wkB9TxpxgSIY6gb.ZNUxQlAk7ArknO', NULL, NULL, NULL, '491dff9798dbbf1f88df747ff902bb7d5ede2143', NULL, NULL, '2026-04-20 22:40:43', '2026-04-23 07:12:17', 1, NULL),
(11, 'PASSIO SAMY', 12, NULL, 0, 'entite', 4, 0, 0, 0, 0, 0, 0, 0, '67453277323', 0, 's.passio@angara.com', NULL, 'users/default.png', '$2y$12$Zx.7hotV3sKPVwKtm7pQ4.kg3UE9rqcFNvaIMkXVNyFKyM2EGJAYe', NULL, NULL, NULL, 'd172789eea31621a8e3e8f005a6051cd4d5fc3fc', NULL, NULL, '2026-04-20 22:42:21', '2026-04-23 07:12:17', 1, NULL),
(12, 'EYENGA MARIE', 18, NULL, 0, 'entite', 4, 0, 0, 0, 0, 0, 0, 0, '656526354', 0, 'm.eyenga@angara.com', NULL, 'users/default.png', '$2y$12$2YGDqkzfnIaifk6QOPfZVe3P2FDHvw/HFzZ3caKxCutHtdRgUmwKq', NULL, NULL, NULL, 'c8389a23725ef47e210163e5ea99fa8fc21f1a5d', NULL, NULL, '2026-04-20 22:44:27', '2026-04-23 07:12:17', 1, NULL),
(13, 'ABOA SALOME', 20, NULL, 0, 'entite', 3, 0, 0, 0, 0, 0, 0, 0, '6965763273', 0, 's.aboa@angara.com', NULL, 'users/default.png', '$2y$12$YtFKgEzRxrgKi7TPePVBM.TA5OuDVjozxTUbF2YvNlm6B/uyifmiy', NULL, NULL, NULL, '3db472043567bfeb882080c0d538c25fdfdb3c1a', NULL, NULL, '2026-04-20 22:45:43', '2026-04-23 07:12:17', 1, NULL),
(14, 'YBE MARIE DAVILA', 19, NULL, 0, 'entite', 2, 0, 0, 0, 0, 0, 0, 0, '6823798293', 0, 'md.ybe@angara.com', NULL, 'users/default.png', '$2y$12$BR3NlkbAiwDP4sShxaAcMuCNncJELMZ92KIOf679gsZc0gZ4N8vjS', NULL, NULL, NULL, 'd132f969049cb422d0c27efa8bf2795e3b97397e', NULL, NULL, '2026-04-20 22:47:12', '2026-04-23 07:12:17', 1, NULL),
(15, 'Hamad Amadou', 4, NULL, 0, 'entite', 5, 0, 0, 0, 0, 0, 0, 0, '6756378273', 0, 'h.amadou@angara.com', NULL, 'users/default.png', '$2y$12$RN.LKppXTGJ/Dz8BmphXo.up4uK38dCxgj/lEPKnhdmsaU2TDUczu', NULL, NULL, NULL, '4f7df5114d014a7415e076dc659bbcf82c6c05a6', NULL, NULL, '2026-04-21 14:42:57', '2026-04-23 07:12:17', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `villages`
--

CREATE TABLE `villages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `departement_id` int NOT NULL DEFAULT '0',
  `arrondissement_id` int NOT NULL DEFAULT '0',
  `latitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int NOT NULL DEFAULT '0',
  `semaine` int NOT NULL DEFAULT '0',
  `moi_id` int NOT NULL DEFAULT '0',
  `annee` int NOT NULL DEFAULT '0',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `villages`
--

INSERT INTO `villages` (`id`, `name`, `zone_id`, `created_at`, `updated_at`, `departement_id`, `arrondissement_id`, `latitude`, `longitude`, `region_id`, `semaine`, `moi_id`, `annee`, `token`, `photo`) VALUES
(1, 'POBO', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(2, 'MINWOHO SUD', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(3, 'NKOLAKOK', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(4, 'ELIG-ZOGO', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(5, 'ETOK', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(6, 'NKOL-ABANG', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(7, 'MEYOS', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(8, 'NKOLSENG I', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(9, 'NKOL-OHANDJA', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(10, 'NKOLSENG II', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(11, 'NKOLMEYOS I', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(12, 'NTOUDA SUD', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(13, 'KOMO ENDO', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(14, 'NTOUDA CENTRE', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(15, 'NGUESSE', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(16, 'MGBABANG II', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(17, 'MGBABANG I', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(18, 'MGBABANG III', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(19, 'EVODOULA VILLAGE', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(20, 'EVODOULA CENTRE', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(21, 'MBEL BIKOL', 0, NULL, NULL, 11, 45, NULL, NULL, 2, 0, 0, 0, NULL, NULL),
(22, 'Essassa', 0, '2026-02-11 22:57:48', '2026-02-11 22:57:48', 14, 77, '72393892', '89328032', 2, 0, 0, 0, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agences`
--
ALTER TABLE `agences`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `analyse_critique_avis`
--
ALTER TABLE `analyse_critique_avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_dac_sort_idx` (`dossier_analyse_critique_id`,`sort_order`),
  ADD KEY `aca_instruction_idx` (`instruction_dossier_id`),
  ADD KEY `aca_user_fk` (`emis_par_user_id`);

--
-- Index pour la table `approches`
--
ALTER TABLE `approches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `arrondissements`
--
ALTER TABLE `arrondissements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `banques`
--
ALTER TABLE `banques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `composantes`
--
ALTER TABLE `composantes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `criteres`
--
ALTER TABLE `criteres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `critere_programme_ponderations`
--
ALTER TABLE `critere_programme_ponderations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delegation_pouvoirs`
--
ALTER TABLE `delegation_pouvoirs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delegation_pouvoirs_seuil_engagements_max_index` (`seuil_engagements_max`);

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dossier_analyse_critiques`
--
ALTER TABLE `dossier_analyse_critiques`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_analyse_critiques_entreprise_id_unique` (`entreprise_id`),
  ADD UNIQUE KEY `dossier_analyse_critiques_token_unique` (`token`);

--
-- Index pour la table `dossier_entree_relations`
--
ALTER TABLE `dossier_entree_relations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_entree_relations_entreprise_id_unique` (`entreprise_id`),
  ADD UNIQUE KEY `dossier_entree_relations_token_unique` (`token`),
  ADD KEY `eer_submitted_user_fk` (`submitted_by_user_id`),
  ADD KEY `eer_qualification_user_fk` (`qualification_user_id`),
  ADD KEY `eer_programmes_user_fk` (`programmes_submitted_by_user_id`),
  ADD KEY `eer_instruction_user_fk` (`instruction_validated_by_user_id`),
  ADD KEY `eer_qualif_agence_user_fk` (`qualification_validated_by_agence_user_id`),
  ADD KEY `eer_instruction_bundle_dossier_fk` (`instruction_bundle_dossier_id`),
  ADD KEY `eer_qualif_reject_agence_user_fk` (`qualification_rejected_by_agence_user_id`);

--
-- Index pour la table `dossier_entree_relation_programmes`
--
ALTER TABLE `dossier_entree_relation_programmes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eer_programme_unique` (`dossier_entree_relation_id`,`programme_id`),
  ADD KEY `eer_prog_programme_fk` (`programme_id`),
  ADD KEY `eer_prog_dossier_fk` (`instruction_dossier_id`);

--
-- Index pour la table `dossier_instruction_programmes`
--
ALTER TABLE `dossier_instruction_programmes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dip_dossier_programme_unique` (`dossier_id`,`programme_id`),
  ADD KEY `dip_programme_fk` (`programme_id`);

--
-- Index pour la table `elements_constitutifs_types`
--
ALTER TABLE `elements_constitutifs_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `engagements`
--
ALTER TABLE `engagements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `engagement_entreprises`
--
ALTER TABLE `engagement_entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprises_elements_constitutifs`
--
ALTER TABLE `entreprises_elements_constitutifs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise_appuis`
--
ALTER TABLE `entreprise_appuis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise_critere_avis`
--
ALTER TABLE `entreprise_critere_avis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_entreprise_critere_avis` (`entreprise_id`,`critere_id`),
  ADD KEY `entreprise_critere_avis_entreprise_id_index` (`entreprise_id`),
  ADD KEY `entreprise_critere_avis_critere_id_index` (`critere_id`),
  ADD KEY `entreprise_critere_avis_user_id_index` (`user_id`),
  ADD KEY `entreprise_critere_avis_saved_at_index` (`saved_at`);

--
-- Index pour la table `entreprise_equipe_membres`
--
ALTER TABLE `entreprise_equipe_membres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_equipe_membres_entreprise_id_index` (`entreprise_id`),
  ADD KEY `entreprise_equipe_membres_entreprise_site_id_index` (`entreprise_site_id`),
  ADD KEY `entreprise_equipe_membres_created_by_user_id_index` (`created_by_user_id`),
  ADD KEY `entreprise_equipe_membres_updated_by_user_id_index` (`updated_by_user_id`),
  ADD KEY `entreprise_equipe_membres_cni_fichier_id_index` (`cni_fichier_id`);

--
-- Index pour la table `entreprise_piece_exigibles`
--
ALTER TABLE `entreprise_piece_exigibles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entreprise_piece_unique` (`entreprise_id`,`piece_exigible_definition_id`),
  ADD KEY `entreprise_piece_exigibles_entreprise_id_index` (`entreprise_id`),
  ADD KEY `ent_piece_definition_fk` (`piece_exigible_definition_id`),
  ADD KEY `ent_piece_fichier_fk` (`fichier_id`),
  ADD KEY `ent_piece_user_fk` (`provided_by_user_id`);

--
-- Index pour la table `entreprise_produits`
--
ALTER TABLE `entreprise_produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise_sites`
--
ALTER TABLE `entreprise_sites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise_types`
--
ALTER TABLE `entreprise_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `fichiers`
--
ALTER TABLE `fichiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fichiers_dossier_id_index` (`dossier_id`);

--
-- Index pour la table `fichiers_types`
--
ALTER TABLE `fichiers_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fichiers_types_active_index` (`active`);

--
-- Index pour la table `filieres`
--
ALTER TABLE `filieres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formes_juridiques`
--
ALTER TABLE `formes_juridiques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `indicateurs`
--
ALTER TABLE `indicateurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `indicateurs_financiers`
--
ALTER TABLE `indicateurs_financiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `liens`
--
ALTER TABLE `liens`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `localites`
--
ALTER TABLE `localites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `organisation_entites`
--
ALTER TABLE `organisation_entites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organisation_entites_key_unique` (`key`);

--
-- Index pour la table `organismes`
--
ALTER TABLE `organismes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `piece_exigible_definitions`
--
ALTER TABLE `piece_exigible_definitions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `profils`
--
ALTER TABLE `profils`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `programme_appuis`
--
ALTER TABLE `programme_appuis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `programme_indicateurs`
--
ALTER TABLE `programme_indicateurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `programme_organismes`
--
ALTER TABLE `programme_organismes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `programme_produits`
--
ALTER TABLE `programme_produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quartiers`
--
ALTER TABLE `quartiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `questions_answers`
--
ALTER TABLE `questions_answers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `questions_choices`
--
ALTER TABLE `questions_choices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `questions_sous_criteres`
--
ALTER TABLE `questions_sous_criteres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `questions_sous_criteres_`
--
ALTER TABLE `questions_sous_criteres_`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `representations`
--
ALTER TABLE `representations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Index pour la table `sme_notes`
--
ALTER TABLE `sme_notes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sous_criteres`
--
ALTER TABLE `sous_criteres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tailles`
--
ALTER TABLE `tailles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tiers`
--
ALTER TABLE `tiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Torganismes`
--
ALTER TABLE `Torganismes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tservices`
--
ALTER TABLE `tservices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Index pour la table `villages`
--
ALTER TABLE `villages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agences`
--
ALTER TABLE `agences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `analyse_critique_avis`
--
ALTER TABLE `analyse_critique_avis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `approches`
--
ALTER TABLE `approches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `arrondissements`
--
ALTER TABLE `arrondissements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT pour la table `banques`
--
ALTER TABLE `banques`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT pour la table `composantes`
--
ALTER TABLE `composantes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `criteres`
--
ALTER TABLE `criteres`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `critere_programme_ponderations`
--
ALTER TABLE `critere_programme_ponderations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delegation_pouvoirs`
--
ALTER TABLE `delegation_pouvoirs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `dossier_analyse_critiques`
--
ALTER TABLE `dossier_analyse_critiques`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `dossier_entree_relations`
--
ALTER TABLE `dossier_entree_relations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `dossier_entree_relation_programmes`
--
ALTER TABLE `dossier_entree_relation_programmes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `dossier_instruction_programmes`
--
ALTER TABLE `dossier_instruction_programmes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `elements_constitutifs_types`
--
ALTER TABLE `elements_constitutifs_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `engagements`
--
ALTER TABLE `engagements`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `engagement_entreprises`
--
ALTER TABLE `engagement_entreprises`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT pour la table `entreprises_elements_constitutifs`
--
ALTER TABLE `entreprises_elements_constitutifs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `entreprise_appuis`
--
ALTER TABLE `entreprise_appuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT pour la table `entreprise_critere_avis`
--
ALTER TABLE `entreprise_critere_avis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `entreprise_equipe_membres`
--
ALTER TABLE `entreprise_equipe_membres`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entreprise_piece_exigibles`
--
ALTER TABLE `entreprise_piece_exigibles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `entreprise_produits`
--
ALTER TABLE `entreprise_produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT pour la table `entreprise_sites`
--
ALTER TABLE `entreprise_sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `entreprise_types`
--
ALTER TABLE `entreprise_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `fichiers_types`
--
ALTER TABLE `fichiers_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `filieres`
--
ALTER TABLE `filieres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT pour la table `formes_juridiques`
--
ALTER TABLE `formes_juridiques`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `indicateurs`
--
ALTER TABLE `indicateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `indicateurs_financiers`
--
ALTER TABLE `indicateurs_financiers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `liens`
--
ALTER TABLE `liens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `localites`
--
ALTER TABLE `localites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pour la table `niveaux`
--
ALTER TABLE `niveaux`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `organisation_entites`
--
ALTER TABLE `organisation_entites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `organismes`
--
ALTER TABLE `organismes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `piece_exigible_definitions`
--
ALTER TABLE `piece_exigible_definitions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- AUTO_INCREMENT pour la table `profils`
--
ALTER TABLE `profils`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `programme_appuis`
--
ALTER TABLE `programme_appuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `programme_indicateurs`
--
ALTER TABLE `programme_indicateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `programme_organismes`
--
ALTER TABLE `programme_organismes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `programme_produits`
--
ALTER TABLE `programme_produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `quartiers`
--
ALTER TABLE `quartiers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `questions_answers`
--
ALTER TABLE `questions_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=745;

--
-- AUTO_INCREMENT pour la table `questions_choices`
--
ALTER TABLE `questions_choices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT pour la table `questions_sous_criteres`
--
ALTER TABLE `questions_sous_criteres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `questions_sous_criteres_`
--
ALTER TABLE `questions_sous_criteres_`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT pour la table `representations`
--
ALTER TABLE `representations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sme_notes`
--
ALTER TABLE `sme_notes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `sous_criteres`
--
ALTER TABLE `sous_criteres`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `tailles`
--
ALTER TABLE `tailles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tiers`
--
ALTER TABLE `tiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `Torganismes`
--
ALTER TABLE `Torganismes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tservices`
--
ALTER TABLE `tservices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `villages`
--
ALTER TABLE `villages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `analyse_critique_avis`
--
ALTER TABLE `analyse_critique_avis`
  ADD CONSTRAINT `aca_dac_fk` FOREIGN KEY (`dossier_analyse_critique_id`) REFERENCES `dossier_analyse_critiques` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_dossier_fk` FOREIGN KEY (`instruction_dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `aca_user_fk` FOREIGN KEY (`emis_par_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dossier_analyse_critiques`
--
ALTER TABLE `dossier_analyse_critiques`
  ADD CONSTRAINT `dac_entreprise_fk` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossier_entree_relations`
--
ALTER TABLE `dossier_entree_relations`
  ADD CONSTRAINT `eer_entreprise_fk` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eer_instruction_bundle_dossier_fk` FOREIGN KEY (`instruction_bundle_dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_instruction_user_fk` FOREIGN KEY (`instruction_validated_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_programmes_user_fk` FOREIGN KEY (`programmes_submitted_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_qualif_agence_user_fk` FOREIGN KEY (`qualification_validated_by_agence_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_qualif_reject_agence_user_fk` FOREIGN KEY (`qualification_rejected_by_agence_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_qualification_user_fk` FOREIGN KEY (`qualification_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_submitted_user_fk` FOREIGN KEY (`submitted_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dossier_entree_relation_programmes`
--
ALTER TABLE `dossier_entree_relation_programmes`
  ADD CONSTRAINT `eer_prog_dossier_fk` FOREIGN KEY (`instruction_dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eer_prog_eer_fk` FOREIGN KEY (`dossier_entree_relation_id`) REFERENCES `dossier_entree_relations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eer_prog_programme_fk` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `dossier_instruction_programmes`
--
ALTER TABLE `dossier_instruction_programmes`
  ADD CONSTRAINT `dip_dossier_fk` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dip_programme_fk` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `entreprise_critere_avis`
--
ALTER TABLE `entreprise_critere_avis`
  ADD CONSTRAINT `entreprise_critere_avis_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `entreprise_equipe_membres`
--
ALTER TABLE `entreprise_equipe_membres`
  ADD CONSTRAINT `entreprise_equipe_membres_entreprise_id_foreign` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entreprise_equipe_membres_entreprise_site_id_foreign` FOREIGN KEY (`entreprise_site_id`) REFERENCES `entreprise_sites` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `entreprise_piece_exigibles`
--
ALTER TABLE `entreprise_piece_exigibles`
  ADD CONSTRAINT `ent_piece_definition_fk` FOREIGN KEY (`piece_exigible_definition_id`) REFERENCES `piece_exigible_definitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ent_piece_entreprise_fk` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ent_piece_fichier_fk` FOREIGN KEY (`fichier_id`) REFERENCES `fichiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ent_piece_user_fk` FOREIGN KEY (`provided_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
