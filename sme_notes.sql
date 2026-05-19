-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 19 mai 2026 à 13:30
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

--
-- Déchargement des données de la table `sme_notes`
--

INSERT INTO `sme_notes` (`id`, `name`, `description`, `mention`, `note`, `created_at`, `updated_at`) VALUES
(11, 'SME1', 'Situation économique et financière et visibilité excellentes :  forte position sur ses marchés, faible endettement par rapport aux fonds propres et au cash flow, peu sensible aux aléas conjoncturels (grande stabilité de la situation financière et des résultats à travers les cycles de son industrie et de l\'économie en général). Sa taille et sa situation lui donnent un accès très facile aux marchés financiers. La capacité du débiteur à exécuter ses engagements financiers est TRES FORTE', 'Excellent', 1, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(12, 'SME2', 'La situation financière et le niveau de cash flow actuels sont très bons, avec un historique montrant une bonne stabilité, mais avec une légère sensibilité aux aléas conjoncturels. Endettement modéré par rapport aux fonds propres et au cash flow. Solide position sur ses marchés. Très bonne visibilité à court et moyen terme. Accès facile aux marchés financiers. Sa capacité à exécuter ses engagements financiers est FORTE.', 'Très Bon', 2, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(13, 'SME3', 'Situation financière et cash flow convenables mais montrant quelque volatilité, et qui pourraient être affaiblis par une conjoncture (cycle) ou des évènements défavorables dont l\'occurrence est possible. Endettement par rapport aux fonds propres et au cash flow dans la moyenne. Bonne visibilité à court et moyen terme. Possibilité d\'accéder à de nouveaux financements dans une conjoncture normale. Cette note s\'applique aussi aux PME dont la situation financière est solide mais qui sont exclues des catégories précédentes en raison de leur taille.', 'Bon', 3, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(14, 'SME4', 'Situation financière et cash flow moyens, avec une plus grande volatilité de la situation financière et du cash flow. Il existe quelques facteurs de risque qui pourraient affaiblir la capacité du débiteur à exécuter ses engagements financiers. Cependant, visibilité satisfaisante à court et moyen terme. Accès restreint aux marchés financiers et plus coûteux, mais possibilité de développement des engagements avec ses banques.', 'Assez Bon', 4, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(15, 'SME5', 'Cash flow suffisant pour le service de la dette. Les incertitudes sur les fondamentaux du débiteur et son exposition à divers risques (sectoriels, financiers, économiques) peuvent affecter à terme sa capacité à s\'acquitter de ses obligations. Visibilité satisfaisante à court terme mais moins claire à moyen terme. Un accroissement des engagements auprès de ses banques reste encore envisageable. La qualité du management est un élément important de la décision. Accès aux marchés financiers limité, restreint et plus coûteux.', 'Moyen', 5, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(16, 'SME6', 'Les risques sectoriels, financiers et économiques sont importants mais devraient être compensés par les fondamentaux du débiteur. Possibilité limitée de trouver des financements en dehors de ses banques. La qualité du management est un élément important de la décision. Les opérations doivent être structurées (garanties, covenants) pour limiter le risque et la rémunération doit être accrue pour tenir compte de la prime de risque plus élevée.', 'Acceptable', 6, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(17, 'SME7', 'Endettement important pour le secteur. Les risques sectoriels, financiers et économiques sont importants et insuffisamment compensés par les fondamentaux du débiteur qui ne présentent pas une qualité, une stabilité et une visibilité suffisantes. Ce niveau nécessite un suivi attentif. La qualité du management est un élément primordial de la décision. Ce niveau nécessite une grande exigence dans la structuration des opérations et une rémunération significative.', 'Potentiellement vulnérable', 7, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(18, 'SME7-', 'Une aggravation des risques économiques et financiers qui pèsent sur le débiteur le conduirait  vraisemblablement à faire défaut sur ses engagements financiers.De nouvelles opérations ne peuvent être envisagées qu\'avec une extrême rigueur dans la structuration et uniquement dans le cadre de politiques de crédit dûment autorisées.', 'Vulnérable', 8, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(19, 'SME8', 'En l\'absence d\'amélioration de l\'environnement (conjoncture et marché), et/ou de mesures drastiques de restructuration industrielle ou financière, la survie de l\'entreprise serait en question. Ce niveau doit être suivi de près et assorti d\'objectifs précis pour réduire les risques (réduction des concours, garanties, etc.). Normalement, il exclut  une entrée en relation ou un accroissement des engagements.', 'Très vulnérable', 9, '2026-05-19 11:03:38', '2026-05-19 11:03:38'),
(20, 'SME8-', 'L\'existence d\'échéances financières impayées en principal et/ou en intérêts depuis plus de 3 mois entraîne le classement dans cette catégorie. Les débiteurs les plus faibles de cette catégorie sont dans une situation nettement dégradée et préoccupante quant à la bonne fin des crédits. Un dépôt de bilan est fortement possible. Ce niveau doit être suivi de très près. Le suivi doit être assortie d\'objectifs précis (réduction des concours, amélioration des garanties, etc.). La gestion doit être centralisée (prise en charge par les équipes spécialisées dans la prévention de la défaillance).', 'Douteux et/ou compromis', 10, '2026-05-19 11:03:38', '2026-05-19 11:03:38');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `sme_notes`
--
ALTER TABLE `sme_notes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `sme_notes`
--
ALTER TABLE `sme_notes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
