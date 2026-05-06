-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 01 avr. 2026 à 09:35
-- Version du serveur : 8.0.45-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `angara_demo_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `adonis_schema`
--

CREATE TABLE `adonis_schema` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  `migration_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adonis_schema`
--

INSERT INTO `adonis_schema` (`id`, `name`, `batch`, `migration_time`) VALUES
(40, 'database/migrations/1731147337124_indicateurs', 1, '2024-12-05 14:31:04'),
(41, 'database/migrations/1731850628126_create_criteres_table', 1, '2024-12-05 14:31:04'),
(42, 'database/migrations/1731850852957_create_choices_table', 1, '2024-12-05 14:31:04'),
(43, 'database/migrations/1731854436868_create_sous_criteres_table', 1, '2024-12-05 14:31:04'),
(44, 'database/migrations/1731921936824_create_critere_programme_ponderations_table', 1, '2024-12-05 14:31:04'),
(45, 'database/migrations/1732040163795_create_reponses_table', 1, '2024-12-05 14:31:04'),
(46, 'database/migrations/1732133858080_create_sme_notes_table', 1, '2024-12-05 14:31:04'),
(47, 'database/migrations/1733047757380_create_engagements_table', 1, '2024-12-05 14:31:04'),
(48, 'database/migrations/1733170479783_create_engagement_entreprise_banque_table', 1, '2024-12-05 14:31:04'),
(49, 'database/migrations/1733240698277_create_banques_table', 1, '2024-12-05 14:31:04'),
(50, 'database/migrations/1733240894866_create_entreprises_table', 1, '2024-12-05 14:31:04'),
(51, 'database/migrations/1733241532844_create_programmes_table', 1, '2024-12-05 14:31:04'),
(52, 'database/migrations/1733245326702_create_dossiers_table', 1, '2024-12-05 14:31:04'),
(53, 'database/migrations/1733405881781_create_recommandations_table', 1, '2024-12-05 14:31:04');

-- --------------------------------------------------------

--
-- Structure de la table `adonis_schema_versions`
--

CREATE TABLE `adonis_schema_versions` (
  `version` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adonis_schema_versions`
--

INSERT INTO `adonis_schema_versions` (`version`) VALUES
(2);

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `choices`
--

INSERT INTO `choices` (`id`, `valeur`, `note`, `critere_id`, `created_at`, `updated_at`) VALUES
(1, 'Très significatif', 1, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(2, 'Significatif', 2, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(3, 'Bon', 3, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(4, 'Plutôt Bon', 4, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(5, 'Moyen', 5, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(6, 'Plutôt Moyen', 6, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(7, 'Faible', 7, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(8, 'Limité', 8, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(9, 'Mauvais', 9, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(10, 'Très Mauvais', 10, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(11, 'très bonne A', 1, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(12, 'très bonne B', 2, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(13, 'bonne', 3, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(14, 'moyenne', 4, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(15, 'faible', 5, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(16, 'plutôt en stagnation', 6, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(17, 'plutôt en baisse', 7, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(18, 'en baisse sensible', 8, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(19, 'en forte baisse', 9, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(20, 'secteur en difficulté ou en déclin', 10, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(21, 'non', 1, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(22, ' non ou très peu ', 2, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(23, 'peu', 3, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(24, 'légèrement', 4, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(25, 'modérement B', 5, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(26, 'modérement A', 6, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(27, 'fortement B', 7, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(28, 'fortement A', 8, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(29, 'très fortement B', 9, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(30, 'très fortement A', 10, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(31, 'extremement bien positionné', 1, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(32, 'très bien positionné', 2, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(33, 'plutot bien positionné', 3, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(34, 'assez bien positionné', 4, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(35, 'moyennement positionné A', 5, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(36, 'moyennement positionné B', 6, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(37, 'plutôt mal positionné et/ou étroit', 7, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(38, 'mal positionné et/ou  très étroit', 8, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(39, 'renouvellement non assuré', 9, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(40, 'obsolescence marquée', 10, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(41, 'très modérée', 1, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(42, 'modérée', 2, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(43, 'plutôt faible', 3, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(44, 'plutot sensible', 4, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(45, 'assez sensible', 5, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(46, 'sensible', 6, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(47, 'assez forte', 7, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(48, 'forte', 8, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(49, 'très forte', 9, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(50, 'extremement forte', 10, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(51, 'très élevéés A', 1, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(52, 'très élevéés B', 2, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(53, 'élevées', 3, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(54, 'assez élevées', 4, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(55, ' plutôt faibles', 5, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(56, ' faibles ', 6, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(57, 'très faibles A', 7, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(58, 'très faibles B', 8, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(59, 'inexistentes A', 9, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(60, 'inexistentes B', 10, 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(61, 'très bonne A', 1, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(62, ' très bonne B', 2, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(63, 'bonne A', 3, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(64, 'bonne B', 4, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(65, 'moyenne à moyen terme', 5, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(66, 'limitée à moyen terme', 6, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(67, 'plutot faible', 7, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(68, 'faible', 8, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(69, 'très faible', 9, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(70, 'extrement faible', 10, 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(71, 'excellente', 1, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(72, 'très bonne', 2, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(73, 'bonne', 3, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(74, 'assez bonne', 4, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(75, 'moyenne', 5, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(76, 'plutôt moyenne', 6, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(77, 'mauvaise', 7, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(78, 'limitée', 8, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(79, 'très mauvaise A', 9, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(80, 'très mauvaise B', 10, 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(81, 'excellente', 1, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(82, 'très bonne', 2, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(83, 'bonne', 3, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(84, 'assez bonne', 4, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(85, 'moyenne', 5, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(86, 'plutôt moyenne', 6, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(87, 'mauvaise', 7, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(88, 'limitée', 8, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(89, 'très mauvaise A', 9, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(90, 'très mauvaise B', 10, 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(91, 'excellente', 1, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(92, 'très bonne', 2, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(93, 'bonne', 3, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(94, 'assez bonne', 4, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(95, 'moyenne', 5, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(96, 'plutôt moyenne', 6, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(97, 'mauvaise', 7, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(98, 'limitée', 8, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(99, 'très mauvaise A', 9, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(100, 'très mauvaise B', 10, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(101, 'excellente', 1, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(102, 'très bonne', 2, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(103, 'bonne', 3, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(104, 'assez bonne', 4, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(105, 'moyenne', 5, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(106, 'plutôt moyenne', 6, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(107, 'mauvaise', 7, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(108, 'limitée', 8, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(109, 'très mauvaise A', 9, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(110, 'très mauvaise B', 10, 11, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(111, 'excellente', 1, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(112, 'très bonne', 2, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(113, 'bonne', 3, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(114, 'assez bonne', 4, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(115, 'moyenne', 5, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(116, 'plutôt moyenne', 6, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(117, 'mauvaise', 7, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(118, 'limitée', 8, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(119, 'très mauvaise A', 9, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(120, 'très mauvaise B', 10, 12, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(121, 'importants,bien adaptés', 1, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(122, 'importants, adaptés', 2, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(123, 'assez importants', 3, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(124, 'suffisants', 4, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(125, 'plutôt faibles', 5, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(126, 'faibles', 6, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(127, 'insuffisants A', 7, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(128, 'insuffisants B', 8, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(129, 'très insuffisants A', 9, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(130, 'très insuffisants B', 10, 13, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(131, 'excellente', 1, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(132, 'très bonne', 2, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(133, 'bonne', 3, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(134, 'assez bonne', 4, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(135, 'moyenne', 5, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(136, 'plutôt faible', 6, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(137, 'faible', 7, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(138, 'limité', 8, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(139, 'très limitée', 9, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(140, 'inexistente', 10, 14, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(141, 'très fiables dans l\'ensemble', 1, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(142, 'plutôt fiables A', 2, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(143, 'plutôt fiables B', 3, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(144, 'fiables', 4, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(145, 'moyennement fiables', 5, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(146, 'pas toufours fiables', 6, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(147, 'peu fiables', 7, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(148, 'peu ou pas fiables ou non communiquées', 8, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(149, 'pas fiables A', 9, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(150, 'pas fiables B', 10, 15, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(151, 'excellente', 1, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(152, 'très bonne', 2, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(153, 'bonne', 3, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(154, 'assez bonne', 4, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(155, 'moyenne', 5, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(156, 'limitée', 6, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(157, 'plutôt faible', 7, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(158, 'faible', 8, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(159, 'mauvaise', 9, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(160, 'très mauvaise', 10, 16, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(161, 'Ne se pose pas du tout', 1, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(162, 'Ne se pose pas', 2, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(163, 'Garantie par le cadre juridique et fonctionnel', 3, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(164, 'Garantie par le cadre juridique ', 4, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(165, 'Assurée depuis un certain temps (Moyen)', 5, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(166, 'Assurée depuis quelque temps (Court)', 6, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(167, 'se pose à moyen terme, relève non prévue', 7, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(168, 'se pose à court terme,relève non assurée', 8, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(169, 'problème aigue de succession', 9, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(170, 'gestion par administrateur ', 10, 17, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(171, 'très fiable', 1, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(172, 'fiable', 2, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(173, 'plutôt fiable A', 3, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(174, 'plutôt fiable B', 4, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(175, 'moyennement fiable', 5, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(176, 'pas toujours fiable', 6, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(177, 'peu fiable', 7, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(178, 'peu ou pas fiable', 8, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(179, 'pas fiable A', 9, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(180, 'pas fiable B', 10, 31, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(181, 'excellente', 1, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(182, 'très bonne', 2, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(183, 'bonne', 3, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(184, 'assez bonne', 4, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(185, 'moyenne', 5, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(186, 'plutôt moyenne', 6, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(187, 'mauvaise', 7, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(188, 'limitée', 8, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(189, 'très mauvaise A', 9, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(190, 'très mauvaise B', 10, 32, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(191, 'très facile en permanence', 1, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(192, 'facile en conjoncture normale A', 2, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(193, 'facile en conjoncture normale B', 3, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(194, 'limité en conjocture normale A', 4, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(195, 'limité en conjocture normale B', 5, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(196, 'incertain mais envisageable', 6, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(197, 'difficile mais possible', 7, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(198, 'très peu probable', 8, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(199, 'non pas d\'appui A', 9, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(200, 'non pas d\'appui B', 10, 33, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(201, 'oui, rapidement et importants', 1, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(202, 'oui, assez rapidement et assez  importants', 2, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(203, 'oui, sans delai A', 3, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(204, 'oui, sans delai B', 4, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(205, 'Surface financière limitée', 5, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(206, 'Surface financière faible', 6, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(207, 'Surface financière très faible', 7, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(208, 'Patrimoine insignifiant', 8, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(209, 'Non, pas patrimoine personnel', 9, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(210, 'Non, pas de patrimoine du tout', 10, 34, '2024-12-05 13:31:05', '2024-12-05 13:31:05');

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
(1, 'Banque mondiale', 'Appuis financiers', 1, 7, 0),
(2, 'Cabinet Alkars', 'Formation technique et professionnelle', 1, 0, 0),
(3, 'Fonds monétaire international (FMI)', 'Appuis financiers', 2, 8, 0),
(4, 'Programme alimentaire mondial (PAM)', 'Formation technique et professionnelle', 2, 16, 0),
(5, 'Cabinet Elesyst', 'Appuis non financiers', 2, 0, 0),
(6, NULL, 'Coordination', 5, 0, 0),
(7, 'Commercial Bank-Cameroun (CBC)', 'Appuis financiers', 5, 0, 9);

-- --------------------------------------------------------

--
-- Structure de la table `criteres`
--

CREATE TABLE `criteres` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `criteres`
--

INSERT INTO `criteres` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ACTIVITE', '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(2, 'GESTION ET STRATEGIE', '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(3, 'FINANCES', '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(4, 'QUALITE DE L\'INFORMATION FINANCIERE ET GARANTIES', '2024-12-05 13:31:05', '2024-12-05 13:31:05');

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Structure de la table `domaines`
--

CREATE TABLE `domaines` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `domaines`
--

INSERT INTO `domaines` (`id`, `name`, `active`) VALUES
(1, 'CACAO', 1),
(2, 'MAIS', 1);

-- --------------------------------------------------------

--
-- Structure de la table `domains`
--

CREATE TABLE `domains` (
  `id` int UNSIGNED NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `domains`
--

INSERT INTO `domains` (`id`, `domain`, `tenant_id`, `created_at`, `updated_at`) VALUES
(1, 'plafopcaeev.demo.angara-finance.net', 1, '2025-10-01 20:15:06', '2025-10-01 20:15:06'),
(2, 'socadyc.demo.angara-finance.net', 2, '2025-10-01 20:20:59', '2025-10-01 20:20:59'),
(3, 'copraec.demo.angara-finance.net', 3, '2025-10-01 20:22:31', '2025-10-01 20:22:31'),
(4, 'socoplanec.demo.angara-finance.net', 4, '2025-10-01 20:24:32', '2025-10-01 20:24:32'),
(5, 'socoopac.demo.angara-finance.net', 5, '2025-10-01 20:26:56', '2025-10-01 20:26:56'),
(6, 'socope.demo.angara-finance.net', 6, '2025-10-01 20:28:42', '2025-10-01 20:28:42'),
(7, 'mgbabangoise.demo.angara-finance.net', 7, '2025-10-01 20:30:32', '2025-10-01 20:30:32'),
(8, 'scoops-emergence-de-ntui.demo.angara-finance.net', 8, '2025-11-18 16:12:55', '2025-11-18 16:12:55');

-- --------------------------------------------------------

--
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` int UNSIGNED NOT NULL,
  `entreprise_id` int DEFAULT NULL,
  `programme_id` int DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`id`, `entreprise_id`, `programme_id`, `analyste_id`, `gestionnaire_id`, `agence_id`, `representation_id`, `donnees_generales`, `analyse_ensemble`, `analyse_financiere`, `appuis`, `analyse_risque`, `analyse_rentabilite`, `conclusions_analyste`, `conclusions_gestionnaire`, `conclusions_ca`, `created_at`, `updated_at`, `active`, `token`) VALUES
(1, 6, 2, 24, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-14 10:19:22', '2024-12-14 10:19:22', 0, '8fc67e27ce44b41298c7ac274af43dfdbcb532e3'),
(2, 8, 2, 16, 14, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-14 12:58:45', '2024-12-14 12:58:45', 0, 'efe545ed422cd53576ac66838815a5f95dfcba4c'),
(3, 10, 1, 36, 34, 7, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-14 13:52:42', '2024-12-14 13:52:42', 0, '5f29945c3d59e3e168c44f6c24c0059aad7fc21c'),
(4, 11, 3, 24, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-20 10:16:31', '2024-12-20 10:16:31', 0, '17ce15009de98b3a82fd96a5b4689fed497b8008'),
(5, 122, 2, 12, 10, 1, 1, '<p>Ceci est la description de l\'emprunteur</p>', NULL, NULL, NULL, NULL, NULL, NULL, '<p>Ici mes recommandations</p>', '<p>Quelques remarques</p>', '2026-03-19 09:11:07', '2026-03-19 11:14:14', 0, '6fe9e520c1c4e9964ab2ac3ba44ade1def17a7d8'),
(6, 195, 2, 12, 10, 1, 1, '<p>gdsjfdhfdj dskbkdsnk fdnndfn fdkndfl fdknfdlnldf</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-08 14:18:03', '2025-01-08 13:18:03', 0, 'a07fb9ab66ded5b4af10868b0c1fbedf98a98246'),
(7, 197, 2, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-09 07:29:25', '2025-01-09 07:29:25', 0, '4389c4c22d5f0c92dd6b366ce94fa5726353be8c'),
(8, 145, 3, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-10 09:29:32', '2025-02-10 09:29:32', 0, '904f83cafec3bfc2bc9ad5109c8d3c1e86c9f5bf'),
(9, 195, 1, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-10 10:15:21', '2025-02-10 10:15:21', 0, '8d7445925f646a9c066470bd50b5a3e2964735dc'),
(10, 203, 1, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-12 10:42:23', '2025-02-12 10:42:23', 0, 'f64a754dde4375f7b230a88780e66414179b7a27'),
(11, 205, 1, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-12 11:19:14', '2025-02-12 11:19:14', 0, '494c54de6b92daa3fbc3d1eb13263e69adbe4833'),
(12, 206, 4, 12, 10, 1, 1, '<p>hdjsdkjldsklds</p>', NULL, '<p><span style=\"background-color: rgb(30, 30, 30); color: rgb(212, 212, 212);\">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugiat provident accusamus expedita nemo ad dolorum explicabo, itaque, aspernatur, ex asperiores aut eaque debitis sint. Fugit reiciendis facilis in nulla suscipit.</span></p><p><span style=\"background-color: rgb(30, 30, 30); color: rgb(212, 212, 212);\">Hic iure porro magnam, optio similique error impedit fugit voluptate aspernatur, sunt harum veniam rem illo maxime nemo libero voluptatibus? Provident maxime, tempora atque et beatae officiis vero eum assumenda?</span></p><p><span style=\"background-color: rgb(30, 30, 30); color: rgb(212, 212, 212);\">Culpa odit consequatur aliquid, earum recusandae ipsa architecto adipisci modi quasi impedit ipsum nulla doloribus error itaque? Harum cum modi eligendi autem architecto minus, veniam necessitatibus, consequatur, doloremque quia labore.</span></p><p><span style=\"background-color: rgb(30, 30, 30); color: rgb(212, 212, 212);\">Consectetur veniam est quidem enim quae. Tempora iste debitis quia quos! Neque sint officia, possimus enim unde vitae? Ducimus atque alias modi quibusdam adipisci nihil provident iusto necessitatibus dolorem. Sit.</span></p><p><span style=\"background-color: rgb(30, 30, 30); color: rgb(212, 212, 212);\">Cumque eum, necessitatibus a fugit excepturi deserunt porro officia ab ipsum. Neque saepe repellendus ducimus aliquam quasi. Adipisci est dolorem non doloremque voluptatum molestiae vitae deleniti animi incidunt? Quas, aut!</span></p><p><br></p>', NULL, NULL, '<p>Petite rentabilite</p>', NULL, NULL, NULL, '2025-03-24 09:58:53', '2025-03-24 08:58:53', 0, '9671bbba512ee7a3b45e390a5dd7307f565c7388'),
(13, 207, 4, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-20 11:44:36', '2025-02-20 11:44:36', 0, '296e86214ac792d870790acf526d079baf1a1d2a'),
(14, 209, 1, 12, 10, 1, 1, '<p>Donne de l\'empre</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 16:07:23', '2025-03-21 15:07:23', 0, '8ca9626a89bcb86b2ed66a333e16bfadb92d9577'),
(15, 215, 1, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 16:14:04', '2025-05-07 16:14:04', 0, '1dce94c256802ee7263413d1d4c2d8b58412add8'),
(16, 215, 3, 12, 10, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 16:14:30', '2025-05-07 16:14:30', 0, '357ffb002b4fa06b4fd58bb0d9dbf31fca73b58e'),
(17, 240, 1, 24, 61, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 09:03:23', '2026-02-12 09:03:23', 0, '034639ea1c79be5dfa29ef60e1a374a63c029f8f'),
(18, 228, 1, 103, 10, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-19 11:37:08', '2026-03-19 11:37:08', 0, 'e9e8f67e0de6032117210653e13ec87dd4019361'),
(19, 244, 1, 112, 107, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-19 14:29:38', '2026-03-19 14:29:38', 0, 'ad80ec2055e723478db1a73d0739866d74110512'),
(20, 245, 1, 111, 105, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-19 14:41:50', '2026-03-19 14:41:50', 0, '14563441b578038cf689c3cdab309ef8751dff4b'),
(21, 246, 6, 112, 105, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-23 16:32:37', '2026-03-23 16:32:37', 0, '5e6a6434ed258e5b6a8a22e405bb8470749c9b94'),
(22, 247, 6, 111, 105, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-24 10:51:47', '2026-03-24 11:31:34', 0, '728812bb03227dddc52b1d7b914c67c8976b3ac3'),
(23, 249, 6, 111, 107, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-24 15:01:52', '2026-03-24 15:01:52', 0, '9815504cb031c3366532641e593f1ca6ae9150df'),
(24, 255, 6, 116, 115, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-25 14:35:55', '2026-03-25 14:41:13', 0, '48e311d2c5284a03484efbfef6486248bf60667b'),
(25, 256, 5, 116, 115, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-25 15:08:38', '2026-03-25 15:08:38', 0, '35841af2409610852aa26ee7580b1975667b687e'),
(26, 259, 7, 111, 115, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-30 10:14:34', '2026-03-30 10:14:34', 0, 'aac00c81feaebb2c23272b1527edcab4fbdf5dd1'),
(27, 260, 6, 124, 121, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p>OK POUR SUIVI DU CLIENT</p>', '2026-03-31 10:11:02', '2026-03-31 11:15:48', 0, '216856a07ad3a743f3cf1a618b158f19ebf77e97');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_esg_evaluations`
--

CREATE TABLE `dossier_esg_evaluations` (
  `id` bigint UNSIGNED NOT NULL,
  `dossier_id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `programme_id` int UNSIGNED DEFAULT NULL,
  `agence_id` bigint UNSIGNED DEFAULT NULL,
  `gestionnaire_id` bigint UNSIGNED DEFAULT NULL,
  `analyste_id` bigint UNSIGNED DEFAULT NULL,
  `reference_framework` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evaluation_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `evaluation_date` date DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `score_environmental` decimal(10,2) NOT NULL DEFAULT '0.00',
  `score_social` decimal(10,2) NOT NULL DEFAULT '0.00',
  `score_governance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `score_financial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `score_compliance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `score_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `risk_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankability_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eligibility_blending` tinyint(1) NOT NULL DEFAULT '0',
  `eligibility_guarantee` tinyint(1) NOT NULL DEFAULT '0',
  `eligibility_global_gateway` tinyint(1) NOT NULL DEFAULT '0',
  `exclusion_flag` tinyint(1) NOT NULL DEFAULT '0',
  `minimum_compliance_passed` tinyint(1) NOT NULL DEFAULT '0',
  `sdg_alignment` json DEFAULT NULL,
  `strengths` text COLLATE utf8mb4_unicode_ci,
  `weaknesses` text COLLATE utf8mb4_unicode_ci,
  `recommendations` text COLLATE utf8mb4_unicode_ci,
  `due_diligence_notes` text COLLATE utf8mb4_unicode_ci,
  `analyst_conclusion` text COLLATE utf8mb4_unicode_ci,
  `validation_comment` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `submitted_by` bigint UNSIGNED DEFAULT NULL,
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `rejected_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_esg_evaluations`
--

INSERT INTO `dossier_esg_evaluations` (`id`, `dossier_id`, `entreprise_id`, `programme_id`, `agence_id`, `gestionnaire_id`, `analyste_id`, `reference_framework`, `evaluation_type`, `status`, `evaluation_date`, `submitted_at`, `validated_at`, `rejected_at`, `score_environmental`, `score_social`, `score_governance`, `score_financial`, `score_compliance`, `score_global`, `risk_level`, `bankability_level`, `eligibility_blending`, `eligibility_guarantee`, `eligibility_global_gateway`, `exclusion_flag`, `minimum_compliance_passed`, `sdg_alignment`, `strengths`, `weaknesses`, `recommendations`, `due_diligence_notes`, `analyst_conclusion`, `validation_comment`, `rejection_reason`, `created_by`, `updated_by`, `submitted_by`, `validated_by`, `rejected_by`, `created_at`, `updated_at`) VALUES
(1, 20, 245, 1, 1, 105, 111, NULL, NULL, 'draft', NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 'Très élevé', 'Non bancable', 0, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 111, 111, NULL, NULL, NULL, '2026-03-19 14:55:35', '2026-03-19 14:55:35'),
(2, 19, 244, 1, 1, 107, 112, NULL, NULL, 'validated', NULL, '2026-03-19 15:39:05', '2026-03-24 12:00:04', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 'Très élevé', 'Non bancable', 0, 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 112, 112, 112, 109, NULL, '2026-03-19 15:32:09', '2026-03-24 12:00:04'),
(3, 21, 246, 6, 1, 105, 112, NULL, NULL, 'validated', NULL, '2026-03-23 16:44:27', '2026-03-24 11:59:48', NULL, '80.00', '60.00', '90.00', '50.00', '88.00', '73.60', 'Faible', 'Bancable', 1, 1, 1, 0, 1, NULL, 'FORTE PRODUCTION', 'RISQUE D\'EPIDEMIE', 'FAVORABLE POUR ACCOMPAGNEMENT', NULL, 'FAVORABLE', NULL, NULL, 112, 112, 112, 109, NULL, '2026-03-23 16:41:00', '2026-03-24 11:59:48'),
(4, 22, 247, 6, 1, 105, 111, NULL, NULL, 'validated', NULL, '2026-03-24 11:47:40', '2026-03-24 12:00:13', NULL, '50.00', '50.00', '50.00', '50.00', '50.00', '50.00', 'Modéré', 'Partiellement bancable', 0, 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 111, 111, 111, 109, NULL, '2026-03-24 11:46:47', '2026-03-24 12:00:13'),
(5, 25, 256, 5, 1, 115, 116, NULL, NULL, 'submitted', NULL, '2026-03-30 11:14:25', NULL, NULL, '70.00', '80.00', '51.00', '62.00', '75.00', '67.60', 'Modéré', 'Bancable', 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 116, 116, 116, NULL, NULL, '2026-03-30 11:10:59', '2026-03-30 11:14:25'),
(6, 27, 260, 6, 1, 121, 124, NULL, NULL, 'validated', NULL, '2026-03-31 12:53:47', '2026-03-31 13:00:01', NULL, '50.00', '60.00', '70.00', '70.00', '95.00', '69.00', 'Modéré', 'Bancable', 1, 1, 1, 0, 1, NULL, 'Respect des condition d\'hygiène et sanitaire', 'Respect des EPI', 'Formation continue du personnel', NULL, 'L\'activité est exploité dans des condition environnemental acceptable, le client maitrise la gouvernance de son activité', NULL, NULL, 124, 124, 124, 109, NULL, '2026-03-31 11:55:41', '2026-03-31 13:00:01');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_esg_evaluation_items`
--

CREATE TABLE `dossier_esg_evaluation_items` (
  `id` bigint UNSIGNED NOT NULL,
  `dossier_esg_evaluation_id` bigint UNSIGNED NOT NULL,
  `category_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicator_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicator_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indicator_description` text COLLATE utf8mb4_unicode_ci,
  `input_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value_text` text COLLATE utf8mb4_unicode_ci,
  `value_number` decimal(15,2) DEFAULT NULL,
  `value_boolean` tinyint(1) DEFAULT NULL,
  `score` decimal(10,2) NOT NULL DEFAULT '0.00',
  `weight` decimal(10,2) NOT NULL DEFAULT '1.00',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `engagement_entreprises`
--

INSERT INTO `engagement_entreprises` (`id`, `entreprise_id`, `banque_id`, `engagement_id`, `montant`, `encours_montant`, `encours_part`, `encours_impaye`, `encours_dt_validite`, `sollicite_montant`, `sollicite_part`, `sollicite_dt_validite`, `total`, `nb_part`, `dt_validite`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 16, 0, 130000, 0, 45000, '2024-11-27', 120000, 0, '2025-01-05', 0, 0, NULL, '2024-12-06 18:42:22', '2024-12-06 17:42:22'),
(2, 1, 3, 21, 0, 430000, 0, 310000, '2024-11-25', 400000, 0, '2025-01-05', 0, 0, NULL, '2024-12-06 08:05:29', '2024-12-06 08:05:29'),
(3, 1, 1, 17, 0, 1300000, 0, 700000, '2024-12-21', 45000000, 0, '2024-12-29', 0, 0, NULL, '2024-12-06 11:29:21', '2024-12-06 11:29:21'),
(4, 1, 1, 19, 0, 40000, 0, 20000, '2024-11-27', 34000, 0, '2025-01-03', 0, 0, NULL, '2024-12-06 17:35:35', '2024-12-06 17:35:35'),
(5, 195, 21, 16, 0, 57000, 0, 4000, '2025-01-02', 6000, 0, '2025-01-07', 0, 0, NULL, '2025-01-08 13:19:49', '2025-01-08 13:19:49'),
(6, 197, 20, 16, 0, 400000, 0, 200000, '2024-12-30', 300000, 0, '2025-01-30', 0, 0, NULL, '2025-01-09 07:36:08', '2025-01-09 07:36:08'),
(7, 206, 5, 16, 0, 50000, 0, 21000, '2025-03-03', 34000, 0, '2025-04-04', 0, 0, NULL, '2025-03-17 15:51:59', '2025-03-17 14:51:59'),
(8, 206, 8, 16, 0, 7000, 0, 2300, '2025-03-11', 45000, 0, '2025-04-04', 0, 0, NULL, '2025-03-17 14:53:27', '2025-03-17 14:53:27'),
(9, 206, 6, 16, 0, 3800, 0, 1500, '2025-03-04', 2000, 0, '2025-04-06', 0, 0, NULL, '2025-03-17 14:54:52', '2025-03-17 14:54:52'),
(10, 206, 2, 19, 0, 45000, 0, 23000, '2025-03-03', 34000, 0, '2025-03-28', 0, 0, NULL, '2025-03-17 14:55:44', '2025-03-17 14:55:44'),
(11, 195, 2, 16, 0, 23000000, 0, 1000000, '2025-10-29', 45000000, 0, '2025-11-09', 0, 0, NULL, '2025-10-31 13:29:18', '2025-10-31 13:29:18'),
(12, 244, 2, 41, 0, 110000000, 0, 0, '2027-02-03', 50000000, 0, '2026-12-19', 0, 0, NULL, '2026-03-19 15:27:20', '2026-03-19 15:27:20'),
(13, 228, 2, 18, 0, 40000, 0, 34, '2026-03-28', 45000, 0, '2026-03-27', 0, 0, NULL, '2026-03-19 15:30:42', '2026-03-19 15:30:42');

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
  `forme_id` int NOT NULL DEFAULT '0',
  `systeme` enum('Normal','Minimal') NOT NULL,
  `capital` double NOT NULL DEFAULT '0',
  `ressources_propres` double NOT NULL DEFAULT '0',
  `total_actif` double NOT NULL DEFAULT '0',
  `nb_personnel` int NOT NULL DEFAULT '0',
  `nb_personnel_permanent` int NOT NULL DEFAULT '0',
  `nb_personnel_saisonier` int NOT NULL DEFAULT '0',
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
  `manager_promoteur` tinyint(1) NOT NULL DEFAULT '1',
  `manager_dtn` date DEFAULT NULL,
  `produit_id` int NOT NULL DEFAULT '0',
  `produit_year_start` int DEFAULT NULL,
  `filiere_id` int NOT NULL DEFAULT '0',
  `branche_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `gestionnaire_id` int NOT NULL DEFAULT '0',
  `agence_id` int NOT NULL DEFAULT '0',
  `representation_id` int NOT NULL DEFAULT '0',
  `region_id` int NOT NULL DEFAULT '0',
  `departement_id` int NOT NULL DEFAULT '0',
  `arrondissement_id` int NOT NULL DEFAULT '0',
  `village_id` int NOT NULL DEFAULT '0',
  `quartier_id` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `prospect` tinyint(1) NOT NULL DEFAULT '0',
  `programme_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `individual` tinyint(1) NOT NULL DEFAULT '0',
  `producteur_id` int NOT NULL DEFAULT '0',
  `verger_id` int NOT NULL DEFAULT '0',
  `cooperative_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `name`, `rccm`, `niu`, `cnps`, `mm_phone`, `taille`, `caractere`, `forme_id`, `systeme`, `capital`, `ressources_propres`, `total_actif`, `nb_personnel`, `nb_personnel_permanent`, `nb_personnel_saisonier`, `personnel_permanent`, `personnel_saisonier`, `personnel_mixte`, `dt_creation`, `dt_start`, `email`, `phone`, `manager`, `manager_contact`, `manager_sexe`, `manager_niveau`, `manager_promoteur`, `manager_dtn`, `produit_id`, `produit_year_start`, `filiere_id`, `branche_id`, `user_id`, `gestionnaire_id`, `agence_id`, `representation_id`, `region_id`, `departement_id`, `arrondissement_id`, `village_id`, `quartier_id`, `created_at`, `updated_at`, `token`, `prospect`, `programme_id`, `parent_id`, `individual`, `producteur_id`, `verger_id`, `cooperative_id`) VALUES
(5, 'Altorx', 'H83290329032', '782390320320', '6787878989', '0687989', 'MOYENNE', 'Formel', 5, 'Normal', 70000, 67000, 56000, 7, 0, 0, 0, 1, 0, '2024-12-03', '2009-01-11', 'info@test.com', '067878989', 'Alima raphael', '96788989', 'Homme', 'Supérieur', 0, '1996-02-01', 19, 2019, 0, 0, 1, 0, 0, 0, 8, 45, 271, 0, 0, '2024-12-12 13:28:57', '2024-12-12 13:28:57', '9dedae4adb85cd55bd4c61c2411af11c9f7351f4', 0, 0, 0, 0, 0, 0, 0),
(6, 'Quick Travel', 'RG57812821', 'HY78921821989', '7676721GF5676712', '9029021090', 'PETITE', 'Informel', 6, 'Minimal', 1000000, 200000, 3000000, 3, 0, 0, 0, 0, 1, '2023-08-08', '2023-11-09', 'info@quicktravel.org', '67821991889', 'Roland Enama', '679893829', 'Homme', 'Secondaire', 1, '1999-01-23', 343, 2022, 0, 0, 1, 0, 0, 0, 2, 8, 70, 0, 0, '2025-02-12 11:17:41', '2024-12-12 16:53:58', 'ec1e4d31b9962ec69aaca699f2b28fd312a08e33', 0, 0, 0, 0, 0, 0, 0),
(7, 'SCTM Gaz', '7329382932', '3268327832', '890392023', NULL, 'MOYENNE', 'Formel', 5, 'Normal', 800000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'contact@sctm-gaz.com', '7892109210', 'Owona Alfred Alain', '698328932', 'Homme', NULL, 1, NULL, 105, NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2025-02-12 11:18:33', '2024-12-13 17:16:44', '6308c919e213d08683e2d8038e53ecf8acec54e6', 1, 0, 0, 0, 0, 0, 0),
(8, 'Agritech', '63726832788932', '7732873288723', '673676387832', '689832032', 'TRES PETITE', 'Formel', 2, 'Minimal', 500000, 300000, 400000, 2, 0, 0, 0, 1, 0, '2024-07-16', '2024-09-10', 'e.olama@gmail.com', '68328293209023', 'Olama Ernest', '67932898329', 'Homme', 'Secondaire', 1, '1983-11-23', 14, 2017, 0, 0, 14, 0, 2, 1, 3, 17, 110, 0, 0, '2024-12-14 13:01:23', '2024-12-14 13:01:23', 'bfee127ec36e18331b3d83b65eea9a2c287e4791', 0, 0, 0, 0, 0, 0, 0),
(9, 'Alliages Technologies', '3832832932', '892389832932', '792388023', NULL, 'PETITE', 'Informel', 2, 'Minimal', 300000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'info@alliages.com', '6830293203290', 'Essomba clement', '68938298239', 'Homme', NULL, 1, NULL, 71, NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2024-12-14 14:27:08', '2024-12-14 14:27:08', '3fcb53da71403c143446316aeb8f47d2ec48275c', 1, 0, 0, 0, 0, 0, 0),
(10, 'Jutix', '67848748383', '6328878823', 'C623783827832', '698923809032', 'MOYENNE', 'Formel', 6, 'Normal', 1000000, 3000000, 4000000, 17, 0, 0, 0, 0, 1, '2007-02-11', '2010-11-20', 'info@jutix', '657892382', 'Owona simon pierre', '67329898329', 'Homme', 'Supérieur', 0, '1980-05-07', 30, 2005, 0, 0, 34, 0, 7, 3, 5, 27, 184, 0, 0, '2025-02-12 11:18:37', '2024-12-14 14:39:41', '153ff2b126ea04044a1c73ef8046d6540f046306', 0, 0, 0, 0, 0, 0, 0),
(11, 'COOPACE', '623738032404', '7580054985489845', '7438934884943', '67832989239', 'COOPERATIVE', 'Formel', 1, 'Minimal', 1000000, 340000, 450000, 56, 0, 0, 0, 1, 0, '2000-11-12', '2002-11-11', 'info@coopace.com', '689899000', 'Edzigui Andre', '673898329328', 'Homme', 'Supérieur', 1, '1977-01-12', 35, 2001, 0, 0, 1, 0, 0, 0, 2, 14, 77, 0, 0, '2024-12-20 11:15:18', '2024-12-20 11:15:18', '183e2c932d6f5cf989a22e181a190d2f2084c46a', 0, 0, 0, 0, 0, 0, 0),
(12, 'AGRIFOOD BEVERAGE', NULL, NULL, NULL, '670573906', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Elissar Wolber', '670573906', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '832625609556f6e2113b5f6e5a33c1c9c1a6c68d', 0, 0, 0, 0, 0, 0, 0),
(13, 'MINA GREEN SARL', NULL, NULL, NULL, '671672565', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'André Serge MOUSSENI', '671672565', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'c6c117270c8bd0f30cb1b6603371885e1abde4b6', 0, 0, 0, 0, 0, 0, 0),
(14, 'SOCOPLACEM', NULL, NULL, NULL, '694523921', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AMBOMO Jovit', '694523921/677157160/678134410', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '726c56e037eaae840b543b4d282dfcc42b02e179', 0, 0, 0, 0, 0, 0, 0),
(15, 'TROPICAL FOREST FOOD AND COSMETICS', NULL, NULL, NULL, '674473097', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MOUKOUDI Pierre Sylvere', '674473097', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'dae2ed66648778ff5f5048616345f340794debac', 0, 0, 0, 0, 0, 0, 0),
(16, 'B2M-AGROINDUSTRIE', NULL, NULL, NULL, '656367332', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BISSO MONESSO Marvist', '656367332', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '90eb858d631947b0bc476ff25a6c07fb1dbcd815', 0, 0, 0, 0, 0, 0, 0),
(17, 'CLUSTER MIDOMAK', NULL, NULL, NULL, '676258798 ', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MBARGA Robert Brice', '676258798 /693841354', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '3e564b8a56f416bf66576a8b8c291959f9e09b9b', 0, 0, 0, 0, 0, 0, 0),
(18, 'CLUSTER SOCOTRADPH- COOP CA', NULL, NULL, NULL, '699080365 ', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NLIBA Roger Casimir', '699080365 / 682422066', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '4e82e48d5cc2594d114ee2e7130936a21d23e758', 0, 0, 0, 0, 0, 0, 0),
(19, 'SVELTCAO', NULL, NULL, NULL, '694616267', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme NDZIE Suzanne', '694616267/653134740', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'bfe74eaa44e534fa221d2e8d6be55d2e9cc42e62', 0, 0, 0, 0, 0, 0, 0),
(20, 'JOAHNA FOOD', NULL, NULL, NULL, '691398444', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MBIEM Joahna', '691398444', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '606f3c31034afd6bd77f094e042c37b0a2a35947', 0, 0, 0, 0, 0, 0, 0),
(21, 'N et J Consulting GROUP', NULL, NULL, NULL, '694065141', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NYABEYEU Nina Dorine', '694065141/656765656', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '7665c7a4207f34013a4883f146f35b4ca14a43d2', 0, 0, 0, 0, 0, 0, 0),
(22, 'SCOOPS Les Femmes rurales Mayang-Mot du Cameroun (SCOOPS FRUMC)', NULL, NULL, NULL, '691843719', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EFFA EBOUDOU Epse MBA', '691843719', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '94ad5c08c5942f2b7902bf9fa24deceb364b5798', 0, 0, 0, 0, 0, 0, 0),
(23, 'R-JOMAK', NULL, NULL, NULL, '696783634', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BIDOUA Epse MBOZO\'O', '696783634/674422449', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '0210ae6abede26c63ef9f9cb458f97527ac485d0', 0, 0, 0, 0, 0, 0, 0),
(24, 'KENBY NATURAL', NULL, NULL, NULL, '675845799', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KENMEGNE Blandine', '675845799', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '8f367abf75056379ba3914309b254aaf164a3fed', 0, 0, 0, 0, 0, 0, 0),
(25, 'EGALITE OBLIGE', NULL, NULL, NULL, '695312225', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EYENGA Epse ANDEGUE BENE Cecile', '695312225', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'e7748ce764e42fc7c42ec0bb3934c9f3d6456976', 0, 0, 0, 0, 0, 0, 0),
(26, 'TECHNOLOGY WORLD SERVICES', NULL, NULL, NULL, '676401255', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Dovy CHOUMZOUE', '676401255', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '8da9dee2a6339f9639340292882763ace919998a', 0, 0, 0, 0, 0, 0, 0),
(27, 'VLITA SARL', NULL, NULL, NULL, '675214638', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'DJAMILATOU Epse', '675214638', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'b9edd764552e9173c886a7698c422cecf8596e62', 0, 0, 0, 0, 0, 0, 0),
(28, 'SNAIL HOUSE', NULL, NULL, NULL, '699788064', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SAMNICK Marcel Fils', '699788064', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '4ee8ba70114f743dfb9bf0e21201d264dc888950', 0, 0, 0, 0, 0, 0, 0),
(29, 'YENE ET COMPAGNIE', NULL, NULL, NULL, '652577094', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EDOA Mélanie Judicaël Peggy', '652577094', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'ed042caf1a12a7c03029c498edba70fd48cf3a4e', 0, 0, 0, 0, 0, 0, 0),
(30, 'BE INTERESTED BE IN', NULL, NULL, NULL, '695050927 ', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BIBI Martial Arnaud', '695050927 / 675635599', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '315aa751ad273df919c67bc927bfefd513045ccd', 0, 0, 0, 0, 0, 0, 0),
(31, 'LIMITES AGROBUSINESS', NULL, NULL, NULL, '699645837', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGAH Berlin Sidoine', '699645837/653036330', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '4fa7814ade93629c07d0efcab9054ec3532c6002', 0, 0, 0, 0, 0, 0, 0),
(32, 'YOUBI KAMGA Lucie', NULL, NULL, NULL, '672559801', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'YOUBI KAMGA Lucie', '672559801', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '1d61368d6eb8a21414679e3e173d8053fef4b6d8', 0, 0, 0, 0, 0, 0, 0),
(33, 'ETRE NOUS', NULL, NULL, NULL, '698665511', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Dr. AVOA MEBENGA Genevieve Sandrine', '698665511', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f0983a519be4f5f05160117d8c9b0643307d62cb', 0, 0, 0, 0, 0, 0, 0),
(34, 'CLAIR MARIE', NULL, NULL, NULL, '699040762', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EBESSA Claire Marie', '699040762', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '8233982fae9c53b3e4c11501106bce45a235c90f', 0, 0, 0, 0, 0, 0, 0),
(35, 'ETS SWEET CHOCO', NULL, NULL, NULL, '677876651', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BISSA Epse NTAP Ghislaine Nathalie', '677876651', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '6d2ea5f461984789bd43e10fd6cfea0b01bd42f2', 0, 0, 0, 0, 0, 0, 0),
(36, 'SOCOPROCAON', NULL, NULL, NULL, '690979405 ', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AMBASSA OTTOU Benoit', '690979405 / 677071513', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '05bbcfdee804ee7a634d5e738e562e6e8b2c114f', 0, 0, 0, 0, 0, 0, 0),
(37, 'SILAS CONSTRUCTION', NULL, NULL, NULL, '696765624 ', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'GOUATE Serges Silas', '696765624 / 650907646', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '5f204d5212980fc93da77e9d3c599589262b9599', 0, 0, 0, 0, 0, 0, 0),
(38, 'NUTRIVIE', NULL, NULL, NULL, '6533792020', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KABIWA TCHOUAKE', '6533792020', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '1c4bf4cf528bcdff530059df219d1d834338a0e4', 0, 0, 0, 0, 0, 0, 0),
(39, 'OKAPI', NULL, NULL, NULL, '657387288', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'ADZEME ANGOULA Yannick Joël', '657387288', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '23a03c91133e02067f8ac3f89d05886404fd28c9', 0, 0, 0, 0, 0, 0, 0),
(40, 'SOTPRAT', NULL, NULL, NULL, '671991968', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'DJAPA DJADJOU Marlyse', '671991968', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '7d8b7db354d5ab704f00d67e0026557f3dc0f444', 0, 0, 0, 0, 0, 0, 0),
(41, 'LABORATOIRE PHYTOVITA', NULL, NULL, NULL, '693822305', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NZIE LEUC Romuald', '693822305/651061629', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '63a50d0eafca38d41f149b6c46e0cb5aa5bef591', 0, 0, 0, 0, 0, 0, 0),
(42, 'JUICE FOR JOY', NULL, NULL, NULL, '699874611', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TANON TCHINDA Epse GOUAGNA Catherine Sylvie', '699874611', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9781eae7bc4be47c82270f614dce8df614118ed4', 0, 0, 0, 0, 0, 0, 0),
(43, 'KATI FOODS', NULL, NULL, NULL, '677237823', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TCHAMDJOU KATI Yannick', '677237823/698177079', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'acd84edc19af161d6679cbc7121841e73c30c331', 0, 0, 0, 0, 0, 0, 0),
(44, 'JOHINA CENTER', NULL, NULL, NULL, '679167192', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AKUAH NDEFRU', '679167192', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'a1fb57b4c61a14900a7aaf5e0135244b343e4eb9', 0, 0, 0, 0, 0, 0, 0),
(45, 'Ets MARY B', NULL, NULL, NULL, '693303331', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme NOUNE NOUMBA Marie', '693303331/676947292', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '80fe65668678a3817f43f0cf28f7bc594dea7549', 0, 0, 0, 0, 0, 0, 0),
(46, 'PROLAC', NULL, NULL, NULL, '699780030', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EYOUM LAWANE Honoré', '699780030', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'dd71c5d0b9eed0aa4f6e43394836541ffd5ba10f', 0, 0, 0, 0, 0, 0, 0),
(47, 'LA MEME Sarl', NULL, NULL, NULL, '690334540', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NOULALA Anne', '690334540', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '4840f65c70fd6fe66b283dcef68096dd35858ba5', 0, 0, 0, 0, 0, 0, 0),
(48, 'AMIE & COMPAGNIE', NULL, NULL, NULL, '662509767', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MFEGUE AMYE Estelle', '662509767/691530816', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f781acd7d0ff6535cfd0e3f53c00a1f0407fd176', 0, 0, 0, 0, 0, 0, 0),
(49, 'ETS KESSENG ET FILS', NULL, NULL, NULL, '699691716', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KOUL A KESSENG Emmanuel Cyrille', '699691716', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '7458d9e369ccd2c7f22b258e25cde6a1bdc4dede', 0, 0, 0, 0, 0, 0, 0),
(50, 'RELESS  BATCHENGA', NULL, NULL, NULL, '677924889', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme ESSENGUE Honorine', '677924889/ 695640144', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 22, 0, 4, 2, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9493c28d2ec62a1bbf44d919da0f8ea97cebbadc', 0, 0, 0, 0, 0, 0, 0),
(51, 'DAVEN BAKERY SAS', NULL, NULL, NULL, '675475212', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'ONGMESSOM Blanche', '675475212', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '3e368c2c6e213598d9014b7c46626ae1868a6647', 0, 0, 0, 0, 0, 0, 0),
(52, 'SOCTRACAO', NULL, NULL, NULL, '677822448', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NDEH Dieudonné', '677822448', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'efb6e646d765c538615f19c93f3ca1bfb7c27666', 0, 0, 0, 0, 0, 0, 0),
(53, 'DK QUEEN SARL', NULL, NULL, NULL, '696803278', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TANGANG Clarisse', '696803278', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9d60be3609b2ee722e470743ca48b6ffdd798cd0', 0, 0, 0, 0, 0, 0, 0),
(54, 'YIIH INDUSTRY SARL', NULL, NULL, NULL, '683932302', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'GNASSIRI Simon', '683932302', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'b2e42248ba7f552af3458c917459634bd616c792', 0, 0, 0, 0, 0, 0, 0),
(55, 'COOP-CA ABIL', NULL, NULL, NULL, '699635439', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'ABBA ILYASSA', '699635439', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '7ab347a99d2fc5e01c67bd77d848c12d6408a411', 0, 0, 0, 0, 0, 0, 0),
(56, 'BATELA FOODS', NULL, NULL, NULL, '699984029', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGOULE NANA Dolvys', '699984029', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '22e7ae8b299ce589846a7e7e7705a093bb2679b2', 0, 0, 0, 0, 0, 0, 0),
(57, 'ETS DKT VET', NULL, NULL, NULL, '699113270', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KOUGOUE TCHOUASSI Dominique', '699113270', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'e21d99e19529faea9aff5378aa1ff74a4476de7e', 0, 0, 0, 0, 0, 0, 0),
(58, 'FARMER FOOD PROCESS', NULL, NULL, NULL, '678827631', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGUEBOU Epse TANKEU Ariane', '678827631', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'be5f1c1d982f3c4428d0ec689fb319756d47d77c', 0, 0, 0, 0, 0, 0, 0),
(59, 'GLOBAL PALM OIL PROCESSING', NULL, NULL, NULL, '656323303', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NEYOU NOUPA Gabin', '656323303', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'c2c58d8ef9ea6402e9bf526856c5c46ef9e366bf', 0, 0, 0, 0, 0, 0, 0),
(60, 'BRULERIE DU MOUNGO', NULL, NULL, NULL, '655741344', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'GAKO Bertrand', '655741344', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '594b0e4f50fe0829bad5aa0655f70fd2eb5310fa', 0, 0, 0, 0, 0, 0, 0),
(61, 'AFRCAN BUSINESS CONSULTING', NULL, NULL, NULL, '690969606', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BOUWE DJEUTIE Edouard', '690969606', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'fc6797dfaa1feee0d92b0f072fd59f49ce076223', 0, 0, 0, 0, 0, 0, 0),
(62, 'DEVELOP GROUP', NULL, NULL, NULL, '674965478', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SONFA TSAGUE Ebenizert', '674965478', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '98c92c0884c08181d54d5573521a683862f9c299', 0, 0, 0, 0, 0, 0, 0),
(63, 'NEJ-INDUS', NULL, NULL, NULL, '693719250', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NKOUANKAM NANKAM Eric', '693719250', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f800812345ada041e1f09aafec5b61a03d1df11f', 0, 0, 0, 0, 0, 0, 0),
(64, 'ETS OPAL', NULL, NULL, NULL, '693909001', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NAN Epse MBOG Céline', '693909001', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '5f2b1338bbc651b03150e2f3a6d0bcf36287bc17', 0, 0, 0, 0, 0, 0, 0),
(65, 'GASTRO SERVICES', NULL, NULL, NULL, '699325104', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGALANI SIEWE Rigobert', '699325104', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'e3e8b29f4b7114efef9223ccfa96756d2245621a', 0, 0, 0, 0, 0, 0, 0),
(66, 'STF NATURAL', NULL, NULL, NULL, '694063395', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TEUWA KOUAM Carelle', '694063395', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '05b2bd4892a57b5be2b2636bb90a1bf47ae3c670', 0, 0, 0, 0, 0, 0, 0),
(67, 'ETRALIA', NULL, NULL, NULL, '676596356', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'HOND Victor', '676596356', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '111ccd5e2a3f0cf87112823fe66b263d4ca64462', 0, 0, 0, 0, 0, 0, 0),
(68, 'SOCIETE COOP-CA PLANEILIT', NULL, NULL, NULL, '679811435', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MEKONTSO Maurice', '679811435', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '3b4fbbbe1c6414be780fe03ab851b4c9e91c1c6e', 0, 0, 0, 0, 0, 0, 0),
(69, 'CEMACO', NULL, NULL, NULL, '675071006', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MANDJOUNG Honorine', '675071006', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '8dc52a11c41f53054c667d16d344c0167be05b3e', 0, 0, 0, 0, 0, 0, 0),
(70, 'DELYSE NATUR’', NULL, NULL, NULL, '691788359', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BILH MANGAMA', '691788359', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'b8ca5f7cafa75c26cf6d9217116f1ad86da9544a', 0, 0, 0, 0, 0, 0, 0),
(71, 'C\'NATUREL', NULL, NULL, NULL, '694264224', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KONDO Robert', '694264224', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '5a8ea5d84ad8588a4152f43211d82f74e5cd8025', 0, 0, 0, 0, 0, 0, 0),
(72, 'MAC FOODS', NULL, NULL, NULL, '699776846', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MINKOUMOU Augustin Caleb', '699776846', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'd10d6a4731c4b1bf05df86bcd87508307b7dd8ff', 0, 0, 0, 0, 0, 0, 0),
(73, 'SOCIPEC SARL', NULL, NULL, NULL, '699800801', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme TAGNE TAPIA', '699800801', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'd52d346398beb2f84eec667a124f49c137d39f02', 0, 0, 0, 0, 0, 0, 0),
(74, 'ETS DECASTRO (GRAND)', NULL, NULL, NULL, '696002733', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'FOMEKONG Decastro', '696002733', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'e3dcaa7abb74a6e18bdd838f860a85821fec54b0', 0, 0, 0, 0, 0, 0, 0),
(75, 'SAPPGO SARL', NULL, NULL, NULL, '699622633', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NDONGOU KEPSEU Christelle', '699622633', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '45ad9a2a5d2767987990b39f62164bef75693224', 0, 0, 0, 0, 0, 0, 0),
(76, 'FERME EMMANUEL', NULL, NULL, NULL, '698115843', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MEKONTSA Maurice', '698115843', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '31782f0016912ba85eb974ed8228abf94ab7fc2d', 0, 0, 0, 0, 0, 0, 0),
(77, 'SOCOTRANS-', NULL, NULL, NULL, '655134030', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KUATE Blaise', '655134030', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '35076a6d9f01ef3856d3ded427c85550f3252ff0', 0, 0, 0, 0, 0, 0, 0),
(78, 'VICTORIA SARL INDUSRY', NULL, NULL, NULL, '679398185', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SAAH Victor', '679398185', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '14ef77ac67753d9466ac952ceeb603fcb8c308ae', 0, 0, 0, 0, 0, 0, 0),
(79, 'LAMANA SARL', NULL, NULL, NULL, '680907373', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NENDA Pascaline', '680907373', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '1cc520404d83b364b2c1923a2c8a445b8c5cfee5', 0, 0, 0, 0, 0, 0, 0),
(80, 'BIOTROPICAL/BIONATURA SCOOP', NULL, NULL, NULL, '699000087', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'IMELE Jean pierre', '699000087', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'ac6b6329b4bb461e673bf26bc5d40de781dcf559', 0, 0, 0, 0, 0, 0, 0),
(81, 'ROSA HOME', NULL, NULL, NULL, '677793660', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'LIBONGO Bob', '677793660', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 34, 0, 7, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f41bb6ccd05541f4bb233c84475d908de0601c73', 0, 0, 0, 0, 0, 0, 0),
(82, 'Cowrie Products Enterprise', NULL, NULL, NULL, '677965031', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Ndonwi Derick Shu', '677965031', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '6fd0bb1f67d86f2e2b921a9ff178ddb99c308916', 0, 0, 0, 0, 0, 0, 0),
(83, 'Kitchen Competition', NULL, NULL, NULL, '650600777', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Meh Anyekeze Sangha', '650600777', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '0846574f8b08208a3630e50a9396f8c13f298949', 0, 0, 0, 0, 0, 0, 0),
(84, 'Beneficial Natural Foods Enterprise', NULL, NULL, NULL, '677770171', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Chenwi Bernard', '677770171', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '2cc863ff1ad103ec5e963b7fbac05fb6fc037699', 0, 0, 0, 0, 0, 0, 0),
(85, 'MICA Foods', NULL, NULL, NULL, '683597693', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Akoni Young Thomas', '683597693', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '5ab21eb8aab27696a806a8e6660453eb9a7c6dec', 0, 0, 0, 0, 0, 0, 0),
(86, 'Floven Winery', NULL, NULL, NULL, '676421631', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Florence Nsu Luti', '676421631', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '4038b1e1644d144d1e1f018204b489f5752d85bf', 0, 0, 0, 0, 0, 0, 0),
(87, 'Refaro Company LTD', NULL, NULL, NULL, '675722336', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SENGKA Jude', '675722336', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '074fa1cb06a0bd8858301862f99c22d8acc4b42c', 0, 0, 0, 0, 0, 0, 0),
(88, 'Sohumas Enterprise', NULL, NULL, NULL, '670736293', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Redemption Godlove', '670736293', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'ec280d1a9f22e191e10890fa787e58deb8dc00bc', 0, 0, 0, 0, 0, 0, 0),
(89, 'Goodwill Ventures Co Ltd', NULL, NULL, NULL, '673071734', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Ngam Alfred Nyinchuo', '673071734', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '051d3644cde19dd1ced5899f1574dcd4a28c90ea', 0, 0, 0, 0, 0, 0, 0),
(90, 'Self Help Agro Business Promoters', NULL, NULL, NULL, '65119 80 98', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Bughe Mathias Ngha', '65119 80 98', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'fbf36d06dbc4f12b8b3ec8b53c707163a27556a6', 0, 0, 0, 0, 0, 0, 0),
(91, 'Babungo Baba  Babessi Cooperative', NULL, NULL, NULL, '670266236', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Nchinda Paul Ngeh', '670266236', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f2fc20a95e573a7af0def941d49fb6def193170a', 0, 0, 0, 0, 0, 0, 0),
(92, 'Teloh Rice Processing Enterprise', NULL, NULL, NULL, '677860247', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Njotunyi Justin', '677860247', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '058ceb0e45cb2020be3ad35198ed1592e96bdb1a', 0, 0, 0, 0, 0, 0, 0),
(93, 'Afribes Cooperative Society', NULL, NULL, NULL, '670823532', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Ndifiembue Raymond Mingoh', '670823532', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'dadc74a73f578b9cedc9e9ed282b039f852468ed', 0, 0, 0, 0, 0, 0, 0),
(94, 'Wosoh Processing Enterprise', NULL, NULL, NULL, '679136177', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Wosoh Ernest Nyibeng', '679136177', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '271a06645234b380f3c7f3b17a59e12a204ac70c', 0, 0, 0, 0, 0, 0, 0),
(95, 'Sali Enterprise', NULL, NULL, NULL, '677801692', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Esenei Paul Ikiafang', '677801692', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'cdd3946408f64e67893a34c9fe4ed6b717c71faf', 0, 0, 0, 0, 0, 0, 0),
(96, 'Ndop Rice Processing', NULL, NULL, NULL, '674549360', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Nkwanui Usman', '674549360', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '338f02ed534c9f7591719641c524c6371a6f130d', 0, 0, 0, 0, 0, 0, 0),
(97, 'Forpena Grace Enterprise', NULL, NULL, NULL, '675552485', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Forpena Grace', '675552485', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '853a24582ce9794d809cd986d6af497ea1573596', 0, 0, 0, 0, 0, 0, 0),
(98, 'Green Earth Corporation', NULL, NULL, NULL, '677521782', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Wilfred Alou', '677521782', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'ff5918976c76c3742752fd30ef43a4e05f6f360c', 0, 0, 0, 0, 0, 0, 0),
(99, 'NOWEFAM', NULL, NULL, NULL, '674722660', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Sevidzem Ernestine Leikeri', '674722660', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '130c9e0972deca1009ce3977e2022bfabae0b425', 0, 0, 0, 0, 0, 0, 0),
(100, 'Agulli Rice Farmers Cooperative', NULL, NULL, NULL, '673750987', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Tafri Joseph Wekeh', '673750987', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '23f154155a18019766b82e3aababa5c850fdda78', 0, 0, 0, 0, 0, 0, 0),
(101, 'Fungom Food Security', NULL, NULL, NULL, '677354053', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Bung Henry Tem', '677354053', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 42, 0, 9, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '7107e82a7869cc0323a46780477380baded24893', 0, 0, 0, 0, 0, 0, 0),
(102, 'GIC ALKAWALKUTABA', NULL, NULL, NULL, '675061699', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Ibrahim TAZE', '675061699', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '031c87e59af62023a813e95eae634fb911e6f961', 0, 0, 0, 0, 0, 0, 0),
(103, 'TRANSFOPHSCOOPS', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MBETCOM Moustapha', '', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'b89f22476b56c68c69b85313371618cff72a074d', 0, 0, 0, 0, 0, 0, 0),
(104, 'SCOOPS LAIT KOUOPTAMO', NULL, NULL, NULL, '699828361', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'S/C IBRAHIM', '699828361', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'e09d184cdce39b9b9550f0468b4dbd6a278fdd11', 0, 0, 0, 0, 0, 0, 0),
(105, 'ETS JONES', NULL, NULL, NULL, '691 03 06 99', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGANTAH JONES', '691 03 06 99', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '274f8e658e780d45d21fe74287ae1d8d140df19e', 0, 0, 0, 0, 0, 0, 0),
(106, 'FOODNOUN', NULL, NULL, NULL, '697007816', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NDAM NSANGOU Ibrahim Nasif', '697007816/677686204', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'cca7a3a67521f3bef066bf4af07b4eccacade2c7', 0, 0, 0, 0, 0, 0, 0),
(107, 'SOCOMAK COOP-CA', NULL, NULL, NULL, '694658450', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NKOUOTOUO OUMAROU', '694658450', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'de976a6e93684933b1d5db50ceacca8814031e7a', 0, 0, 0, 0, 0, 0, 0),
(108, 'GIC NTAMTE', NULL, NULL, NULL, '695569140', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'GUIFFO Théophile', '695569140', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9a078b8bff1fc6d59b1d63e8b4ea9bccc76334ac', 0, 0, 0, 0, 0, 0, 0),
(109, 'KEGNE EVELINE', NULL, NULL, NULL, '697217821', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KEGNE EVELINE', '697217821', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '8e461fb1c53968909003ef959e9ee13dd53086bb', 0, 0, 0, 0, 0, 0, 0),
(110, 'OSPLAMNE', NULL, NULL, NULL, '696672094', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGANGANG Jean', '696672094', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9e71fd09b076facbbf84ed92157a1c1c3db0a293', 0, 0, 0, 0, 0, 0, 0),
(111, 'SEPAL SARL', NULL, NULL, NULL, '693775050', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'FOKAM Charles', '693775050', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '86f190fd32a579240c07b45e18126a8ba5602ce4', 0, 0, 0, 0, 0, 0, 0),
(112, 'AVITA GROUP Sarl', NULL, NULL, NULL, '699663334', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MASSA FOKA', '699663334', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '90e255dcf2d1651113a93ecfb676dfb725c6d78a', 0, 0, 0, 0, 0, 0, 0),
(113, 'Plantation TOKAM Sarl', NULL, NULL, NULL, '695569140', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TOKAM', '695569140', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '31e666d1c6a8c01c1c915bf94ea1a714366e8d4a', 0, 0, 0, 0, 0, 0, 0),
(114, 'SIPO FRUITS ET LEGUMES', NULL, NULL, NULL, '699828229', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NOUANEGUE Louise', '699828229', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'ab9b3b87ca32d7e19c5ab6757f2828fd192b7c3e', 0, 0, 0, 0, 0, 0, 0),
(115, 'BIOFOOD Industry COOP-CA', NULL, NULL, NULL, '690169798', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Eric Martial KAPTUE', '690169798', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '574772e6b6ae71909730d539096ddab8078f950d', 0, 0, 0, 0, 0, 0, 0),
(116, 'SENAT DES ELEVEURS', NULL, NULL, NULL, '694717159', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'KENGMATIO Elvis', '694717159', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'dc22e6833891bdcb389739b5b4ad29a9d162f051', 0, 0, 0, 0, 0, 0, 0),
(117, 'Ets MBOU ET ASSOCIERS', NULL, NULL, NULL, '670633912', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MBOU Hubert', '670633912', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '3da2108a368da3285aac84526cdb31401a525e92', 0, 0, 0, 0, 0, 0, 0),
(118, 'AVICONSULTING', NULL, NULL, NULL, '694141324', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'ZOYIM COLLINS', '694141324', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '2b1db1cc1fcf465934c21e50b32d81dc411208ae', 0, 0, 0, 0, 0, 0, 0),
(119, 'SŒURS DE BABETE', NULL, NULL, NULL, '694717159', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SANDJO B. WILLY', '694717159', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '5e7b6bff5747914ac7fac2cef3fa8342ea96ae26', 0, 0, 0, 0, 0, 0, 0),
(120, 'PLANTATION TOKAM Sarl', NULL, NULL, NULL, '695569140', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'TOKAM', '695569140', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '792c28287cb21f2593a5e3474ed011a78d0d77d8', 0, 0, 0, 0, 0, 0, 0),
(121, 'GIC GFB-FRUITNAS', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NGUETCHOUE Marie Therese', '', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 46, 0, 10, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'be15123ae131127c28141b4e233e5e63ce73d8d2', 0, 0, 0, 0, 0, 0, 0),
(122, 'SOCOMANGA', 'G230932932', 'JU732983200', '71289810190', '6799988847348', 'PETITE', 'Formel', 3, 'Minimal', 100000, 500000, 3200000, 5, 0, 0, 1, 1, 0, '2025-01-13', '2025-01-22', 'info@socomanga.com', '07732989', 'Obalima Richmond', '923903299320', 'Homme', 'Secondaire', 1, '1988-03-11', 17, 2007, 0, 0, 10, 0, 1, 1, 3, 17, 107, 0, 0, '2025-01-08 12:13:51', '2025-01-08 11:13:51', 'fc6016f0508e17746fd147bf59edc93b0a535586', 0, 0, 0, 0, 0, 0, 0),
(123, 'GIC KAWTAL', NULL, NULL, NULL, '695393342', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme BATAKI PAULINE', '695393342', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '017cb18f187f1aacd82cb32070cebb6043249387', 0, 0, 0, 0, 0, 0, 0),
(124, 'ETS NATPRODUITS', NULL, NULL, NULL, '677515165', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.ABOUBAKARY YAYA', '677515165', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '0e7487bcc889428bf0207ce837740b7179ba2fad', 0, 0, 0, 0, 0, 0, 0),
(125, 'STA SARL', NULL, NULL, NULL, '697097365', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.TCHUITCHANG THIERRY', '697097365', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '34a704a1c0b8b8d9f54529d52a360b5e99fdb3ac', 0, 0, 0, 0, 0, 0, 0),
(126, 'ETS ALI MIXO', NULL, NULL, NULL, '698828208', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.ALI MARLOURY', '698828208', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '3e17733c616565c21685adc45583b2b2fc072ddc', 0, 0, 0, 0, 0, 0, 0),
(127, 'COOP DANKI KOSSAM', NULL, NULL, NULL, '697609755', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.TIDJANI AHMADOU', '697609755', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'f204b2e8b314207c8af6b7814fd3b8230362288b', 0, 0, 0, 0, 0, 0, 0),
(128, 'ETS ZAHAM', NULL, NULL, NULL, '699837627', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.ISMAILLA BABA', '699837627', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '07870952365ce74d4b12ed1db308c9e87b6ab81f', 0, 0, 0, 0, 0, 0, 0),
(129, 'ETS TANY', NULL, NULL, NULL, '691575126', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '', '691575126', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '57ecf956be0e935abf67b5f4e59d320164623944', 0, 0, 0, 0, 0, 0, 0),
(130, 'GIC AMA', NULL, NULL, NULL, '674011010', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M AHIDJIO YAOUBA', '674011010', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'cf660564bb98a88f8309942ed9b1ea1c060be471', 0, 0, 0, 0, 0, 0, 0),
(131, 'PDG SARL', NULL, NULL, NULL, '696261722', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M NANA THEOPHILE', '696261722', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', 'a04fee402ec81b788139090f62bd3f322b8f1df6', 0, 0, 0, 0, 0, 0, 0),
(132, 'GIC DES ABEILLES DU DJEREM', NULL, NULL, NULL, '651927654', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M ABBA DANIEL', '651927654', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '9ca9ccf16e204cbfae75318d2502a1ff1bc310f9', 0, 0, 0, 0, 0, 0, 0),
(133, 'ETS SANTA BARBARA', NULL, NULL, NULL, '672771663', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme HALIMA', '672771663', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '79c2724b43c76018e4f496ead3270ef2a9baefea', 0, 0, 0, 0, 0, 0, 0),
(134, 'SCOOPS LUMIERE', NULL, NULL, NULL, '697292517', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme ADAMA MYOUTH', '697292517', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '08d30316ecc89ff54ffcc9af147cb62d22dfaac5', 0, 0, 0, 0, 0, 0, 0),
(135, 'COOP HANDIKAI', NULL, NULL, NULL, '694098739', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.OUMAROU BALLA', '694098739', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '07ccc52d6182a7aa73ca709239f1a9e917d80bb8', 0, 0, 0, 0, 0, 0, 0),
(136, 'GIC DES JEUNES DE TIBATI', NULL, NULL, NULL, '699867891', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. ISSA TANKO', '699867891', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '850e63d7f8747268682b27fa3e72fe3868f996c4', 0, 0, 0, 0, 0, 0, 0),
(137, 'COOP PAYSAN DU MBERE', NULL, NULL, NULL, '699475064', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.SERNO', '699475064', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '46f3661b3cb11d049b682da218d64ccce619bf54', 0, 0, 0, 0, 0, 0, 0),
(138, 'GIC DEMA REHMA DOURA', NULL, NULL, NULL, '671250912', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.KOULAGNA', '671250912', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:46', '2025-01-05 16:09:46', '06322ac5d9755240f911e7c914d6ef46651ae35e', 0, 0, 0, 0, 0, 0, 0),
(139, 'GIC GANGAI', NULL, NULL, NULL, '676201933', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. NDOMMO JUSTIN', '676201933', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '384ff0563f2dcdfbbf37eee99e4bc99f6d804695', 0, 0, 0, 0, 0, 0, 0),
(140, 'GIC MIELCAM', NULL, NULL, NULL, '672172261', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. MBEN YONDO', '672172261', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '028b3460174aee304ec7e77d39e65c974a177d72', 0, 0, 0, 0, 0, 0, 0),
(141, 'TRANSCOLAIM SARL', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AHMADOU YAYA', '', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '3090e0260a332fb2958a08b00b0a0ae1260306e8', 0, 0, 0, 0, 0, 0, 0),
(142, 'ETS ROSY BIOCOSMESTIC', NULL, NULL, NULL, '655164321', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme MALLA ODETTE', '655164321', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '26703b1717e689c95d3e19f68d3db1c825d19894', 0, 0, 0, 0, 0, 0, 0),
(143, 'COOP PROMODEM', NULL, NULL, NULL, '672802955', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.DOUMBA PIERRE', '672802955', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '948791829c3a3f3c6972f78f5bb1049120e4fcf6', 0, 0, 0, 0, 0, 0, 0),
(144, 'COOP MIEL ANNOUR', NULL, NULL, NULL, '699852023', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.BIYA HAMIDOU', '699852023', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '8ee3831f799bdbe2f683deb3a662770ca1b8c493', 0, 0, 0, 0, 0, 0, 0),
(145, 'WALDE KOSSAM ADAMAOUA SA', NULL, NULL, NULL, '697375447', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. SOULEYMANOU', '697375447', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'cce6c63ebcefc20b574caddd8c92a8fb1ec51fff', 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `entreprises` (`id`, `name`, `rccm`, `niu`, `cnps`, `mm_phone`, `taille`, `caractere`, `forme_id`, `systeme`, `capital`, `ressources_propres`, `total_actif`, `nb_personnel`, `nb_personnel_permanent`, `nb_personnel_saisonier`, `personnel_permanent`, `personnel_saisonier`, `personnel_mixte`, `dt_creation`, `dt_start`, `email`, `phone`, `manager`, `manager_contact`, `manager_sexe`, `manager_niveau`, `manager_promoteur`, `manager_dtn`, `produit_id`, `produit_year_start`, `filiere_id`, `branche_id`, `user_id`, `gestionnaire_id`, `agence_id`, `representation_id`, `region_id`, `departement_id`, `arrondissement_id`, `village_id`, `quartier_id`, `created_at`, `updated_at`, `token`, `prospect`, `programme_id`, `parent_id`, `individual`, `producteur_id`, `verger_id`, `cooperative_id`) VALUES
(146, 'COOP MAHADI', NULL, NULL, NULL, '699468231', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M.ALIOU AOUDOU', '699468231', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'cb082fdd9033c51d8fd093d00adf4721a7a2c799', 0, 0, 0, 0, 0, 0, 0),
(147, 'SOCIETE COOPERATIVE SIMPLIFIE DE PRODUCTEURS DE CEREALES (SCOOPS MAH-MOULPA)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme ASTA YOUSSOUFA', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '1d4711a93f7d53aac7f6d65c87c986d9ac902e12', 0, 0, 0, 0, 0, 0, 0),
(148, 'ETS AMINATOU BOUBA', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme AMINATOU BOUBA', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '40f9a20a61d5e8e03bd0a9f54bed1312508c69b7', 0, 0, 0, 0, 0, 0, 0),
(149, 'JAM\'S NATURE', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme MVOGO ELISE CHRISTELLE', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '26672f43f81b86dc7506cd84ef43a17711db272e', 0, 0, 0, 0, 0, 0, 0),
(150, 'SARKI SARL (ANCIEN Ets COMPLEXE SOYA)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. MOUHAMAN SANI ABDOU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '5044d379a7366162107758b49f23b2fe6893a974', 0, 0, 0, 0, 0, 0, 0),
(151, 'BOULANGERIE GALETTE CHINOISE (AMBI)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme HABIBA ABASSI', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '73b9f38adca925b7f88146616ce8c8501664232c', 0, 0, 0, 0, 0, 0, 0),
(152, 'BOULANGERIE ETOILE de Guider', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. MOHAMADOU YOUBOU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '53cc20021fe7593e5e3eaea43e709115888d1612', 0, 0, 0, 0, 0, 0, 0),
(153, 'PAPA PEE', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. NGWA PETER NFORSI', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'e0a2128159c5fd66131b699f82101052413e3b21', 0, 0, 0, 0, 0, 0, 0),
(154, 'BOULANGERIE ARTISANALE NYAMSE NGBANGO', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. NYAMSE NGBANGO', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '36444e1a709029c70fb2f2c8f29c67fb24231da5', 0, 0, 0, 0, 0, 0, 0),
(155, 'ATELIER DE CONSTRUCTION METALLIQUE MOUCTARD Hamadjoda', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. MOUCTARD HAMADJODA', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'e1c8005330f1a8a2f32a4498f2031c08dd2a4607', 0, 0, 0, 0, 0, 0, 0),
(156, 'Ets YONGA METALIK (DJAMO GAB Daniel)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. DJAMO GAB Daniel', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'ace21538e490066b6812e537db480703d0d34b06', 0, 0, 0, 0, 0, 0, 0),
(157, 'SCOOPS FOOD AND BEVERAGE ENGINEERING', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. JERALE TCHIHEBO TCHAKONANG NGOKO', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'e47cdb47c8021830fabdbdeae203d9116ca83aa2', 0, 0, 0, 0, 0, 0, 0),
(158, 'COOP-CA SOCOOPRORAMOU', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. MOUSSA BONOU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '3c0b09fd414122a7ff03b48e2ada1006005c43b8', 0, 0, 0, 0, 0, 0, 0),
(159, 'SCOOPS BOTTE KOSSAM', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme KOULTOUMI Epse BOUBAKARY', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '6e7a90a9b865e0c7a303b77b600ba8e75e18c19d', 0, 0, 0, 0, 0, 0, 0),
(160, 'SCOOPS DESSERT', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. Solom Moise', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '0239c54b5900d2b4a58c48203c11e798866db658', 0, 0, 0, 0, 0, 0, 0),
(161, 'SOCIETE COOP-CA KAWTAL MBIDOBE YEBBAM', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme WALDARAI', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'a1e7a6b313664bd404636aedc0a528f58d692c8c', 0, 0, 0, 0, 0, 0, 0),
(162, 'BOULANGERIE DU MAYO LOUTI', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. Youssoufa MAHMOUDOU/', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'c5376135c7cecdce0b6256e0e517dffe2ff9e899', 0, 0, 0, 0, 0, 0, 0),
(163, 'RIO DOS CAMAROES SARL', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M HAYATOU HAMIDOU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'a355c22050094c7174643fcfe960244696589a0f', 0, 0, 0, 0, 0, 0, 0),
(164, 'SOTRA RICE SARL', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M IBRAHIMA MIDJAKA NASSOUROU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'c1f6b42a5e883938cb632b8895268ceda727e05f', 0, 0, 0, 0, 0, 0, 0),
(165, 'Sté COOPÉRATIVE SIMPLIFIÉE DES PRODUCTRICES DES HUILES VÉGÉTALES BRUTES (SCOOPS WAKEM-ABE)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme TCHIVED KILAGAI', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'b0821e13007fd43aa0bedcd9e451aabd7245d62a', 0, 0, 0, 0, 0, 0, 0),
(166, 'SCOOPS des ELEVEURS BARKA BODEDJI', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. BOUBAKARI MAL GONI', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '921017bb35fd0e3f86269e27922067f15f0e3fad', 0, 0, 0, 0, 0, 0, 0),
(167, 'ETS APAYE (M. HASSANA ALIM)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. HASSANA ALIM', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'c91377abd11a4abd110fc482d8c22a1d7a07c678', 0, 0, 0, 0, 0, 0, 0),
(168, 'ETS NARRAL', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. BOUBAKARI OUMAROU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'b2b1804ce7919559d4d491317a49118087cc7a63', 0, 0, 0, 0, 0, 0, 0),
(169, 'COOPERATIVE SIMPLIFIEE  DES JEUNES  PRODUCTEURS D’ARACHIDE', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. AMADOU GAMBO', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'b4937e00a4c79964a46e9060034bf60fdf1cb26a', 0, 0, 0, 0, 0, 0, 0),
(170, 'SCOOPS BARKA LESDY', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme RAMATOU SOUSANI', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '1e429929d5371127ec7979e6aa0ef140317ae878', 0, 0, 0, 0, 0, 0, 0),
(171, 'TIZI VONDOU Marc', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. TIZI VONDOU Marc', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'ecee9efb1f41467699a7b88953d82631f9a9621d', 0, 0, 0, 0, 0, 0, 0),
(172, 'BOULANGERIE PATISSERIE LYST SARL (ANCIENNE PATISSERIE LYST)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. LELE CHARLY', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '1219fbbbd4f7120e45ab062696221fe6b82ed720', 0, 0, 0, 0, 0, 0, 0),
(173, 'TRAPON', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. OUSMANOU GUEDJEOU', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '0a3f28d994062bdef86888fb3b81a049d91fce03', 0, 0, 0, 0, 0, 0, 0),
(174, 'JIMMY TECH', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. YOUASSI Jimmy Rostand', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '59ca1401f40bbaa799853657b1d2446867dba12e', 0, 0, 0, 0, 0, 0, 0),
(175, 'COOPERATIVE AVEC CONSEIL D’ADMINISTRATION DES PRODUCTEURS ORGANISES DE RIZ AMELIORE DE LAGDO (COOP-CA SOCCAPORAL)', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'M. NDEWA PIERRE', '', 'Homme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'dfaa384a3678011b570328dbbf51c57f01639522', 0, 0, 0, 0, 0, 0, 0),
(176, 'SINAI NATURE', NULL, NULL, NULL, '', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Mme NANI SONIA ERNESTINE', '', 'Femme', NULL, 1, NULL, 0, NULL, 0, 0, 14, 0, 2, 1, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '08b05a63b4edc84afc4ff77af8a90037952cc896', 0, 0, 0, 0, 0, 0, 0),
(177, 'DILIGENT AGRICLTURAL PRODUCTS', NULL, NULL, NULL, '677744991', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SUSANAHE Epse NKWANTANG', '677744991', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '36c04141ae32c41034915a71898232cfe02291f2', 0, 0, 0, 0, 0, 0, 0),
(178, 'ETS COBITER ENTERPRISE', NULL, NULL, NULL, '677578973', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AWUNG JENNET NJUKANG', '677578973', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '51f187090fb5bb60b57c196bdfc0b5389ce1fb90', 0, 0, 0, 0, 0, 0, 0),
(179, 'LAURYANEL’S ORGANIC FOODS (LOFs)', NULL, NULL, NULL, '679404732', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'UFEITUGO Laura', '679404732', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '324bf21889e923f36bf2c910a5ba784647c2dd39', 0, 0, 0, 0, 0, 0, 0),
(180, 'UNUQUE FARMER’S COOP', NULL, NULL, NULL, '677469373', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EPITIME Vallery EPIE', '677469373', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'e04859a34ee7096def698a3d930dda2099527f8e', 0, 0, 0, 0, 0, 0, 0),
(181, 'AMWEH FARMERS COOPERATIVE', NULL, NULL, NULL, '673134307', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EPINKWELLE ETONE', '673134307', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'bacf2c222bbd2fdbf95305fc7dba1974074a8007', 0, 0, 0, 0, 0, 0, 0),
(182, 'GLOBAL HAND CAMEROON', NULL, NULL, NULL, '679670064', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'EVAMBE Thompson ATRA', '679670064', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'a0f1f4589a86f574370777e75a37300e39dba655', 0, 0, 0, 0, 0, 0, 0),
(183, 'MASTER COMPANY LIMITED', NULL, NULL, NULL, '677413329', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NKWELLE Dérick MEJAME', '677413329', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'b4f0d606959dd82a71b58a7c0ccb4f463989e397', 0, 0, 0, 0, 0, 0, 0),
(184, 'NANYO ENTERPRISE', NULL, NULL, NULL, '654567222', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MEDIKI Ages', '654567222', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '2e2bfbdd5104566008fa6eda1471ed3be1cba7f7', 0, 0, 0, 0, 0, 0, 0),
(185, 'EDIBE DOMINION HUB', NULL, NULL, NULL, '674682871', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AJAH-EKUTE Brigdget EDIBE', '674682871', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '69392948366c444a78514a634746711c832b8013', 0, 0, 0, 0, 0, 0, 0),
(186, 'NEWCAM FOTRA COOP LTD', NULL, NULL, NULL, '677913829', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Marie BASAKA MBENDE', '677913829', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '88af68e6724bfcfc960b8c0184f071aead26403f', 0, 0, 0, 0, 0, 0, 0),
(187, 'NATURAL JOKO ENTERPRISE', NULL, NULL, NULL, '651419553', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'SAMA MOJOKO Mriana', '651419553', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '822cb2cb4318cf617ff6d418c7342ea6ee5de64d', 0, 0, 0, 0, 0, 0, 0),
(188, 'LEKEYA BIO LTD', NULL, NULL, NULL, '678673986', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'LEKEYA Foletia Cathérine', '678673986', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'bce66e10b1d3364060a01de01fb460ac3d8cb91e', 0, 0, 0, 0, 0, 0, 0),
(189, 'VICJES-MF-SCOOPS', NULL, NULL, NULL, '675146472', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MBEZAN NEIGAHA N.', '675146472', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '703fa528ae6bad2fabc184f07bffb1e9f92338ad', 0, 0, 0, 0, 0, 0, 0),
(190, 'CASSVITA LTD', NULL, NULL, NULL, '677581214', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'NEMBU Milton', '677581214', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', 'f9e4aae311453ac816cebd7977a34659416150a2', 0, 0, 0, 0, 0, 0, 0),
(191, 'SHEKINAH FARMS LTD', NULL, NULL, NULL, '675115712', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'PIBMENYI AMINOU PANGWOH', '675115712', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '70f0d9c24b8bd1d0c4eefde51f16ee0ef91fce14', 0, 0, 0, 0, 0, 0, 0),
(192, 'WELISANE NATURALS', NULL, NULL, NULL, '653972906', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'ENANGA IDAH', '653972906', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '0001e81956a37fb548920d20c3a7b91671696241', 0, 0, 0, 0, 0, 0, 0),
(193, 'PROGRESSIVE TRANSFORMATION COMPANY', NULL, NULL, NULL, '677869927', NULL, NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'MOKUNDU Clovis', '677869927', NULL, NULL, 1, NULL, 0, NULL, 0, 0, 38, 0, 8, 3, 0, 0, 0, 0, 0, '2025-01-05 16:09:47', '2025-01-05 16:09:47', '36b8fd0d91846c695e38c7b375acb02bbeb18698', 0, 0, 0, 0, 0, 0, 0),
(194, 'ALTA COR', '4367348734', '32623787329', '6127712981', NULL, 'MOYENNE', 'Informel', 2, 'Minimal', 34000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'contact@alta.com', '062398932', 'Yves Bembela', '6327898230', 'Homme', NULL, 1, NULL, 14, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-01-08 11:35:19', '2025-01-08 11:35:19', '02b1a44da88e1f1ba1509a5b8916e22acd3e7d17', 1, 0, 0, 0, 0, 0, 0),
(195, 'WARRYS', 'RC53272332892', 'NIU63279328039', 'CNPS53273268', '673279329', 'TRES PETITE', 'Informel', 2, 'Minimal', 500000, 700000, 1200000, 2, 0, 0, 0, 0, 1, '2017-11-12', '2018-11-12', 'contact@warrys.com', '68443909034', 'Warren ABENA', '693209032', 'Homme', 'Supérieur', 1, '1998-09-21', 70, 2017, 0, 0, 10, 0, 1, 1, 1, 4, 21, 0, 0, '2025-01-08 13:31:03', '2025-01-08 13:31:03', '2d3fadb4e7a105664a854eb846e71d39f206680e', 0, 0, 0, 0, 0, 0, 0),
(196, 'TRANSDERTO', 'R453673827382', 'N567998890', 'CNPS6723879832', NULL, 'MOYENNE', 'Formel', 3, 'Normal', 8000000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'contact@transderto.com', '693209230', 'Romeo Dika', '67438493984', 'Homme', NULL, 1, NULL, 77, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-02-12 11:18:49', '2025-01-08 13:44:47', '125daec677c9b367f85e10dfcd2bd0cf749a17d9', 1, 0, 0, 0, 0, 0, 0),
(197, 'WEROTIC', 'hjdhjds8990', 'jdkjdksdkkds', 'kdjdslklsdlds', '993289389', 'MOYENNE', 'Informel', 3, 'Minimal', 40000, 900000, 40000, 5, 0, 0, 0, 0, 1, '2024-10-16', '2024-10-16', 'contact@tester.com', '6790409340', 'Essomba clement', '065782090', 'Homme', 'Secondaire', 0, '2025-01-14', 77, 2021, 0, 0, 10, 0, 1, 1, 5, 28, 181, 0, 0, '2025-01-09 08:22:12', '2025-01-09 08:22:12', '08056a45c758651d9bf9d34b55c2f052b7894764', 0, 0, 0, 0, 0, 0, 0),
(198, 'TYUC', '58798989', '7843793489', '89348943894', NULL, 'TRES PETITE', 'Informel', 5, 'Minimal', 78000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'info@tuyc.org', '680929202', 'Warren Dulo', '673283209320', 'Homme', NULL, 1, NULL, 33, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-02-12 11:18:44', '2025-01-09 08:25:04', '51e49da53665880f92051f4e0776a77f53dc6b63', 1, 0, 0, 0, 0, 0, 0),
(201, 'Akala', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 4, 0, 0, 0, '2025-01-20 21:12:16', '2025-01-20 21:12:16', '5b447dad0f9be60dacee9471b1f6daddc19b8960', 0, 0, 0, 0, 0, 0, 0),
(202, 'Coopace', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 2, 7, 0, 0, 0, '2025-02-06 08:33:39', '2025-02-06 08:33:39', '51058c366af2babf7ca23a2d6c551202277ee9a7', 0, 0, 0, 0, 0, 0, 0),
(203, 'Franchooo', '87283239', '83298932', '3278372938', '68923898', 'PETITE', 'Informel', 6, 'Minimal', 6340000, 90000, 890000, 60, 0, 0, 0, 1, 0, '2025-02-03', '2025-02-10', 'info@franchoo.com', '658980349', 'Franck Ebanga', '683829320', 'Femme', 'Primaire', 0, '2025-02-18', 26, 2000, 0, 0, 10, 0, 1, 1, 3, 18, 96, 0, 0, '2025-02-12 09:13:16', '2025-02-12 09:13:16', '19336954077ecf6db9e42778e445a9eed8077687', 0, 0, 0, 0, 0, 0, 0),
(204, 'ETALA', '7438739843', '43743889438', '374388943', '683724439', 'MOYENNE', 'Informel', 7, 'Minimal', 7348700, 200000, 300000, 3, 0, 0, 0, 1, 0, '2007-11-12', '2009-10-20', 'contact@etala.com', '696438843', 'Wamba Normain', '684398478', 'Homme', 'Secondaire', 1, '1980-09-12', 20, 2009, 0, 0, 10, 0, 1, 1, 4, 21, 128, 0, 0, '2025-02-12 10:16:14', '2025-02-12 10:16:14', '4a9fc6fdf89d3346f368bf0303b0b20da7c87b4e', 0, 0, 0, 0, 0, 0, 0),
(205, 'MAKIT', '656843349', '7874398439349', '3523267326', '6787329893', 'PETITE', 'Formel', 1, 'Minimal', 500000, 400000, 700000, 9, 0, 0, 0, 1, 0, '2010-08-11', '2013-03-10', 'info@makit.cm', '67984398438', 'Warren elogo', '6734898943', 'Homme', 'Supérieur', 1, '1980-06-12', 15, 2017, 0, 0, 10, 0, 1, 1, 2, 11, 41, 0, 0, '2025-02-12 12:15:45', '2025-02-12 12:15:45', '6011cbfa43acc066a99176264f3f460b541b1025', 0, 0, 0, 0, 0, 0, 0),
(206, 'Instantum', '679809', '80900', '98090', '6898328', 'PETITE', 'Informel', 7, 'Minimal', 0, 0, 0, 7, 0, 0, 0, 1, 0, '2025-01-06', '2024-12-16', 'instantum@gmail.com', '6782309320', 'Kalla Massis', '68918239321', 'Homme', 'Secondaire', 0, '1975-02-12', 17, 2009, 0, 0, 10, 0, 1, 1, 3, 17, 95, 0, 0, '2025-02-13 08:19:27', '2025-02-13 08:19:27', '0ed07f208b2a61e7214467912f1157bb886c4e7d', 0, 0, 0, 0, 0, 0, 0),
(207, 'SOLOYA', '4567677', '435565', '323445678', '698676437', 'MOYENNE', 'Formel', 3, 'Minimal', 45000000, 500000, 790000, 6, 0, 0, 0, 1, 0, '2009-02-11', '2010-06-13', 'info@soloya.com', '6984563734', 'Alima Rostand', '69000999', 'Homme', 'Supérieur', 0, '1983-07-14', 27, 2008, 0, 0, 10, 0, 1, 1, 2, 11, 45, 0, 0, '2025-02-20 12:36:43', '2025-02-20 12:36:43', '112ddda5bb661c4a770e998fb310028eae420a36', 0, 0, 0, 0, 0, 0, 0),
(208, 'Wartys', '56328732', '68989999', '8567688', NULL, 'MOYENNE', 'Formel', 4, 'Normal', 60000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'wartys@gmail.com', '683404904', 'Onano Remy', '67439384943', 'Homme', NULL, 1, NULL, 63, NULL, 0, 0, 10, 0, 1, 1, 0, 0, 0, 0, 0, '2025-02-20 12:39:48', '2025-02-20 12:39:48', '3f7d94b5caa7463f4720efd38f92316c184cd230', 1, 0, 0, 0, 0, 0, 0),
(209, 'TARALA Inc.', '7329823032', '6732823989', 'G651267821', '654676887', 'PETITE', 'Formel', 4, 'Minimal', 1000000, 2000000, 3000000, 2, 0, 0, 0, 1, 0, '2020-09-11', '2021-08-23', 'contact@tralala.com', '678989898', 'Cyrille ONANA', '653537267', 'Homme', 'Supérieur', 1, '1976-07-13', 14, 2020, 0, 0, 10, 0, 1, 1, 1, 4, 21, 0, 0, '2025-02-27 09:33:30', '2025-02-27 09:33:30', '927ba5c4646480d994287f725870aa863cdc0b6e', 0, 0, 0, 0, 0, 0, 0),
(210, 'COCOA STAR', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 2, 8, 0, 0, 0, '2025-02-27 10:14:26', '2025-02-27 10:14:26', '3cd997369f8850f795954f1b38e89dd56a2b6d23', 0, 0, 0, 0, 0, 0, 0),
(211, 'GARATIS', '678e78', '561738832', '673268328', '657637267', 'PETITE', 'Informel', 7, 'Minimal', 0, 0, 0, 3, 0, 0, 0, 1, 0, '2020-04-12', '2021-06-12', 'contact@garatis.com', '665723875', 'Abena Arnaud', '69676768', 'Homme', 'Secondaire', 1, '1980-09-21', 38, 2021, 0, 0, 10, 0, 1, 1, 2, 11, 41, 0, 0, '2025-03-01 10:45:27', '2025-03-01 10:45:27', 'a441366b11766223f11258cf95eab925f0f378f5', 0, 0, 0, 0, 0, 0, 0),
(212, 'WASSA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 2, 7, 0, 0, 0, '2025-03-21 18:32:32', '2025-03-21 18:32:32', '520615e0334033297aa80bebdff7ab0a0c5aab5d', 0, 0, 0, 0, 0, 0, 0),
(213, 'WAQUOL', '5326387344', 'G62378723', 'CF8932803', '678829332', 'PETITE', 'Formel', 7, 'Normal', 0, 0, 0, 0, 3, 12, 1, 0, 0, '2010-12-12', '2012-04-15', 'info@test.com', '68849849487', 'Warren Eba\'a', '685773324', 'Homme', 'Supérieur', 1, '1998-06-11', 17, 2014, 0, 0, 10, 0, 1, 1, 4, 21, 126, 0, 0, '2025-05-07 16:01:21', '2025-05-07 15:01:21', '4751304dcaf207f57bea907e4344c4518185a610', 0, 0, 0, 1, 0, 0, 0),
(214, 'Exploitation Walissa', '567878989', '63278793288', '3623787832', '65398989', 'PETITE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 5, 14, 0, 0, 0, '2014-05-14', '2016-05-14', 'info@smt.cg', '68294039239', 'Walissa  Alfred', '6545653623', 'Homme', 'Secondaire', 1, '1976-11-12', 39, 2015, 0, 0, 10, 0, 1, 1, 3, 18, 97, 0, 0, '2025-05-07 15:46:52', '2025-05-07 15:46:52', '44326b6b1b37f1de9a2fc4de9426bb0af8bc2df1', 0, 0, 0, 1, 0, 0, 0),
(215, 'Exploitation Walissa&Fils', 'FR6328732932', '3277934894380', '437873498043', '678934893', 'MOYENNE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 2, 4, 0, 0, 0, '2012-11-12', '2017-06-06', 'info@makit.cm', '6590034', 'Walissa  Alfred', '6545653623', 'Homme', 'Primaire', 1, '1976-11-12', 15, 2014, 0, 0, 10, 0, 1, 1, 3, 18, 96, 0, 0, '2025-05-07 16:21:53', '2025-05-07 16:21:53', 'e96f33d0680a30f65696d7b0d26f8abffb00e24f', 0, 0, 0, 1, 3, 0, 0),
(216, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:09:23', '2025-06-03 14:09:23', 'a5b4b85f002812fd68276d09b500fc63f4c68081', 0, 0, 0, 0, 0, 0, 0),
(217, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:40:58', '2025-06-03 14:40:58', '74b5779166adca49fda29294bcb0cf718d4fbe7e', 0, 0, 0, 0, 0, 0, 0),
(218, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:42:01', '2025-06-03 14:42:01', '50e9bff7d3b7d38b147c333f48786fd210472262', 0, 0, 0, 0, 0, 0, 0),
(219, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:44:05', '2025-06-03 14:44:05', '42fab56f2b022cc7ca19b22ad6003a244ae0b532', 0, 0, 0, 0, 0, 0, 0),
(220, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:52:49', '2025-06-03 14:52:49', '87f055b3f0b323df0924728729b0f2abd98491e9', 0, 0, 0, 0, 0, 0, 0),
(221, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:55:31', '2025-06-03 14:55:31', 'd358299bb317a9649ab5307bfbcf22d23c7012ca', 0, 0, 0, 0, 0, 0, 0),
(222, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 1, 0, 0, 0, '2025-06-03 14:56:58', '2025-06-03 14:56:58', '8ca2a0bfd628e7fdf0714f32e10ae2c4183fae30', 0, 0, 0, 0, 0, 0, 0),
(223, 'CAPCA', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 0, 1, 1, 1, 2, 0, 0, 0, '2025-06-03 15:09:09', '2025-06-03 15:09:09', 'a1bb08dae59f1801654a0924d0a68fd72cb4f175', 0, 0, 0, 0, 0, 0, 0),
(224, 'YAT 1 - Adama  Hamad', NULL, NULL, NULL, NULL, 'TRES PETITE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Adama  Hamad', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 3, 0, 1, 1, 1, 2, 0, 13, 0, '2025-08-23 21:24:07', '2025-08-23 21:24:07', 'cfd0acb89068f1dd53069c4a12430647a221eb0f', 0, 0, 0, 1, 1, 1, 1),
(225, 'Galaxy Evodoula', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 10, 1, 1, 2, 7, 0, 0, 0, '2025-09-27 15:02:29', '2025-09-27 15:02:29', 'b945abfa9eaa7e59e0a8e13540ebdf4f865c65a8', 0, 0, 0, 0, 0, 0, 0),
(226, 'SOCACAO', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 10, 1, 1, 2, 11, 0, 0, 0, '2025-09-27 15:37:54', '2025-09-27 15:37:54', '999645cd57127aa55b398de43111957ca0128285', 0, 0, 0, 0, 0, 0, 0),
(227, 'UNICAO', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 10, 1, 1, 2, 11, 0, 0, 0, '2025-09-27 15:45:04', '2025-09-27 15:45:04', '1b67e4c50178d076aac4a683e520f7a723628b57', 0, 0, 0, 0, 0, 0, 0),
(228, 'VERGER ETW 1 - Etongue  Wilfried', NULL, NULL, NULL, NULL, 'TRES PETITE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Etongue  Wilfried', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 10, 10, 1, 1, 1, 2, 0, 13, 0, '2025-09-27 18:11:37', '2025-09-27 18:11:37', '58dd863a247e123ad1fea51c32c2fa9eed405335', 0, 0, 0, 1, 4, 6, 1),
(229, 'PLAFOPCAEEV', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:11:46', '2025-10-01 20:11:46', '57ca16bc5b298e243dabfc30f0cde32b6101bbcd', 0, 0, 0, 0, 0, 0, 0),
(230, 'PLAFOPCAEEV', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:15:06', '2025-10-01 20:15:06', '146fcbec29961ac501c924d4871772d5744cac01', 0, 0, 0, 0, 0, 0, 0),
(231, 'SOCADYC', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:20:59', '2025-10-01 20:20:59', '998b7cc803760907b69f71553a69342295802108', 0, 0, 0, 0, 0, 0, 0),
(232, 'COPRAEC', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:22:31', '2025-10-01 20:22:31', '183e02ca1eb254011938214c439a67e1324e0b26', 0, 0, 0, 0, 0, 0, 0),
(233, 'SOCOPLANEC', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:24:31', '2025-10-01 20:24:31', '1de4bbf488d7276c5e0c2f19efc82954009e9e0b', 0, 0, 0, 0, 0, 0, 0),
(234, 'SOCOOPAC', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:26:55', '2025-10-01 20:26:55', '07882b68297ba2f4f8e2bcad4eb91b2fbfea57c6', 0, 0, 0, 0, 0, 0, 0),
(235, 'SOCOPE', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:28:42', '2025-10-01 20:28:42', '32a66d7bba6b2c9cb63c0af146da8120526d5e35', 0, 0, 0, 0, 0, 0, 0),
(236, 'MGBABANGOISE', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 0, 0, '2025-10-01 20:30:32', '2025-10-01 20:30:32', '5764313b4609bb2a5462639768ed5858d451e010', 0, 0, 0, 0, 0, 0, 0),
(237, 'VERGER AMB1 - AMBOMO   Laurentine', NULL, NULL, NULL, NULL, 'TRES PETITE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'AMBOMO   Laurentine', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 11, 0, 5, 0, '2025-10-08 11:08:15', '2025-10-08 11:08:15', '8d6791adb4fda195c12f61a588d7dc8460cdf15a', 0, 0, 0, 1, 203, 1, 7),
(238, 'XYZ SARL', 'RCCM', 'M 223000489A', '10001', '674001221', 'MOYENNE', 'Formel', 1, 'Normal', 1000000, 200000, 300000, 5, 0, 0, 1, 0, 0, '2025-12-01', '2025-12-01', 'XYZ@gmail.com', '675009815', 'NDJANA LUC', '6568 123 220', 'Homme', 'Supérieur', 1, '1989-12-10', 477, 3, 0, 0, 61, 61, 4, 0, 1, 4, 7, 0, 0, '2025-11-18 15:02:07', '2025-11-18 15:02:07', '70fca940c8c49eec7e6ecd582d11521380a368a0', 0, 0, 0, 0, 0, 0, 0),
(239, 'XYZ SARL', 'RCCM', 'M 223000489A', '10001', '674001221', 'MOYENNE', 'Formel', 1, 'Normal', 1000000, 200000, 300000, 5, 0, 0, 1, 0, 0, '2025-12-01', '2025-12-01', 'XYZ@gmail.com', '675009815', 'NDJANA LUC', '6568 123 220', 'Homme', 'Supérieur', 1, '1989-12-10', 40, 3, 0, 0, 61, 61, 4, 0, 1, 4, 7, 0, 0, '2025-11-18 15:06:42', '2025-11-18 15:06:42', '81299e2576704ee938e7cdb12cecf9a31f50e52d', 0, 0, 0, 0, 0, 0, 0),
(240, 'scoops Emergence de NTUI', NULL, NULL, NULL, NULL, 'COOPERATIVE', NULL, 0, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 14, 0, 0, 0, '2025-11-18 16:12:55', '2025-11-18 16:12:55', 'e2c7dc473141b65b988d5b7d8f642e25736bbb5d', 0, 0, 0, 0, 0, 0, 0),
(241, 'ETS DECOR', 'RCCM', 'P12300023444', '657223338', '675 33 67 90', 'TRES PETITE', 'Formel', 2, 'Minimal', 100000, 40000, 50000, 0, 1, 3, 0, 0, 0, '2024-12-03', '2024-12-03', 'emile@gmail.com', '657 87 98 80', 'OMBOLO JEAN', '678 34 6790', 'Homme', 'Secondaire', 1, '1980-01-10', 22, 3, 0, 0, 10, 0, 1, 1, 2, 8, 31, 0, 0, '2025-11-18 17:47:49', '2025-11-18 17:47:49', '33d94d6db1eb435d6ef7c61ab7051671b33415ef', 0, 0, 0, 1, 0, 0, 0),
(242, 'ABM SARL', 'RCCM', 'M 3330000456', '678893432', '698674532', 'PETITE', 'Formel', 1, 'Minimal', 200000, 50000, 100000, 5, 0, 0, 1, 0, 0, '2023-12-01', '2023-12-01', 'clement@gmail.com', '656 7758 29', 'EMBOLO CLEMENT', '656 00 45 36', 'Homme', 'Supérieur', 1, '1987-02-03', 17, 4, 0, 0, 10, 10, 1, 1, 5, 26, 172, 0, 0, '2025-11-18 18:08:46', '2026-03-19 08:21:34', 'c988b397a0107081c111a34fa9ffea7b82a3854b', 0, 0, 0, 0, 0, 0, 0),
(243, 'WASSY verger 1 - Wassy  Aline', NULL, NULL, NULL, NULL, 'TRES PETITE', 'Informel', 7, 'Normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'Wassy  Aline', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, 0, 61, 61, 4, 0, 2, 14, 0, 22, 0, '2026-02-12 00:07:41', '2026-02-12 00:07:41', '55e80ba36f10e6d6c2479f032ba286d2e87c9316', 0, 0, 0, 1, 252, 2, 8),
(244, 'TABI & FILS', 'RC/YAO/A/2019', 'P011912354786', 'NI785222', '681582099', 'PETITE', 'Formel', 1, 'Normal', 1000000, 100000000, 52000000, 8, 0, 0, 0, 0, 1, '2019-01-25', '2019-06-25', 'armandkouadjo@gmail.com', '681582099', 'KOUADJO ARMAND', '682502099', 'Homme', 'Supérieur', 1, '1974-02-15', 158, 7, 0, 0, 107, 107, 1, 0, 2, 14, 56, 0, 0, '2026-03-19 13:42:31', '2026-03-19 13:42:31', '1c7170abf7abe641dac9a3e6dc73de0fdb07823f', 0, 0, 0, 0, 0, 0, 0),
(245, 'Ets GALLAH', 'CM-2022-B-125', 'M0125378945', '2130089', '699883355', 'TRES PETITE', 'Formel', 2, 'Minimal', 100000, 50000, 450000, 2, 0, 0, 1, 0, 0, '2025-04-04', '2024-03-19', NULL, '688335577', 'Josée Yvan', '688335566', 'Homme', 'Secondaire', 1, '1979-03-19', 3, 10, 0, 0, 105, 105, 1, 0, 2, 11, 45, 0, 0, '2026-03-19 13:44:50', '2026-03-19 13:48:21', '525f1a2386fe58d3bcae59c264da4c86edcbdfb7', 0, 0, 0, 0, 0, 0, 0),
(246, 'RANCH MOISE ET FILS', 'RC/YAO/2020', 'P011912354788', 'NI785223', '688558899', 'GRANDE', 'Formel', 4, 'Normal', 100000000, 15000000, 45000000, 100, 0, 0, 0, 0, 1, '2030-01-01', '2010-01-20', NULL, '655998855', 'MOUAFFO MOISE', '699558899', 'Homme', 'Supérieur', 1, '1885-05-22', 51, 10, 0, 0, 105, 105, 1, 0, 1, 4, 6, 0, 0, '2026-03-23 15:42:31', '2026-03-23 15:47:58', '5f0783d1a88075a73f46499dfac138c9e035477a', 0, 0, 0, 0, 0, 0, 0),
(247, 'GIC DES ELEVEURS, AGRICULTEURS ET PISCICULTEURS D\'ATING BANE (GIC NTOBO-NNAM)', 'SU/GP/03/06/0321', 'M080616698485C', 'M080616698485C', '698498982', 'COOPERATIVE', 'Formel', 4, 'Minimal', 100000, 23000000, 1000000, 4, 0, 0, 0, 0, 1, '2006-08-21', '2006-08-21', 'TANGUEN@GMAIL.COM', '699784162', 'NZUNO', '699784162', 'Homme', 'Secondaire', 1, '1957-05-02', 94, 20, 0, 0, 105, 105, 1, 0, 9, 52, 309, 0, 0, '2026-03-24 09:28:46', '2026-03-24 09:28:46', 'a2e24e4b608f47e46fed3377770aad8726e020f5', 0, 0, 0, 0, 0, 0, 0),
(248, 'CAPEF', 'YDE/02/03/2025', 'M080812565486', 'M08280668225', NULL, 'PETITE', 'Formel', 5, 'Normal', 1000000, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'CAPEEF@YAHOO.COM', '655998899', 'WELISANE', '633569986', 'Femme', NULL, 1, NULL, 97, NULL, 0, 0, 105, 0, 1, 0, 0, 0, 0, 0, 0, '2026-03-24 09:40:32', '2026-03-24 09:40:32', '9f1df28315ce236b7ec10e459ee562b4b041f83d', 1, 0, 0, 0, 0, 0, 0),
(249, 'Ets tantapion', 'RC/YAO/2020', 'M080616698485C', 'NI785222', '687459210', 'TRES PETITE', 'Formel', 5, 'Normal', 100000000, 200000, 150000, 5, 0, 0, 0, 0, 1, '2012-12-10', '2010-05-12', 'fabien.tabi@angara.cm', '65566989', 'NZUNO', '012345678 / 658974102', 'Homme', 'Primaire', 1, '1983-12-04', 17, NULL, 0, 0, 107, 107, 1, 0, 2, 10, 81, 0, 0, '2026-03-24 14:52:28', '2026-03-24 14:52:28', '8abd75771364a890781fbb57e1746e7964c083bd', 0, 0, 0, 0, 0, 0, 0),
(250, 'SAAGRY SA', 'RC/DLA/2008/M/1849', 'M099800017199X', '11111111111', '699910196', 'MOYENNE', 'Formel', 5, 'Normal', 50000000, 60000000, 150000000, 50, 0, 0, 1, 0, 0, '1998-09-09', '1998-09-30', 'saagry@yahoo.fr', '699910196', 'YOUSSA ANSELME', '699910196', 'Homme', 'Supérieur', 1, '1965-04-22', 16, NULL, 0, 0, 123, 123, 1, 0, 1, 3, 5, 0, 0, '2026-03-24 15:29:41', '2026-03-24 15:29:41', '29add7c740c113b2ee037cc543e81f0c6b1d4f81', 0, 0, 0, 0, 0, 0, 0),
(251, 'SAAGRY SA', 'RC/DLA/2008/M/1849', 'M099800017199X', '11111111111', '699910196', 'MOYENNE', 'Formel', 5, 'Normal', 5000000, 60000000, 150000000, 50, 0, 0, 1, 0, 0, '1998-09-09', '1998-09-30', 'saagry@yahoo.fr', '699910196', 'YOUSSA ANSELME', '699910196', 'Homme', 'Supérieur', 1, '1965-04-22', 16, NULL, 0, 0, 123, 123, 1, 0, 1, 3, 5, 0, 0, '2026-03-24 15:48:00', '2026-03-24 15:48:00', 'fc467f77c4dd32d8be015af663abf856e8bc9a33', 0, 0, 0, 0, 0, 0, 0),
(252, 'ETS ZE ET FILS', 'RC/2015/00/DLA/00125', '12458963254', '120254', '681582004', 'PETITE', 'Formel', 2, 'Normal', 1000000, 5000000, 10000000, 6, 0, 0, 1, 0, 0, '2020-03-12', '2021-05-05', 'zeblaise@yahoo.fr', '681582004', 'Roland ZE', '681582004', 'Homme', 'Supérieur', 1, '1985-03-12', 1, 5, 0, 0, 121, 121, 1, 0, 1, 3, 5, 0, 0, '2026-03-24 18:32:17', '2026-03-24 18:32:17', 'd4deb5f5d2e5acfb97f8b6f984baf1483077d2d7', 0, 0, 0, 0, 0, 0, 0),
(253, 'SAAGRY SA', 'RC/DLA/2008/M/1849', 'M099800017199X', '11111111111', '699910196', 'MOYENNE', 'Formel', 5, 'Normal', 5000000, 60000000, 150000000, 50, 0, 0, 1, 0, 0, '1998-09-09', '1998-09-30', 'saagry@yahoo.fr', '699910196', 'YOUSSA ANSELME', '699910196', 'Homme', 'Supérieur', 1, '1965-04-22', 16, 28, 0, 0, 123, 123, 1, 0, 1, 3, 5, 0, 0, '2026-03-25 08:34:31', '2026-03-25 08:34:31', 'dde38d1433e82aec43995540fa9f79a7e7386356', 0, 0, 0, 0, 0, 0, 0),
(254, 'BIO AGROFARM SARL', 'CM/NSI/01/2025/B12/', 'M072517858914Q', 'M072517858914', '699735068', 'PETITE', 'Formel', 1, 'Normal', 999999, 400000, 500000, 3, 0, 0, 1, 0, 0, '2025-07-08', '2026-01-01', 'tmelandi@gmail.com', '699735068', 'MENGANG OBAMA EPSE ELANDI THERESE MARIE', '699735068', 'Femme', 'Supérieur', 1, '1979-10-23', 1, 1, 0, 0, 105, 105, 1, 0, 2, 15, 84, 0, 0, '2026-03-25 13:00:28', '2026-03-25 13:00:28', '905a1d836c46194698be2fe37efd7c34984e5fcc', 0, 0, 0, 0, 0, 0, 0),
(255, 'ETS LES COCCINELLES', 'RC/YAO/2023/B/72', 'P115236874', '215', '655124787', 'PETITE', 'Formel', 2, 'Minimal', 1000000, 500000, 800000, 3, 0, 0, 1, 0, 1, '2024-02-05', '2024-03-10', 'LEWAMBY@GMAIL.COM', '658741231', 'LE WAMBY', '655124787', 'Femme', 'Secondaire', 1, '2004-03-08', 4, 1, 0, 0, 115, 115, 1, 0, 1, 1, 2, 0, 0, '2026-03-25 14:17:32', '2026-03-25 14:33:41', 'd2ae593e669e3ae9c2dfb7912378af7042e91609', 0, 0, 0, 0, 0, 0, 0),
(256, 'OLIVE OIL', 'RC/DLA/5478/M/87', 'M65810247', '654', '655827415', 'MOYENNE', 'Formel', 1, 'Normal', 50000000, 25000000, 87000000, 25, 0, 0, 0, 0, 1, '2000-06-25', '2000-10-25', 'OLIVE54@GMAIL.COM', '695874121', 'OUMBETTI LEON', '655254874', 'Homme', 'Supérieur', 1, '1985-12-01', 197, 25, 0, 0, 115, 115, 1, 0, 1, 3, 12, 0, 0, '2026-03-25 14:59:36', '2026-03-25 14:59:36', 'aba8beff0ee63908e8d75b69916fdd37fc438412', 0, 0, 0, 0, 0, 0, 0),
(257, 'EXTRAIT VEGETAL SARL', 'RC/DLN/2020/B/283', 'M022014402364F', '10000000001', '677934803', 'PETITE', 'Formel', 1, 'Normal', 1, 1000000, 46000000, 3, 0, 0, 1, 0, 0, '2020-02-05', '2020-02-10', 'extraitvegetal@gmail.com', '691730707', 'NDOUNGUE KITIO', '699696940', 'Femme', 'Supérieur', 1, '1979-01-16', 24, 6, 0, 0, 125, 125, 1, 0, 1, 3, 5, 0, 0, '2026-03-25 16:23:15', '2026-03-25 16:23:15', '6985cafad840e12a06f35fdb0d72feca8e80c42f', 0, 0, 0, 0, 0, 0, 0),
(258, 'ZAALING TRADING AND SERVICES SARL', 'CM/DLA/03/2025/B12', 'M112518183915R', '123000000', '698762175', 'TRES PETITE', 'Formel', 1, 'Normal', 950000, 7000000, 10000000, 6, 0, 0, 1, 0, 0, '2025-11-13', '2026-01-22', 'zaalingtrading@gmail.com', '698762175', 'NGOUCHEME ZAKARI ALI', '698762175', 'Homme', 'Supérieur', 1, '1992-07-24', 1, 1, 0, 0, 123, 123, 1, 0, 8, 47, 286, 0, 0, '2026-03-26 09:32:55', '2026-03-26 09:32:55', 'adbfce0c65c8bf10bf48bae99a1fc70ab276b8aa', 0, 0, 0, 0, 0, 0, 0),
(259, 'LABEL&CO', 'RC/YAO/OO1', 'NUI000RRRRRP', '3245001', '655022204', 'PETITE', 'Formel', 2, 'Normal', 10000000, 5000000, 12000000, 5, 0, 0, 1, 0, 0, '2023-12-02', '2021-04-04', 'kemdem.@yahoo.fr', '652000201', 'KAMDEM MANUEL', '65500203', 'Homme', 'Secondaire', 1, '1985-12-03', 7, 6, 0, 0, 115, 115, 1, 0, 2, 13, 37, 0, 0, '2026-03-30 09:38:40', '2026-03-30 09:38:40', '48a1d27783a5f386f021b6071d3471efe90fee6e', 0, 0, 0, 0, 0, 0, 0),
(260, 'STE D\'INOVATION AGRO PASTORALE', 'RC/DLN/2021/B801', 'M032116337993H', '1003624404', '695753518', 'TRES PETITE', 'Formel', 1, 'Normal', 1000000, 7000000, 12000000, 12, 0, 0, 0, 0, 1, '2021-03-03', '2021-03-30', 'ebwellegladys@gmail.com', '695753518', 'DIBANGA EBWELE GLADYS', '695753518', 'Femme', 'Supérieur', 1, '1986-06-20', 60, 5, 0, 0, 121, 121, 1, 0, 5, 29, 204, 0, 0, '2026-03-31 09:48:03', '2026-03-31 09:48:03', '2e7ee4426ab56c8d6ddf3836b013f38ca2639297', 0, 0, 0, 0, 0, 0, 0);

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
(1, 203, 3, 'elements_constitutifs/cd9e350463df49ddd2c123091f0105a5bc49a82a.pdf', 1, NULL, NULL, 'cd9e350463df49ddd2c123091f0105a5bc49a82a'),
(2, 206, 2, 'elements_constitutifs/e91e900d682328c8c7a4e2318f5a96c35cb6e807.pdf', 1, NULL, NULL, 'e91e900d682328c8c7a4e2318f5a96c35cb6e807'),
(3, 247, 10, NULL, 1, NULL, NULL, '8f11cd7fc55c67a3e9bae524c93dae7d3fe85a78'),
(4, 247, 1, NULL, 1, NULL, NULL, '1664aad8402b29e6d1a54ec3478f5ecc83c9dc40'),
(5, 247, 0, NULL, 1, NULL, NULL, 'e13ab96417770c3600679b5ec4299f87098aaee3'),
(6, 247, 2, NULL, 1, NULL, NULL, 'dda7c236f19a3f648d4efc72a4159b70dde48025');

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
(1, 5, 17, 1),
(2, 5, 20, 1),
(3, 5, 22, 1),
(4, 5, 5, 1),
(5, 5, 8, 1),
(6, 5, 2, 1),
(7, 5, 1, 1),
(8, 6, 17, 1),
(9, 6, 21, 1),
(10, 6, 7, 1),
(11, 6, 11, 1),
(12, 8, 17, 1),
(13, 8, 20, 1),
(14, 8, 5, 1),
(15, 8, 9, 1),
(16, 10, 19, 1),
(17, 10, 23, 1),
(18, 10, 9, 1),
(19, 11, 17, 1),
(20, 11, 20, 1),
(21, 11, 2, 1),
(22, 11, 4, 1),
(23, 122, 15, 1),
(24, 122, 12, 1),
(25, 195, 16, 1),
(26, 195, 25, 1),
(27, 195, 28, 1),
(28, 195, 31, 1),
(29, 195, 2, 1),
(30, 195, 8, 1),
(31, 195, 9, 1),
(32, 195, 30, 1),
(33, 197, 16, 1),
(34, 197, 22, 1),
(35, 197, 28, 1),
(36, 197, 4, 1),
(37, 197, 8, 1),
(38, 197, 10, 1),
(39, 197, 5, 1),
(40, 195, 17, 1),
(41, 203, 15, 1),
(42, 203, 20, 1),
(43, 203, 24, 1),
(44, 203, 3, 1),
(45, 203, 7, 1),
(46, 203, 11, 1),
(47, 204, 16, 1),
(48, 204, 19, 1),
(49, 204, 20, 1),
(50, 204, 2, 1),
(51, 204, 9, 1),
(52, 205, 16, 1),
(53, 205, 25, 1),
(54, 205, 9, 1),
(55, 205, 12, 1),
(56, 206, 15, 1),
(57, 206, 18, 1),
(58, 206, 20, 1),
(59, 206, 16, 1),
(60, 206, 2, 1),
(61, 206, 10, 1),
(62, 206, 12, 1),
(63, 207, 17, 1),
(64, 207, 20, 1),
(65, 207, 22, 1),
(66, 207, 3, 1),
(67, 207, 10, 1),
(68, 207, 12, 1),
(69, 209, 17, 1),
(70, 209, 19, 1),
(71, 209, 22, 1),
(72, 209, 3, 1),
(73, 209, 8, 1),
(74, 211, 18, 1),
(75, 211, 20, 1),
(76, 211, 5, 1),
(77, 211, 3, 1),
(78, 211, 7, 1),
(79, 213, 15, 1),
(80, 213, 17, 1),
(81, 213, 18, 1),
(82, 213, 12, 1),
(83, 213, 3, 1),
(84, 214, 15, 1),
(85, 214, 18, 1),
(86, 214, 19, 1),
(87, 214, 5, 1),
(88, 214, 7, 1),
(89, 215, 15, 1),
(90, 215, 17, 1),
(91, 215, 19, 1),
(92, 215, 2, 1),
(93, 215, 4, 1),
(94, 238, 15, 1),
(95, 238, 9, 1),
(96, 239, 15, 1),
(97, 239, 8, 1),
(98, 241, 19, 1),
(99, 241, 2, 1),
(100, 242, 15, 1),
(101, 242, 2, 1),
(102, 240, 1, 1),
(103, 244, 17, 1),
(104, 244, 3, 1),
(105, 245, 17, 1),
(106, 246, 15, 1),
(107, 246, 1, 1),
(108, 247, 15, 1),
(109, 247, 14, 1),
(110, 247, 17, 1),
(111, 247, 18, 1),
(112, 247, 19, 1),
(113, 247, 20, 1),
(114, 247, 21, 1),
(115, 247, 28, 1),
(116, 247, 29, 1),
(117, 247, 2, 1),
(118, 247, 13, 1),
(119, 249, 18, 1),
(120, 249, 22, 1),
(121, 249, 20, 1),
(122, 249, 4, 1),
(123, 249, 3, 1),
(124, 250, 15, 1),
(125, 250, 4, 1),
(126, 251, 14, 1),
(127, 251, 4, 1),
(128, 252, 14, 1),
(129, 252, 2, 1),
(130, 254, 14, 1),
(131, 255, 14, 1),
(132, 255, 11, 1),
(133, 256, 15, 1),
(134, 256, 4, 1),
(135, 256, 3, 1),
(136, 257, 14, 1),
(137, 257, 2, 1),
(138, 258, 14, 1),
(139, 258, 4, 1),
(140, 259, 14, 1),
(141, 259, 15, 1),
(142, 259, 18, 1),
(143, 259, 19, 1),
(144, 259, 20, 1),
(145, 259, 5, 1),
(146, 260, 14, 1),
(147, 260, 17, 1),
(148, 260, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_evaluation_profiles`
--

CREATE TABLE `entreprise_evaluation_profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `entreprise_id` int UNSIGNED NOT NULL,
  `gestionnaire_id` bigint UNSIGNED DEFAULT NULL,
  `agence_id` bigint UNSIGNED DEFAULT NULL,
  `reference_framework` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_sector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_subsector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annual_turnover` decimal(15,2) DEFAULT NULL,
  `annual_turnover_year` int DEFAULT NULL,
  `net_income` decimal(15,2) DEFAULT NULL,
  `balance_sheet_total` decimal(15,2) DEFAULT NULL,
  `employees_total` int NOT NULL DEFAULT '0',
  `employees_permanent` int NOT NULL DEFAULT '0',
  `employees_temporary` int NOT NULL DEFAULT '0',
  `env_policy` tinyint(1) NOT NULL DEFAULT '0',
  `env_certified` tinyint(1) NOT NULL DEFAULT '0',
  `env_certification_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uses_renewable_energy` tinyint(1) NOT NULL DEFAULT '0',
  `renewable_energy_ratio` decimal(5,2) NOT NULL DEFAULT '0.00',
  `waste_management_system` tinyint(1) NOT NULL DEFAULT '0',
  `water_management_system` tinyint(1) NOT NULL DEFAULT '0',
  `carbon_measurement` tinyint(1) NOT NULL DEFAULT '0',
  `estimated_co2_emission` decimal(15,2) NOT NULL DEFAULT '0.00',
  `climate_risk_exposure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `climate_adaptation_strategy` tinyint(1) NOT NULL DEFAULT '0',
  `women_led` tinyint(1) NOT NULL DEFAULT '0',
  `youth_led` tinyint(1) NOT NULL DEFAULT '0',
  `nb_women` int NOT NULL DEFAULT '0',
  `nb_youth` int NOT NULL DEFAULT '0',
  `nb_disabled` int NOT NULL DEFAULT '0',
  `inclusive_business` tinyint(1) NOT NULL DEFAULT '0',
  `social_protection` tinyint(1) NOT NULL DEFAULT '0',
  `training_program` tinyint(1) NOT NULL DEFAULT '0',
  `community_impact` tinyint(1) NOT NULL DEFAULT '0',
  `community_impact_description` text COLLATE utf8mb4_unicode_ci,
  `has_board` tinyint(1) NOT NULL DEFAULT '0',
  `board_size` int NOT NULL DEFAULT '0',
  `financial_statements_available` tinyint(1) NOT NULL DEFAULT '0',
  `audited_financials` tinyint(1) NOT NULL DEFAULT '0',
  `anti_corruption_policy` tinyint(1) NOT NULL DEFAULT '0',
  `esg_policy` tinyint(1) NOT NULL DEFAULT '0',
  `legal_compliance` tinyint(1) NOT NULL DEFAULT '1',
  `tax_compliance` tinyint(1) NOT NULL DEFAULT '1',
  `digital_accounting` tinyint(1) NOT NULL DEFAULT '0',
  `erp_system` tinyint(1) NOT NULL DEFAULT '0',
  `kyc_completed` tinyint(1) NOT NULL DEFAULT '0',
  `aml_check` tinyint(1) NOT NULL DEFAULT '0',
  `sanction_screening` tinyint(1) NOT NULL DEFAULT '0',
  `pep_check` tinyint(1) NOT NULL DEFAULT '0',
  `seeking_investment` tinyint(1) NOT NULL DEFAULT '0',
  `investment_needed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `investment_stage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_plan_available` tinyint(1) NOT NULL DEFAULT '0',
  `previous_grants` tinyint(1) NOT NULL DEFAULT '0',
  `grant_amount_received` decimal(15,2) NOT NULL DEFAULT '0.00',
  `outstanding_loans` decimal(15,2) NOT NULL DEFAULT '0.00',
  `export_potential` tinyint(1) NOT NULL DEFAULT '0',
  `local_value_chain` tinyint(1) NOT NULL DEFAULT '0',
  `agri_value_chain` tinyint(1) NOT NULL DEFAULT '0',
  `digital_economy` tinyint(1) NOT NULL DEFAULT '0',
  `green_economy` tinyint(1) NOT NULL DEFAULT '0',
  `sdg_alignment` json DEFAULT NULL,
  `last_updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise_evaluation_profiles`
--

INSERT INTO `entreprise_evaluation_profiles` (`id`, `entreprise_id`, `gestionnaire_id`, `agence_id`, `reference_framework`, `business_sector`, `business_subsector`, `annual_turnover`, `annual_turnover_year`, `net_income`, `balance_sheet_total`, `employees_total`, `employees_permanent`, `employees_temporary`, `env_policy`, `env_certified`, `env_certification_type`, `uses_renewable_energy`, `renewable_energy_ratio`, `waste_management_system`, `water_management_system`, `carbon_measurement`, `estimated_co2_emission`, `climate_risk_exposure`, `climate_adaptation_strategy`, `women_led`, `youth_led`, `nb_women`, `nb_youth`, `nb_disabled`, `inclusive_business`, `social_protection`, `training_program`, `community_impact`, `community_impact_description`, `has_board`, `board_size`, `financial_statements_available`, `audited_financials`, `anti_corruption_policy`, `esg_policy`, `legal_compliance`, `tax_compliance`, `digital_accounting`, `erp_system`, `kyc_completed`, `aml_check`, `sanction_screening`, `pep_check`, `seeking_investment`, `investment_needed`, `investment_stage`, `business_plan_available`, `previous_grants`, `grant_amount_received`, `outstanding_loans`, `export_potential`, `local_value_chain`, `agri_value_chain`, `digital_economy`, `green_economy`, `sdg_alignment`, `last_updated_by`, `created_at`, `updated_at`) VALUES
(1, 242, 10, 1, NULL, '60', NULL, '45.00', NULL, NULL, NULL, 9, 0, 0, 1, 0, NULL, 0, '0.00', 0, 0, 0, '0.00', NULL, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, '0.00', NULL, 0, 0, '0.00', '0.00', 0, 0, 0, 0, 0, NULL, 10, '2026-03-19 08:48:24', '2026-03-19 08:48:24');

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
(1, 5, 92, 2),
(2, 5, 113, 2),
(3, 5, 114, 2),
(4, 6, 36, 2),
(5, 6, 58, 2),
(6, 8, 18, 2),
(7, 8, 16, 2),
(8, 10, 15, 2),
(9, 10, 53, 2),
(10, 10, 83, 2),
(11, 11, 37, 2),
(12, 11, 38, 2),
(13, 195, 39, 2),
(14, 195, 68, 2),
(15, 197, 60, 2),
(16, 197, 93, 2),
(17, 203, 17, 2),
(18, 203, 26, 2),
(19, 203, 29, 2),
(20, 204, 17, 2),
(21, 204, 25, 2),
(22, 205, 53, 2),
(23, 205, 70, 2),
(24, 206, 14, 2),
(25, 206, 52, 2),
(26, 206, 82, 2),
(27, 206, 81, 2),
(28, 207, 9, 2),
(29, 207, 11, 2),
(30, 207, 38, 2),
(31, 209, 20, 2),
(32, 209, 37, 2),
(33, 209, 38, 2),
(34, 211, 52, 2),
(35, 211, 70, 2),
(36, 213, 9, 2),
(37, 213, 11, 2),
(38, 214, 15, 2),
(39, 214, 23, 2),
(40, 215, 7, 2),
(41, 215, 18, 2),
(42, 215, 40, 2),
(43, 239, 13, 2),
(44, 241, 1, 2),
(45, 242, 1, 2),
(46, 244, 61, 2),
(47, 244, 54, 2),
(48, 244, 57, 2),
(49, 245, 8, 2),
(50, 245, 12, 2),
(51, 245, 62, 2),
(52, 246, 1, 2),
(53, 249, 1, 2),
(54, 249, 18, 2),
(55, 249, 66, 2),
(56, 250, 13, 2),
(57, 251, 16, 2),
(58, 252, 51, 2),
(59, 253, 16, 2),
(60, 255, 1, 2),
(61, 255, 3, 2),
(62, 256, 199, 2),
(63, 257, 214, 2),
(64, 258, 3, 2),
(65, 259, 16, 2),
(66, 259, 17, 2),
(67, 259, 14, 2),
(68, 260, 24, 2);

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
-- Structure de la table `evaluation_categories`
--

CREATE TABLE `evaluation_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `framework_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `weight` decimal(10,2) NOT NULL DEFAULT '1.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluation_categories`
--

INSERT INTO `evaluation_categories` (`id`, `framework_id`, `name`, `code`, `description`, `weight`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Environnement', 'environmental', NULL, '1.00', 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(2, 1, 'Social', 'social', NULL, '1.00', 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(3, 1, 'Gouvernance', 'governance', NULL, '1.00', 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(4, 1, 'Financier', 'financial', NULL, '1.00', 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(5, 1, 'Conformité', 'compliance', NULL, '1.00', 1, 5, '2026-03-19 08:47:26', '2026-03-19 08:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_frameworks`
--

CREATE TABLE `evaluation_frameworks` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `applies_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluation_frameworks`
--

INSERT INTO `evaluation_frameworks` (`id`, `name`, `code`, `description`, `is_active`, `applies_to`, `created_at`, `updated_at`) VALUES
(1, 'Référentiel ESG ANGARA', 'esg-angara', 'Référentiel d\'évaluation ESG pour les PME et coopératives', 1, 'pme,cooperatives', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(2, 'Critères d\'éligibilité instruments européens', 'eib-eligibility', 'Critères pour l\'éligibilité Blending, Guarantee et Global Gateway', 1, 'eib', '2026-03-19 08:47:26', '2026-03-19 08:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_indicators`
--

CREATE TABLE `evaluation_indicators` (
  `id` bigint UNSIGNED NOT NULL,
  `framework_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `input_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_weight` decimal(10,2) NOT NULL DEFAULT '1.00',
  `min_score` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_score` decimal(10,2) NOT NULL DEFAULT '100.00',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluation_indicators`
--

INSERT INTO `evaluation_indicators` (`id`, `framework_id`, `category_id`, `name`, `code`, `description`, `input_type`, `score_type`, `default_weight`, `min_score`, `max_score`, `required`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Politique environnementale', 'env_policy', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(2, NULL, 1, 'Certification environnementale', 'env_certification', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(3, NULL, 1, 'Utilisation d\'énergies renouvelables', 'renewable_energy', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(4, NULL, 1, 'Mesure de l\'empreinte carbone', 'carbon_footprint', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(5, NULL, 2, 'Dirigée par des femmes', 'women_led', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(6, NULL, 2, 'Dirigée par des jeunes', 'youth_led', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(7, NULL, 2, 'Protection sociale des employés', 'social_protection', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(8, NULL, 2, 'Programme de formation', 'training_program', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(9, NULL, 3, 'Conseil d\'administration', 'has_board', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(10, NULL, 3, 'États financiers audités', 'audited_financials', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(11, NULL, 3, 'Politique anti-corruption', 'anti_corruption', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(12, NULL, 3, 'Politique ESG formalisée', 'esg_policy', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(13, NULL, 4, 'États financiers disponibles', 'financial_statements', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(14, NULL, 4, 'Comptabilité digitale', 'digital_accounting', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(15, NULL, 5, 'KYC complété', 'kyc_completed', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(16, NULL, 5, 'Vérification AML', 'aml_check', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(17, NULL, 5, 'Conformité légale', 'legal_compliance', NULL, 'boolean', NULL, '1.00', '0.00', '100.00', 0, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_score_thresholds`
--

CREATE TABLE `evaluation_score_thresholds` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `threshold_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_value` decimal(10,2) NOT NULL DEFAULT '100.00',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluation_score_thresholds`
--

INSERT INTO `evaluation_score_thresholds` (`id`, `name`, `threshold_type`, `label`, `min_value`, `max_value`, `color`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Risque faible', 'risk', 'Faible', '70.00', '100.00', '#28a745', NULL, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(2, 'Risque modéré', 'risk', 'Modéré', '50.00', '69.99', '#ffc107', NULL, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(3, 'Risque élevé', 'risk', 'Élevé', '30.00', '49.99', '#fd7e14', NULL, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(4, 'Risque très élevé', 'risk', 'Très élevé', '0.00', '29.99', '#dc3545', NULL, 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(5, 'Très bancable', 'bankability', 'Très bancable', '75.00', '100.00', '#28a745', NULL, 1, 1, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(6, 'Bancable', 'bankability', 'Bancable', '55.00', '74.99', '#20c997', NULL, 1, 2, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(7, 'Partiellement bancable', 'bankability', 'Partiellement bancable', '40.00', '54.99', '#ffc107', NULL, 1, 3, '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(8, 'Non bancable', 'bankability', 'Non bancable', '0.00', '39.99', '#dc3545', NULL, 1, 4, '2026-03-19 08:47:26', '2026-03-19 08:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_settings`
--

CREATE TABLE `evaluation_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluation_settings`
--

INSERT INTO `evaluation_settings` (`id`, `key`, `value`, `type`, `description`, `group`, `created_at`, `updated_at`) VALUES
(1, 'minimum_compliance_score', '40', 'decimal', 'Score global minimum pour la conformité', 'conformite', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(2, 'minimum_environmental_score', '30', 'decimal', 'Score environnemental minimum', 'conformite', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(3, 'minimum_social_score', '30', 'decimal', 'Score social minimum', 'conformite', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(4, 'minimum_governance_score', '30', 'decimal', 'Score gouvernance minimum', 'conformite', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(5, 'exclusion_min_score', '30', 'decimal', 'Score en dessous duquel exclusion automatique', 'exclusion', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(6, 'eligibility_blending_min_score', '60', 'decimal', 'Score minimum pour éligibilité Blending', 'eligibility', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(7, 'eligibility_guarantee_min_score', '50', 'decimal', 'Score minimum pour éligibilité Guarantee', 'eligibility', '2026-03-19 08:47:26', '2026-03-19 08:47:26'),
(8, 'eligibility_global_gateway_min_score', '55', 'decimal', 'Score minimum pour éligibilité Global Gateway', 'eligibility', '2026-03-19 08:47:26', '2026-03-19 08:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'b57dd726-dc9b-42a7-98d2-ebc416d9deeb', 'database', 'default', '{\"uuid\":\"b57dd726-dc9b-42a7-98d2-ebc416d9deeb\",\"displayName\":\"App\\\\Jobs\\\\ProcessPaymentJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessPaymentJob\",\"command\":\"O:26:\\\"App\\\\Jobs\\\\ProcessPaymentJob\\\":2:{s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:33:\\\"App\\\\Models\\\\Structuration\\\\Paiement\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:6:\\\"tenant\\\";s:15:\\\"collectionClass\\\";N;}s:9:\\\"\\u0000*\\u0000tenant\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Tenant\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"domains\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"tenant_id\":1}', 'TypeError: Illuminate\\Http\\Client\\PendingRequest::withBasicAuth(): Argument #1 ($username) must be of type string, array given, called in /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/Factory.php on line 461 and defined in /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/PendingRequest.php:447\nStack trace:\n#0 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/Factory.php(461): Illuminate\\Http\\Client\\PendingRequest->withBasicAuth(Array)\n#1 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(355): Illuminate\\Http\\Client\\Factory->__call(\'withBasicAuth\', Array)\n#2 /Users/mac/Projects/angara/app/Jobs/ProcessPaymentJob.php(47): Illuminate\\Support\\Facades\\Facade::__callStatic(\'withBasicAuth\', Array)\n#3 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessPaymentJob->handle()\n#4 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#5 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#6 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#7 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#8 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#9 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#10 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#11 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#12 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessPaymentJob), false)\n#13 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#14 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#15 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#16 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessPaymentJob))\n#17 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#18 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#19 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#20 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(137): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#23 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#24 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#25 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#26 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#27 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#28 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call(Array)\n#29 /Users/mac/Projects/angara/vendor/symfony/console/Command/Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#31 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/mac/Projects/angara/artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2025-07-21 16:57:19'),
(2, '20a29764-efc9-47af-85f1-ee1842c943b4', 'database', 'default', '{\"uuid\":\"20a29764-efc9-47af-85f1-ee1842c943b4\",\"displayName\":\"App\\\\Jobs\\\\ProcessPaymentJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessPaymentJob\",\"command\":\"O:26:\\\"App\\\\Jobs\\\\ProcessPaymentJob\\\":2:{s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:33:\\\"App\\\\Models\\\\Structuration\\\\Paiement\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:6:\\\"tenant\\\";s:15:\\\"collectionClass\\\";N;}s:9:\\\"\\u0000*\\u0000tenant\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Tenant\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"domains\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"tenant_id\":1}', 'TypeError: Illuminate\\Http\\Client\\PendingRequest::withBasicAuth(): Argument #1 ($username) must be of type string, array given, called in /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/Factory.php on line 461 and defined in /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/PendingRequest.php:447\nStack trace:\n#0 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Http/Client/Factory.php(461): Illuminate\\Http\\Client\\PendingRequest->withBasicAuth(Array)\n#1 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(355): Illuminate\\Http\\Client\\Factory->__call(\'withBasicAuth\', Array)\n#2 /Users/mac/Projects/angara/app/Jobs/ProcessPaymentJob.php(47): Illuminate\\Support\\Facades\\Facade::__callStatic(\'withBasicAuth\', Array)\n#3 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessPaymentJob->handle()\n#4 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#5 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#6 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#7 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#8 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#9 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#10 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#11 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#12 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessPaymentJob), false)\n#13 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#14 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessPaymentJob))\n#15 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#16 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessPaymentJob))\n#17 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#18 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#19 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#20 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(137): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#23 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#24 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#25 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#26 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#27 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#28 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call(Array)\n#29 /Users/mac/Projects/angara/vendor/symfony/console/Command/Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#31 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 /Users/mac/Projects/angara/vendor/symfony/console/Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/mac/Projects/angara/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/mac/Projects/angara/artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}', '2025-07-21 17:23:06');

-- --------------------------------------------------------

--
-- Structure de la table `fichiers`
--

CREATE TABLE `fichiers` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `entreprise_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fichiers_types`
--

CREATE TABLE `fichiers_types` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fichiers_types`
--

INSERT INTO `fichiers_types` (`id`, `name`) VALUES
(1, 'Dossier juridique et administratif'),
(2, 'Dossier financier'),
(3, 'Dossier Pouvoirs et Signatures'),
(4, 'Dossier des garanties'),
(5, 'Dossier de suivi des cautions reçues'),
(6, 'Dossier de suivi des relations avec les parties prenantes'),
(7, 'Dossier des engagements'),
(8, 'Dossier des correspondances avec les partenaires financiers'),
(9, 'Dossier des réclamations adressées aux partenaires financiers'),
(10, 'Dossier Divers');

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
-- Structure de la table `gammes`
--

CREATE TABLE `gammes` (
  `id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `domaine_id` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gammes`
--

INSERT INTO `gammes` (`id`, `name`, `domaine_id`, `active`) VALUES
(1, 'Grade 1', 1, 1),
(2, 'Grade 2', 1, 1),
(3, 'Excellence', 1, 1);

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `indicateurs_financiers`
--

INSERT INTO `indicateurs_financiers` (`id`, `ca`, `marge_commerciale`, `va`, `ebe`, `resultat_expl`, `resultat_fin`, `resultat_ao`, `resultat_hao`, `resultat_net`, `val_compt_cci`, `prod_cci`, `revenus_fin`, `gains_change`, `transf_charges_fin`, `prod_hao`, `transf_charges_hao`, `frais_fin`, `pertes_change`, `participation`, `impots_resultats`, `distrib_divid`, `capitaux_propres_res_assim`, `dettes_fin`, `actif_immo`, `actif_circulant_expl`, `passif_circulant_expl`, `actif_circulant_hao`, `passif_circulant_hao`, `controle_treso_net`, `flux_treso_act_op`, `flux_treso_act_invest`, `flux_treso_act_fin`, `endettement_fin_brut`, `treso_actif`, `stock_moyen`, `cout_prod_vendu`, `ratio_endet_global`, `couverture_frais_fin`, `solvabilite_glob_rx_liq`, `liquidite_generale`, `rentab_eco`, `rentab_fin`, `capacite_endettement`, `delai_client`, `delai_fournisseur`, `rentab_eco_ratio`, `rentab_fin_ratio`, `bilan_row_23`, `bilan_row_24`, `dossier_id`, `user_id`, `parent_id`, `annee`, `created_at`, `updated_at`) VALUES
(17, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 6, 0, 0, 2024, '2025-01-08 13:09:15', '2025-01-08 13:09:15'),
(18, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 6, 0, 17, 2023, '2025-01-08 13:09:15', '2025-01-08 13:09:15'),
(19, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 7, 0, 0, 2024, '2025-01-09 07:31:26', '2025-01-09 07:31:26'),
(20, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 7, 0, 19, 2023, '2025-01-09 07:31:26', '2025-01-09 07:31:26'),
(21, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 8, 0, 0, 2024, '2025-02-10 09:30:53', '2025-02-10 09:30:53'),
(22, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 8, 0, 21, 2023, '2025-02-10 09:30:53', '2025-02-10 09:30:53'),
(23, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 9, 0, 0, 2024, '2025-02-10 10:23:07', '2025-02-10 10:23:07'),
(24, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 9, 0, 23, 2023, '2025-02-10 10:23:07', '2025-02-10 10:23:07'),
(25, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 11, 0, 0, 2024, '2025-02-12 11:20:36', '2025-02-12 11:20:36'),
(26, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 11, 0, 25, 2023, '2025-02-12 11:20:36', '2025-02-12 11:20:36'),
(27, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 13, 0, 0, 2024, '2025-02-20 11:46:47', '2025-02-20 11:46:47'),
(28, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 13, 0, 27, 2023, '2025-02-20 11:46:47', '2025-02-20 11:46:47'),
(29, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.1031390134529148, 7.208333333333333, 0.6852248394004282, 0.8467741935483871, 0.4608695652173913, 0.4, 4, 13.949044585987261, 243.33333333333331, 0.297085201793722, 5.333333333333333, 8000000, 0, 14, 0, 0, 2024, '2025-02-27 09:54:41', '2025-02-27 08:54:41'),
(30, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698576612, 8.601036269430052, 0.6606942889137738, 0.8333333333333334, 0.3854347826086956, 0.3333333333333333, 2.95, 13.660179640718562, 219, 0.24741836449902316, 5.9, 10000000, 0, 14, 0, 29, 2023, '2025-02-27 09:54:41', '2025-02-27 08:54:41'),
(31, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698577, 8.6010362694301, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 12, 0, 0, 2022, '2025-03-12 12:11:05', '2025-03-12 12:11:05'),
(32, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.10313901345291, 7.2083333333333, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 12, 0, 0, 2023, '2025-03-12 12:11:05', '2025-03-12 12:11:05'),
(33, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, 8300000, 0, 0, 10350000, 3500000, 0, 31935000, 0.17666759698577, 8.6010362694301, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 6, 0, 0, 2019, '2025-10-31 13:27:20', '2025-10-31 13:27:20'),
(34, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, 8650000, 0, 0, 8500000, 4500000, 0, 32900000, 0.10313901345291, 7.2083333333333, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 6, 0, 0, 2020, '2025-10-31 13:27:20', '2025-10-31 13:27:20'),
(35, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 18, 0, 0, 2022, '2026-03-19 11:37:58', '2026-03-19 11:37:58'),
(36, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 18, 0, 0, 2023, '2026-03-19 11:37:58', '2026-03-19 11:37:58'),
(37, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 19, 0, 0, 2024, '2026-03-19 14:43:44', '2026-03-19 14:43:44'),
(38, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 19, 0, 0, 2025, '2026-03-19 14:43:44', '2026-03-19 14:43:44'),
(39, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 20, 0, 0, 2024, '2026-03-19 15:20:37', '2026-03-19 15:20:37'),
(40, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 20, 0, 0, 2025, '2026-03-19 15:20:37', '2026-03-19 15:20:37'),
(41, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 21, 0, 0, 2024, '2026-03-23 16:39:54', '2026-03-23 16:39:54'),
(42, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 21, 0, 0, 2025, '2026-03-23 16:39:54', '2026-03-23 16:39:54'),
(43, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 22, 0, 0, 2024, '2026-03-24 11:33:16', '2026-03-24 11:33:16'),
(44, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 22, 0, 0, 2025, '2026-03-24 11:33:16', '2026-03-24 11:33:16'),
(45, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 23, 0, 0, 1999, '2026-03-24 15:06:38', '2026-03-24 15:06:38'),
(46, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 23, 0, 0, 2000, '2026-03-24 15:06:38', '2026-03-24 15:06:38'),
(47, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 23, 0, 0, -1, '2026-03-24 15:13:03', '2026-03-24 15:13:03'),
(48, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 23, 0, 0, NULL, '2026-03-24 15:13:03', '2026-03-24 15:13:03'),
(49, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 25, 0, 0, 2024, '2026-03-30 10:43:05', '2026-03-30 10:43:05'),
(50, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 25, 0, 0, 2025, '2026-03-30 10:43:05', '2026-03-30 10:43:05'),
(51, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 27, 0, 0, 2003, '2026-03-31 11:29:58', '2026-03-31 11:29:58'),
(52, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 27, 0, 0, 2004, '2026-03-31 11:29:58', '2026-03-31 11:29:58'),
(53, 66800000, 22000000, 28458000, 11365000, 8865000, -965000, 7900000, 0, 5000000, 0, 0, 0, 0, 0, 0, 0, 965000, 0, 0, 2900000, 0, 29500000, 10000000, 28330000, 4000000, 2300000, 0, 2500000, 3150000, NULL, NULL, NULL, 10350000, 3500000, 0, 31935000, 0.17666759698577, NULL, 0.66069428891377, 0.83333333333333, 0.3854347826087, 0.33333333333333, 2.95, 13.660179640719, 219, 0.24741836449902, 5.9, 10000000, 0, 27, 0, 0, -1, '2026-03-31 11:30:33', '2026-03-31 11:30:33'),
(54, 78500000, 23000000, 31355000, 13100000, 10600000, -1200000, 9400000, 0, 6000000, 0, 0, 0, 0, 0, 0, 0, 1200000, 0, 0, 3400000, 0, 32000000, 8000000, 25930000, 5250000, 3200000, 0, 3000000, 4000000, NULL, NULL, NULL, 8500000, 4500000, 0, 32900000, 0.10313901345291, NULL, 0.68522483940043, 0.84677419354839, 0.46086956521739, 0.4, 4, 13.949044585987, 243.33333333333, 0.29708520179372, 5.3333333333333, 8000000, 0, 27, 0, 0, NULL, '2026-03-31 11:30:33', '2026-03-31 11:30:33');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Structure de la table `menus`
--

CREATE TABLE `menus` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int UNSIGNED NOT NULL,
  `menu_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 2),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 2),
(4, '2016_01_01_000000_add_voyager_user_fields', 2),
(5, '2016_01_01_000000_create_data_types_table', 2),
(6, '2016_05_19_173453_create_menu_table', 2),
(7, '2016_10_21_190000_create_roles_table', 3),
(8, '2016_10_21_190000_create_settings_table', 3),
(9, '2016_11_30_135954_create_permission_table', 4),
(10, '2016_11_30_141208_create_permission_role_table', 4),
(11, '2016_12_26_201236_data_types__add__server_side', 4),
(12, '2017_01_13_000000_add_route_to_menu_items_table', 4),
(13, '2017_01_14_005015_create_translations_table', 4),
(14, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 4),
(15, '2017_03_06_000000_add_controller_to_data_types_table', 4),
(16, '2017_04_21_000000_add_order_to_data_rows_table', 4),
(17, '2017_07_05_210000_add_policyname_to_data_types_table', 4),
(18, '2017_08_05_000000_add_group_to_settings_table', 4),
(19, '2017_11_26_013050_add_user_role_relationship', 4),
(20, '2017_11_26_015000_create_user_roles_table', 4),
(21, '2018_03_11_000000_add_user_settings', 4),
(29, '2025_06_03_141835_create_cooperatives_table', 0),
(54, '2018_03_14_000000_add_details_to_data_types_table', 5),
(55, '2018_03_16_000000_make_settings_value_nullable', 5),
(56, '2019_08_19_000000_create_failed_jobs_table', 5),
(65, '2019_09_15_000010_create_tenants_table', 6),
(66, '2019_09_15_000020_create_domains_table', 6),
(67, '2019_12_14_000001_create_personal_access_tokens_table', 6),
(68, '2024_12_16_144106_create_permission_tables', 6),
(69, '2025_06_04_123454_create_paiements_requests_cooperatives_table', 0),
(70, '2025_06_04_180325_create_notifications_table', 7),
(71, '2025_07_17_104104_create_Torganismes_table', 0),
(72, '2025_07_17_104104_create_adonis_schema_table', 0),
(73, '2025_07_17_104104_create_adonis_schema_versions_table', 0),
(74, '2025_07_17_104104_create_agences_table', 0),
(75, '2025_07_17_104104_create_agents_table', 0),
(76, '2025_07_17_104104_create_approches_table', 0),
(77, '2025_07_17_104104_create_arrondissements_table', 0),
(78, '2025_07_17_104104_create_banques_table', 0),
(79, '2025_07_17_104104_create_branches_table', 0),
(80, '2025_07_17_104104_create_calendrier_items_table', 0),
(81, '2025_07_17_104104_create_calendrier_items_villages_table', 0),
(82, '2025_07_17_104104_create_calendriers_table', 0),
(83, '2025_07_17_104104_create_choices_table', 0),
(84, '2025_07_17_104104_create_composantes_table', 0),
(85, '2025_07_17_104104_create_cooperatives_table', 0),
(86, '2025_07_17_104104_create_critere_programme_ponderations_table', 0),
(87, '2025_07_17_104104_create_criteres_table', 0),
(88, '2025_07_17_104104_create_data_rows_table', 0),
(89, '2025_07_17_104104_create_data_types_table', 0),
(90, '2025_07_17_104104_create_departements_table', 0),
(91, '2025_07_17_104104_create_domaines_table', 0),
(92, '2025_07_17_104104_create_domains_table', 0),
(93, '2025_07_17_104104_create_dossiers_table', 0),
(94, '2025_07_17_104104_create_elements_constitutifs_types_table', 0),
(95, '2025_07_17_104104_create_engagement_entreprises_table', 0),
(96, '2025_07_17_104104_create_engagements_table', 0),
(97, '2025_07_17_104104_create_entrees_table', 0),
(98, '2025_07_17_104104_create_entrepots_table', 0),
(99, '2025_07_17_104104_create_entrepots_gammes_table', 0),
(100, '2025_07_17_104104_create_entreprise_appuis_table', 0),
(101, '2025_07_17_104104_create_entreprise_produits_table', 0),
(102, '2025_07_17_104104_create_entreprise_types_table', 0),
(103, '2025_07_17_104104_create_entreprises_table', 0),
(104, '2025_07_17_104104_create_entreprises_elements_constitutifs_table', 0),
(105, '2025_07_17_104104_create_exploitants_table', 0),
(106, '2025_07_17_104104_create_exploitants_plateformes_table', 0),
(107, '2025_07_17_104104_create_failed_jobs_table', 0),
(108, '2025_07_17_104104_create_fichiers_table', 0),
(109, '2025_07_17_104104_create_fichiers_types_table', 0),
(110, '2025_07_17_104104_create_filieres_table', 0),
(111, '2025_07_17_104104_create_formes_juridiques_table', 0),
(112, '2025_07_17_104104_create_gammes_table', 0),
(113, '2025_07_17_104104_create_indicateurs_table', 0),
(114, '2025_07_17_104104_create_indicateurs_financiers_table', 0),
(115, '2025_07_17_104104_create_liens_table', 0),
(116, '2025_07_17_104104_create_localites_table', 0),
(117, '2025_07_17_104104_create_menu_items_table', 0),
(118, '2025_07_17_104104_create_menus_table', 0),
(119, '2025_07_17_104104_create_model_has_permissions_table', 0),
(120, '2025_07_17_104104_create_model_has_roles_table', 0),
(121, '2025_07_17_104104_create_mouvements_table', 0),
(122, '2025_07_17_104104_create_niveaux_table', 0),
(123, '2025_07_17_104104_create_notifications_table', 0),
(124, '2025_07_17_104104_create_operateur_mobiles_table', 0),
(125, '2025_07_17_104104_create_operateur_mobiles_agents_table', 0),
(126, '2025_07_17_104104_create_operateur_mobiles_agents_recharges_table', 0),
(127, '2025_07_17_104104_create_operateur_mobiles_cooperatives_table', 0),
(128, '2025_07_17_104104_create_organismes_table', 0),
(129, '2025_07_17_104104_create_paiement_parts_table', 0),
(130, '2025_07_17_104104_create_paiements_table', 0),
(131, '2025_07_17_104104_create_parts_table', 0),
(132, '2025_07_17_104104_create_password_reset_tokens_table', 0),
(133, '2025_07_17_104104_create_permissions_table', 0),
(134, '2025_07_17_104104_create_permissions___table', 0),
(135, '2025_07_17_104104_create_personal_access_tokens_table', 0),
(136, '2025_07_17_104104_create_persons_table', 0),
(137, '2025_07_17_104104_create_plateformes_table', 0),
(138, '2025_07_17_104104_create_produits_table', 0),
(139, '2025_07_17_104104_create_produits_save_table', 0),
(140, '2025_07_17_104104_create_profils_table', 0),
(141, '2025_07_17_104104_create_programme_appuis_table', 0),
(142, '2025_07_17_104104_create_programme_indicateurs_table', 0),
(143, '2025_07_17_104104_create_programme_organismes_table', 0),
(144, '2025_07_17_104104_create_programme_produits_table', 0),
(145, '2025_07_17_104104_create_programmes_table', 0),
(146, '2025_07_17_104104_create_quartiers_table', 0),
(147, '2025_07_17_104104_create_questions_table', 0),
(148, '2025_07_17_104104_create_questions_answers_table', 0),
(149, '2025_07_17_104104_create_questions_choices_table', 0),
(150, '2025_07_17_104104_create_questions_sous_criteres_table', 0),
(151, '2025_07_17_104104_create_recommandations_table', 0),
(152, '2025_07_17_104104_create_regions_table', 0),
(153, '2025_07_17_104104_create_reponses_table', 0),
(154, '2025_07_17_104104_create_representations_table', 0),
(155, '2025_07_17_104104_create_role_has_permissions_table', 0),
(156, '2025_07_17_104104_create_roles_table', 0),
(157, '2025_07_17_104104_create_roles___table', 0),
(158, '2025_07_17_104104_create_saisons_table', 0),
(159, '2025_07_17_104104_create_secteurs_table', 0),
(160, '2025_07_17_104104_create_services_table', 0),
(161, '2025_07_17_104104_create_settings_table', 0),
(162, '2025_07_17_104104_create_sme_notes_table', 0),
(163, '2025_07_17_104104_create_sorties_table', 0),
(164, '2025_07_17_104104_create_sous_criteres_table', 0),
(165, '2025_07_17_104104_create_structuration_caisses_table', 0),
(166, '2025_07_17_104104_create_structuration_modes_paiements_table', 0),
(167, '2025_07_17_104104_create_structuration_produits_phytosanitaires_table', 0),
(168, '2025_07_17_104104_create_structuration_roles_table', 0),
(169, '2025_07_17_104104_create_structuration_types_produits_table', 0),
(170, '2025_07_17_104104_create_structuration_types_travaux_vergers_table', 0),
(171, '2025_07_17_104104_create_structuration_types_vergers_table', 0),
(172, '2025_07_17_104104_create_tailles_table', 0),
(173, '2025_07_17_104104_create_tenants_table', 0),
(174, '2025_07_17_104104_create_tiers_table', 0),
(175, '2025_07_17_104104_create_translations_table', 0),
(176, '2025_07_17_104104_create_tservices_table', 0),
(177, '2025_07_17_104104_create_users_table', 0),
(178, '2025_07_17_104104_create_users_save_table', 0),
(179, '2025_07_17_104104_create_villages_table', 0),
(180, '2025_07_17_104107_add_foreign_keys_to_data_rows_table', 0),
(181, '2025_07_17_104107_add_foreign_keys_to_domains_table', 0),
(182, '2025_07_17_104107_add_foreign_keys_to_menu_items_table', 0),
(183, '2025_07_17_104107_add_foreign_keys_to_model_has_permissions_table', 0),
(184, '2025_07_17_104107_add_foreign_keys_to_model_has_roles_table', 0),
(185, '2025_07_17_104107_add_foreign_keys_to_role_has_permissions_table', 0),
(186, '2025_07_21_165044_create_jobs_table', 8),
(187, '2025_03_11_100001_create_evaluation_frameworks_table', 9),
(188, '2025_03_11_100002_create_evaluation_categories_table', 9),
(189, '2025_03_11_100003_create_evaluation_indicators_table', 9),
(190, '2025_03_11_100004_create_evaluation_score_thresholds_table', 9),
(191, '2025_03_11_100005_create_evaluation_settings_table', 9),
(192, '2025_03_11_100006_create_entreprise_evaluation_profiles_table', 9),
(193, '2025_03_11_100007_create_dossier_esg_evaluations_table', 9),
(194, '2025_03_11_100008_create_dossier_esg_evaluation_items_table', 9);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0007e039-6306-45aa-85e8-5a3fee785b87', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"4000000\"}', NULL, '2025-09-29 14:03:50', '2025-09-29 14:03:50'),
('004dca96-0e83-49dc-b48a-4fc2c96b395e', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 66, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:58', '2026-02-12 00:04:58'),
('016dee5f-c321-479e-96f5-6f11781afdf3', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-09-27 15:26:41', '2025-09-27 15:26:41'),
('01b447ae-3396-4bb3-9fb2-aaa612fd5202', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-09-29 14:31:04', '2025-09-29 14:31:04'),
('03cc3a18-6014-4533-bed9-a72811089931', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"1500000\"}', NULL, '2025-10-08 10:07:52', '2025-10-08 10:07:52'),
('04517383-8e57-479b-aa44-73c273fb4d99', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:55', '2026-02-12 00:04:55'),
('05c6e98c-fa29-417b-875a-8feaea58e1eb', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 87, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:03', '2026-02-12 00:05:03'),
('081a3728-4e32-49de-b836-727098e4b8b2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"3900000\"}', NULL, '2025-09-29 11:56:54', '2025-09-29 11:56:54'),
('092c178c-602a-447d-800f-5aaa760154a0', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-09-27 15:26:42', '2025-09-27 15:26:42'),
('0a96615c-7067-4112-81ef-ff95cffde379', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-07-19 16:42:58', '2025-07-19 16:42:58'),
('0c37ccf0-c5f2-406e-80c1-ebd77fcc1d50', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 85, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('0d2d265d-7893-40cb-bad7-d46427c99f18', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"1500000\"}', NULL, '2025-10-08 10:07:52', '2025-10-08 10:07:52'),
('0ed594de-9f8f-4bab-832f-4426a0b4781b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"29000\"}', NULL, '2025-06-05 12:44:00', '2025-06-05 12:44:00'),
('0f58c3c8-e550-4f77-8267-a0c70a59e00d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 86, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:03', '2026-02-12 00:05:03'),
('0f8d8d0f-56cd-445c-b565-0aad67a9971f', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:58', '2026-03-25 17:58:58'),
('136771f6-d8f0-40fd-934c-b1b4e76c9f99', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-10-08 10:08:14', '2025-10-08 10:08:14'),
('138347d3-e0cf-4412-a0ef-8862e209c65d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 69, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:15', '2026-02-12 09:00:15'),
('13857832-244c-46f1-b778-c0d0d6fa1a21', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 80, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:02', '2026-02-12 00:05:02'),
('14002f7f-0dd3-4bec-9fee-e795fa95ba34', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-07-19 16:42:57', '2025-07-19 16:42:57'),
('14856df1-f805-475f-b85c-be2712591d5a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 69, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:00', '2026-03-25 17:59:00'),
('14cfafa4-444b-4a14-b6fe-a1516c2b5187', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 89, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:04', '2026-02-12 00:05:04'),
('155f1704-9466-4d82-aa2d-465c2b5555bc', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 99, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:25', '2026-02-12 09:00:25'),
('18255aec-a5a3-4c18-998d-0787ec1ca8bd', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 76, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:00', '2026-02-12 00:05:00'),
('197f3e90-a64a-440e-af40-539effc7edf1', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:13', '2026-02-12 09:00:13'),
('1bc1b797-f403-4b3a-a142-a60ca156c6f8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-10-13 17:33:05', '2025-10-13 17:33:05'),
('1c675ea3-4b0d-41a7-bfa1-8e5ea3e4220d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:57', '2026-03-25 17:58:57'),
('1c7407b5-2d9c-4495-bfc9-9330cd3e61e9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 62, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:57', '2026-02-12 00:04:57'),
('1dea16ee-a7fd-4002-93dc-908c66350bcf', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 99, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('1e58c9c2-7197-4a8a-a4c1-61e69e763b88', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 67, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:15', '2026-02-12 09:00:15'),
('1eea5041-dc35-4baa-a310-3763af826a49', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 89, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:22', '2026-02-12 09:00:22'),
('1f074e9c-53c2-4f6f-a419-e59606aa1857', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 96, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:24', '2026-02-12 09:00:24'),
('20ba2f06-ae70-4b31-a789-d5d3f3fa41f3', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-27 15:25:04', '2025-09-27 15:25:04'),
('21f0f919-1490-49ab-9f87-ec163bd23bd2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 98, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('235880f4-44fd-4742-828e-564d29f04d6d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 97, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:06', '2026-02-12 00:05:06'),
('241567f1-f9d2-489e-a8dc-d22aaab83400', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"720000\"}', NULL, '2025-06-05 12:33:27', '2025-06-05 12:33:27'),
('248ae75b-3bb1-422d-b346-dbebf1af249c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"20000\"}', NULL, '2025-10-08 09:32:21', '2025-10-08 09:32:21'),
('25074573-f5c9-4b26-ac66-98067ba21056', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 68, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:58', '2026-02-12 00:04:58'),
('26223bf8-bede-493b-804c-c15e2b227dd7', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 94, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:06', '2026-02-12 00:05:06'),
('2744382f-87c0-41fc-8049-37bf4779d85c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-07-19 16:42:25', '2025-07-19 16:42:25'),
('29d1ff4e-2094-4982-b56a-978314206bab', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 93, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:23', '2026-02-12 09:00:23'),
('2a7cf132-b077-4db9-ae61-a74fa190beab', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-06-05 12:41:58', '2025-06-05 12:41:58'),
('2c032f8c-d028-468a-947f-c688f125ac0d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-10-08 10:08:15', '2025-10-08 10:08:15'),
('2c61e4e2-a79a-4b69-bbc3-5c8082e7c6e7', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 73, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:00', '2026-02-12 00:05:00'),
('2d4c50a1-cf9a-4915-b3ca-30152f6039bf', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 85, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:20', '2026-02-12 09:00:20'),
('2d7a1b83-01ef-4684-abd2-30be58341810', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 67, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('2e2652a6-4f31-4cb7-804c-83013ae2f9ad', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"4000000\"}', NULL, '2025-09-29 14:03:52', '2025-09-29 14:03:52'),
('2ff4d1e5-8d9e-4359-95da-b68cfe1bb8bc', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 81, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:02', '2026-03-25 17:59:02'),
('315ded07-9e28-4143-8338-348788595930', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 76, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:17', '2026-02-12 09:00:17'),
('316bd12c-af99-4214-a4bb-4d1346a93964', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 62, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:13', '2026-02-12 09:00:13'),
('33101eda-0e31-4710-8534-15455901357d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 71, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:00', '2026-03-25 17:59:00'),
('3351d7e6-1048-4eeb-96b3-5455b2b5d6ef', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-06-05 12:41:56', '2025-06-05 12:41:56'),
('34d0c940-cc3f-4b60-adab-2c41b21a2f30', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-06-05 12:41:57', '2025-06-05 12:41:57'),
('34e772c2-c387-4f6a-b818-b07faaf806ce', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 73, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:00', '2026-03-25 17:59:00'),
('39914c85-2076-4bd0-8f60-3e51792ef95d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"720000\"}', NULL, '2025-06-05 12:33:28', '2025-06-05 12:33:28'),
('4081706a-ee14-4eb4-a542-d75355ba204b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"20000\"}', NULL, '2025-10-08 09:32:21', '2025-10-08 09:32:21'),
('439fc5af-cd9d-4206-892a-a085904b1bff', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:12', '2026-02-12 09:00:12'),
('43cace32-995e-4b46-8dbf-4a35efea6c8a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 65, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('44f55565-42b1-4d22-8b0a-8bb87291e909', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-09-29 14:30:59', '2025-09-29 14:30:59'),
('46407656-d198-4f2e-bd3d-c03f6db95298', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 101, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:25', '2026-02-12 09:00:25'),
('464511d0-ff4c-495e-a98d-cced33145b86', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:58', '2026-03-25 17:58:58'),
('472dabdc-f3bc-4253-ab53-4ca31fd44228', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 91, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:04', '2026-03-25 17:59:04'),
('48b7d7c2-7143-4d7b-bbf1-b828e3f5245d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 87, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('49848f11-b4c6-4e10-8b07-b1fa44b87dd4', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 92, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:05', '2026-02-12 00:05:05'),
('498516c2-99b2-48b3-a9fa-4389d99c7453', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 81, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:02', '2026-02-12 00:05:02'),
('49b9bb99-5d7f-4fe3-8a37-71a7304c176f', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 80, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:02', '2026-03-25 17:59:02'),
('4a1c05d7-488e-4bc8-9e71-af2da2ad3bc7', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 97, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('4a696ea4-452f-4350-b6e1-44c82b25d5d1', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-09-29 14:31:00', '2025-09-29 14:31:00'),
('4cfc7a30-4767-47bf-8aa2-226c73dc85c9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 74, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:00', '2026-02-12 00:05:00'),
('5335c729-3c99-4886-9395-ccf06982177d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 81, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:19', '2026-02-12 09:00:19'),
('540a68ab-8df7-42d5-91ff-849ed9c74fdb', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 71, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:16', '2026-02-12 09:00:16'),
('544600bd-35cd-4357-86a6-1010217abec1', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 70, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:59', '2026-02-12 00:04:59'),
('54c3a36d-7e08-40ce-b550-5289f98feb62', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 63, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:13', '2026-02-12 09:00:13'),
('558e34df-7199-4cc8-9200-5b791d60b2e2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 70, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:15', '2026-02-12 09:00:15'),
('55b8e7ed-d1fa-471d-8afd-043b1a318681', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"3900000\"}', NULL, '2025-09-29 11:56:56', '2025-09-29 11:56:56'),
('58949e1b-973f-4919-aeed-86e69dc33941', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 98, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:07', '2026-02-12 00:05:07'),
('59e132b1-f92b-4cde-b6b0-d2a652d59dc6', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 73, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:16', '2026-02-12 09:00:16'),
('5ad82bbf-17fe-4207-ab3c-5426b9cb6e5c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:56', '2026-02-12 00:04:56'),
('5b360c31-ca35-45bf-b8fb-474312fbb212', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"1500000\"}', NULL, '2025-10-08 10:07:52', '2025-10-08 10:07:52'),
('5b5aeb5b-28b8-4f4c-826d-ac2ae4083713', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-29 14:04:16', '2025-09-29 14:04:16'),
('5bec824e-5904-4767-959f-bfaa18f5defe', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-07-19 16:42:26', '2025-07-19 16:42:26'),
('5d28285f-92e1-41e1-b1c9-241f52d3b5dc', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 79, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('5dda1a33-68e3-4c58-897f-717439a6bb9e', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"29000\"}', NULL, '2025-06-05 12:43:59', '2025-06-05 12:43:59'),
('6119fce5-ef0c-4be1-b9a9-2b3fa9960477', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 74, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('6157c4fd-6967-4813-ba8b-41a656e2df74', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 70, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:00', '2026-03-25 17:59:00'),
('6295e312-7d26-4b45-ad6c-d8163d53c136', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:12', '2026-02-12 09:00:12'),
('64a47060-a790-453f-9176-5861b7e56e4c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-10-13 17:33:04', '2025-10-13 17:33:04'),
('657dfe11-9972-40b9-b14f-b9365db0c890', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 83, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:02', '2026-02-12 00:05:02'),
('65c24b9f-5e9d-40d8-80e1-09379fdcaba6', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2025-06-04 21:09:49', '2025-06-04 21:09:49'),
('68c4d3e8-6fdd-4acf-baf2-16bd3dc7e50c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-07-19 16:42:56', '2025-07-19 16:42:56'),
('69708af7-1c64-4570-98eb-b387e7ec2927', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-27 15:25:05', '2025-09-27 15:25:05'),
('6a184595-3b8d-48a2-b1cf-15713f357db6', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"7000000\"}', NULL, '2025-09-29 14:20:01', '2025-09-29 14:20:01'),
('6ab75bb1-66fe-47b9-8397-b3911cdf9012', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"29000\"}', NULL, '2025-06-05 12:43:58', '2025-06-05 12:43:58'),
('6ba25bcd-ec23-44e2-9c64-3e49b59119a0', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 66, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('6e6c4fb4-93c3-44a8-be8d-6750f4f87d56', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 83, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:02', '2026-03-25 17:59:02'),
('6ffed52f-e4ad-4a8c-8861-6c49780410af', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 93, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:04', '2026-03-25 17:59:04'),
('702b4783-67a5-4b3f-ae8e-818fe1340e1e', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 95, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:04', '2026-03-25 17:59:04'),
('70ae66a6-b57b-4bf3-8089-1760fd2d12bd', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 94, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:04', '2026-03-25 17:59:04'),
('74e07db4-e791-4129-916c-85167f8feb96', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 78, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:01', '2026-02-12 00:05:01'),
('753ea0bb-4b90-4138-994d-028527b2be16', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-07-19 16:42:23', '2025-07-19 16:42:23'),
('76b5bc08-5beb-4634-a1dc-41fe836e531a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-29 14:04:17', '2025-09-29 14:04:17'),
('77401157-db0f-4fa5-8271-dcfa65bf5e82', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"4000000\"}', NULL, '2025-09-29 14:03:48', '2025-09-29 14:03:48'),
('77c400ee-69df-43a6-a2b9-0294769f9644', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-27 15:25:06', '2025-09-27 15:25:06'),
('7b4f45fd-6617-49fb-8f05-8184a94b532d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:56', '2026-02-12 00:04:56'),
('7c47c688-cc2c-4011-81ea-d229943b43ce', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"720000\"}', NULL, '2025-06-05 12:33:25', '2025-06-05 12:33:25'),
('7d8bf877-e526-4bb0-ae71-3ab13d4964ce', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 80, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:19', '2026-02-12 09:00:19'),
('7f7a8aee-6cc2-4343-8cb0-a66f46fe3bc4', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-10-08 10:08:15', '2025-10-08 10:08:15'),
('7f9a2e6c-27b0-496d-8aab-2d43e7499827', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 77, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('81d7ea65-295e-4fcd-a23c-a1ec026153a7', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 68, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:15', '2026-02-12 09:00:15'),
('82cb640b-bf19-4ef5-b541-e089f0d8bc2c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 90, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:04', '2026-02-12 00:05:04'),
('84866eff-1a1d-4ec6-9899-89bc111535ad', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 64, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('850fadf0-d74c-4a95-843b-f4a1154e40b9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 63, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:57', '2026-02-12 00:04:57'),
('85568d20-2ec7-41ac-9d2d-75c18ec3249b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2025-06-04 21:09:51', '2025-06-04 21:09:51'),
('893f2764-3844-4a6b-8698-ff6c9c850cfe', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 88, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:21', '2026-02-12 09:00:21'),
('8a86cdfe-b3a0-47fe-a33d-bc6c146746eb', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 78, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:18', '2026-02-12 09:00:18'),
('8a98a99e-72ef-4297-9c4c-4441f1807541', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:56', '2026-02-12 00:04:56'),
('8b2a89f5-613a-4279-8322-6a43bff8ee3d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 100, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:07', '2026-02-12 00:05:07'),
('8bc91ec6-8ebf-4511-83fe-4efb440e77e1', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 99, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:07', '2026-02-12 00:05:07'),
('8c30e9d5-591e-4e1b-b39e-82fc09924023', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 82, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:02', '2026-03-25 17:59:02'),
('8ee788bd-2747-42db-8186-9cc6bba9a85a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 74, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:17', '2026-02-12 09:00:17'),
('8f4ae40b-0d36-462c-bd47-96321ca539f4', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 94, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:23', '2026-02-12 09:00:23'),
('9125a49d-30a6-4634-b244-8feff417e159', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 87, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:21', '2026-02-12 09:00:21'),
('93f4ce87-f982-4a3d-ba4c-cad9a905394d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-09-27 15:26:40', '2025-09-27 15:26:40'),
('94e7293a-27ce-4364-b2a3-719e1567d2a9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"20000\"}', NULL, '2025-10-08 09:32:21', '2025-10-08 09:32:21'),
('9c0fe86a-abe8-46bb-96d9-5c7e718c96f7', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"29000\"}', NULL, '2025-06-05 12:43:57', '2025-06-05 12:43:57'),
('9d2a0947-9a60-48af-af49-b0c2eeaef36b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 84, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:20', '2026-02-12 09:00:20'),
('9d341970-97a7-4a8c-b2c2-a56e3c11a4c6', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 65, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:14', '2026-02-12 09:00:14'),
('9e40633b-7def-4fee-bc41-3680b25c1127', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-09-27 15:26:38', '2025-09-27 15:26:38'),
('a1c98882-c9c5-4bba-b943-49ba87107c2e', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 78, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('a28b37c8-9131-4198-8382-48f1e1ed8b48', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"720000\"}', NULL, '2025-06-05 12:33:26', '2025-06-05 12:33:26'),
('a355c438-9aa7-4c6a-9f46-bde820fd5bf9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 84, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:03', '2026-02-12 00:05:03'),
('a4e86b34-1718-42c0-b638-9e2fa9316861', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-27 15:25:07', '2025-09-27 15:25:07'),
('a5d32516-9222-4488-8933-c6eb5be62ddd', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-06-05 12:41:59', '2025-06-05 12:41:59'),
('a60a7650-0f6c-4c22-b371-99fa1cf98af4', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"7000000\"}', NULL, '2025-09-29 14:19:59', '2025-09-29 14:19:59'),
('ab230d83-90ad-4582-8578-fa90ec00b8cf', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 77, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:18', '2026-02-12 09:00:18'),
('ab75c867-6f83-454d-8a5b-cd68f60ae6e1', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 101, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:08', '2026-02-12 00:05:08'),
('ab9fb47f-25a3-4305-b22d-9fe646db0a45', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 69, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:59', '2026-02-12 00:04:59'),
('abb04a33-0a63-4b40-99e1-3bd146acb575', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 95, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:24', '2026-02-12 09:00:24'),
('acccbf96-6b77-402f-8255-ec75870caec3', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 64, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:57', '2026-02-12 00:04:57'),
('ad467cb9-3157-42f9-93bb-7b173d9217a2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:58', '2026-03-25 17:58:58'),
('aec21494-b049-4349-8e27-66da089be679', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 90, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('b180ec7f-0381-458f-9634-a34dcd2afca6', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 92, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:23', '2026-02-12 09:00:23'),
('b1b2414b-e663-4333-8b0c-632f506c944b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 91, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:05', '2026-02-12 00:05:05'),
('b211bac4-8f04-4be1-a34d-3e33bd571d33', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 92, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:04', '2026-03-25 17:59:04'),
('b54d6287-e131-4378-aa2c-24653c6c242f', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 83, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:20', '2026-02-12 09:00:20'),
('b5cb0e0b-dc65-49f4-82b2-dd05443e1787', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 100, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('b8d2485c-355c-4293-a0c3-7ee6aff51c08', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-29 14:04:15', '2025-09-29 14:04:15'),
('b91dccd3-aa9f-45f0-91dd-44ea15f83412', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 100, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:25', '2026-02-12 09:00:25'),
('b96b2734-7ea0-4e53-ad1b-f42140265f96', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 71, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:59', '2026-02-12 00:04:59'),
('b9849a36-3d0d-48e4-be43-4598cb6f732a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"1500000\"}', NULL, '2025-10-08 10:07:51', '2025-10-08 10:07:51'),
('b9d3e52f-7141-48f0-97ce-f6e5f7635170', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 68, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('bb439931-26a6-416a-af51-2eee421f405a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 75, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:00', '2026-02-12 00:05:00'),
('bb5cd866-1e7e-4e36-a590-ac20e4bd4652', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 67, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:58', '2026-02-12 00:04:58'),
('bc17c280-41ce-4927-85ba-762f071a33af', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"20000\"}', NULL, '2025-10-08 09:32:20', '2025-10-08 09:32:20'),
('bc38a201-53eb-43f0-872e-644b16c42241', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"7000000\"}', NULL, '2025-09-29 14:20:03', '2025-09-29 14:20:03'),
('bda6d4a7-df6e-4b39-a013-8804e2c1c128', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 95, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:06', '2026-02-12 00:05:06'),
('be6d7d13-fe99-4684-a2c5-68c7ec202b68', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"3900000\"}', NULL, '2025-09-29 11:56:53', '2025-09-29 11:56:53'),
('c3a395be-1731-4759-a101-4b5e97681a1a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 90, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:22', '2026-02-12 09:00:22'),
('c454febd-e16f-4fda-805c-996c3673358d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 89, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('c5e6900c-f68a-4324-9a91-433e4f6270e8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 65, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:57', '2026-02-12 00:04:57'),
('c6793fc1-f4fb-4c1d-9ca0-eae1d0840903', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:12', '2026-02-12 09:00:12'),
('c8881249-1231-48cd-aca5-504edae9aa3a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"1500000\"}', NULL, '2025-10-08 10:07:52', '2025-10-08 10:07:52'),
('c92c2ad0-6dac-4e24-bc80-7cd5fb79344a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 72, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:00', '2026-03-25 17:59:00'),
('c985fa70-b314-4121-9da7-4d989a3285eb', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 75, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:17', '2026-02-12 09:00:17'),
('cceea7f9-0f7b-484e-bafe-0051bed5bf74', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 79, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:18', '2026-02-12 09:00:18'),
('cd450c57-8157-4af2-b9d0-b8c7b3f8e305', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 10, '{\"title\":\"Appel de fond\",\"montant\":\"4000000\"}', NULL, '2025-09-29 14:03:49', '2025-09-29 14:03:49'),
('cd8d205a-b16a-4e04-926b-7147ca15b8ed', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"300000\"}', NULL, '2025-09-29 14:04:14', '2025-09-29 14:04:14'),
('ce17bfd2-b191-4b69-a2dc-2e0684d1883d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 91, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:23', '2026-02-12 09:00:23'),
('cedb685c-7a9c-41eb-9e8b-96a22d5d4c31', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 84, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('cf7f7977-30ee-4a8e-b382-a3550e68c353', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 96, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('cfdd0611-5a63-4816-a480-92425e928827', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 72, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:59', '2026-02-12 00:04:59'),
('d04e2004-9824-4c58-b024-47b42cd090be', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 23, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:04:56', '2026-02-12 00:04:56'),
('d19516af-51af-4ad9-9dfa-16b8ed64bf35', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 85, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:03', '2026-02-12 00:05:03'),
('d199cd32-fc0a-400f-be84-da7376f01164', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-07-19 16:42:28', '2025-07-19 16:42:28'),
('d29b5d40-20df-48b3-8397-ae996a858f44', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"7000000\"}', NULL, '2025-09-29 14:20:02', '2025-09-29 14:20:02'),
('d470e453-91f0-4d26-8f12-b2ac5aef433c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-10-08 10:08:15', '2025-10-08 10:08:15'),
('d6de929a-75b1-44e3-a225-accfcd9300a4', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 98, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:24', '2026-02-12 09:00:24'),
('d6fc6ad8-8519-4d00-9297-147fc7fb045b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-10-13 17:33:04', '2025-10-13 17:33:04'),
('d90f1468-c824-4aae-b21f-8e3ed2b8890d', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"3900000\"}', NULL, '2025-09-29 11:56:52', '2025-09-29 11:56:52'),
('d958d82d-dd76-4d20-a780-55d253736855', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 88, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('d977f617-eaa1-444d-915e-c25cde55e75a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"30000\"}', NULL, '2025-10-08 10:08:14', '2025-10-08 10:08:14'),
('da5b65e1-edab-4505-a681-a43d5843a1f2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 62, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:58', '2026-03-25 17:58:58'),
('dc7d635f-9efd-4f6f-8dea-1a691e254fb5', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 72, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:16', '2026-02-12 09:00:16'),
('dee44ae7-c1f6-4df2-a86d-ebf45e024bf8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 11, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-09-29 14:31:02', '2025-09-29 14:31:02'),
('df4cddda-d1d1-4c0d-9889-f72b62bb66f2', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 63, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:59', '2026-03-25 17:58:59'),
('e30b0247-7d03-4c6e-b7af-9b4256068600', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 77, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:01', '2026-02-12 00:05:01'),
('e35c893c-2803-498a-940d-2b1adeacca3f', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 82, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:02', '2026-02-12 00:05:02'),
('e69cce4a-9d80-4f1e-8646-2fb307ab3c77', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 96, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:06', '2026-02-12 00:05:06'),
('e87b7191-0a3b-429e-97cd-bdcb8d5ca024', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 66, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:14', '2026-02-12 09:00:14'),
('e8a876c5-b417-4049-98a7-8e2874fa0757', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 101, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:05', '2026-03-25 17:59:05'),
('e940ec31-d935-494d-9e3f-358db4269d7b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 76, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('e9bc306f-8187-42aa-966d-631de0c11966', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 22, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-10-13 17:33:04', '2025-10-13 17:33:04'),
('ea1706c7-be22-4674-afbe-991136a10d5f', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"20000\"}', NULL, '2025-10-08 09:32:22', '2025-10-08 09:32:22'),
('ebe921a1-1c49-42b0-8156-c3094c6bb15a', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 21, '{\"title\":\"Appel de fond\",\"montant\":\"500000\"}', NULL, '2025-10-13 17:33:03', '2025-10-13 17:33:03'),
('ec4ce343-d6f3-48c3-8b7d-b2015958c060', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"50000\"}', NULL, '2025-07-19 16:42:59', '2025-07-19 16:42:59'),
('ed1efd34-17a6-4ea8-910c-98b72cf8d7f8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 86, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:21', '2026-02-12 09:00:21'),
('ed553772-e655-4a75-b4e0-c0e95a730560', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 88, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:04', '2026-02-12 00:05:04'),
('ef118362-90b9-488a-a27f-d4397e8f0585', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 64, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:14', '2026-02-12 09:00:14'),
('f308ac2e-4913-48fd-8199-24f937b5449c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 97, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:24', '2026-02-12 09:00:24'),
('f38b8bc5-59c5-4af2-985b-5a370cb68bb8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 93, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:05', '2026-02-12 00:05:05'),
('f3f80a72-4538-4a44-a362-c003ab379e52', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 9, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2025-06-04 21:09:48', '2025-06-04 21:09:48'),
('f423d4f4-bb16-4e1c-836e-33ba56a8b703', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 79, '{\"title\":\"Appel de fond\",\"montant\":\"30000000\"}', NULL, '2026-02-12 00:05:01', '2026-02-12 00:05:01'),
('f6aec244-4d8a-4397-93d9-9fc472bae657', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 86, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:03', '2026-03-25 17:59:03'),
('f7dfab7a-4bfb-468c-bfd2-72229768e5d8', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 12, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2025-06-04 21:09:52', '2025-06-04 21:09:52'),
('f892fbb6-03a9-4bee-bd58-d954b199cc4b', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 24, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:12', '2026-02-12 09:00:12'),
('f8fd9fe1-d1ed-4ed5-946d-56fa3784af95', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 82, '{\"title\":\"Appel de fond\",\"montant\":\"4500000\"}', NULL, '2026-02-12 09:00:19', '2026-02-12 09:00:19'),
('fc2f4937-0fce-46a4-a722-a0be6911255c', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 75, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:59:01', '2026-03-25 17:59:01'),
('fcc6dbf7-469b-4f7f-874c-c280b503a2d9', 'App\\Notifications\\Structuration\\RequestNotification', 'App\\Models\\User', 61, '{\"title\":\"Appel de fond\",\"montant\":\"450000\"}', NULL, '2026-03-25 17:58:58', '2026-03-25 17:58:58');

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
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
(1, 'Bomba Madeleine', '6237237982398823', 1, 'm.bomba@gmail.com', '68128092003', 'Quelque part au cameroun', '6faeadb31bda82e570ebae070b100980f2963627', NULL, NULL),
(2, 'ELOUNDOU ERIC', '67238328238', 34, 'e.eloundou@angara.com', '6898329032', 'Quelque part au quartier Nvan', 'd91073bc236fc88b558673db32fb4fc9ef849b7f', NULL, NULL),
(3, 'ALIMA SIMON', 'NUI52368723823', 10, 'alimasimon@gmail.com', '643894830430', 'Quartier Damas Yaoundé', '7d6341a74ba28e775fe49b11dec3a0013467f01f', NULL, NULL),
(4, 'ARIWU GASTON', '73443884388', 10, 'g.ariwu@gmail.com', '6978787878', 'Somewhere', '42422870766936080c0c8ea20b067e1323401eb1', NULL, NULL),
(5, 'Gallah Leslie', 'P0899475761', 105, 'gallah@gmail.com', '577663388', 'Rue des écureuils \r\nYaoundé', '75c8b368ce50e5496ad23a43a68b43db572f168a', NULL, NULL),
(6, 'MBENDA ANNAELLE epse kouadjo', 'P01748575645', 107, 'annaellembenda@gmail.com', '678451296', 'omnisport lieu dit derriere le stade', '6140a7c1005a61ef7cf639fc48f82982438a6b6b', NULL, NULL),
(7, 'TCHADJUI NELSON', 'P01748575647', 105, 'TEST@GMAIL.COM', '65566989', 'YAGOUA', '05fc417f078d107b0a71acbaf671c77124f45bf0', NULL, NULL),
(8, 'CHEGUEM EPSE NZUNO ELISABETH', '100146356', 105, 'CHEGUEM@GMAIL.COM', '698498982', 'MEKALAT/EBOLOWA', '5e49feae7d446757a42733b9bdf2cde3ff122b31', NULL, NULL),
(9, 'TCHADJUI NELSON', 'md^sjipgsp', 107, 'TANGUEN@GMAIL.COM', '65566989', 'smdjqsmjdfipqs', '9e1e0b182c8c7a0e187d8c47331543c8a03195eb', NULL, NULL),
(10, 'Gilles', 'M080616698485C', 107, 'dpsdpos@gmail.Com', '65566989', ';;mjp', '27b91ecd29c5f218a905fee77c57dca278ab36f6', NULL, NULL),
(11, 'LE WAMBY JOSEPH', 'P158879654', 115, 'LEWAMBY@GMAIL.COM', '659874123', 'VINA LE PLATEAU', '758406472088a7123aebaeb623a8253b3acba2e6', NULL, NULL),
(12, 'MICHEL OLIVIER', 'P06874512', 115, 'OLIVE54@GMAIL.COM', '658741254', 'LA VALLEE', '65c8071fe603c1c34372d267c7168a391c3266f4', NULL, NULL),
(13, 'KAMDEM JEANNE', 'NUI000008889P', 115, 'jeannek@yahoo.fr', '551002008', 'yaoundé-nilo', '5eeac570f58dc3727c7ec04b53c209df65ee4ad5', NULL, NULL);

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
(2, 'PRESIDENT DU CONSEIL D’ADMINISTRATION', 'PCA', 0, 1, 0, 1),
(3, 'ADMINISTRATEUR', 'ADM', 0, 1, 0, 1),
(4, 'DIRECTEUR GENERAL', 'DG', 1, 1, 0, 1),
(5, 'DIRECTEUR GENERAL ADJOINT', 'DGA', 1, 1, 0, 1),
(6, 'RESPONSABLE EXPLOITATION', 'RESP-EXP', 2, 1, 0, 1),
(7, 'RESPONSABLE AUDIT INTERNE', 'RESP-AUD', 2, 1, 0, 1),
(8, 'RESPONSABLE CONTROLE INTERNE', 'RESP-CI', 2, 1, 0, 1),
(9, 'RESPONSABLE ENGAGEMENTS ET RISQUES', 'RERI', 2, 1, 0, 1),
(10, 'RESPONSABLE JURIDIQUE', 'REJU', 2, 1, 0, 1),
(11, 'RESPONSABLE REGIONAL', 'RESP-REG', 3, 1, 0, 1),
(12, 'CHEF D’AGENCE', 'CA', 3, 1, 0, 1),
(13, 'GESTIONNAIRE', 'GEST', 4, 1, 0, 1),
(14, 'ANALYSTE FINANCIER EXPLOITATION', 'AFE', 4, 1, 0, 1),
(15, 'ANALYSTE FINANCIER RISQUE', 'AFR', 4, 1, 0, 1),
(16, 'ANALYSTE JURIDIQUE', 'ANJ', 4, 1, 0, 1),
(17, 'AUDITEUR', 'AUD', 4, 1, 0, 1),
(18, 'CONTROLEUR', 'CONT', 4, 1, 0, 1),
(20, 'RESPONSABLE SECTORIEL', 'RS', 0, 1, 0, 1),
(21, 'GESTIONNAIRE DE COMPTE ', NULL, 0, 1, 0, 1);

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
(1, 'Transfagri', '6883298932', '2024-11-25', 'Ministere des PME', 0, 340000, 30000, 36000, 'Femme-Jeune', 'TRES PETITE-ASSOCIATION', '2024-12-28', 'abdala', 1, 1, '2024-12-14 01:14:41', '2025-01-13 09:29:10', 'cc1a8e2a25986d7307ecb8fdd3b000f340c5b5a9'),
(2, 'Chocofill', '6273832787238732', '2020-01-21', 'Ministere du commerce', 0, 3200000, 40000000, 5400000, 'Homme-Femme-Jeune', 'PETITE-COOPERATIVE-ASSOCIATION', '2024-12-27', 'Mr Abena Martin, Tel : 689430990439', 1, 1, '2024-12-14 10:14:17', '2024-12-14 10:14:17', '7a4c723121664c08b0ec68e4d742a51940c155cb'),
(3, 'Puratos', '62383278', '2024-12-19', 'MINADER', 0, 450000, 300000, 450000, 'Femme-Jeune-Personnes vulnérables', 'PETITE-TRES PETITE-COOPERATIVE', '2024-12-03', 'Fernand Assiga', 1, 1, '2024-12-20 11:08:45', '2024-12-20 11:08:45', '366a8d544b853287a3c5ce3cd5ff6c365d313c51'),
(4, 'AGRIFARDI', '8328923', '2021-09-11', 'Ministere de l\'economie', 0, 500000000, 400000000, 75000000, 'Jeune-Personnes vulnérables', 'PETITE-TRES PETITE-COOPERATIVE', '2022-06-23', 'DG economie', 10, 1, '2025-02-13 08:26:25', '2025-02-13 08:26:25', 'b2f9d9fe89f54af9e4fed7906cde7a28a484068f'),
(5, 'TRANSFAGRI', 'MINEPAT', '2021-12-09', 'MINEPAT', 0, 40000000, 10000000, 200000000, 'Homme-Femme-Jeune', 'TRES PETITE', '2021-02-03', 'ERIC ELOUNDOU NGAH', 10, 1, '2025-11-18 18:23:03', '2025-11-18 18:23:03', '70e0bd6e600192b4b287ca642b73ed2805c9e331'),
(6, 'PIISAH', 'FDSVFDBSBER', '2025-01-01', 'ETAT CAMEROUNAIS', 0, 0, 41000000000, 0, 'Homme-Femme-Personnes vulnérables', 'GRANDE-MOYENNE-PETITE-TRES PETITE-COOPERATIVE-ASSOCIATION', '2025-01-01', 'AMADOU HAMAN', 118, 1, '2026-03-23 16:09:32', '2026-03-23 16:09:32', '696c17188b19c68da38714ea5f1f44a440e00532'),
(7, 'PIISAH', 'FDSVFDBSBER', '2025-01-01', 'ETAT CAMEROUNAIS', 0, 1, 41000000000, 1, 'Homme-Femme-Personnes vulnérables', 'GRANDE-MOYENNE-PETITE-TRES PETITE-COOPERATIVE-ASSOCIATION', '2025-01-01', 'AMADOU HAMAN', 118, 1, '2026-03-23 16:10:28', '2026-03-23 16:10:28', '1cdbb0a7e926dc83bdea6414d6bf0c93417402f5'),
(8, 'PIISAH', '1233355', '2025-01-01', 'ETAT CAMEROUNAIS', 0, 1, 41000000000, 1, 'Homme-Femme-Personnes vulnérables', 'GRANDE-MOYENNE-PETITE-TRES PETITE-COOPERATIVE-ASSOCIATION', '2025-01-01', 'AMADOU HAMAN', 118, 1, '2026-03-23 16:13:25', '2026-03-23 16:13:25', '7bf432b85e6276fdb994b8d042a95fa7730a3ec7'),
(9, 'PIISAH', '1233355', '2025-01-01', 'ETAT CAMEROUNAIS', 0, 1, 41000000000, 1, 'Homme-Femme-Personnes vulnérables', 'GRANDE-MOYENNE-PETITE-TRES PETITE-COOPERATIVE-ASSOCIATION', '2025-01-01', 'AMADOU HAMAN', 118, 1, '2026-03-23 16:15:22', '2026-03-23 16:15:22', '2b2bd0909dc3467b3e216ee546cc41690ef36eeb');

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
(1, 1, 15, 1),
(2, 1, 18, 1),
(3, 1, 2, 1),
(4, 1, 4, 1),
(5, 1, 8, 1),
(6, 2, 21, 1),
(7, 2, 2, 1),
(8, 2, 5, 1),
(9, 3, 17, 1),
(10, 3, 20, 1),
(11, 3, 2, 1),
(12, 1, 17, 1),
(13, 1, 1, 1),
(14, 4, 17, 1),
(15, 4, 20, 1),
(16, 4, 15, 1),
(17, 4, 2, 1),
(18, 4, 8, 1),
(19, 4, 10, 1),
(20, 5, 17, 1),
(21, 5, 1, 1),
(22, 6, 14, 1),
(23, 6, 1, 1),
(24, 7, 14, 1),
(25, 7, 1, 1),
(26, 8, 14, 1),
(27, 8, 1, 1),
(28, 9, 14, 1),
(29, 9, 1, 1);

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
(1, 1, 12, '300'),
(2, 2, 25, '200'),
(3, 2, 31, '50'),
(4, 5, 3, 'IMPACT SUR LE DEVELOPPEMENT DES PME');

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
(1, 1, 3),
(2, 1, 5),
(3, 2, 4),
(4, 2, 9),
(5, 3, 4),
(6, 3, 7),
(7, 4, 4),
(8, 4, 2),
(9, 4, 12),
(10, 5, 1),
(11, 5, 2),
(12, 6, 1),
(13, 7, 1),
(14, 8, 1),
(15, 9, 1);

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
(1, 1, 8),
(2, 1, 10),
(3, 1, 11),
(4, 2, 35),
(5, 2, 160),
(6, 2, 159),
(7, 3, 35),
(8, 1, 26),
(9, 4, 60),
(10, 4, 99),
(11, 4, 108),
(12, 5, 16),
(13, 6, 1),
(14, 7, 1),
(15, 8, 1),
(16, 9, 1);

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
(1, 6, 4, '3', 1, 1, 1, NULL, NULL),
(2, 6, 11, '2', 2, 1, 1, NULL, NULL),
(3, 6, 16, '4', 3, 1, 1, NULL, NULL),
(4, 6, 23, '5', 4, 2, 1, NULL, NULL),
(5, 6, 28, '4', 5, 3, 1, NULL, NULL),
(6, 6, 35, '5', 6, 3, 1, NULL, NULL),
(7, 6, 39, '3', 7, 3, 1, NULL, NULL),
(8, 6, 44, '2', 8, 4, 1, NULL, NULL),
(9, 6, 53, '5', 9, 4, 1, NULL, NULL),
(10, 6, 0, NULL, 71, 21, 4, NULL, '2024-12-13'),
(11, 6, 63, '3', 11, 5, 1, NULL, NULL),
(12, 6, 73, '3', 12, 5, 1, NULL, NULL),
(13, 6, 82, '-', 13, 5, 1, NULL, NULL),
(14, 6, 84, '5', 14, 6, 1, NULL, NULL),
(15, 6, 90, '5', 15, 6, 1, NULL, NULL),
(16, 6, 96, '5', 16, 6, 1, NULL, NULL),
(17, 6, 102, '2', 17, 7, 1, NULL, NULL),
(18, 6, 108, '2', 18, 8, 2, NULL, NULL),
(19, 6, 115, '3', 19, 8, 2, NULL, NULL),
(20, 6, 120, '2', 20, 8, 2, NULL, NULL),
(21, 6, 129, '5', 21, 8, 2, NULL, NULL),
(22, 6, 132, '2', 22, 8, 2, NULL, NULL),
(23, 6, 138, '2', 23, 8, 2, NULL, NULL),
(24, 6, 147, '5', 24, 8, 2, NULL, NULL),
(25, 6, 156, '2', 26, 10, 2, NULL, NULL),
(26, 6, 162, '2', 27, 10, 2, NULL, NULL),
(27, 6, 168, '2', 28, 10, 2, NULL, NULL),
(28, 6, 2, '5', 1, 1, 1, '2024-12-13', '2024-12-13'),
(29, 6, 21, '3', 4, 2, 1, '2024-12-13', '2024-12-13'),
(30, 6, 40, '4', 7, 3, 1, '2024-12-13', '2024-12-13'),
(31, 6, 64, '4', 11, 5, 1, '2024-12-13', '2024-12-13'),
(32, 6, 75, '5', 12, 5, 1, '2024-12-13', '2024-12-13'),
(33, 6, 81, '+', 13, 5, 1, '2024-12-13', '2024-12-13'),
(34, 6, 104, '4', 17, 7, 1, '2024-12-13', '2024-12-13'),
(35, 6, 114, '2', 19, 8, 2, '2024-12-13', '2024-12-13'),
(36, 6, 121, '3', 20, 8, 2, '2024-12-13', '2024-12-13'),
(37, 6, 127, '3', 21, 8, 2, '2024-12-13', '2024-12-13'),
(38, 6, 139, '3', 23, 8, 2, '2024-12-13', '2024-12-13'),
(39, 6, 186, '2', 31, 11, 2, '2024-12-13', '2024-12-13'),
(40, 6, 192, '2', 32, 11, 2, '2024-12-13', '2024-12-13'),
(41, 6, 198, '2', 33, 11, 2, '2024-12-13', '2024-12-13'),
(42, 6, 205, '3', 34, 11, 2, '2024-12-13', '2024-12-13'),
(43, 6, 307, '3', 53, 16, 2, '2024-12-13', '2024-12-13'),
(44, 6, 310, '2', 54, 16, 2, '2024-12-13', '2024-12-13'),
(45, 6, 314, '2', 55, 16, 2, '2024-12-13', '2024-12-13'),
(46, 6, 319, '3', 56, 16, 2, '2024-12-13', '2024-12-13'),
(47, 6, 321, '1', 57, 16, 2, '2024-12-13', '2024-12-13'),
(48, 6, 326, '2', 58, 16, 2, '2024-12-13', '2024-12-13'),
(49, 6, 340, '2', 62, 18, 4, '2024-12-13', '2024-12-13'),
(50, 6, 344, '2', 63, 18, 4, '2024-12-13', '2024-12-13'),
(51, 6, 348, '1,3', 64, 18, 4, '2024-12-13', '2024-12-13'),
(52, 6, 355, '1,3', 65, 18, 4, '2024-12-13', '2024-12-13'),
(53, 10, 3, '4', 1, 1, 1, '2024-12-14', '2024-12-14'),
(54, 10, 9, '4', 2, 1, 1, '2024-12-14', '2024-12-14'),
(55, 10, 15, '3', 3, 1, 1, '2024-12-14', '2024-12-14'),
(56, 10, 21, '3', 4, 2, 1, '2024-12-14', '2024-12-14'),
(57, 10, 0, NULL, 70, 20, 4, '2024-12-14', '2024-12-14'),
(58, 10, 187, '3', 31, 11, 2, '2024-12-14', '2024-12-14'),
(59, 10, 192, '2', 32, 11, 2, '2024-12-14', '2024-12-14'),
(60, 10, 199, '3', 33, 11, 2, '2024-12-14', '2024-12-14'),
(61, 10, 204, '2', 34, 11, 2, '2024-12-14', '2024-12-14'),
(62, 10, 210, '2', 35, 12, 2, '2024-12-14', '2024-12-14'),
(63, 10, 217, '3', 36, 12, 2, '2024-12-14', '2024-12-14'),
(64, 10, 223, '3', 37, 12, 2, '2024-12-14', '2024-12-14'),
(65, 10, 228, '2', 38, 12, 2, '2024-12-14', '2024-12-14'),
(66, 10, 235, '3', 39, 12, 2, '2024-12-14', '2024-12-14'),
(67, 10, 240, '2', 40, 12, 2, '2024-12-14', '2024-12-14'),
(68, 10, 246, '2', 41, 12, 2, '2024-12-14', '2024-12-14'),
(69, 10, 252, '2', 42, 12, 2, '2024-12-14', '2024-12-14'),
(70, 10, 306, '2', 53, 16, 2, '2024-12-14', '2024-12-14'),
(71, 10, 310, '2', 54, 16, 2, '2024-12-14', '2024-12-14'),
(72, 10, 314, '2', 55, 16, 2, '2024-12-14', '2024-12-14'),
(73, 10, 318, '2', 56, 16, 2, '2024-12-14', '2024-12-14'),
(74, 10, 322, '2', 57, 16, 2, '2024-12-14', '2024-12-14'),
(75, 10, 326, '2', 58, 16, 2, '2024-12-14', '2024-12-14'),
(76, 10, 330, '2', 59, 16, 2, '2024-12-14', '2024-12-14'),
(77, 10, 334, '2', 60, 17, 2, '2024-12-14', '2024-12-14'),
(78, 10, 337, '2', 61, 17, 2, '2024-12-14', '2024-12-14'),
(79, 10, 362, '2', 66, 19, 4, '2024-12-14', '2024-12-14'),
(80, 10, 367, '3', 67, 19, 4, '2024-12-14', '2024-12-14'),
(81, 10, 370, '2', 68, 19, 4, '2024-12-14', '2024-12-14'),
(82, 10, 383, '3', 71, 21, 4, '2024-12-14', '2024-12-14'),
(83, 122, 2, '5', 1, 1, 1, '2025-01-08', '2025-01-08'),
(84, 122, 9, '4', 2, 1, 1, '2025-01-08', '2025-01-08'),
(85, 122, 15, '3', 3, 1, 1, '2025-01-08', '2025-01-08'),
(86, 122, 20, '2', 4, 2, 1, '2025-01-08', '2025-01-08'),
(87, 122, 26, '2', 5, 3, 1, '2025-01-08', '2025-01-08'),
(88, 122, 32, '2', 6, 3, 1, '2025-01-08', '2025-01-08'),
(89, 122, 39, '3', 7, 3, 1, '2025-01-08', '2025-01-08'),
(90, 122, 44, '2', 8, 4, 1, '2025-01-08', '2025-01-08'),
(91, 122, 50, '2', 9, 4, 1, '2025-01-08', '2025-01-08'),
(92, 122, 57, '3', 10, 4, 1, '2025-01-08', '2025-01-08'),
(93, 122, 62, '2', 11, 5, 1, '2025-01-08', '2025-01-08'),
(94, 122, 72, '2', 12, 5, 1, '2025-01-08', '2025-01-08'),
(95, 122, 82, '-', 13, 5, 1, '2025-01-08', '2025-01-08'),
(96, 122, 84, '5', 14, 6, 1, '2025-01-08', '2025-01-08'),
(97, 122, 90, '5', 15, 6, 1, '2025-01-08', '2025-01-08'),
(98, 122, 97, '4', 16, 6, 1, '2025-01-08', '2025-01-08'),
(99, 122, 102, '2', 17, 7, 1, '2025-01-08', '2025-01-08'),
(100, 122, 107, '1', 18, 8, 2, '2025-01-08', '2025-01-08'),
(101, 122, 114, '2', 19, 8, 2, '2025-01-08', '2025-01-08'),
(102, 122, 120, '2', 20, 8, 2, '2025-01-08', '2025-01-08'),
(103, 122, 125, '1', 21, 8, 2, '2025-01-08', '2025-01-08'),
(104, 122, 131, '1', 22, 8, 2, '2025-01-08', '2025-01-08'),
(105, 122, 138, '2', 23, 8, 2, '2025-01-08', '2025-01-08'),
(106, 122, 144, '2', 24, 8, 2, '2025-01-08', '2025-01-08'),
(107, 122, 150, '2', 25, 9, 2, '2025-01-08', '2025-01-08'),
(108, 122, 156, '2', 26, 10, 2, '2025-01-08', '2025-01-08'),
(109, 122, 163, '3', 27, 10, 2, '2025-01-08', '2025-01-08'),
(110, 122, 168, '2', 28, 10, 2, '2025-01-08', '2025-01-08'),
(111, 122, 174, '2', 29, 10, 2, '2025-01-08', '2025-01-08'),
(112, 122, 181, '3', 30, 10, 2, '2025-01-08', '2025-01-08'),
(113, 122, 186, '2', 31, 11, 2, '2025-01-08', '2025-01-08'),
(114, 122, 193, '3', 32, 11, 2, '2025-01-08', '2025-01-08'),
(115, 122, 199, '3', 33, 11, 2, '2025-01-08', '2025-01-08'),
(116, 122, 205, '3', 34, 11, 2, '2025-01-08', '2025-01-08'),
(117, 122, 0, NULL, 71, 21, 4, '2025-01-08', '2025-01-08'),
(118, 195, 2, '5', 1, 1, 1, '2025-01-08', '2025-01-08'),
(119, 195, 8, '5', 2, 1, 1, '2025-01-08', '2025-01-08'),
(120, 195, 14, '2', 3, 1, 1, '2025-01-08', '2025-01-08'),
(121, 195, 21, '3', 4, 2, 1, '2025-01-08', '2025-01-08'),
(122, 195, 27, '3', 5, 3, 1, '2025-01-08', '2025-01-08'),
(123, 195, 33, '3', 6, 3, 1, '2025-01-08', '2025-01-08'),
(124, 195, 39, '3', 7, 3, 1, '2025-01-08', '2025-01-08'),
(125, 195, 44, '2', 8, 4, 1, '2025-01-08', '2025-01-08'),
(126, 195, 50, '2', 9, 4, 1, '2025-01-08', '2025-01-08'),
(127, 195, 57, '3', 10, 4, 1, '2025-01-08', '2025-01-08'),
(128, 195, 63, '3', 11, 5, 1, '2025-01-08', '2025-01-08'),
(129, 195, 73, '3', 12, 5, 1, '2025-01-08', '2025-01-08'),
(130, 195, 81, '+', 13, 5, 1, '2025-01-08', '2025-01-08'),
(131, 195, 84, '5', 14, 6, 1, '2025-01-08', '2025-01-08'),
(132, 195, 90, '5', 15, 6, 1, '2025-01-08', '2025-01-08'),
(133, 195, 97, '4', 16, 6, 1, '2025-01-08', '2025-01-08'),
(134, 195, 102, '2', 17, 7, 1, '2025-01-08', '2025-01-08'),
(135, 195, 108, '2', 18, 8, 2, '2025-01-08', '2025-01-08'),
(136, 195, 114, '2', 19, 8, 2, '2025-01-08', '2025-01-08'),
(137, 195, 120, '2', 20, 8, 2, '2025-01-08', '2025-01-08'),
(138, 195, 126, '2', 21, 8, 2, '2025-01-08', '2025-01-08'),
(139, 195, 132, '2', 22, 8, 2, '2025-01-08', '2025-01-08'),
(140, 195, 138, '2', 23, 8, 2, '2025-01-08', '2025-01-08'),
(141, 195, 144, '2', 24, 8, 2, '2025-01-08', '2025-01-08'),
(142, 195, 150, '2', 25, 9, 2, '2025-01-08', '2025-01-08'),
(143, 195, 157, '3', 26, 10, 2, '2025-01-08', '2025-01-08'),
(144, 195, 162, '2', 27, 10, 2, '2025-01-08', '2025-01-08'),
(145, 195, 168, '2', 28, 10, 2, '2025-01-08', '2025-01-08'),
(146, 195, 175, '3', 29, 10, 2, '2025-01-08', '2025-01-08'),
(147, 195, 182, '4', 30, 10, 2, '2025-01-08', '2025-01-08'),
(148, 195, 187, '3', 31, 11, 2, '2025-01-08', '2025-01-08'),
(149, 195, 192, '2', 32, 11, 2, '2025-01-08', '2025-01-08'),
(150, 195, 198, '2', 33, 11, 2, '2025-01-08', '2025-01-08'),
(151, 195, 204, '2', 34, 11, 2, '2025-01-08', '2025-01-08'),
(152, 195, 211, '3', 35, 12, 2, '2025-01-08', '2025-01-08'),
(153, 195, 216, '2', 36, 12, 2, '2025-01-08', '2025-01-08'),
(154, 195, 222, '2', 37, 12, 2, '2025-01-08', '2025-01-08'),
(155, 195, 228, '2', 38, 12, 2, '2025-01-08', '2025-01-08'),
(156, 195, 234, '2', 39, 12, 2, '2025-01-08', '2025-01-08'),
(157, 195, 243, '5', 40, 12, 2, '2025-01-08', '2025-01-08'),
(158, 195, 246, '2', 41, 12, 2, '2025-01-08', '2025-01-08'),
(159, 195, 252, '2', 42, 12, 2, '2025-01-08', '2025-01-08'),
(160, 195, 0, NULL, 65, 18, 4, '2025-01-08', '2025-01-08'),
(161, 195, 310, '2', 54, 16, 2, '2025-01-08', '2025-01-08'),
(162, 195, 314, '2', 55, 16, 2, '2025-01-08', '2025-01-08'),
(163, 195, 318, '2', 56, 16, 2, '2025-01-08', '2025-01-08'),
(164, 195, 322, '2', 57, 16, 2, '2025-01-08', '2025-01-08'),
(165, 195, 326, '2', 58, 16, 2, '2025-01-08', '2025-01-08'),
(166, 195, 331, '3', 59, 16, 2, '2025-01-08', '2025-01-08'),
(167, 195, 362, '2', 66, 19, 4, '2025-01-08', '2025-01-08'),
(168, 195, 366, '2', 67, 19, 4, '2025-01-08', '2025-01-08'),
(169, 195, 370, '2', 68, 19, 4, '2025-01-08', '2025-01-08'),
(170, 195, 373, '1', 69, 20, 4, '2025-01-08', '2025-01-08'),
(171, 195, 378, '2', 70, 20, 4, '2025-01-08', '2025-01-08'),
(172, 195, 382, '2', 71, 21, 4, '2025-01-08', '2025-01-08'),
(173, 197, 3, '4', 1, 1, 1, '2025-01-09', '2025-01-09'),
(174, 197, 8, '5', 2, 1, 1, '2025-01-09', '2025-01-09'),
(175, 197, 15, '3', 3, 1, 1, '2025-01-09', '2025-01-09'),
(176, 197, 20, '2', 4, 2, 1, '2025-01-09', '2025-01-09'),
(177, 197, 26, '2', 5, 3, 1, '2025-01-09', '2025-01-09'),
(178, 197, 32, '2', 6, 3, 1, '2025-01-09', '2025-01-09'),
(179, 197, 39, '3', 7, 3, 1, '2025-01-09', '2025-01-09'),
(180, 197, 0, NULL, 68, 19, 4, '2025-01-09', '2025-01-09'),
(181, 197, 62, '2', 11, 5, 1, '2025-01-09', '2025-01-09'),
(182, 197, 73, '3', 12, 5, 1, '2025-01-09', '2025-01-09'),
(183, 197, 82, '-', 13, 5, 1, '2025-01-09', '2025-01-09'),
(184, 197, 103, '3', 17, 7, 1, '2025-01-09', '2025-01-09'),
(185, 197, 150, '2', 25, 9, 2, '2025-01-09', '2025-01-09'),
(186, 197, 186, '2', 31, 11, 2, '2025-01-09', '2025-01-09'),
(187, 197, 192, '2', 32, 11, 2, '2025-01-09', '2025-01-09'),
(188, 197, 198, '2', 33, 11, 2, '2025-01-09', '2025-01-09'),
(189, 197, 204, '2', 34, 11, 2, '2025-01-09', '2025-01-09'),
(190, 197, 258, '2', 43, 13, 2, '2025-01-09', '2025-01-09'),
(191, 197, 263, '2', 44, 13, 2, '2025-01-09', '2025-01-09'),
(192, 197, 268, '2', 45, 13, 2, '2025-01-09', '2025-01-09'),
(193, 197, 273, '2', 46, 13, 2, '2025-01-09', '2025-01-09'),
(194, 197, 299, '3', 52, 15, 2, '2025-01-09', '2025-01-09'),
(195, 197, 306, '2', 53, 16, 2, '2025-01-09', '2025-01-09'),
(196, 197, 310, '2', 54, 16, 2, '2025-01-09', '2025-01-09'),
(197, 197, 314, '2', 55, 16, 2, '2025-01-09', '2025-01-09'),
(198, 197, 318, '2', 56, 16, 2, '2025-01-09', '2025-01-09'),
(199, 197, 322, '2', 57, 16, 2, '2025-01-09', '2025-01-09'),
(200, 197, 325, '1', 58, 16, 2, '2025-01-09', '2025-01-09'),
(201, 197, 330, '2', 59, 16, 2, '2025-01-09', '2025-01-09'),
(202, 197, 333, '1', 60, 17, 2, '2025-01-09', '2025-01-09'),
(203, 197, 337, '2', 61, 17, 2, '2025-01-09', '2025-01-09'),
(204, 197, 340, '2', 62, 18, 4, '2025-01-09', '2025-01-09'),
(205, 197, 344, '2', 63, 18, 4, '2025-01-09', '2025-01-09'),
(206, 197, 349, '1,8', 64, 18, 4, '2025-01-09', '2025-01-09'),
(207, 197, 356, '1,8', 65, 18, 4, '2025-01-09', '2025-01-09'),
(208, 197, 374, '2', 69, 20, 4, '2025-01-09', '2025-01-09'),
(209, 197, 378, '2', 70, 20, 4, '2025-01-09', '2025-01-09'),
(210, 197, 382, '2', 71, 21, 4, '2025-01-09', '2025-01-09'),
(211, 207, 3, '4', 1, 1, 1, '2025-02-20', '2025-02-20'),
(212, 207, 9, '4', 2, 1, 1, '2025-02-20', '2025-02-20'),
(213, 207, 14, '2', 3, 1, 1, '2025-02-20', '2025-02-20'),
(214, 207, 21, '3', 4, 2, 1, '2025-02-20', '2025-02-20'),
(215, 207, 27, '3', 5, 3, 1, '2025-02-20', '2025-02-20'),
(216, 207, 33, '3', 6, 3, 1, '2025-02-20', '2025-02-20'),
(217, 207, 41, '5', 7, 3, 1, '2025-02-20', '2025-02-20'),
(218, 207, 44, '2', 8, 4, 1, '2025-02-20', '2025-02-20'),
(219, 207, 50, '2', 9, 4, 1, '2025-02-20', '2025-02-20'),
(220, 207, 58, '4', 10, 4, 1, '2025-02-20', '2025-02-20'),
(221, 207, 62, '2', 11, 5, 1, '2025-02-20', '2025-02-20'),
(222, 207, 72, '2', 12, 5, 1, '2025-02-20', '2025-02-20'),
(223, 207, 82, '-', 13, 5, 1, '2025-02-20', '2025-02-20'),
(224, 207, 84, '5', 14, 6, 1, '2025-02-20', '2025-02-20'),
(225, 207, 90, '5', 15, 6, 1, '2025-02-20', '2025-02-20'),
(226, 207, 96, '5', 16, 6, 1, '2025-02-20', '2025-02-20'),
(227, 207, 102, '2', 17, 7, 1, '2025-02-20', '2025-02-20'),
(228, 207, 108, '2', 18, 8, 2, '2025-02-20', '2025-02-20'),
(229, 207, 115, '3', 19, 8, 2, '2025-02-20', '2025-02-20'),
(230, 207, 121, '3', 20, 8, 2, '2025-02-20', '2025-02-20'),
(231, 207, 125, '1', 21, 8, 2, '2025-02-20', '2025-02-20'),
(232, 207, 132, '2', 22, 8, 2, '2025-02-20', '2025-02-20'),
(233, 207, 138, '2', 23, 8, 2, '2025-02-20', '2025-02-20'),
(234, 207, 144, '2', 24, 8, 2, '2025-02-20', '2025-02-20'),
(235, 207, 150, '2', 25, 9, 2, '2025-02-20', '2025-02-20'),
(236, 207, 157, '3', 26, 10, 2, '2025-02-20', '2025-02-20'),
(237, 207, 162, '2', 27, 10, 2, '2025-02-20', '2025-02-20'),
(238, 207, 167, '1', 28, 10, 2, '2025-02-20', '2025-02-20'),
(239, 207, 174, '2', 29, 10, 2, '2025-02-20', '2025-02-20'),
(240, 207, 180, '2', 30, 10, 2, '2025-02-20', '2025-02-20'),
(241, 207, 185, '1', 31, 11, 2, '2025-02-20', '2025-02-20'),
(242, 207, 192, '2', 32, 11, 2, '2025-02-20', '2025-02-20'),
(243, 207, 199, '3', 33, 11, 2, '2025-02-20', '2025-02-20'),
(244, 207, 204, '2', 34, 11, 2, '2025-02-20', '2025-02-20'),
(245, 207, 211, '3', 35, 12, 2, '2025-02-20', '2025-02-20'),
(246, 207, 216, '2', 36, 12, 2, '2025-02-20', '2025-02-20'),
(247, 207, 222, '2', 37, 12, 2, '2025-02-20', '2025-02-20'),
(248, 207, 228, '2', 38, 12, 2, '2025-02-20', '2025-02-20'),
(249, 207, 233, '1', 39, 12, 2, '2025-02-20', '2025-02-20'),
(250, 207, 240, '2', 40, 12, 2, '2025-02-20', '2025-02-20'),
(251, 207, 245, '1', 41, 12, 2, '2025-02-20', '2025-02-20'),
(252, 207, 254, '4', 42, 12, 2, '2025-02-20', '2025-02-20'),
(253, 207, 258, '2', 43, 13, 2, '2025-02-20', '2025-02-20'),
(254, 207, 263, '2', 44, 13, 2, '2025-02-20', '2025-02-20'),
(255, 207, 269, '3', 45, 13, 2, '2025-02-20', '2025-02-20'),
(256, 207, 273, '2', 46, 13, 2, '2025-02-20', '2025-02-20'),
(257, 207, 0, NULL, 61, 17, 2, '2025-02-20', '2025-02-20'),
(258, 207, 339, '1', 62, 18, 4, '2025-02-20', '2025-02-20'),
(259, 207, 344, '2', 63, 18, 4, '2025-02-20', '2025-02-20'),
(260, 207, 347, '1', 64, 18, 4, '2025-02-20', '2025-02-20'),
(261, 207, 355, '1,3', 65, 18, 4, '2025-02-20', '2025-02-20'),
(262, 207, 362, '2', 66, 19, 4, '2025-02-20', '2025-02-20'),
(263, 207, 366, '2', 67, 19, 4, '2025-02-20', '2025-02-20'),
(264, 207, 369, '1', 68, 19, 4, '2025-02-20', '2025-02-20'),
(265, 207, 374, '2', 69, 20, 4, '2025-02-20', '2025-02-20'),
(266, 207, 378, '2', 70, 20, 4, '2025-02-20', '2025-02-20'),
(267, 207, 382, '2', 71, 21, 4, '2025-02-20', '2025-02-20'),
(268, 209, 3, '4', 1, 1, 1, '2025-02-27', '2025-02-27'),
(269, 209, 9, '4', 2, 1, 1, '2025-02-27', '2025-02-27'),
(270, 209, 15, '3', 3, 1, 1, '2025-02-27', '2025-02-27'),
(271, 209, 21, '3', 4, 2, 1, '2025-02-27', '2025-02-27'),
(272, 209, 26, '2', 5, 3, 1, '2025-02-27', '2025-02-27'),
(273, 209, 34, '4', 6, 3, 1, '2025-02-27', '2025-02-27'),
(274, 209, 40, '4', 7, 3, 1, '2025-02-27', '2025-02-27'),
(275, 209, 45, '3', 8, 4, 1, '2025-02-27', '2025-02-27'),
(276, 209, 51, '3', 9, 4, 1, '2025-02-27', '2025-02-27'),
(277, 209, 58, '4', 10, 4, 1, '2025-02-27', '2025-02-27'),
(278, 209, 64, '4', 11, 5, 1, '2025-02-27', '2025-02-27'),
(279, 209, 75, '5', 12, 5, 1, '2025-02-27', '2025-02-27'),
(280, 209, 81, '+', 13, 5, 1, '2025-02-27', '2025-02-27'),
(281, 209, 84, '5', 14, 6, 1, '2025-02-27', '2025-02-27'),
(282, 209, 91, '4', 15, 6, 1, '2025-02-27', '2025-02-27'),
(283, 209, 98, '3', 16, 6, 1, '2025-02-27', '2025-02-27'),
(284, 209, 104, '4', 17, 7, 1, '2025-02-27', '2025-02-27'),
(285, 209, 109, '3', 18, 8, 2, '2025-02-27', '2025-02-27'),
(286, 209, 115, '3', 19, 8, 2, '2025-02-27', '2025-02-27'),
(287, 209, 120, '2', 20, 8, 2, '2025-02-27', '2025-02-27'),
(288, 209, 126, '2', 21, 8, 2, '2025-02-27', '2025-02-27'),
(289, 209, 0, NULL, 71, 21, 4, '2025-02-27', '2025-02-27'),
(290, 209, 306, '2', 53, 16, 2, '2025-02-27', '2025-02-27'),
(291, 209, 319, '3', 56, 16, 2, '2025-02-27', '2025-02-27'),
(292, 209, 326, '2', 58, 16, 2, '2025-02-27', '2025-02-27'),
(293, 209, 341, '3', 62, 18, 4, '2025-02-27', '2025-02-27'),
(294, 209, 344, '2', 63, 18, 4, '2025-02-27', '2025-02-27'),
(295, 209, 348, '1,3', 64, 18, 4, '2025-02-27', '2025-02-27'),
(296, 209, 355, '1,3', 65, 18, 4, '2025-02-27', '2025-02-27'),
(297, 213, 2, '5', 1, 1, 1, '2025-05-07', '2025-05-07'),
(298, 213, 8, '5', 2, 1, 1, '2025-05-07', '2025-05-07'),
(299, 213, 15, '3', 3, 1, 1, '2025-05-07', '2025-05-07'),
(300, 213, 21, '3', 4, 2, 1, '2025-05-07', '2025-05-07'),
(301, 213, 26, '2', 5, 3, 1, '2025-05-07', '2025-05-07'),
(302, 213, 32, '2', 6, 3, 1, '2025-05-07', '2025-05-07'),
(303, 213, 39, '3', 7, 3, 1, '2025-05-07', '2025-05-07'),
(304, 213, 0, NULL, 71, 21, 4, '2025-05-07', '2025-05-07'),
(305, 213, 9, '4', 2, 1, 1, '2025-05-07', '2025-05-07'),
(306, 213, 16, '4', 3, 1, 1, '2025-05-07', '2025-05-07'),
(307, 213, 27, '3', 5, 3, 1, '2025-05-07', '2025-05-07'),
(308, 213, 40, '4', 7, 3, 1, '2025-05-07', '2025-05-07'),
(309, 213, 45, '3', 8, 4, 1, '2025-05-07', '2025-05-07'),
(310, 213, 51, '3', 9, 4, 1, '2025-05-07', '2025-05-07'),
(311, 213, 57, '3', 10, 4, 1, '2025-05-07', '2025-05-07'),
(312, 213, 65, '5', 11, 5, 1, '2025-05-07', '2025-05-07'),
(313, 213, 75, '5', 12, 5, 1, '2025-05-07', '2025-05-07'),
(314, 213, 81, '+', 13, 5, 1, '2025-05-07', '2025-05-07'),
(315, 242, 2, '5', 1, 1, 1, '2026-03-19', '2026-03-19'),
(316, 242, 8, '5', 2, 1, 1, '2026-03-19', '2026-03-19'),
(317, 242, 15, '3', 3, 1, 1, '2026-03-19', '2026-03-19'),
(318, 242, 21, '3', 4, 2, 1, '2026-03-19', '2026-03-19'),
(319, 242, 27, '3', 5, 3, 1, '2026-03-19', '2026-03-19'),
(320, 242, 33, '3', 6, 3, 1, '2026-03-19', '2026-03-19'),
(321, 242, 39, '3', 7, 3, 1, '2026-03-19', '2026-03-19'),
(322, 242, 44, '2', 8, 4, 1, '2026-03-19', '2026-03-19'),
(323, 242, 50, '2', 9, 4, 1, '2026-03-19', '2026-03-19'),
(324, 242, 57, '3', 10, 4, 1, '2026-03-19', '2026-03-19'),
(325, 242, 62, '2', 11, 5, 1, '2026-03-19', '2026-03-19'),
(326, 242, 72, '2', 12, 5, 1, '2026-03-19', '2026-03-19'),
(327, 242, 81, '0', 13, 5, 1, '2026-03-19', '2026-03-19'),
(328, 242, 85, '4', 14, 6, 1, '2026-03-19', '2026-03-19'),
(329, 242, 91, '4', 15, 6, 1, '2026-03-19', '2026-03-19'),
(330, 242, 96, '5', 16, 6, 1, '2026-03-19', '2026-03-19'),
(331, 242, 102, '2', 17, 7, 1, '2026-03-19', '2026-03-19'),
(332, 242, 108, '2', 18, 8, 2, '2026-03-19', '2026-03-19'),
(333, 242, 113, '1', 19, 8, 2, '2026-03-19', '2026-03-19'),
(334, 242, 120, '2', 20, 8, 2, '2026-03-19', '2026-03-19'),
(335, 242, 126, '2', 21, 8, 2, '2026-03-19', '2026-03-19'),
(336, 242, 133, '3', 22, 8, 2, '2026-03-19', '2026-03-19'),
(337, 242, 138, '2', 23, 8, 2, '2026-03-19', '2026-03-19'),
(338, 242, 147, '5', 24, 8, 2, '2026-03-19', '2026-03-19'),
(339, 242, 151, '3', 25, 9, 2, '2026-03-19', '2026-03-19'),
(340, 242, 340, '2', 62, 18, 4, '2026-03-19', '2026-03-19'),
(341, 242, 344, '2', 63, 18, 4, '2026-03-19', '2026-03-19'),
(342, 242, 348, '1', 64, 18, 4, '2026-03-19', '2026-03-19'),
(343, 242, 356, '1', 65, 18, 4, '2026-03-19', '2026-03-19'),
(344, 245, 1, '6', 1, 1, 1, '2026-03-19', '2026-03-19'),
(345, 245, 8, '5', 2, 1, 1, '2026-03-19', '2026-03-19'),
(346, 245, 18, '6', 3, 1, 1, '2026-03-19', '2026-03-19'),
(347, 244, 2, '5', 1, 1, 1, '2026-03-19', '2026-03-19'),
(348, 244, 7, '6', 2, 1, 1, '2026-03-19', '2026-03-19'),
(349, 244, 16, '4', 3, 1, 1, '2026-03-19', '2026-03-19'),
(350, 244, 21, '3', 4, 2, 1, '2026-03-19', '2026-03-19'),
(351, 245, 21, '3', 4, 2, 1, '2026-03-19', '2026-03-19'),
(352, 245, 29, '5', 5, 3, 1, '2026-03-19', '2026-03-19'),
(353, 245, 35, '5', 6, 3, 1, '2026-03-19', '2026-03-19'),
(354, 245, 41, '5', 7, 3, 1, '2026-03-19', '2026-03-19'),
(355, 245, 43, '1', 8, 4, 1, '2026-03-19', '2026-03-19'),
(356, 245, 50, '2', 9, 4, 1, '2026-03-19', '2026-03-19'),
(357, 245, 57, '3', 10, 4, 1, '2026-03-19', '2026-03-19'),
(358, 247, 1, '6', 1, 1, 1, '2026-03-24', '2026-03-24'),
(359, 247, 9, '4', 2, 1, 1, '2026-03-24', '2026-03-24'),
(360, 247, 17, '5', 3, 1, 1, '2026-03-24', '2026-03-24'),
(361, 247, 20, '2', 4, 2, 1, '2026-03-24', '2026-03-24'),
(362, 247, 29, '5', 5, 3, 1, '2026-03-24', '2026-03-24'),
(363, 247, 35, '5', 6, 3, 1, '2026-03-24', '2026-03-24'),
(364, 247, 38, '2', 7, 3, 1, '2026-03-24', '2026-03-24'),
(365, 247, 43, '1', 8, 4, 1, '2026-03-24', '2026-03-24'),
(366, 247, 50, '2', 9, 4, 1, '2026-03-24', '2026-03-24'),
(367, 247, 56, '2', 10, 4, 1, '2026-03-24', '2026-03-24'),
(368, 247, 69, '9', 11, 5, 1, '2026-03-24', '2026-03-24'),
(369, 247, 80, '10', 12, 5, 1, '2026-03-24', '2026-03-24'),
(370, 247, 81, '0', 13, 5, 1, '2026-03-24', '2026-03-24'),
(371, 247, 87, '2', 14, 6, 1, '2026-03-24', '2026-03-24'),
(372, 247, 93, '2', 15, 6, 1, '2026-03-24', '2026-03-24'),
(373, 247, 98, '3', 16, 6, 1, '2026-03-24', '2026-03-24'),
(374, 247, 102, '2', 17, 7, 1, '2026-03-24', '2026-03-24'),
(375, 247, 111, '5', 18, 8, 2, '2026-03-24', '2026-03-24'),
(376, 247, 113, '1', 19, 8, 2, '2026-03-24', '2026-03-24'),
(377, 247, 120, '2', 20, 8, 2, '2026-03-24', '2026-03-24'),
(378, 247, 126, '2', 21, 8, 2, '2026-03-24', '2026-03-24'),
(379, 247, 133, '3', 22, 8, 2, '2026-03-24', '2026-03-24'),
(380, 247, 142, '6', 23, 8, 2, '2026-03-24', '2026-03-24'),
(381, 247, 143, '1', 24, 8, 2, '2026-03-24', '2026-03-24'),
(382, 247, 151, '3', 25, 9, 2, '2026-03-24', '2026-03-24'),
(383, 247, 160, '6', 26, 10, 2, '2026-03-24', '2026-03-24'),
(384, 247, 166, '6', 27, 10, 2, '2026-03-24', '2026-03-24'),
(385, 247, 172, '6', 28, 10, 2, '2026-03-24', '2026-03-24'),
(386, 247, 174, '2', 29, 10, 2, '2026-03-24', '2026-03-24'),
(387, 247, 184, '6', 30, 10, 2, '2026-03-24', '2026-03-24'),
(388, 247, 186, '2', 31, 11, 2, '2026-03-24', '2026-03-24'),
(389, 247, 196, '6', 32, 11, 2, '2026-03-24', '2026-03-24'),
(390, 247, 197, '1', 33, 11, 2, '2026-03-24', '2026-03-24'),
(391, 247, 204, '2', 34, 11, 2, '2026-03-24', '2026-03-24'),
(392, 247, 210, '2', 35, 12, 2, '2026-03-24', '2026-03-24'),
(393, 247, 216, '2', 36, 12, 2, '2026-03-24', '2026-03-24'),
(394, 247, 225, '5', 37, 12, 2, '2026-03-24', '2026-03-24'),
(395, 247, 231, '5', 38, 12, 2, '2026-03-24', '2026-03-24'),
(396, 247, 235, '3', 39, 12, 2, '2026-03-24', '2026-03-24'),
(397, 247, 240, '2', 40, 12, 2, '2026-03-24', '2026-03-24'),
(398, 247, 245, '1', 41, 12, 2, '2026-03-24', '2026-03-24'),
(399, 247, 254, '4', 42, 12, 2, '2026-03-24', '2026-03-24'),
(400, 247, 258, '2', 43, 13, 2, '2026-03-24', '2026-03-24'),
(401, 247, 264, '3', 44, 13, 2, '2026-03-24', '2026-03-24'),
(402, 247, 268, '2', 45, 13, 2, '2026-03-24', '2026-03-24'),
(403, 247, 274, '3', 46, 13, 2, '2026-03-24', '2026-03-24'),
(404, 247, 278, '2', 47, 14, 2, '2026-03-24', '2026-03-24'),
(405, 247, 282, '2', 48, 14, 2, '2026-03-24', '2026-03-24'),
(406, 247, 287, '3', 49, 14, 2, '2026-03-24', '2026-03-24'),
(407, 247, 289, '1', 50, 14, 2, '2026-03-24', '2026-03-24'),
(408, 247, 294, '2', 51, 14, 2, '2026-03-24', '2026-03-24'),
(409, 247, 298, '2', 52, 15, 2, '2026-03-24', '2026-03-24'),
(410, 247, 305, '1', 53, 16, 2, '2026-03-24', '2026-03-24'),
(411, 247, 312, '4', 54, 16, 2, '2026-03-24', '2026-03-24'),
(412, 247, 315, '3', 55, 16, 2, '2026-03-24', '2026-03-24'),
(413, 247, 318, '2', 56, 16, 2, '2026-03-24', '2026-03-24'),
(414, 247, 322, '2', 57, 16, 2, '2026-03-24', '2026-03-24'),
(415, 247, 326, '2', 58, 16, 2, '2026-03-24', '2026-03-24'),
(416, 247, 330, '2', 59, 16, 2, '2026-03-24', '2026-03-24'),
(417, 247, 333, '1', 60, 17, 2, '2026-03-24', '2026-03-24'),
(418, 247, 336, '1', 61, 17, 2, '2026-03-24', '2026-03-24'),
(419, 247, 340, '2', 62, 18, 4, '2026-03-24', '2026-03-24'),
(420, 247, 343, '1', 63, 18, 4, '2026-03-24', '2026-03-24'),
(421, 247, 350, '2', 64, 18, 4, '2026-03-24', '2026-03-24'),
(422, 247, 358, '3', 65, 18, 4, '2026-03-24', '2026-03-24'),
(423, 247, 362, '2', 66, 19, 4, '2026-03-24', '2026-03-24'),
(424, 247, 366, '2', 67, 19, 4, '2026-03-24', '2026-03-24'),
(425, 247, 370, '2', 68, 19, 4, '2026-03-24', '2026-03-24'),
(426, 247, 375, '3', 69, 20, 4, '2026-03-24', '2026-03-24'),
(427, 247, 378, '2', 70, 20, 4, '2026-03-24', '2026-03-24'),
(428, 247, 382, '2', 71, 21, 4, '2026-03-24', '2026-03-24'),
(429, 256, 1, '6', 1, 1, 1, '2026-03-25', '2026-03-25'),
(430, 256, 8, '5', 2, 1, 1, '2026-03-25', '2026-03-25'),
(431, 256, 15, '3', 3, 1, 1, '2026-03-25', '2026-03-25'),
(432, 256, 374, '2', 69, 20, 4, '2026-03-25', '2026-03-25'),
(433, 256, 377, '1', 70, 20, 4, '2026-03-25', '2026-03-25'),
(434, 260, 1, '6', 1, 1, 1, '2026-03-31', '2026-03-31'),
(435, 260, 8, '5', 2, 1, 1, '2026-03-31', '2026-03-31'),
(436, 260, 14, '2', 3, 1, 1, '2026-03-31', '2026-03-31'),
(437, 260, 19, '1', 4, 2, 1, '2026-03-31', '2026-03-31'),
(438, 260, 28, '4', 5, 3, 1, '2026-03-31', '2026-03-31'),
(439, 260, 32, '2', 6, 3, 1, '2026-03-31', '2026-03-31'),
(440, 260, 38, '2', 7, 3, 1, '2026-03-31', '2026-03-31'),
(441, 260, 43, '1', 8, 4, 1, '2026-03-31', '2026-03-31'),
(442, 260, 50, '2', 9, 4, 1, '2026-03-31', '2026-03-31'),
(443, 260, 55, '1', 10, 4, 1, '2026-03-31', '2026-03-31'),
(444, 260, 69, '9', 11, 5, 1, '2026-03-31', '2026-03-31'),
(445, 260, 79, '9', 12, 5, 1, '2026-03-31', '2026-03-31'),
(446, 260, 81, '+', 13, 5, 1, '2026-03-31', '2026-03-31'),
(447, 260, 87, '2', 14, 6, 1, '2026-03-31', '2026-03-31'),
(448, 260, 93, '2', 15, 6, 1, '2026-03-31', '2026-03-31'),
(449, 260, 99, '2', 16, 6, 1, '2026-03-31', '2026-03-31'),
(450, 260, 102, '2', 17, 7, 1, '2026-03-31', '2026-03-31'),
(451, 260, 108, '2', 18, 8, 2, '2026-03-31', '2026-03-31'),
(452, 260, 116, '4', 19, 8, 2, '2026-03-31', '2026-03-31'),
(453, 260, 120, '2', 20, 8, 2, '2026-03-31', '2026-03-31'),
(454, 260, 129, '5', 21, 8, 2, '2026-03-31', '2026-03-31'),
(455, 260, 134, '4', 22, 8, 2, '2026-03-31', '2026-03-31'),
(456, 260, 140, '4', 23, 8, 2, '2026-03-31', '2026-03-31'),
(457, 260, 143, '1', 24, 8, 2, '2026-03-31', '2026-03-31'),
(458, 260, 150, '2', 25, 9, 2, '2026-03-31', '2026-03-31'),
(459, 260, 156, '2', 26, 10, 2, '2026-03-31', '2026-03-31'),
(460, 260, 166, '6', 27, 10, 2, '2026-03-31', '2026-03-31'),
(461, 260, 168, '2', 28, 10, 2, '2026-03-31', '2026-03-31'),
(462, 260, 174, '2', 29, 10, 2, '2026-03-31', '2026-03-31'),
(463, 260, 180, '2', 30, 10, 2, '2026-03-31', '2026-03-31'),
(464, 260, 185, '1', 31, 11, 2, '2026-03-31', '2026-03-31'),
(465, 260, 192, '2', 32, 11, 2, '2026-03-31', '2026-03-31'),
(466, 260, 199, '3', 33, 11, 2, '2026-03-31', '2026-03-31'),
(467, 260, 204, '2', 34, 11, 2, '2026-03-31', '2026-03-31'),
(468, 260, 210, '2', 35, 12, 2, '2026-03-31', '2026-03-31'),
(469, 260, 216, '2', 36, 12, 2, '2026-03-31', '2026-03-31'),
(470, 260, 222, '2', 37, 12, 2, '2026-03-31', '2026-03-31'),
(471, 260, 228, '2', 38, 12, 2, '2026-03-31', '2026-03-31'),
(472, 260, 234, '2', 39, 12, 2, '2026-03-31', '2026-03-31'),
(473, 260, 240, '2', 40, 12, 2, '2026-03-31', '2026-03-31'),
(474, 260, 246, '2', 41, 12, 2, '2026-03-31', '2026-03-31'),
(475, 260, 253, '3', 42, 12, 2, '2026-03-31', '2026-03-31'),
(476, 260, 257, '1', 43, 13, 2, '2026-03-31', '2026-03-31'),
(477, 260, 263, '2', 44, 13, 2, '2026-03-31', '2026-03-31'),
(478, 260, 268, '2', 45, 13, 2, '2026-03-31', '2026-03-31'),
(479, 260, 273, '2', 46, 13, 2, '2026-03-31', '2026-03-31'),
(480, 260, 278, '2', 47, 14, 2, '2026-03-31', '2026-03-31'),
(481, 260, 283, '3', 48, 14, 2, '2026-03-31', '2026-03-31'),
(482, 260, 287, '3', 49, 14, 2, '2026-03-31', '2026-03-31'),
(483, 260, 289, '1', 50, 14, 2, '2026-03-31', '2026-03-31'),
(484, 260, 293, '1', 51, 14, 2, '2026-03-31', '2026-03-31'),
(485, 260, 298, '2', 52, 15, 2, '2026-03-31', '2026-03-31'),
(486, 260, 0, NULL, 71, 21, 4, '2026-03-31', '2026-03-31'),
(487, 260, 10, '3', 2, 1, 1, '2026-03-31', '2026-03-31'),
(488, 260, 20, '2', 4, 2, 1, '2026-03-31', '2026-03-31'),
(489, 260, 27, '3', 5, 3, 1, '2026-03-31', '2026-03-31'),
(490, 260, 34, '4', 6, 3, 1, '2026-03-31', '2026-03-31'),
(491, 260, 101, '1', 17, 7, 1, '2026-03-31', '2026-03-31'),
(492, 260, 114, '2', 19, 8, 2, '2026-03-31', '2026-03-31'),
(493, 260, 127, '3', 21, 8, 2, '2026-03-31', '2026-03-31'),
(494, 260, 142, '6', 23, 8, 2, '2026-03-31', '2026-03-31'),
(495, 260, 144, '2', 24, 8, 2, '2026-03-31', '2026-03-31'),
(496, 260, 176, '4', 29, 10, 2, '2026-03-31', '2026-03-31'),
(497, 260, 200, '4', 33, 11, 2, '2026-03-31', '2026-03-31'),
(498, 260, 235, '3', 39, 12, 2, '2026-03-31', '2026-03-31'),
(499, 260, 258, '2', 43, 13, 2, '2026-03-31', '2026-03-31'),
(500, 260, 281, '1', 48, 14, 2, '2026-03-31', '2026-03-31'),
(501, 260, 290, '2', 50, 14, 2, '2026-03-31', '2026-03-31'),
(502, 260, 295, '3', 51, 14, 2, '2026-03-31', '2026-03-31'),
(503, 260, 305, '1', 53, 16, 2, '2026-03-31', '2026-03-31'),
(504, 260, 310, '2', 54, 16, 2, '2026-03-31', '2026-03-31'),
(505, 260, 314, '2', 55, 16, 2, '2026-03-31', '2026-03-31'),
(506, 260, 318, '2', 56, 16, 2, '2026-03-31', '2026-03-31'),
(507, 260, 322, '2', 57, 16, 2, '2026-03-31', '2026-03-31'),
(508, 260, 326, '2', 58, 16, 2, '2026-03-31', '2026-03-31'),
(509, 260, 329, '1', 59, 16, 2, '2026-03-31', '2026-03-31'),
(510, 260, 333, '1', 60, 17, 2, '2026-03-31', '2026-03-31'),
(511, 260, 336, '1', 61, 17, 2, '2026-03-31', '2026-03-31'),
(512, 260, 339, '1', 62, 18, 4, '2026-03-31', '2026-03-31'),
(513, 260, 344, '2', 63, 18, 4, '2026-03-31', '2026-03-31'),
(514, 260, 348, '1,3', 64, 18, 4, '2026-03-31', '2026-03-31'),
(515, 260, 355, '1,3', 65, 18, 4, '2026-03-31', '2026-03-31'),
(516, 260, 362, '2', 66, 19, 4, '2026-03-31', '2026-03-31'),
(517, 260, 366, '2', 67, 19, 4, '2026-03-31', '2026-03-31'),
(518, 260, 370, '2', 68, 19, 4, '2026-03-31', '2026-03-31'),
(519, 260, 376, '4', 69, 20, 4, '2026-03-31', '2026-03-31'),
(520, 260, 377, '1', 70, 20, 4, '2026-03-31', '2026-03-31'),
(521, 260, 382, '2', 71, 21, 4, '2026-03-31', '2026-03-31');

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
(1, 'A/Positionnement concurrentiel sur le marché\"', 1),
(2, 'B/Tendance structurelle du marché', 1),
(3, 'C/Marchés cycliques ou à prix volatils\"', 1),
(4, 'D/Portefeuille produit', 1),
(5, 'E/Pression liée à la clientèle', 1),
(6, 'F/Barrières à l\'entrée ', 1),
(7, 'G/Visibilité sur le secteur', 1),
(8, 'H/Qualité du profil du dirigeant\"', 2),
(9, 'I/Qualité de l’organisation', 2),
(10, 'J/Qualité du système de contrôle interne\"', 2),
(11, 'K/Qualité de la Stratégie commerciale et marketing\"', 2),
(12, 'L/Qualité des Moyens humains et techniques\"', 2),
(13, 'M/Adéquation des investissements\"', 2),
(14, 'N/Maîtrise des coûts de production\"', 2),
(15, 'O/Cohérence des  options stratégiques', 2),
(16, 'P/Qualité des relations sociales\"', 2),
(17, 'Q/Succession des Dirigeants\"', 2),
(18, 'R/Fiabilité des prévisions / Evolution produits & charges\"', 4),
(19, 'S/Représentation fidèle réalité comptable et financière/ Transparence des transactions', 4),
(20, 'T/Implication des dirigeants / Surface financière des promoteurs', 4),
(21, 'U/Actifs cessibles', 4);

-- --------------------------------------------------------

--
-- Structure de la table `recommandations`
--

CREATE TABLE `recommandations` (
  `id` int UNSIGNED NOT NULL,
  `dossier_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `avis` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `dossier_id`, `critere_id`, `choice_id`, `note`, `ponderation`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 13, 3, 1, 0.03, '2024-12-05 15:41:59', '2024-12-05 15:41:59'),
(2, 1, 33, 193, 3, 5, 0.15, '2024-12-05 16:06:36', '2024-12-05 16:06:36'),
(3, 1, 8, 75, 5, 4, 0.2, '2024-12-06 07:43:38', '2024-12-06 07:43:38'),
(4, 1, 9, 86, 6, 1, 0.06, '2024-12-06 07:43:51', '2024-12-06 07:43:51'),
(5, 1, 10, 98, 8, 1, 0.08, '2024-12-06 07:43:57', '2024-12-06 07:43:57'),
(6, 1, 3, 29, 9, 1, 0.09, '2024-12-06 07:44:03', '2024-12-06 07:44:03'),
(7, 1, 4, 39, 9, 2, 0.18, '2024-12-06 07:44:10', '2024-12-06 07:44:10'),
(8, 1, 7, 65, 5, 1, 0.05, '2024-12-06 07:44:24', '2024-12-06 07:44:24'),
(9, 1, 13, 122, 2, 3, 0.06, '2024-12-06 09:26:00', '2024-12-06 09:26:00'),
(10, 1, 31, 173, 3, 5, 0.15, '2024-12-06 09:26:10', '2024-12-06 09:26:10'),
(11, 1, 1, 3, 3, 1.5, 0.045, '2024-12-06 11:25:00', '2024-12-06 11:25:00'),
(12, 1, 5, 41, 1, 2, 0.02, '2024-12-06 12:25:17', '2024-12-06 11:25:17'),
(13, 1, 32, 182, 2, 5, 0.1, '2024-12-06 11:25:40', '2024-12-06 11:25:40'),
(14, 1, 34, 202, 2, 10, 0.2, '2024-12-06 11:25:50', '2024-12-06 11:25:50'),
(15, 4, 1, 2, 2, 1.5, 0.03, '2025-01-06 14:08:27', '2025-01-06 14:08:27'),
(16, 4, 33, 195, 5, 5, 0.25, '2025-01-06 14:08:38', '2025-01-06 14:08:38'),
(17, 4, 34, 204, 4, 10, 0.4, '2025-01-06 14:08:47', '2025-01-06 14:08:47'),
(18, 4, 31, 174, 4, 5, 0.2, '2025-01-06 14:08:56', '2025-01-06 14:08:56'),
(19, 4, 2, 12, 2, 1, 0.02, '2025-01-06 14:09:02', '2025-01-06 14:09:02'),
(20, 4, 8, 73, 3, 4, 0.12, '2025-01-06 14:17:01', '2025-01-06 14:17:01'),
(21, 4, 15, 146, 6, 3, 0.18, '2025-01-06 14:17:11', '2025-01-06 14:17:11'),
(22, 4, 7, 68, 8, 1, 0.08, '2025-01-06 14:17:20', '2025-01-06 14:17:20'),
(23, 4, 4, 35, 5, 2, 0.1, '2025-01-06 14:17:30', '2025-01-06 14:17:30'),
(24, 5, 7, 65, 5, 1, 0.05, '2025-01-08 07:52:51', '2025-01-08 07:52:51'),
(25, 5, 14, 135, 5, 3, 0.15, '2025-01-08 07:53:00', '2025-01-08 07:53:00'),
(26, 5, 2, 13, 3, 1, 0.03, '2025-01-08 07:53:13', '2025-01-08 07:53:13'),
(27, 6, 1, 4, 4, 1.5, 0.06, '2025-01-08 13:10:04', '2025-01-08 13:10:04'),
(28, 6, 2, 15, 5, 1, 0.05, '2025-01-08 13:10:45', '2025-01-08 13:10:45'),
(29, 6, 3, 24, 4, 1, 0.04, '2025-01-08 13:11:15', '2025-01-08 13:11:15'),
(30, 6, 4, 35, 5, 2, 0.1, '2025-01-08 13:11:23', '2025-01-08 13:11:23'),
(31, 6, 5, 45, 5, 2, 0.1, '2025-01-08 13:11:32', '2025-01-08 13:11:32'),
(32, 6, 6, 57, 7, 1.5, 0.105, '2025-01-08 13:11:41', '2025-01-08 13:11:41'),
(33, 6, 7, 67, 7, 1, 0.07, '2025-01-08 13:11:51', '2025-01-08 13:11:51'),
(34, 6, 8, 75, 5, 4, 0.2, '2025-01-08 13:12:09', '2025-01-08 13:12:09'),
(35, 6, 9, 84, 4, 1, 0.04, '2025-01-08 13:12:19', '2025-01-08 13:12:19'),
(36, 6, 10, 94, 4, 1, 0.04, '2025-01-08 13:12:30', '2025-01-08 13:12:30'),
(37, 6, 11, 104, 4, 1, 0.04, '2025-01-08 13:12:39', '2025-01-08 13:12:39'),
(38, 6, 12, 120, 10, 2, 0.2, '2025-01-08 13:12:47', '2025-01-08 13:12:47'),
(39, 6, 14, 138, 8, 3, 0.24, '2025-01-08 13:13:02', '2025-01-08 13:13:02'),
(40, 6, 13, 130, 10, 3, 0.3, '2025-01-08 13:13:13', '2025-01-08 13:13:13'),
(41, 6, 15, 149, 9, 3, 0.27, '2025-01-08 13:13:26', '2025-01-08 13:13:26'),
(42, 6, 16, 153, 3, 1, 0.03, '2025-01-08 13:13:37', '2025-01-08 13:13:37'),
(43, 6, 17, 162, 2, 1, 0.02, '2025-01-08 13:13:48', '2025-01-08 13:13:48'),
(44, 6, 31, 172, 2, 5, 0.1, '2025-01-08 13:14:05', '2025-01-08 13:14:05'),
(45, 6, 32, 183, 3, 5, 0.15, '2025-01-08 13:14:17', '2025-01-08 13:14:17'),
(46, 6, 33, 195, 5, 5, 0.25, '2025-01-08 13:14:28', '2025-01-08 13:14:28'),
(47, 6, 34, 208, 8, 1, 0.08, '2025-03-16 11:52:17', '2025-01-08 13:14:40'),
(48, 7, 1, 4, 4, 1.5, 0.06, '2025-01-09 07:32:07', '2025-01-09 07:32:07'),
(49, 7, 2, 15, 5, 1, 0.05, '2025-01-09 07:32:18', '2025-01-09 07:32:18'),
(50, 7, 3, 26, 6, 1, 0.06, '2025-01-09 07:32:28', '2025-01-09 07:32:28'),
(51, 7, 4, 32, 2, 2, 0.04, '2025-01-09 07:32:44', '2025-01-09 07:32:44'),
(52, 7, 5, 47, 7, 2, 0.14, '2025-01-09 07:32:55', '2025-01-09 07:32:55'),
(53, 7, 6, 53, 3, 1.5, 0.045, '2025-01-09 07:33:05', '2025-01-09 07:33:05'),
(54, 7, 7, 69, 9, 1, 0.09, '2025-01-09 07:33:19', '2025-01-09 07:33:19'),
(55, 7, 8, 78, 8, 4, 0.32, '2025-01-09 07:33:42', '2025-01-09 07:33:42'),
(56, 7, 9, 88, 8, 1, 0.08, '2025-01-09 07:33:56', '2025-01-09 07:33:56'),
(57, 7, 15, 143, 3, 3, 0.09, '2025-01-09 07:34:12', '2025-01-09 07:34:12'),
(58, 8, 1, 3, 3, 1.5, 0.045, '2025-02-10 09:31:03', '2025-02-10 09:31:03'),
(59, 8, 2, 17, 7, 1, 0.07, '2025-02-10 09:31:10', '2025-02-10 09:31:10'),
(60, 8, 3, 27, 7, 1, 0.07, '2025-02-10 10:31:23', '2025-02-10 09:31:23'),
(61, 8, 4, 35, 5, 2, 0.1, '2025-02-10 09:53:49', '2025-02-10 09:53:49'),
(62, 8, 5, 44, 4, 2, 0.08, '2025-02-10 09:53:56', '2025-02-10 09:53:56'),
(63, 8, 10, 94, 4, 1, 0.04, '2025-02-10 09:54:22', '2025-02-10 09:54:22'),
(64, 8, 11, 103, 3, 1, 0.03, '2025-02-10 09:54:34', '2025-02-10 09:54:34'),
(65, 8, 6, 55, 5, 1.5, 0.075, '2025-02-10 09:54:42', '2025-02-10 09:54:42'),
(66, 8, 12, 113, 3, 2, 0.06, '2025-02-10 09:54:52', '2025-02-10 09:54:52'),
(67, 8, 13, 124, 4, 3, 0.12, '2025-02-10 09:55:05', '2025-02-10 09:55:05'),
(68, 9, 1, 2, 2, 1.5, 0.03, '2025-02-10 10:34:03', '2025-02-10 10:34:03'),
(69, 9, 2, 12, 2, 1, 0.02, '2025-02-10 10:34:14', '2025-02-10 10:34:14'),
(70, 9, 3, 29, 9, 1, 0.09, '2025-02-10 10:34:21', '2025-02-10 10:34:21'),
(71, 9, 4, 39, 9, 2, 0.18, '2025-02-10 10:34:28', '2025-02-10 10:34:28'),
(72, 9, 5, 48, 8, 2, 0.16, '2025-02-10 10:34:37', '2025-02-10 10:34:37'),
(73, 9, 6, 57, 7, 1.5, 0.105, '2025-02-10 10:34:46', '2025-02-10 10:34:46'),
(74, 9, 7, 67, 7, 1, 0.07, '2025-02-10 10:34:55', '2025-02-10 10:34:55'),
(75, 9, 8, 73, 3, 4, 0.12, '2025-02-10 10:39:22', '2025-02-10 10:39:22'),
(76, 9, 9, 87, 7, 1, 0.07, '2025-02-10 10:39:34', '2025-02-10 10:39:34'),
(77, 9, 10, 97, 7, 1, 0.07, '2025-02-10 10:39:44', '2025-02-10 10:39:44'),
(78, 9, 11, 108, 8, 1, 0.08, '2025-02-10 10:39:56', '2025-02-10 10:39:56'),
(79, 9, 12, 112, 2, 2, 0.04, '2025-02-10 10:40:06', '2025-02-10 10:40:06'),
(80, 9, 13, 127, 7, 3, 0.21, '2025-02-10 10:40:15', '2025-02-10 10:40:15'),
(81, 9, 14, 138, 8, 3, 0.24, '2025-02-10 10:40:27', '2025-02-10 10:40:27'),
(82, 9, 15, 147, 7, 3, 0.21, '2025-02-10 10:41:56', '2025-02-10 10:41:56'),
(83, 9, 16, 155, 5, 1, 0.05, '2025-02-10 10:42:10', '2025-02-10 10:42:10'),
(84, 9, 17, 166, 6, 1, 0.06, '2025-02-10 10:42:25', '2025-02-10 10:42:25'),
(85, 7, 13, 125, 5, 3, 0.15, '2025-02-12 09:40:51', '2025-02-12 09:40:51'),
(86, 7, 33, 191, 1, 5, 0.05, '2025-02-12 10:41:11', '2025-02-12 09:41:11'),
(87, 11, 1, 2, 2, 1.5, 0.03, '2025-02-12 11:21:42', '2025-02-12 11:21:42'),
(88, 11, 2, 12, 2, 1, 0.02, '2025-02-12 11:21:49', '2025-02-12 11:21:49'),
(89, 11, 31, 172, 2, 5, 0.1, '2025-02-12 11:22:21', '2025-02-12 11:22:21'),
(90, 11, 3, 24, 4, 1, 0.04, '2025-02-12 11:22:27', '2025-02-12 11:22:27'),
(91, 11, 7, 62, 2, 1, 0.02, '2025-02-12 11:22:35', '2025-02-12 11:22:35'),
(92, 11, 4, 33, 3, 2, 0.06, '2025-02-12 11:22:42', '2025-02-12 11:22:42'),
(93, 11, 5, 45, 5, 2, 0.1, '2025-02-12 11:22:49', '2025-02-12 11:22:49'),
(94, 11, 6, 53, 3, 1.5, 0.045, '2025-02-12 11:22:56', '2025-02-12 11:22:56'),
(95, 11, 32, 186, 6, 5, 0.3, '2025-02-12 11:23:27', '2025-02-12 11:23:27'),
(96, 13, 1, 3, 3, 1.5, 0.045, '2025-02-20 11:49:25', '2025-02-20 11:49:25'),
(97, 13, 2, 14, 4, 1, 0.04, '2025-02-20 11:49:33', '2025-02-20 11:49:33'),
(98, 13, 3, 24, 4, 1, 0.04, '2025-02-20 11:49:42', '2025-02-20 11:49:42'),
(99, 13, 4, 33, 3, 2, 0.06, '2025-02-20 12:50:01', '2025-02-20 11:50:01'),
(100, 13, 5, 43, 3, 2, 0.06, '2025-02-20 11:50:28', '2025-02-20 11:50:28'),
(101, 13, 6, 55, 5, 1.5, 0.075, '2025-02-20 11:50:36', '2025-02-20 11:50:36'),
(102, 13, 7, 64, 4, 1, 0.04, '2025-02-20 11:50:45', '2025-02-20 11:50:45'),
(103, 13, 8, 72, 2, 4, 0.08, '2025-02-20 11:50:57', '2025-02-20 11:50:57'),
(104, 13, 9, 85, 5, 1, 0.05, '2025-02-20 11:51:23', '2025-02-20 11:51:23'),
(105, 13, 10, 94, 4, 1, 0.04, '2025-02-20 11:51:31', '2025-02-20 11:51:31'),
(106, 13, 11, 104, 4, 1, 0.04, '2025-02-20 11:51:41', '2025-02-20 11:51:41'),
(107, 13, 12, 115, 5, 2, 0.1, '2025-02-20 11:51:52', '2025-02-20 11:51:52'),
(108, 13, 31, 174, 4, 5, 0.2, '2025-02-20 11:52:06', '2025-02-20 11:52:06'),
(109, 14, 1, 2, 2, 1.5, 0.03, '2025-02-27 08:56:25', '2025-02-27 08:56:25'),
(110, 14, 2, 12, 2, 1, 0.02, '2025-02-27 08:56:32', '2025-02-27 08:56:32'),
(111, 14, 3, 24, 4, 1, 0.04, '2025-02-27 08:56:41', '2025-02-27 08:56:41'),
(112, 14, 4, 33, 3, 2, 0.06, '2025-02-27 08:56:50', '2025-02-27 08:56:50'),
(113, 14, 5, 43, 3, 2, 0.06, '2025-02-27 08:56:58', '2025-02-27 08:56:58'),
(114, 14, 6, 53, 3, 1.5, 0.045, '2025-02-27 08:57:07', '2025-02-27 08:57:07'),
(115, 14, 7, 64, 4, 1, 0.04, '2025-02-27 08:57:16', '2025-02-27 08:57:16'),
(116, 14, 8, 73, 3, 4, 0.12, '2025-02-27 08:57:27', '2025-02-27 08:57:27'),
(117, 14, 9, 85, 5, 1, 0.05, '2025-02-27 08:57:36', '2025-02-27 08:57:36'),
(118, 14, 10, 92, 2, 1, 0.02, '2025-02-27 08:57:47', '2025-02-27 08:57:47'),
(119, 14, 11, 105, 5, 1, 0.05, '2025-02-27 08:58:04', '2025-02-27 08:58:04'),
(120, 14, 12, 114, 4, 2, 0.08, '2025-02-27 08:58:13', '2025-02-27 08:58:13'),
(121, 14, 13, 127, 7, 3, 0.21, '2025-02-27 08:58:22', '2025-02-27 08:58:22'),
(122, 14, 14, 136, 6, 3, 0.18, '2025-02-27 08:58:32', '2025-02-27 08:58:32'),
(123, 14, 15, 142, 2, 3, 0.06, '2025-02-27 08:58:42', '2025-02-27 08:58:42'),
(124, 14, 16, 154, 4, 1, 0.04, '2025-02-27 08:58:52', '2025-02-27 08:58:52'),
(125, 14, 17, 166, 6, 1, 0.06, '2025-02-27 08:59:04', '2025-02-27 08:59:04'),
(126, 14, 31, 175, 5, 5, 0.25, '2025-02-27 09:00:24', '2025-02-27 09:00:24'),
(127, 14, 32, 184, 4, 5, 0.2, '2025-02-27 09:00:35', '2025-02-27 09:00:35'),
(128, 14, 33, 193, 3, 5, 0.15, '2025-02-27 09:00:46', '2025-02-27 09:00:46'),
(129, 14, 34, 204, 4, 10, 0.4, '2025-02-27 09:00:57', '2025-02-27 09:00:57'),
(141, 12, 1, 2, 2, 1.5, 0.2, '2025-03-16 11:56:33', '2025-03-16 10:46:19'),
(142, 12, 2, 12, 2, 1, 0.02, '2025-03-16 11:57:15', '2025-03-16 10:57:15'),
(143, 12, 3, 28, 8, 1, 0.08, '2025-03-16 11:57:22', '2025-03-16 10:57:22'),
(144, 12, 4, 32, 2, 2, 0.04, '2025-03-16 11:57:29', '2025-03-16 10:57:29'),
(145, 12, 33, 191, 1, 5, 0.05, '2025-03-16 12:24:56', '2025-03-16 11:24:56'),
(146, 12, 5, 42, 2, 2, 0.04, '2025-03-16 11:16:53', '2025-03-16 11:16:53'),
(147, 12, 9, 84, 4, 1, 0.04, '2025-03-16 11:18:46', '2025-03-16 11:18:46'),
(148, 19, 1, 4, 4, 1.5, 0.06, '2026-03-19 14:46:39', '2026-03-19 14:46:39'),
(149, 19, 2, 14, 4, 1, 0.04, '2026-03-19 14:47:00', '2026-03-19 14:47:00'),
(150, 19, 9, 82, 2, 1, 0.02, '2026-03-19 14:47:44', '2026-03-19 14:47:44'),
(151, 22, 1, 4, 4, 1.5, 0.06, '2026-03-24 11:36:39', '2026-03-24 11:36:39'),
(152, 22, 2, 14, 4, 1, 0.04, '2026-03-24 11:37:02', '2026-03-24 11:37:02'),
(153, 22, 3, 26, 6, 1, 0.06, '2026-03-24 11:37:47', '2026-03-24 11:37:47'),
(154, 22, 4, 33, 3, 2, 0.06, '2026-03-24 11:38:42', '2026-03-24 11:38:42'),
(155, 22, 5, 42, 2, 2, 0.04, '2026-03-24 11:38:55', '2026-03-24 11:38:55'),
(156, 22, 6, 53, 3, 1.5, 0.045, '2026-03-24 11:39:10', '2026-03-24 11:39:10'),
(157, 22, 7, 65, 5, 1, 0.05, '2026-03-24 11:39:34', '2026-03-24 11:39:34'),
(158, 22, 8, 73, 3, 4, 0.12, '2026-03-24 11:39:57', '2026-03-24 11:39:57'),
(159, 22, 9, 84, 4, 1, 0.04, '2026-03-24 11:40:09', '2026-03-24 11:40:09'),
(160, 22, 10, 96, 6, 1, 0.06, '2026-03-24 11:40:24', '2026-03-24 11:40:24'),
(161, 22, 11, 103, 3, 1, 0.03, '2026-03-24 11:40:37', '2026-03-24 11:40:37'),
(162, 22, 12, 114, 4, 2, 0.08, '2026-03-24 11:40:49', '2026-03-24 11:40:49'),
(163, 22, 13, 122, 2, 3, 0.06, '2026-03-24 11:41:12', '2026-03-24 11:41:12'),
(164, 22, 14, 133, 3, 3, 0.09, '2026-03-24 11:41:27', '2026-03-24 11:41:27'),
(165, 22, 15, 143, 3, 3, 0.09, '2026-03-24 11:41:54', '2026-03-24 11:41:54'),
(166, 22, 16, 153, 3, 1, 0.03, '2026-03-24 11:42:31', '2026-03-24 11:42:31'),
(167, 22, 17, 163, 3, 1, 0.03, '2026-03-24 11:43:26', '2026-03-24 11:43:26'),
(168, 22, 31, 173, 3, 5, 0.15, '2026-03-24 11:44:06', '2026-03-24 11:44:06'),
(169, 22, 32, 183, 3, 5, 0.15, '2026-03-24 11:44:30', '2026-03-24 11:44:30'),
(170, 22, 33, 191, 1, 5, 0.05, '2026-03-24 11:44:49', '2026-03-24 11:44:49'),
(171, 22, 34, 202, 2, 10, 0.2, '2026-03-24 11:45:30', '2026-03-24 11:45:30'),
(172, 21, 1, 2, 2, 1.5, 0.03, '2026-03-24 14:20:01', '2026-03-24 14:20:01'),
(173, 23, 1, 3, 3, 1.5, 0.045, '2026-03-24 15:16:43', '2026-03-24 15:16:43'),
(174, 23, 2, 14, 4, 1, 0.04, '2026-03-24 15:20:04', '2026-03-24 15:20:04'),
(175, 23, 5, 44, 4, 2, 0.08, '2026-03-24 15:27:58', '2026-03-24 15:27:58'),
(176, 23, 3, 24, 4, 1, 0.04, '2026-03-24 15:32:10', '2026-03-24 15:32:10'),
(177, 23, 4, 34, 4, 2, 0.08, '2026-03-24 15:40:02', '2026-03-24 15:40:02'),
(178, 23, 8, 80, 10, 4, 0.4, '2026-03-24 15:52:25', '2026-03-24 15:52:25'),
(179, 23, 9, 81, 1, 1, 0.01, '2026-03-24 15:54:29', '2026-03-24 15:54:29'),
(180, 23, 12, 117, 7, 2, 0.14, '2026-03-24 15:56:30', '2026-03-24 15:56:30'),
(181, 23, 31, 175, 5, 5, 0.25, '2026-03-24 15:57:15', '2026-03-24 15:57:15'),
(182, 25, 1, 1, 1, 1.5, 0.015, '2026-03-30 10:51:47', '2026-03-30 10:51:47'),
(183, 25, 2, 17, 7, 1, 0.07, '2026-03-30 10:52:38', '2026-03-30 10:52:38'),
(184, 25, 3, 24, 4, 1, 0.04, '2026-03-30 10:55:00', '2026-03-30 10:55:00'),
(185, 25, 4, 35, 5, 2, 0.1, '2026-03-30 10:59:52', '2026-03-30 10:59:52'),
(186, 25, 5, 42, 2, 2, 0.04, '2026-03-30 11:00:06', '2026-03-30 11:00:06'),
(187, 25, 6, 55, 5, 1.5, 0.075, '2026-03-30 11:00:20', '2026-03-30 11:00:20'),
(188, 25, 7, 66, 6, 1, 0.06, '2026-03-30 11:01:46', '2026-03-30 11:01:46'),
(189, 25, 8, 73, 3, 4, 0.12, '2026-03-30 11:02:26', '2026-03-30 11:02:26'),
(190, 25, 9, 84, 4, 1, 0.04, '2026-03-30 11:04:57', '2026-03-30 11:04:57'),
(191, 25, 10, 96, 6, 1, 0.06, '2026-03-30 11:05:12', '2026-03-30 11:05:12'),
(192, 25, 11, 104, 4, 1, 0.04, '2026-03-30 11:05:27', '2026-03-30 11:05:27'),
(193, 25, 12, 113, 3, 2, 0.06, '2026-03-30 11:05:41', '2026-03-30 11:05:41'),
(194, 25, 13, 124, 4, 3, 0.12, '2026-03-30 11:05:59', '2026-03-30 11:05:59'),
(195, 25, 14, 132, 2, 3, 0.06, '2026-03-30 11:06:19', '2026-03-30 11:06:19'),
(196, 25, 15, 143, 3, 3, 0.09, '2026-03-30 11:06:40', '2026-03-30 11:06:40'),
(197, 25, 31, 174, 4, 5, 0.2, '2026-03-30 11:06:59', '2026-03-30 11:06:59'),
(198, 25, 16, 155, 5, 1, 0.05, '2026-03-30 11:07:38', '2026-03-30 11:07:38'),
(199, 25, 17, 166, 6, 1, 0.06, '2026-03-30 11:08:07', '2026-03-30 11:08:07'),
(200, 25, 32, 184, 4, 5, 0.2, '2026-03-30 11:08:40', '2026-03-30 11:08:40'),
(201, 25, 33, 192, 2, 5, 0.1, '2026-03-30 11:09:10', '2026-03-30 11:09:10'),
(202, 25, 34, 209, 9, 10, 0.9, '2026-03-30 11:09:32', '2026-03-30 11:09:32'),
(203, 27, 1, 4, 4, 1.5, 0.06, '2026-03-31 11:32:48', '2026-03-31 11:32:48'),
(204, 27, 2, 13, 3, 1, 0.03, '2026-03-31 11:33:04', '2026-03-31 11:33:04'),
(205, 27, 3, 24, 4, 1, 0.04, '2026-03-31 11:33:31', '2026-03-31 11:33:31'),
(206, 27, 4, 34, 4, 2, 0.08, '2026-03-31 11:33:51', '2026-03-31 11:33:51'),
(207, 27, 5, 42, 2, 2, 0.04, '2026-03-31 11:34:26', '2026-03-31 11:34:26'),
(208, 27, 6, 56, 6, 1.5, 0.09, '2026-03-31 11:34:48', '2026-03-31 11:34:48'),
(209, 27, 7, 66, 6, 1, 0.06, '2026-03-31 11:35:27', '2026-03-31 11:35:27'),
(210, 27, 8, 73, 3, 4, 0.12, '2026-03-31 11:36:19', '2026-03-31 11:36:19'),
(211, 27, 9, 83, 3, 1, 0.03, '2026-03-31 11:36:46', '2026-03-31 11:36:46'),
(212, 27, 10, 93, 3, 1, 0.03, '2026-03-31 11:37:15', '2026-03-31 11:37:15'),
(213, 27, 11, 103, 3, 1, 0.03, '2026-03-31 11:37:49', '2026-03-31 11:37:49'),
(214, 27, 12, 113, 3, 2, 0.06, '2026-03-31 11:38:17', '2026-03-31 11:38:17'),
(215, 27, 13, 124, 4, 3, 0.12, '2026-03-31 11:38:50', '2026-03-31 11:38:50'),
(216, 27, 14, 133, 3, 3, 0.09, '2026-03-31 11:39:19', '2026-03-31 11:39:19'),
(217, 27, 15, 144, 4, 3, 0.12, '2026-03-31 11:39:52', '2026-03-31 11:39:52'),
(218, 27, 16, 153, 3, 1, 0.03, '2026-03-31 11:40:18', '2026-03-31 11:40:18'),
(219, 27, 17, 161, 1, 1, 0.01, '2026-03-31 11:40:46', '2026-03-31 11:40:46'),
(220, 27, 31, 172, 2, 5, 0.1, '2026-03-31 11:42:39', '2026-03-31 11:42:39'),
(221, 27, 32, 184, 4, 5, 0.2, '2026-03-31 11:43:03', '2026-03-31 11:43:03'),
(222, 27, 33, 191, 1, 5, 0.05, '2026-03-31 11:44:05', '2026-03-31 11:44:05'),
(223, 27, 34, 202, 2, 10, 0.2, '2026-03-31 11:44:41', '2026-03-31 11:44:41');

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
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `secteurs`
--

CREATE TABLE `secteurs` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `agence_id` int NOT NULL DEFAULT '0',
  `localisation` varchar(192) DEFAULT NULL,
  `contacts` text,
  `token` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `secteurs`
--

INSERT INTO `secteurs` (`id`, `name`, `agence_id`, `localisation`, `contacts`, `token`, `active`) VALUES
(1, 'SECTEUR GRAND CENTRE', 4, NULL, NULL, '739824b9837933ee072ee226aa0895064a697fbd', 1),
(2, 'SECTEUR GRAND NORD', 4, 'Lat:573279239837,Lgn873847843', 'Voici quelques infos.', 'e97d8359e41fe78125b7d44fdca930b8906b3323', 1),
(3, 'SECTEUR GRAND NORD 1', 1, '6237923 73289823 93y29u32', 'Quelque infos', 'b34896ce28876b6c7680838c47844e6787715e64', 1),
(4, 'SECTEUR GRAND NORD 2', 1, '63298923 893289382 89238932', 'Informations sur le secteur grand nord 2', '2c8d67cf3a19a12090e691793e655bb4a8d7d59d', 1),
(5, 'ZONE 4', 6, '56675.5,79890', 'Essomba 6778, Owona remy 673287382', 'ceb3da1fff72ee16fb251f8fc8e9407e2b2c92d6', 1),
(6, 'BAFOUSSAM', 10, 'MENDZI', '65225689', 'fa254ab9c1f3fa2f9facd481fa3fbe55ca0972d3', 1);

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
-- Structure de la table `sme_notes`
--

CREATE TABLE `sme_notes` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `mention` varchar(255) DEFAULT NULL,
  `note` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sme_notes`
--

INSERT INTO `sme_notes` (`id`, `name`, `description`, `mention`, `note`, `created_at`, `updated_at`) VALUES
(1, 'SME1', 'Situation économique et financière et visibilité excellentes :  forte position sur ses marchés, faible endettement par rapport aux fonds propres et au cash flow, peu sensible aux aléas conjoncturels (grande stabilité de la situation financière et des résultats à travers les cycles de son industrie et de l\'économie en général). Sa taille et sa situation lui donnent un accès très facile aux marchés financiers. La capacité du débiteur à exécuter ses engagements financiers est TRES FORTE', 'Excellent', 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(2, 'SME2', 'La situation financière et le niveau de cash flow actuels sont très bons, avec un historique montrant une bonne stabilité, mais avec une légère sensibilité aux aléas conjoncturels. Endettement modéré par rapport aux fonds propres et au cash flow. Solide position sur ses marchés. Très bonne visibilité à court et moyen terme. Accès facile aux marchés financiers. Sa capacité à exécuter ses engagements financiers est FORTE.', 'Très Bon', 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(3, 'SME3', 'Situation financière et cash flow convenables mais montrant quelque volatilité, et qui pourraient être affaiblis par une conjoncture (cycle) ou des évènements défavorables dont l\'occurrence est possible. Endettement par rapport aux fonds propres et au cash flow dans la moyenne. Bonne visibilité à court et moyen terme. Possibilité d\'accéder à de nouveaux financements dans une conjoncture normale. Cette note s\'applique aussi aux PME dont la situation financière est solide mais qui sont exclues des catégories précédentes en raison de leur taille.', 'Bon', 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(4, 'SME4', 'Situation financière et cash flow moyens, avec une plus grande volatilité de la situation financière et du cash flow. Il existe quelques facteurs de risque qui pourraient affaiblir la capacité du débiteur à exécuter ses engagements financiers. Cependant, visibilité satisfaisante à court et moyen terme. Accès restreint aux marchés financiers et plus coûteux, mais possibilité de développement des engagements avec ses banques.', 'Assez Bon', 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(5, 'SME5', 'Cash flow suffisant pour le service de la dette. Les incertitudes sur les fondamentaux du débiteur et son exposition à divers risques (sectoriels, financiers, économiques) peuvent affecter à terme sa capacité à s\'acquitter de ses obligations. Visibilité satisfaisante à court terme mais moins claire à moyen terme. Un accroissement des engagements auprès de ses banques reste encore envisageable. La qualité du management est un élément important de la décision. Accès aux marchés financiers limité, restreint et plus coûteux.', 'Moyen', 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(6, 'SME6', 'Les risques sectoriels, financiers et économiques sont importants mais devraient être compensés par les fondamentaux du débiteur. Possibilité limitée de trouver des financements en dehors de ses banques. La qualité du management est un élément important de la décision. Les opérations doivent être structurées (garanties, covenants) pour limiter le risque et la rémunération doit être accrue pour tenir compte de la prime de risque plus élevée.', 'Acceptable', 6, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(7, 'SME7', 'Endettement important pour le secteur. Les risques sectoriels, financiers et économiques sont importants et insuffisamment compensés par les fondamentaux du débiteur qui ne présentent pas une qualité, une stabilité et une visibilité suffisantes. Ce niveau nécessite un suivi attentif. La qualité du management est un élément primordial de la décision. Ce niveau nécessite une grande exigence dans la structuration des opérations et une rémunération significative.', 'Potentiellement vulnérable', 7, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(8, 'SME7-', 'Une aggravation des risques économiques et financiers qui pèsent sur le débiteur le conduirait  vraisemblablement à faire défaut sur ses engagements financiers.De nouvelles opérations ne peuvent être envisagées qu\'avec une extrême rigueur dans la structuration et uniquement dans le cadre de politiques de crédit dûment autorisées.', 'Vulnérable', 8, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(9, 'SME8', 'En l\'absence d\'amélioration de l\'environnement (conjoncture et marché), et/ou de mesures drastiques de restructuration industrielle ou financière, la survie de l\'entreprise serait en question. Ce niveau doit être suivi de près et assorti d\'objectifs précis pour réduire les risques (réduction des concours, garanties, etc.). Normalement, il exclut  une entrée en relation ou un accroissement des engagements.', 'Très vulnérable', 9, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(10, 'SME8-', 'L\'existence d\'échéances financières impayées en principal et/ou en intérêts depuis plus de 3 mois entraîne le classement dans cette catégorie. Les débiteurs les plus faibles de cette catégorie sont dans une situation nettement dégradée et préoccupante quant à la bonne fin des crédits. Un dépôt de bilan est fortement possible. Ce niveau doit être suivi de très près. Le suivi doit être assortie d\'objectifs précis (réduction des concours, amélioration des garanties, etc.). La gestion doit être centralisée (prise en charge par les équipes spécialisées dans la prévention de la défaillance).', 'Douteux et/ou compromis', 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05');

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sous_criteres`
--

INSERT INTO `sous_criteres` (`id`, `name`, `critere_id`, `sequence`, `default`, `created_at`, `updated_at`) VALUES
(1, 'positionnement concurentiel sur le marché', 1, 1, 1.5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(2, 'Tendance structurelle du marché	', 1, 2, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(3, 'Marchés cycliques ou à prix volatils', 1, 3, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(4, 'Portefeuille produit', 1, 4, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(5, 'Pression liée à la clientèle', 1, 5, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(6, 'Barrière à l\'entrée', 1, 6, 1.5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(7, 'Visibilité du secteur', 1, 7, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(8, 'Qualité du profil du drigeant', 2, 8, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(9, 'Qualité de l\'organisation', 2, 9, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(10, 'Qualité du système de contrôle interne', 2, 10, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(11, 'Qualité de la Stratégie commerciale et marketing', 2, 11, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(12, 'Qualité des Moyens humains et techniques', 2, 12, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(13, 'Adéquation des investissements / Impact sur l\'emploi', 2, 13, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(14, 'Maîtrise des coûts de productivité / Impact sur la création des richesses', 2, 14, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(15, 'Cohérence des options stratégiques / Impact sur l\'environnement	', 2, 15, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(16, 'Qualité des relations sociales', 2, 16, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(17, 'Succession des Dirigeants', 2, 17, 1, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(18, 'Dynamique de l\'Equilibre Financier (FR/BFR)', 3, 18, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(19, 'Ratio d\'endettement global ((Actifs - Capitaux propres)/ Actifs) <1', 3, 19, 3, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(20, 'Capacité de  Remboursement (DLMT/CAFG)', 3, 20, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(21, 'Couverture des frais financiers (FTAO/FF)', 3, 21, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(22, 'Solvabilité globale  Risque liquidatif (Ressources Propres /Total Bilan)', 3, 22, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(23, 'Liquidité générale (Ratio de FR= Actifs circulants/ Passifs circulants)>1', 3, 23, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(24, 'Rentabilité d\'exploitation par rapport au CA (EBE/ CA)>30%', 3, 24, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(25, 'Rentabilité économique (REX/ACTIF)>10%', 3, 25, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(26, 'Rentabilité Financière (RN/Capitaux propres)>10%', 3, 26, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(27, 'Capacité d\'endettement (Ressources Propres/Dettes structurelles)>1', 3, 27, 4, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(28, 'Délai Client  ((créances client /CA)*365)', 3, 28, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(29, 'Délai Fournisseur ((dettes fournisseurs /Achats à crédit)*365)', 3, 29, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(30, 'Délai d\'Ecoulement des stocks ((stock moyen/Coûts des produits vendus)*365 )', 3, 30, 2, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(31, 'Fiabilité des prévisions / Evolution des produits & charges', 4, 31, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(32, 'Représentation fidèle réalité  comptable et financière/ Transparence des transactions', 4, 32, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(33, 'Implication du dirigeant / Surface financière des promoteurs', 4, 33, 5, '2024-12-05 13:31:05', '2024-12-05 13:31:05'),
(34, 'Qualité des garanties & Actifs cessibles', 4, 34, 10, '2024-12-05 13:31:05', '2024-12-05 13:31:05');

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
-- Structure de la table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secteur_id` int NOT NULL DEFAULT '0',
  `region_id` int NOT NULL DEFAULT '0',
  `entreprise_id` int NOT NULL DEFAULT '0',
  `domaine_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `agence_id` int NOT NULL DEFAULT '0',
  `representation_id` int NOT NULL DEFAULT '0',
  `arrondissement_id` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_uri` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departement_id` int NOT NULL DEFAULT '0',
  `union_id` int NOT NULL DEFAULT '0',
  `is_union` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `address`, `phone`, `secteur_id`, `region_id`, `entreprise_id`, `domaine_id`, `user_id`, `agence_id`, `representation_id`, `arrondissement_id`, `active`, `token`, `photo_uri`, `departement_id`, `union_id`, `is_union`, `created_at`, `updated_at`, `data`) VALUES
(1, 'PLAFOPCAEEV', 'Evodoula', '647347878', 1, 2, 230, 1, 61, 4, 0, 45, 1, 'd42b22f11a4f7ae2c25fde087e01d83e208ddb43', 'cooperatives/d42b22f11a4f7ae2c25fde087e01d83e208ddb43.png', 11, 0, 1, '2025-10-01 20:15:06', '2025-10-01 20:15:06', '{\"updated_at\":\"2025-10-01 20:15:06\",\"created_at\":\"2025-10-01 20:15:06\",\"tenancy_db_name\":\"angara_demo_1_db\"}'),
(2, 'SOCADYC', 'Evodoula', '6872878232', 1, 2, 231, 1, 61, 4, 0, 45, 1, 'fd5b19d57b350ad1db344ff99ba4d10d2c8c2f75', 'cooperatives/fd5b19d57b350ad1db344ff99ba4d10d2c8c2f75.png', 11, 1, 0, '2025-10-01 20:20:59', '2025-10-01 20:20:59', '{\"updated_at\":\"2025-10-01 20:20:59\",\"created_at\":\"2025-10-01 20:20:59\",\"tenancy_db_name\":\"angara_demo_2_db\"}'),
(3, 'COPRAEC', 'Evodoula', '673298933', 1, 2, 232, 1, 61, 4, 0, 45, 1, 'a15ace6c07ddc8db4994ce3d81732ca5dc41fac1', 'cooperatives/a15ace6c07ddc8db4994ce3d81732ca5dc41fac1.png', 11, 1, 0, '2025-10-01 20:22:31', '2025-10-01 20:22:31', '{\"updated_at\":\"2025-10-01 20:22:31\",\"created_at\":\"2025-10-01 20:22:31\",\"tenancy_db_name\":\"angara_demo_3_db\"}'),
(4, 'SOCOPLANEC', 'Evodoula', '6577777322', 1, 2, 233, 1, 61, 4, 0, 45, 1, '5b4d810ecefbad8ef8940762911d3734df6b02be', 'cooperatives/5b4d810ecefbad8ef8940762911d3734df6b02be.png', 11, 1, 0, '2025-10-01 20:24:31', '2025-10-01 20:24:31', '{\"updated_at\":\"2025-10-01 20:24:31\",\"created_at\":\"2025-10-01 20:24:31\",\"tenancy_db_name\":\"angara_demo_4_db\"}'),
(5, 'SOCOOPAC', 'Evodoula', '6784739823', 1, 2, 234, 1, 61, 4, 0, 45, 1, '5f4481c71ede40e3fc143be5a192a1813a09d49f', 'cooperatives/5f4481c71ede40e3fc143be5a192a1813a09d49f.png', 11, 1, 0, '2025-10-01 20:26:55', '2025-10-01 20:26:55', '{\"updated_at\":\"2025-10-01 20:26:55\",\"created_at\":\"2025-10-01 20:26:55\",\"tenancy_db_name\":\"angara_demo_5_db\"}'),
(6, 'SOCOPE', 'Evodoula', '6787899989', 1, 2, 235, 1, 61, 4, 0, 45, 1, '8db5d6cb7618b3a0d64d911c75d3af839f537bc0', 'cooperatives/8db5d6cb7618b3a0d64d911c75d3af839f537bc0.png', 11, 1, 0, '2025-10-01 20:28:42', '2025-10-01 20:28:42', '{\"updated_at\":\"2025-10-01 20:28:42\",\"created_at\":\"2025-10-01 20:28:42\",\"tenancy_db_name\":\"angara_demo_6_db\"}'),
(7, 'MGBABANGOISE', 'Evodoula', '6787899989', 1, 2, 236, 1, 61, 4, 0, 45, 1, '7e2a52aeed07fa25dd9a9d1f4770236056023188', 'cooperatives/7e2a52aeed07fa25dd9a9d1f4770236056023188.png', 11, 1, 0, '2025-10-01 20:30:32', '2025-10-01 20:30:32', '{\"updated_at\":\"2025-10-01 20:30:32\",\"created_at\":\"2025-10-01 20:30:32\",\"tenancy_db_name\":\"angara_demo_7_db\"}'),
(8, 'scoops Emergence de NTUI', '698282402', '675473833', 1, 2, 240, 1, 61, 4, 0, 77, 1, '8957984140744f73a5289d09b8bbf17f9ba82b05', 'cooperatives/8957984140744f73a5289d09b8bbf17f9ba82b05.png', 14, 0, 0, '2025-11-18 16:12:55', '2025-11-18 16:12:55', '{\"updated_at\":\"2025-11-18 16:12:55\",\"created_at\":\"2025-11-18 16:12:55\",\"tenancy_db_name\":\"angara_demo_8_db\"}');

-- --------------------------------------------------------

--
-- Structure de la table `tenants_banques`
--

CREATE TABLE `tenants_banques` (
  `id` int NOT NULL,
  `banque_id` int NOT NULL DEFAULT '0',
  `tenant_id` int NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `montant` double NOT NULL DEFAULT '0',
  `token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tenants_banques`
--

INSERT INTO `tenants_banques` (`id`, `banque_id`, `tenant_id`, `name`, `montant`, `token`, `created_at`, `updated_at`) VALUES
(1, 12, 7, '56238788996878', 970000, '9b405d11c3ea04eb8b65f914b7e5d54155040c18', '2025-10-08 10:06:01', '2025-10-08 10:18:08'),
(2, 3, 8, '6832789238938209', 7000000, '7b38189efd7bc18b80be7a919fce40aaf3deab31', '2026-02-12 00:02:39', '2026-02-12 00:02:39'),
(3, 12, 8, '328783274388239', -5000000, 'fb2f3d00657ff087a24fe6edce908d61b0f30b7c', '2026-02-12 09:30:29', '2026-02-12 09:32:19');

-- --------------------------------------------------------

--
-- Structure de la table `tiers`
--

CREATE TABLE `tiers` (
  `id` int NOT NULL,
  `person_id` int NOT NULL DEFAULT '0',
  `lien` varchar(50) DEFAULT NULL,
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

INSERT INTO `tiers` (`id`, `person_id`, `lien`, `company_id`, `banque_id`, `entreprise_id`, `client`, `fournisseur`, `bank`) VALUES
(1, 1, 'Associatif', 0, 0, 6, 0, 0, 0),
(2, 0, 'Client', 7, 0, 6, 0, 0, 0),
(3, 0, 'Fournisseur', 9, 0, 6, 0, 0, 0),
(4, 2, 'Amical', 0, 0, 10, 0, 0, 0),
(5, 0, 'Fournisseur', 194, 0, 122, 0, 0, 0),
(6, 3, 'Amical', 0, 0, 195, 0, 0, 0),
(7, 0, 'Fournisseur', 196, 0, 195, 0, 0, 0),
(8, 0, 'Fournisseur', 198, 0, 197, 0, 0, 0),
(9, 0, 'Fournisseur', 208, 0, 207, 0, 0, 0),
(10, 4, 'Professionnel', 0, 0, 242, 0, 0, 0),
(11, 5, 'Familial', 0, 0, 245, 0, 0, 0),
(12, 6, 'Familial', 0, 0, 244, 0, 0, 0),
(13, 7, 'Professionnel', 0, 0, 246, 0, 0, 0),
(14, 8, 'Familial', 0, 0, 247, 0, 0, 0),
(15, 0, 'Fournisseur', 248, 0, 247, 0, 0, 0),
(16, 9, 'Familial', 0, 0, 249, 0, 0, 0),
(17, 10, 'Familial', 0, 0, 249, 0, 0, 0),
(18, 11, 'Familial', 0, 0, 255, 0, 0, 0),
(19, 12, 'Professionnel', 0, 0, 256, 0, 0, 0),
(20, 13, 'Familial', 0, 0, 259, 0, 0, 0);

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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL DEFAULT '0',
  `photo_uri` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agence_id` int NOT NULL DEFAULT '0',
  `representation_id` int NOT NULL DEFAULT '0',
  `programme_id` int NOT NULL DEFAULT '0',
  `cooperative_id` int NOT NULL DEFAULT '0',
  `secteur_id` int NOT NULL DEFAULT '0',
  `banque_id` int NOT NULL DEFAULT '0',
  `poste_id` int NOT NULL DEFAULT '0',
  `departement_id` int NOT NULL DEFAULT '0',
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `role_id`, `photo_uri`, `agence_id`, `representation_id`, `programme_id`, `cooperative_id`, `secteur_id`, `banque_id`, `poste_id`, `departement_id`, `phone`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `active`, `token`, `permissions`) VALUES
(1, 'Haisenheim', 1, 'profil/gfhgjdhhjdshgftehfgh.jpeg', 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'clementessomba@gmail.com', NULL, '$2y$12$ydP1pV4C4Qcv/NImPWIVV.0m5CxBm0fl68D8ZPngUFok2yodjdOvS', NULL, NULL, NULL, NULL, '2024-08-27 10:36:45', '2025-01-06 13:16:53', 1, 'gfhgjdhhjdshgftehfgh', NULL),
(2, 'ELOUNDOU ERIC', 2, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '678787989', 'pca@angara.com', NULL, '$2y$12$tTYPEFwR0dESynE/xBUtle2IGEOfQ2e/tEosOUbMdZPJDPZD6mKMG', NULL, NULL, NULL, NULL, '2024-12-12 16:13:07', '2024-12-12 16:13:07', 1, 'b9d68fb67526d1281955b95a42fd65c4694c7b40', NULL),
(3, 'ELOUNDOU ERIC', 3, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '66237872329892', 'administrateur1@angara.com', NULL, '$2y$12$9ev6l99ygTu1n5k47yOCrOIAhIhk6oSuiHWcAmrtoib6Io9UPtWAe', NULL, NULL, NULL, NULL, '2024-12-12 16:18:47', '2024-12-12 16:18:47', 1, '27c8a2d072ad61b1d6e49e8c879bc733de04e630', NULL),
(4, 'MVILONGO STEVE', 6, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '62387832783', 'drex@angara.com', NULL, '$2y$12$pa0HiQcPfuWZ2SfnnA8RSOiQ9a9PdiGmBpf0b5NJRljFUxZAzCz82', NULL, NULL, NULL, NULL, '2024-12-12 16:20:24', '2025-01-05 16:03:07', 1, 'ae1a21a1b0162572f815aa0adcf974ebc91966ea', NULL),
(5, 'MAIRAMOU ALLASSANE', 9, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '698238923', 'dengrx@angara.com', NULL, '$2y$12$ZTAcIABNknbcBhrN9k5NzuJox48nR7yJUFY/8xWlP7mJPjtGWmrsW', NULL, NULL, NULL, NULL, '2024-12-12 16:21:48', '2024-12-12 16:21:48', 1, '78aaf3bab202b4ce4806a54cb533a8ffbd414d5f', NULL),
(6, 'AMBASSA STEPHANE', 11, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '6789899', 'resp_nord@angara.com', NULL, '$2y$12$PjfDDtO36e41Zo4ra7ef.O.14kjf6eEaHcbs9WshX9EVHVawZTuom', NULL, NULL, NULL, NULL, '2024-12-12 16:23:59', '2024-12-12 16:23:59', 1, '05b70128486504e087c7952233d727c4afe1c5c9', NULL),
(7, 'MBEMBE EDOUARD', 11, NULL, 0, 2, 0, 0, 0, 0, 0, 0, '6989999787', 'resp_sud@angara.com', NULL, '$2y$12$ETU3GSuSopOIuOq94xiYG.F24zVeIAKTgCx0lOKSdOGCtEa1CyWaq', NULL, NULL, NULL, NULL, '2024-12-12 16:25:07', '2024-12-12 16:25:07', 1, '9a7b21ab778a760a8349ecca4d513bd8a67f6bfa', NULL),
(8, 'TCHOMTE STEVE', 11, NULL, 0, 3, 0, 0, 0, 0, 0, 0, '6783773278', 'resp_ouest@angara.com', NULL, '$2y$12$rgZJu6A4gme4VMZ2Zcej2evdq7iBz1Fi3AfWvXNeqkOkG5sCoZGT.', NULL, NULL, NULL, NULL, '2024-12-12 16:26:26', '2024-12-12 16:26:26', 1, '549670faca765a10e00c5721dc87901e4a15cab6', NULL),
(9, 'AMBASSA STEPHANE', 12, NULL, 1, 1, 0, 0, 0, 0, 0, 0, '6524924337', 's.ambassa90@angara.com', NULL, '$2y$12$qjhaxrmjXMgb0k.r2XVmY.7u0o1pLNn.dJDlgZt9HP9UNPslxtE/W', NULL, NULL, NULL, NULL, NULL, '2025-11-18 17:10:05', 1, '788fe0358eb745d8b9b5b0fcd214cfca590bdd82', NULL),
(10, 'NGOAH Armelle', 13, NULL, 1, 1, 0, 0, 0, 0, 0, 0, '6633532466', 'a.ngoah43@angara.com', NULL, '$2y$12$UMmSF0ruHkjEaL3KRX90nOOe1U1Nm0UeFgQf.wwvFafAsw/SENxzW', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:10:45', 1, 'b43e4118bc4a23701e5f084c6abab1c458fdfdcd', NULL),
(11, 'ELOUNDOU Francis', 13, NULL, 1, 1, 0, 0, 0, 0, 0, 0, '6799649451', 'f.eloundou73@angara.com', NULL, '$2y$12$SPxsI0vzpoHnp.ovNwRgdu71nsuDJ6GuTQrgZibO/OOMWtnOv9yuW', NULL, NULL, NULL, NULL, NULL, '2025-01-05 16:03:56', 1, 'ad7d9a00f7ab0deef9795b06157e02bdc3276357', NULL),
(12, 'ENDIGA Cédric', 14, NULL, 1, 1, 0, 0, 0, 0, 0, 0, '699909816', 'c.endiga62@angara.com', NULL, '$2y$12$XwVmRFcqTwCDr2vNXmlsLewtcB8AlJDY1S3Gb5cg84ejwvCObIoUC', NULL, NULL, NULL, NULL, NULL, '2025-11-18 17:08:57', 1, 'ff28cccb3dc379d2dbc18d94136fbecc95b58bd0', NULL),
(13, 'AMBASSA STEPHANE', 12, NULL, 2, 1, 0, 0, 0, 0, 0, 0, '6440614812', 's.ambassa27@angara.com', NULL, '$2y$12$E2UhGtvmJLoQ4fCszm4VCeBWoKBoCc734YCjaqCGQshTTx3Wut1Cy', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:40', 1, 'baaf6f24a33d2fe485dff7285fe55453a63719f0', NULL),
(14, 'NGOAH Armelle', 13, NULL, 2, 1, 0, 0, 0, 0, 0, 0, '6903589110', 'a.ngoah55@angara.com', NULL, '$2y$12$7unkbEo4IExUsMc6sNTLbet5SGoqY7qx2YNAZdIa7jZUOqZi3SEEi', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:40', 1, 'c6c672e46f07a00c8fe00435fe79492775418431', NULL),
(15, 'ELOUNDOU Francis', 13, NULL, 2, 1, 0, 0, 0, 0, 0, 0, '6858476428', 'f.eloundou35@angara.com', NULL, '$2y$12$PUfgYwy6qO/ezUwpqZKo1OyokPzUVewQlC6hB5ekCg/XVQDeoGpD2', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:40', 1, 'd71d5d0d0e40ae7827c28e6557e9626ceb49f223', NULL),
(16, 'ENDIGA Cédric', 14, NULL, 2, 1, 0, 0, 0, 0, 0, 0, '6785528599', 'c.endiga84@angara.com', NULL, '$2y$12$d3htezWWMwoXMILUSwYj5O4l2I7wlZ64Hy8PL4lmoK8gCfvuI/nz.', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:41', 1, 'deccc3d8f76c21f127e400870f9e1ed609090401', NULL),
(17, 'AMBASSA STEPHANE', 12, NULL, 3, 1, 0, 0, 0, 0, 0, 0, '6976995745', 's.ambassa31@angara.com', NULL, '$2y$12$q4midUT1.yrz6usU7AVAhu.I.NQ5JJj2XQiBk2052w/InYdLB6OQG', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:41', 1, 'dad743c4f294ed8d27cebf859b6f425fcc1d8751', NULL),
(18, 'NGOAH Armelle', 13, NULL, 3, 1, 0, 0, 0, 0, 0, 0, '6440278090', 'a.ngoah32@angara.com', NULL, '$2y$12$q.WJNsKJlcKPnIhYsAgk3uP.GTaQbOqgqK1nzmDfADRncbD8kSUWy', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:41', 1, 'fa3d61887fb8bc5310417c0ab42b7226a9f289dd', NULL),
(19, 'ELOUNDOU Francis', 13, NULL, 3, 1, 0, 0, 0, 0, 0, 0, '6126348584', 'f.eloundou93@angara.com', NULL, '$2y$12$o8DxXWbdveM8.4RBrhDMUOzjBS1N8glOQAx5ZoZ4w6s1pVYtssTv6', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:42', 1, '4c419940028f1f160eb69b099b613c6993ec7b17', NULL),
(20, 'ENDIGA Cédric', 14, NULL, 3, 1, 0, 0, 0, 0, 0, 0, '6821522493', 'c.endiga52@angara.com', NULL, '$2y$12$VP8DwbxnJZutfccBqOP33uBQBeiYdzL.xiH0fWFSQh66asR6uVHQO', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:42', 1, '5ca4045f83677d68114a72586591727fefe7b0c2', NULL),
(21, 'MBEMBE EDOUARD', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, '6900766955', 'e.mbembe99@angara.com', NULL, '$2y$12$lLymLipSSk8kuL3puEsG0Odl6oqLWomnKix32jBjBAWj/crnAW9Bq', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:42', 1, 'bf195aabf036cd3f84575386e3411b8d081d8c9f', NULL),
(22, 'NGOAH Armelle', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, '6764056828', 'a.ngoah27@angara.com', NULL, '$2y$12$3S5Rgx5u2tar7WlmQL8GF.Z5fJGJlLz/0ektBDO0RBd1Zf0SqNPMa', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:42', 1, '8a0de06e3ccadb2a6c576c67c684638ba0b2ed78', NULL),
(23, 'ELOUNDOU Francis', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, '6510155596', 'f.eloundou29@angara.com', NULL, '$2y$12$tGMQ8xYm/r1vT7CgasVQeOMOg1bPd1XXoo0LBwrX.NhyhgrpffAzm', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:43', 1, '536dd8f8cf0f36d7857c7ffce7c52fb11eba54a6', NULL),
(24, 'ENDIGA Cédric', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, '6242514298', 'c.endiga65@angara.com', NULL, '$2y$12$/MTfuzMyf8F.dZCdw.zSduKleiAjXcW9aO3HXj8s43M7exWYbvhgC', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:43', 1, 'f75fd34ed4c74decca774db7a9ff5af535591c11', NULL),
(25, 'MBEMBE EDOUARD', 12, NULL, 5, 2, 0, 0, 0, 0, 0, 0, '6781528860', 'e.mbembe13@angara.com', NULL, '$2y$12$XGtZmDyi6icak7UM69CPpeQFzT.4gdhC0n06/UfFbvywxStaCm2Wy', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:43', 1, 'aef0ba5717e68858051732f1f981d59cb9610dee', NULL),
(26, 'NGOAH Armelle', 13, NULL, 5, 2, 0, 0, 0, 0, 0, 0, '6951818606', 'a.ngoah11@angara.com', NULL, '$2y$12$DjYGIRev.2UyUlYZ.o8ls.J7KPOg.uawMbIbyQVLDSh.Om.QTP.Ry', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:44', 1, '8b6dde8d346ae0eafac5973ee539f039055b38cd', NULL),
(27, 'ELOUNDOU Francis', 13, NULL, 5, 2, 0, 0, 0, 0, 0, 0, '6171479456', 'f.eloundou26@angara.com', NULL, '$2y$12$ZeoHd74dlbKb7t5r88jcB..miEEjcr6IUCgl9ZeHOvNU2nUhDz.2a', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:44', 1, '4f78fcc2d749bcf961a66bbb4e635b9888068d3d', NULL),
(28, 'ENDIGA Cédric', 14, NULL, 5, 2, 0, 0, 0, 0, 0, 0, '6481530774', 'c.endiga83@angara.com', NULL, '$2y$12$245iu0vtf/5TguxKY/zUC.H4jFHUfHLrnMpwNo56VV8fl597hJHFi', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:44', 1, 'e2a1905b38d22e22bf83a16627baa7e6e5107fbb', NULL),
(29, 'MBEMBE EDOUARD', 12, NULL, 6, 2, 0, 0, 0, 0, 0, 0, '6681224960', 'e.mbembe52@angara.com', NULL, '$2y$12$8rdimgKdikThvYCCMX/yyeUCWk90TrxIhZxsu7Q8MGGRjkj8nKVVK', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:45', 1, '1a32f680b8fa242ea83632a796981d4f562812cf', NULL),
(30, 'NGOAH Armelle', 13, NULL, 6, 2, 0, 0, 0, 0, 0, 0, '673762472', 'a.ngoah82@angara.com', NULL, '$2y$12$uFy0vKxz/cRImPwqQwvpZeX6LKfsEVOSzBUsvscUL7zyQjT/TafNy', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:45', 1, '16cd16ae32ce6f9f94e9b9f210a4053c554e9d57', NULL),
(31, 'ELOUNDOU Francis', 13, NULL, 6, 2, 0, 0, 0, 0, 0, 0, '6645725209', 'f.eloundou85@angara.com', NULL, '$2y$12$.Y9daMN765nsvZGpGk9ax.rcPv1tF6EtVM8vejgLv9D8JE7XAHz5.', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:45', 1, 'ce0fa75c48cb9ac09eff411c34422bc0d8ce0aca', NULL),
(32, 'ENDIGA Cédric', 14, NULL, 6, 2, 0, 0, 0, 0, 0, 0, '6891127093', 'c.endiga30@angara.com', NULL, '$2y$12$nrtLNDfNS87LVd9iCwtnwez/wnpY0oJTLOVEWrBv.mTWdyDeg/6.O', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:45', 1, 'b53e3407eb4be5a3dded9b45dc3620bf96e30f33', NULL),
(33, 'TCHOMTE STEVE', 12, NULL, 7, 3, 0, 0, 0, 0, 0, 0, '6617195599', 's.tchomte66@angara.com', NULL, '$2y$12$rntPNy5bV.1hZsqFq48qiuxIBVWFDpiP1CV7JN6TSi0Q2QXhz12XS', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:46', 1, '94e265a85707871d824e61636abe6b6b36e33963', NULL),
(34, 'NGOAH Armelle', 13, NULL, 7, 3, 0, 0, 0, 0, 0, 0, '6690964748', 'a.ngoah89@angara.com', NULL, '$2y$12$c7KNCMgryV/BylRHgXjFOOlPfVIsa6c5ONjKBF2nccRobIDrpTPIe', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:46', 1, '7bde195f717bb6af7690de003d13c33bea7edaac', NULL),
(35, 'ELOUNDOU Francis', 13, NULL, 7, 3, 0, 0, 0, 0, 0, 0, '6497958445', 'f.eloundou42@angara.com', NULL, '$2y$12$hsJ6MRAdqVbpZYroJxsA1ukO456xxlwJHN9rTrQ8wdCQo40owAkyC', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:46', 1, 'edb39703de099b0a1eea23173eeb13d06f741725', NULL),
(36, 'ENDIGA Cédric', 14, NULL, 7, 3, 0, 0, 0, 0, 0, 0, '6910247023', 'c.endiga28@angara.com', NULL, '$2y$12$76iMMgw3j0krIEI0XGU5KO0wcNCdqLiFNS9lgxewSWAGcW/szA8Rm', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:47', 1, 'aa0e75c22bed8229583bf6329e305d1f01e7fd84', NULL),
(37, 'TCHOMTE STEVE', 12, NULL, 8, 3, 0, 0, 0, 0, 0, 0, '6167733555', 's.tchomte56@angara.com', NULL, '$2y$12$Khac3jC1I3DZT5wlMWlmjeH9dm5pE22ZNG.Y72j0covZmGfBZaiEa', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:05:47', 1, '73cde513fffb09848fbfdbe6c37f3327d08b5938', NULL),
(38, 'NGOAH Armelle', 13, NULL, 8, 3, 0, 0, 0, 0, 0, 0, '6312052463', 'a.ngoah77@angara.com', NULL, '$2y$12$rh2GDiLvBcTENQ3N0pFAruSY6oZmHLjr1Snd9GZmVNRk85UYZBL5a', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:07:28', 1, '77aacc41e2c477fb92a9d5b111cabea149a969f6', NULL),
(39, 'ELOUNDOU Francis', 13, NULL, 8, 3, 0, 0, 0, 0, 0, 0, '662636057', 'f.eloundou67@angara.com', NULL, '$2y$12$r2GTKZaRNgNB8.rKeYn7WuJIvz4M4huK9e67hmUNwNUfAGm/bhJsG', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:07:28', 1, '8aaa53d4049f35faaa0952823260e3438ab7cecb', NULL),
(40, 'ENDIGA Cédric', 14, NULL, 8, 3, 0, 0, 0, 0, 0, 0, '6914710255', 'c.endiga94@angara.com', NULL, '$2y$12$vQnEEgj23WZBoxXKT3U3g.KCcv1gnc5Igti20k.gvx8hMEKqPSWmO', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:07:29', 1, '33af294a6ed148b512bcc7c33d47b4947e7d3e20', NULL),
(41, 'TCHOMTE STEVE', 12, NULL, 9, 3, 0, 0, 0, 0, 0, 0, '6121010588', 's.tchomte47@angara.com', NULL, '$2y$12$/PqBYvs5s2jfhYKABo7K/eFyXeBPQ/sdVTmjhU2bkSUOQQoN7ZQNW', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:07:29', 1, '644443aeae5f53a3520b6a760b923bebffae940f', NULL),
(42, 'NGOAH Armelle', 13, NULL, 9, 3, 0, 0, 0, 0, 0, 0, '6239991780', 'a.ngoah63@angara.com', NULL, '$2y$12$ZyN0nXYS/wlN9.HaCuGhXuVyDU6Ssdt9mSyeFw2V7JJ5NNaRbYPmy', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:23', 1, 'aa74e3ab489741fa34ec8b1828f6abb87df8c757', NULL),
(43, 'ELOUNDOU Francis', 13, NULL, 9, 3, 0, 0, 0, 0, 0, 0, '6818623295', 'f.eloundou83@angara.com', NULL, '$2y$12$d5X/KNiwk.KVkTBk5rF5k.Nfxx55tjCopuETZ1ERvCpm953YDHAoO', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:24', 1, 'f5e908aef815e5dc3293799be01ce063f7702a11', NULL),
(44, 'ENDIGA Cédric', 14, NULL, 9, 3, 0, 0, 0, 0, 0, 0, '6570956448', 'c.endiga49@angara.com', NULL, '$2y$12$N0VvmlfalFw1H06GxQNJguVcQrBZQHZYmciqFH3c4a.q06SONTyHK', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:24', 1, '26904cab73f6e07190260cad825d18f71729a17d', NULL),
(45, 'TCHOMTE STEVE', 12, NULL, 10, 3, 0, 0, 0, 0, 0, 0, '6647201317', 's.tchomte95@angara.com', NULL, '$2y$12$bNe6.9ZyMAPFOx8IPmNUPe2rBOiXu4ZXbhzCksZOUG7JjtDVNwV8y', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:24', 1, '64aa7a34f3645368be2da960a87fd33596979696', NULL),
(46, 'NGOAH Armelle', 13, NULL, 10, 3, 0, 0, 0, 0, 0, 0, '6248517471', 'a.ngoah24@angara.com', NULL, '$2y$12$pvxoR8KHqAoAWi3j.AY5xOtQwg.d0AfyMMITXP4bwRgDCW8MrWBja', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:24', 1, '35180606cc78d12437717823190bd4d7d3f74599', NULL),
(47, 'ELOUNDOU Francis', 13, NULL, 10, 3, 0, 0, 0, 0, 0, 0, '6530924619', 'f.eloundou65@angara.com', NULL, '$2y$12$MiyWfrzYcz0sohtZofD65exUgE3tPrFMwV.pn3WXGW9eKfdr9gB3q', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:25', 1, '557e1607d6f2e5a4d0a66f3b03d39f0896453aaf', NULL),
(48, 'ENDIGA Cédric', 14, NULL, 10, 3, 0, 0, 0, 0, 0, 0, '6901522585', 'c.endiga89@angara.com', NULL, '$2y$12$BaDt0AZMGumkEHY2IFs/6OG7DhhUv075zj4GXxZKW4vUSA2RsDrdu', NULL, NULL, NULL, NULL, NULL, '2024-12-12 17:08:25', 1, '57e14c9765db134b936cad0d5d5c7298a8dd6ac0', NULL),
(51, 'Salam sosso', 21, NULL, 0, 0, 0, 1, 0, 0, 0, 0, NULL, 's.salam@gmail.com', NULL, '$2y$12$hen.M6ShrXbjnKjNSPQw7.w0qs1dN.L4yVZY6umrZWOxB7mQERW62', NULL, NULL, NULL, NULL, '2025-01-20 20:06:53', '2025-01-20 20:06:53', 1, 'b5fe782bb0446c042186cc540b23fff8fbeab1f4', NULL),
(53, 'Edzigui bernard', 21, NULL, 0, 0, 0, 2, 0, 0, 0, 0, NULL, 'b.edzigui@angara.com', NULL, '$2y$12$5NExxsYrYshtm1vRG6dc7.vZnwhpsPofmo1gz/4g8haA8fMNfMgxO', NULL, NULL, NULL, NULL, '2025-02-06 07:33:40', '2025-02-06 07:33:40', 1, '8bf8323f6d93f1c3cb44cc6c6e1c5380d5f58a14', NULL),
(54, 'haisenheim', 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'haisen@admin.com', NULL, '$2y$12$o7zYahJ25wft6b0fJG6iZe8EvoTt2qqa6/zYHPnWcA51mqovw1m9S', NULL, NULL, NULL, NULL, '2025-02-14 10:09:54', '2025-02-14 10:09:54', 1, NULL, '{\"platform.systems.roles\":true,\"platform.systems.users\":true,\"platform.systems.attachment\":true,\"platform.index\":true}'),
(55, 'Mbarga Armand', 21, NULL, 0, 0, 0, 3, 0, 0, 0, 0, NULL, 'a.mbarga@angara.com', NULL, '$2y$12$nQUCFvJ96Z64SQWyUKaxEuw3WscpPWh7cu3.ikaC1LuhkWMWmW4w2', NULL, NULL, NULL, NULL, '2025-02-27 09:14:26', '2025-02-27 09:14:26', 1, 'da5dffed1d112842d143d6c22cb337e3d93c0759', NULL),
(56, 'Salomon Louli', 21, NULL, 0, 0, 0, 4, 0, 0, 0, 0, NULL, 'sal.louli@gmail.com', NULL, '$2y$12$tRQuMcVFc3OxC1JXZj15aOJUx8zwlNLxd7FWCLlQQd24MW7WB80Ym', NULL, NULL, NULL, NULL, '2025-03-21 17:32:32', '2025-03-21 17:32:32', 1, 'c396463dc382685e64f2c1830629d000336be3e7', NULL),
(57, 'NGUEMA ALAIN', 20, NULL, 0, 0, 0, 0, 3, 0, 0, 0, '677894834', 'a.guema@angara.com', NULL, '$2y$12$M81X1p9hjHlDyelXe.KiP.hBuYxW/qkvnOTtk7giRY2QUVMZM7MTG', NULL, NULL, NULL, NULL, '2025-06-03 09:04:57', '2025-06-03 09:04:57', 1, '20b0f129a0217d3281249514245a9ac87b5f443f', NULL),
(58, 'Owona Jean Pierre', 20, NULL, 0, 0, 0, 0, 4, 0, 0, 0, '698345678', 'jp.owona@angara.com', NULL, '$2y$12$7122YqCNouTQfbMjW.OmI.mQ8DX3xhwvjEEiuhKwP.qsI39VjSFOm', NULL, NULL, NULL, NULL, '2025-06-10 11:25:43', '2025-06-10 11:25:43', 1, '5a4c4ed164b89414a46cc4ded7ad51005632c696', NULL),
(59, 'Owona Marcel', 21, NULL, 0, 0, 0, 0, 0, 5, 0, 0, '6734289834', 'm.owona@gmail.com', NULL, '$2y$12$eAVweH0/2Ad5agbua7hv6.56gE.rlsRQt3jp72RxwbuwtAAmdky/m', NULL, NULL, NULL, NULL, '2025-07-18 16:28:01', '2025-07-18 16:28:01', 1, '124d2b62fcf90fb3975379282340ff0ef5e100ea', NULL),
(60, 'Owona Armand', 21, NULL, 0, 0, 0, 0, 0, 12, 0, 0, '69984782738', 'a.owona@gmail.com', NULL, '$2y$12$3oBqoAoREhJTML7mxcYcDeKE2y6BHvF0G9mjwNW62iMvwIpaBulri', NULL, NULL, NULL, NULL, '2025-07-18 16:33:03', '2025-07-18 16:33:03', 1, 'f30911e2564ba25c990e92b1e1f71bf9cc23a1ea', NULL),
(61, 'ERIC ELOUNDOU NGAH', 13, NULL, 4, 0, 0, 0, 0, 0, 0, 0, '6787899989', 'eric.eloundou@angara-finance.net', NULL, '$2y$12$1KeOcs8BRc95oGtinuzvzeY4NwaGn.MnCAhNwzIi9LiDyq/22/sku', NULL, NULL, NULL, NULL, '2025-10-01 20:07:25', '2025-10-01 20:07:25', 1, '3b75a390fd7db79dde8746a0d023b501e6432028', NULL),
(62, 'HAMAN AMADOU', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'haman.amadou+gestionnaire@bc-pme.cm', NULL, '$2y$12$JO918gc8//GhpRrQU976qeAVVUqh0hmrT/F//CjWD0dtNJWRROiCe', NULL, NULL, NULL, NULL, '2025-11-26 18:25:08', '2025-11-26 18:25:08', 1, '16ddef7b012ad6b549b79cf9af84f46d5f655a6c', NULL),
(63, 'HAMAN AMADOU', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'haman.amadou+analyste@bc-pme.cm', NULL, '$2y$12$Lz8wpta.BfgwuQGB7FaXION1gtis.d9Pizvb0/7ZDfmUvK0CXMDOi', NULL, NULL, NULL, NULL, '2025-11-26 18:25:08', '2025-11-26 18:25:08', 1, '1316e27f900ab014fa3cd8597a02a2b59aa466dc', NULL),
(64, 'HAMAN AMADOU', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'haman.amadou+chef@bc-pme.cm', NULL, '$2y$12$RR8RehFcdPwlEgpJU6niuOG5Ngd4jjy34Ttpw.1yPSvWA6Igp2VAq', NULL, NULL, NULL, NULL, '2025-11-26 18:25:09', '2025-11-26 18:25:09', 1, '8a9e68584d6ec33d3863798f87401a45ae7e90c0', NULL),
(65, 'HAMAN AMADOU', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'haman.amadou+responsable@bc-pme.cm', NULL, '$2y$12$Bs0NJvhj6hKJdhlhjR.wrO7Ldj.HidjRYZcDxyTsPq7JFDtFOA5w2', NULL, NULL, NULL, NULL, '2025-11-26 18:25:09', '2025-11-26 18:25:09', 1, '8ef892b3c42d8b58efbdd3e659b0b808ca6b2b13', NULL),
(66, 'NDJOMO EKO Rodrigue', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rodrigue.ndjomo+gestionnaire@bc-pme.cm', NULL, '$2y$12$ARzNbJQPxiBcztRxVujkI./j6iLGdPpPw/tOokgT4AvVJ.bmLuLZO', NULL, NULL, NULL, NULL, '2025-11-26 18:25:09', '2025-11-26 18:25:09', 1, '696ddb220f033da8bd7bb2f356e4996d7dec4cc0', NULL),
(67, 'NDJOMO EKO Rodrigue', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rodrigue.ndjomo+analyste@bc-pme.cm', NULL, '$2y$12$HP7JNk5zT5upJ9eOTVDg4.7vFb21kQketimSdpofKuGbP3TBPu89W', NULL, NULL, NULL, NULL, '2025-11-26 18:25:09', '2025-11-26 18:25:09', 1, 'b8fab43227fac9710cf5ce97abd90a78d3c9f890', NULL),
(68, 'NDJOMO EKO Rodrigue', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rodrigue.ndjomo+chef@bc-pme.cm', NULL, '$2y$12$HmHIJ7Cg/Uqe4Z/W63XV0uyWbayPcTExGILmUJuYgHmNXxtvehZ/.', NULL, NULL, NULL, NULL, '2025-11-26 18:25:10', '2025-11-26 18:25:10', 1, 'eda8951ec97a87c240c943971c10c4879980350e', NULL),
(69, 'NDJOMO EKO Rodrigue', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rodrigue.ndjomo+responsable@bc-pme.cm', NULL, '$2y$12$WAvwmNxrgMMGebDOSyM3quYl3j4QeuyTJqUxuzbpR2vYrBtN2gjHC', NULL, NULL, NULL, NULL, '2025-11-26 18:25:10', '2025-11-26 18:25:10', 1, '180625e6a0bde377a8b0d4d1983ad66a5b597318', NULL),
(70, 'HEBIBE Ambachair', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'ambachair.hebibe+gestionnaire@bc-pme.cm', NULL, '$2y$12$rX4cs5W8W8YQGHUINxs/kuymfo2ZS20UB4YMZ1TYgW3PXTtmaW.zm', NULL, NULL, NULL, NULL, '2025-11-26 18:25:10', '2025-11-26 18:25:10', 1, '53163abbc9ef9ba5e798f1e7a50c810a3e9b76e6', NULL),
(71, 'HEBIBE Ambachair', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'ambachair.hebibe+analyste@bc-pme.cm', NULL, '$2y$12$VxKFmnPHo1P3cX4SulVuzechc.9tHv4PuQi/VlMs9WlOhVZuUcJ2m', NULL, NULL, NULL, NULL, '2025-11-26 18:25:10', '2025-11-26 18:25:10', 1, '4c474bd8bf724cdf0e79a1cdb769c7317b10b9dc', NULL),
(72, 'HEBIBE Ambachair', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'ambachair.hebibe+chef@bc-pme.cm', NULL, '$2y$12$v/it/2hUqka1BSeT7o1RPOCcz2A1XHyCmm5NnfT2UtAqkEsUj6cZW', NULL, NULL, NULL, NULL, '2025-11-26 18:25:10', '2025-11-26 18:25:10', 1, '3b76dc86f6bb415d0bb5ce7ad3e3d0dd1ca3b1e1', NULL),
(73, 'HEBIBE Ambachair', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'ambachair.hebibe+responsable@bc-pme.cm', NULL, '$2y$12$5i9lpgDyLpTtwJQ6N4P1GOWZhSTawbhJato86cjt7/EyxsoA9MSCO', NULL, NULL, NULL, NULL, '2025-11-26 18:25:11', '2025-11-26 18:25:11', 1, '415dbe766e4570b903a77e79d02e4e85efa4a37f', NULL),
(74, 'KAMLA Victor', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla+gestionnaire@bc-pme.cm', NULL, '$2y$12$jHsG.Z9EYrIR/u5yIubG2OJOql6.wNhKdTi7LW1IykvjUrbCnM1Ty', NULL, NULL, NULL, NULL, '2025-11-26 18:25:11', '2025-11-26 18:25:11', 1, 'd3136a762ed09b1b7f9c271a690945949f6ceb8a', NULL),
(75, 'KAMLA Victor', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla+analyste@bc-pme.cm', NULL, '$2y$12$aIVOW9UylPyTIcO7BQ.JQuaff8gyeAW2xz2hhR8jkr097tOYGgS2W', NULL, NULL, NULL, NULL, '2025-11-26 18:25:11', '2025-11-26 18:25:11', 1, '936e357adb6bab7a6579c5a5fc12a1da133ab573', NULL),
(76, 'KAMLA Victor', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla+chef@bc-pme.cm', NULL, '$2y$12$6rb3v0dDY03oGMmoME2LmuAEAYeALGPQ2uTdDXgjyQ/qoyakqwlQe', NULL, NULL, NULL, NULL, '2025-11-26 18:25:11', '2025-11-26 18:25:11', 1, '53877c26b7acb365f0f197698f532dd8c7e97405', NULL),
(77, 'KAMLA Victor', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla+responsable@bc-pme.cm', NULL, '$2y$12$KvDLXYqGOj7SCRIpQKmPgOTFe36uD5X8LIbRoVThki4RVE6MWolAm', NULL, NULL, NULL, NULL, '2025-11-26 18:25:11', '2025-11-26 18:25:11', 1, 'b9ca9ed9d289306404188871e6d499286e2af811', NULL),
(78, 'GALLAH Leslie', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'leslie.gallah+gestionnaire@bc-pme.cm', NULL, '$2y$12$FeV7.Slsfk9.6URIzO4N1OjicnSB2WiNzixMpWxM8uP9o5H.8b9W2', NULL, NULL, NULL, NULL, '2025-11-26 18:25:12', '2025-11-26 18:25:12', 1, '23b700ffc133e0e115acb798b68e73c6c788e293', NULL),
(79, 'GALLAH Leslie', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'leslie.gallah+analyste@bc-pme.cm', NULL, '$2y$12$EB0GyS5sEsJ3j6Mvn7QX4.mSb5Bbi5isb5ebzJqGcNcihisB9RwO2', NULL, NULL, NULL, NULL, '2025-11-26 18:25:12', '2025-11-26 18:25:12', 1, '0f82dd9b97fda32cdb6276a905d5f854c4050c11', NULL),
(80, 'GALLAH Leslie', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'leslie.gallah+chef@bc-pme.cm', NULL, '$2y$12$v36hm/EKH62/Xc4dVBGdBunvWKpTz.RFWsvfYnfA9Exi6rzvWz68e', NULL, NULL, NULL, NULL, '2025-11-26 18:25:12', '2025-11-26 18:25:12', 1, '8af0fb84a2b1775fc8d7aafd9fa4a70dd12944aa', NULL),
(81, 'GALLAH Leslie', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'leslie.gallah+responsable@bc-pme.cm', NULL, '$2y$12$mQ0CJeG4rF8OqT7MkNBFHuL4cSMqmk7OgRyAj6CLRPTdF2tXTfJJK', NULL, NULL, NULL, NULL, '2025-11-26 18:25:12', '2025-11-26 18:25:12', 1, '1eb1f1d4853772ded1e8b8da81303d29addf409c', NULL),
(82, 'RAHIMATOU Djidjatou', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rahimatou.djidjatou+gestionnaire@bc-pme.cm', NULL, '$2y$12$Dj6.ioHNji0580h1LnKNweS/ah48Byou.UXeQOxytWrKW1HxXTtaa', NULL, NULL, NULL, NULL, '2025-11-26 18:25:12', '2025-11-26 18:25:12', 1, '1ec9b8db02e59d726afb8065d6dd3dcce0855c5b', NULL),
(83, 'RAHIMATOU Djidjatou', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rahimatou.djidjatou+analyste@bc-pme.cm', NULL, '$2y$12$a3CZkGHFuCHArr7fyK1RquoB0VUXa5aUY5neudevsCyiM69xVDNOm', NULL, NULL, NULL, NULL, '2025-11-26 18:25:13', '2025-11-26 18:25:13', 1, 'f509e579b65cad74b582844abf2dcd99fce32648', NULL),
(84, 'RAHIMATOU Djidjatou', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rahimatou.djidjatou+chef@bc-pme.cm', NULL, '$2y$12$NoQ90DLGyl7T7BqMDcsuR.U8vJAtxIA8AXV4Qf4/RkCTlMk0ilo5S', NULL, NULL, NULL, NULL, '2025-11-26 18:25:13', '2025-11-26 18:25:13', 1, 'cdec8fa575d7ad5a81b3db3ba79eab542f3123b6', NULL),
(85, 'RAHIMATOU Djidjatou', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'rahimatou.djidjatou+responsable@bc-pme.cm', NULL, '$2y$12$61ogs0z/.iKRwMSurgLQ3Osf6G4wYXPfQiXRQesWRgQ0QY.NfXFl6', NULL, NULL, NULL, NULL, '2025-11-26 18:25:13', '2025-11-26 18:25:13', 1, 'c1b4de67199a20157ebe892299f2562ebd6cd3a9', NULL),
(86, 'TABI Fabien', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'fabien.tabi+gestionnaire@bc-pme.cm', NULL, '$2y$12$dLUY8E5EDuIyUxXxdlH33eVtMwA0Y1kVfPvEguslqdq3kFxq7DC4O', NULL, NULL, NULL, NULL, '2025-11-26 18:25:13', '2025-11-26 18:25:13', 1, '58acb48f8169c09d826e2c439c2588ec5bfbe2cb', NULL),
(87, 'TABI Fabien', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'fabien.tabi+analyste@bc-pme.cm', NULL, '$2y$12$Ba7AVE3ot7T4abv78ih/g.24go3ACSvd63.7cZinY3JDPd1Ol6zya', NULL, NULL, NULL, NULL, '2025-11-26 18:25:13', '2025-11-26 18:25:13', 1, '55ad252841b766150b39c3db59cf39de7ffd4acc', NULL),
(88, 'TABI Fabien', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'fabien.tabi+chef@bc-pme.cm', NULL, '$2y$12$H8el9sPxOoXyn1H29N9Tzu9q2VDvMM8Sb6boW7BfuMI75uoJMktjO', NULL, NULL, NULL, NULL, '2025-11-26 18:25:14', '2025-11-26 18:25:14', 1, 'cb2dd796c6cfce32cf2eadb6590bcc81fa1669ec', NULL),
(89, 'TABI Fabien', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'fabien.tabi+responsable@bc-pme.cm', NULL, '$2y$12$KBvQPhfeAlzbQk1MN8b4aunkJaPfD72zHKLKPMKa6Wj27UlpVrFuu', NULL, NULL, NULL, NULL, '2025-11-26 18:25:14', '2025-11-26 18:25:14', 1, '86211e0e25bd30a2197da3a37a540d8a0c925422', NULL),
(90, 'ZE Roland', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'roland.ze+gestionnaire@bc-pme.cm', NULL, '$2y$12$Dfsmg/mWecs5dR95GzLdv.Tcc3OlzC5bwDPlP4yTM/zN4htzvx4wm', NULL, NULL, NULL, NULL, '2025-11-26 18:25:14', '2025-11-26 18:25:14', 1, 'a754bad7c9d520e28d571e09717fb62612e6ea50', NULL),
(91, 'ZE Roland', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'roland.ze+analyste@bc-pme.cm', NULL, '$2y$12$JelJxwUPuvOw.tfQ2Vp9pu/o9R7fWuGyR/CuwdmpOLHIZLX9z5Lgq', NULL, NULL, NULL, NULL, '2025-11-26 18:25:14', '2025-11-26 18:25:14', 1, '45e0d04de492d15d91068e63b63745cbf4c6e3fa', NULL),
(92, 'ZE Roland', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'roland.ze+chef@bc-pme.cm', NULL, '$2y$12$8Q9GrMX5imQKZ5nJtzdytOqtecDzIprBgGvg7SEM5dqESIRu0VdO6', NULL, NULL, NULL, NULL, '2025-11-26 18:25:14', '2025-11-26 18:25:14', 1, 'f4f2a38b03f28a7753eee05ecfb89cec2d9d7790', NULL),
(93, 'ZE Roland', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'roland.ze+responsable@bc-pme.cm', NULL, '$2y$12$1pvaNryRseF9VPc3UqR0l.MkOSAgpLwVT5tLGIPNbsQ7OvxVqoNvK', NULL, NULL, NULL, NULL, '2025-11-26 18:25:15', '2025-11-26 18:25:15', 1, 'f94b69bbf41deba1db1cc89f944a41a862d530d9', NULL),
(94, 'MAIRAMOU BOBO', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'bobo.mairamou+gestionnaire@bc-pme.cm', NULL, '$2y$12$OWsvFDt5kdvzeCW6Ea4wVeL3bEzucB9emmxXq/Aux87pTgdzjjtBe', NULL, NULL, NULL, NULL, '2025-11-26 18:25:15', '2025-11-26 18:25:15', 1, 'f923eef41c459ab38fc8483a99a0431ceb6b2834', NULL),
(95, 'MAIRAMOU BOBO', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'bobo.mairamou+analyste@bc-pme.cm', NULL, '$2y$12$e3GrnqA/xKNJSQvqWUZilO.AvFOhxjdHQHllfzRGNcL361z2t3gRu', NULL, NULL, NULL, NULL, '2025-11-26 18:25:15', '2025-11-26 18:25:15', 1, 'd6bac0928bc8d3004c43bbd2d149ae255103242b', NULL),
(96, 'MAIRAMOU BOBO', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'bobo.mairamou+chef@bc-pme.cm', NULL, '$2y$12$Z3ktXtFdBty9t104Yj4xU.Lnbr7aMYmjAPF8yENUaD0JY3saUi5ni', NULL, NULL, NULL, NULL, '2025-11-26 18:25:15', '2025-11-26 18:25:15', 1, '0a1f4016703966b4489b189d48e5bfd2beeb371d', NULL),
(97, 'MAIRAMOU BOBO', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'bobo.mairamou+responsable@bc-pme.cm', NULL, '$2y$12$bQnr7zQGAHYlHhXNsM280uTaf4jaC5rg5voIoCpVS20WrOqd/VRT.', NULL, NULL, NULL, NULL, '2025-11-26 18:25:15', '2025-11-26 18:25:15', 1, '132aa7b97f0197ca58ef09b72fd38fc47c582a0c', NULL),
(98, 'MANGA Welisane', 13, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'Welisane.Manga+gestionnaire@bc-pme.cm', NULL, '$2y$12$Qt/Er3mrMs7JVCqJizTxKOVdYi24VgM1MnhDejvOv6ZeKIjfFPlkC', NULL, NULL, NULL, NULL, '2025-11-26 18:25:16', '2025-11-26 18:25:16', 1, '498c280cb46d06282766f9fd8c2afdefcc7d07b8', NULL),
(99, 'MANGA Welisane', 14, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'Welisane.Manga+analyste@bc-pme.cm', NULL, '$2y$12$NQt2nK6FZgFY3WUed7Vra.3WHhQlumGNY8ycpl7ZUS1/XzB8d/dSW', NULL, NULL, NULL, NULL, '2025-11-26 18:25:16', '2025-11-26 18:25:16', 1, '9ca119282fdff08f3e89948af187c197769ace71', NULL),
(100, 'MANGA Welisane', 12, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'Welisane.Manga+chef@bc-pme.cm', NULL, '$2y$12$KOl4Hh2yOB9Cwm4g6/b8L.bjJvomyXA.NfnQpdUlJ2WzdqYTu4DXW', NULL, NULL, NULL, NULL, '2025-11-26 18:25:16', '2025-11-26 18:25:16', 1, '0d0887c783ce6a4dc087f7192ad6051ad2272ffb', NULL),
(101, 'MANGA Welisane', 11, NULL, 4, 2, 0, 0, 0, 0, 0, 0, NULL, 'Welisane.Manga+responsable@bc-pme.cm', NULL, '$2y$12$ilFjEqAzHQSi.V/jN2KlzO8VNs8sTZc33jhBzRuVv4lQtPNBp5GTO', NULL, NULL, NULL, NULL, '2025-11-26 18:25:16', '2025-11-26 18:25:16', 1, 'bf2cd263421c6a8f4bf3de14b960b2915d74efc1', NULL),
(102, 'HAMAN AMADOU', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'amadou.haman@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'aa71f9520d08623743b7e3fdd9f5cd5d599e7951', NULL),
(103, 'HAMAN AMADOU', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'amadou.haman1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, '273aed2fa8554dff62c570d9de7fa777e14c154d', NULL),
(104, 'HAMAN AMADOU', 12, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'amadou.haman2@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'bbc734901824a9c91521d26baf89f36df05378e3', NULL),
(105, 'YAKOUBOU MOUSSA', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'moussa.yakoubou@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'e95873c3698585a252f43166c7c7c2459bc43e78', NULL),
(106, 'YAKOUBOU MOUSSA', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'moussa.yakoubou1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'dc005ddbedb05ce94843bc5b3df185e4f9df8ea6', NULL),
(107, 'MBENA NDONGO', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'ndongo.mbena@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, '231fbb587f6214a9e087cc15fc4828e520e8eab8', NULL),
(108, 'MBENA NDONGO', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'ndongo.mbena1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'ca59b428a80936459cd3f3de3914c05c70d594ec', NULL),
(109, 'TABI FABIEN', 12, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'fabien.tabi@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, 'd956cc633b18deddc3c69ca07225c0706a6bbd2f', NULL),
(110, 'HASSAN MEY', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'mey.hassan@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, '7b4de0216bdda961080776a51a7368ed026a5324', NULL),
(111, 'MAIRAMOU BOBO', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'bobo.mairamou@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, '1f863eaa1212451fd64ce82d169d338056e69098', NULL),
(112, 'GALLAH LESLIE', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'leslie.gallah@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:25', '2026-04-01 08:23:45', 1, '00d219c344348b3bcaef3fd47f7e47e5f032a2c0', NULL),
(113, 'KAMLA VICTOR', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '5628c214be2ae1284506a0ef6f48f3b9de628d61', NULL),
(114, 'KAMLA VICTOR', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'victor.kamla1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, 'd72cecb771ef3b2583d17fd64b84e87f36684d40', NULL),
(115, 'MINTOM YVETTE', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'yvette.mintom@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '16ff0c2325053a2ccb17cf252f70b503f255f7f7', NULL),
(116, 'MINTOM YVETTE', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'yvette.mintom1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '6b05213e7a791b447b1ae4a61ac644b51c0d8a07', NULL),
(117, 'NDJOMO EKO', 1, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'eko.ndjomo@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-03-19 10:27:26', 1, NULL, NULL),
(118, 'MANGA Welisane', 1, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'welisane.manga@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-03-19 10:27:26', 1, NULL, NULL),
(119, 'YAKANA BABOYA', 1, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'baboya.yakana@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-03-19 10:27:26', 1, NULL, NULL),
(120, 'WAMBA FOKOU', 1, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'fokou.wamba@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-03-19 10:27:26', 1, NULL, NULL),
(121, 'BLAISE ROLAND ZE', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'roland.blaise@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '68da368ea3a90f684f7696667d44692668228b9b', NULL),
(122, 'BLAISE ROLAND ZE', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'roland.blaise1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '202cd61455026d5148c869338075d6a2636147b5', NULL),
(123, 'ESSAKA SANDRINE', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'sandrine.essaka@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '5402e22ff6f085bb58b6f8999f592c7bbdc87761', NULL),
(124, 'ESSAKA SANDRINE', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'sandrine.essaka1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '90af22c6fb3c60db8b897349891d343ce9480505', NULL),
(125, 'EKITIKE BONDIMA', 13, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'bondima.ekitike@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '04c1918ae9ffcf59e14c6731a6500d06b3e16635', NULL),
(126, 'EKITIKE BONDIMA', 14, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 'bondima.ekitike1@angara.cm', NULL, '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG', NULL, NULL, NULL, NULL, '2026-03-19 10:27:26', '2026-04-01 08:23:45', 1, '4c43c6f26d343aa0593bd760dad75fbca8fcba1e', NULL);

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
(22, 'Essassa', 0, '2026-02-11 23:57:48', '2026-02-11 23:57:48', 14, 77, '72393892', '89328032', 2, 0, 0, 0, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adonis_schema`
--
ALTER TABLE `adonis_schema`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `adonis_schema_versions`
--
ALTER TABLE `adonis_schema_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `agences`
--
ALTER TABLE `agences`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `branches`
--
ALTER TABLE `branches`
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
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `domaines`
--
ALTER TABLE `domaines`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_domain_unique` (`domain`),
  ADD KEY `domains_tenant_id_foreign` (`tenant_id`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dossier_esg_evaluations`
--
ALTER TABLE `dossier_esg_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_esg_evaluations_dossier_id_unique` (`dossier_id`);

--
-- Index pour la table `dossier_esg_evaluation_items`
--
ALTER TABLE `dossier_esg_evaluation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier_esg_evaluation_items_dossier_esg_evaluation_id_foreign` (`dossier_esg_evaluation_id`);

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
-- Index pour la table `entreprise_evaluation_profiles`
--
ALTER TABLE `entreprise_evaluation_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entreprise_evaluation_profiles_entreprise_id_unique` (`entreprise_id`);

--
-- Index pour la table `entreprise_produits`
--
ALTER TABLE `entreprise_produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise_types`
--
ALTER TABLE `entreprise_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluation_categories`
--
ALTER TABLE `evaluation_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluation_categories_code_unique` (`code`),
  ADD KEY `evaluation_categories_framework_id_foreign` (`framework_id`);

--
-- Index pour la table `evaluation_frameworks`
--
ALTER TABLE `evaluation_frameworks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluation_frameworks_code_unique` (`code`);

--
-- Index pour la table `evaluation_indicators`
--
ALTER TABLE `evaluation_indicators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluation_indicators_code_unique` (`code`),
  ADD KEY `evaluation_indicators_framework_id_foreign` (`framework_id`),
  ADD KEY `evaluation_indicators_category_id_foreign` (`category_id`);

--
-- Index pour la table `evaluation_score_thresholds`
--
ALTER TABLE `evaluation_score_thresholds`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluation_settings`
--
ALTER TABLE `evaluation_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluation_settings_key_unique` (`key`);

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
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fichiers_types`
--
ALTER TABLE `fichiers_types`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `gammes`
--
ALTER TABLE `gammes`
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
-- Index pour la table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`);

--
-- Index pour la table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

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
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

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
-- Index pour la table `questions`
--
ALTER TABLE `questions`
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
-- Index pour la table `recommandations`
--
ALTER TABLE `recommandations`
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
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `secteurs`
--
ALTER TABLE `secteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tenants_banques`
--
ALTER TABLE `tenants_banques`
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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `villages`
--
ALTER TABLE `villages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adonis_schema`
--
ALTER TABLE `adonis_schema`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `agences`
--
ALTER TABLE `agences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT pour la table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT pour la table `composantes`
--
ALTER TABLE `composantes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT pour la table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `domaines`
--
ALTER TABLE `domaines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `dossier_esg_evaluations`
--
ALTER TABLE `dossier_esg_evaluations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `dossier_esg_evaluation_items`
--
ALTER TABLE `dossier_esg_evaluation_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT pour la table `entreprises_elements_constitutifs`
--
ALTER TABLE `entreprises_elements_constitutifs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `entreprise_appuis`
--
ALTER TABLE `entreprise_appuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT pour la table `entreprise_evaluation_profiles`
--
ALTER TABLE `entreprise_evaluation_profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entreprise_produits`
--
ALTER TABLE `entreprise_produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `entreprise_types`
--
ALTER TABLE `entreprise_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `evaluation_categories`
--
ALTER TABLE `evaluation_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `evaluation_frameworks`
--
ALTER TABLE `evaluation_frameworks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `evaluation_indicators`
--
ALTER TABLE `evaluation_indicators`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `evaluation_score_thresholds`
--
ALTER TABLE `evaluation_score_thresholds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `evaluation_settings`
--
ALTER TABLE `evaluation_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT pour la table `gammes`
--
ALTER TABLE `gammes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `indicateurs`
--
ALTER TABLE `indicateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `indicateurs_financiers`
--
ALTER TABLE `indicateurs_financiers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT pour la table `niveaux`
--
ALTER TABLE `niveaux`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `organismes`
--
ALTER TABLE `organismes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- AUTO_INCREMENT pour la table `profils`
--
ALTER TABLE `profils`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `programme_appuis`
--
ALTER TABLE `programme_appuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `programme_indicateurs`
--
ALTER TABLE `programme_indicateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `programme_organismes`
--
ALTER TABLE `programme_organismes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `programme_produits`
--
ALTER TABLE `programme_produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `quartiers`
--
ALTER TABLE `quartiers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `questions_answers`
--
ALTER TABLE `questions_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=522;

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
-- AUTO_INCREMENT pour la table `recommandations`
--
ALTER TABLE `recommandations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT pour la table `representations`
--
ALTER TABLE `representations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `secteurs`
--
ALTER TABLE `secteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- AUTO_INCREMENT pour la table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `tenants_banques`
--
ALTER TABLE `tenants_banques`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tiers`
--
ALTER TABLE `tiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT pour la table `villages`
--
ALTER TABLE `villages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dossier_esg_evaluation_items`
--
ALTER TABLE `dossier_esg_evaluation_items`
  ADD CONSTRAINT `dossier_esg_evaluation_items_dossier_esg_evaluation_id_foreign` FOREIGN KEY (`dossier_esg_evaluation_id`) REFERENCES `dossier_esg_evaluations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluation_categories`
--
ALTER TABLE `evaluation_categories`
  ADD CONSTRAINT `evaluation_categories_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `evaluation_frameworks` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `evaluation_indicators`
--
ALTER TABLE `evaluation_indicators`
  ADD CONSTRAINT `evaluation_indicators_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `evaluation_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evaluation_indicators_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `evaluation_frameworks` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
