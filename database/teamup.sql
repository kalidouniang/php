-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 13 oct. 2019 à 20:43
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `teamup`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `sp_evenement_add`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_add` (IN `subject` VARCHAR(500), IN `description` VARCHAR(500), IN `location` VARCHAR(500), IN `tstamp` DATETIME, IN `dtstart` DATETIME, IN `dtend` DATETIME, IN `id_utilisateur` INT)  BEGIN
INSERT INTO `evenement` 
(`id_evenement`, 
 `evenement_tstamp`, 
 `evenement_dtstart`, 
 `evenement_dtend`, 
 `evenement_uid`, 
 `evenement_subject`, 
 `evenement_description`, 
 `evenement_location`, 
 `evenement_id_utilisateur`) 
 VALUES 
 (NULL, 
 tstamp,dtstart,dtend, uuid(), subject,description,location,id_utilisateur
 );
 SELECT last_insert_id();
 END$$

DROP PROCEDURE IF EXISTS `sp_evenement_by_id`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_by_id` (IN `id_ev` INT)  NO SQL
select 
evenement.evenement_tstamp,
evenement.evenement_dtstart,
evenement.evenement_dtend,
evenement.evenement_uid,
evenement.evenement_subject,
evenement.evenement_description,
evenement.evenement_location,
evenement.evenement_id_utilisateur
from evenement
where evenement.id_evenement = id_ev$$

DROP PROCEDURE IF EXISTS `sp_evenement_edit`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_edit` (IN `id_ev` INT, IN `dtstart` DATETIME, IN `dtend` DATETIME, IN `subject` VARCHAR(500), IN `description` VARCHAR(500), IN `location` VARCHAR(500), IN `id_utilisateur` INT)  NO SQL
UPDATE `evenement` 
set  
 `evenement_dtstart` = dtstart,
 `evenement_dtend` = dtend,
 `evenement_subject` = subject,
 `evenement_description` = description,
 `evenement_location` = location,
 `evenement_id_utilisateur`= id_utilisateur
where ID_EVENEMENT = id_ev$$

DROP PROCEDURE IF EXISTS `sp_evenement_from_dates`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_from_dates` (IN `dmin` DATETIME, IN `dmax` DATETIME)  NO SQL
select 
evenement.id_evenement,
evenement.evenement_tstamp,
evenement.evenement_dtstart,
evenement.evenement_dtend,
evenement.evenement_uid,
evenement.evenement_subject,
evenement.evenement_description,
evenement.evenement_location,
evenement.evenement_id_utilisateur
from evenement
where evenement.evenement_dtstart between dmin and dmax$$

DROP PROCEDURE IF EXISTS `sp_evenement_from_dates_utilisateur`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_from_dates_utilisateur` (IN `dmin` DATETIME, IN `dmax` DATETIME, IN `id_u` INT)  NO SQL
select 
evenement.id_evenement,
evenement.evenement_tstamp,
evenement.evenement_dtstart,
evenement.evenement_dtend,
evenement.evenement_uid,
evenement.evenement_subject,
evenement.evenement_description,
evenement.evenement_location,
evenement.evenement_id_utilisateur
from evenement
where evenement.evenement_dtstart>= dmin and evenement.evenement_dtstart <= dmax and evenement.evenement_id_utilisateur = id_u$$

DROP PROCEDURE IF EXISTS `sp_evenement_from_utilisateur`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_from_utilisateur` (IN `id_u` INT)  NO SQL
select 
evenement.evenement_tstamp,
evenement.evenement_dtstart,
evenement.evenement_dtend,
evenement.evenement_uid,
evenement.evenement_subject,
evenement.evenement_description,
evenement.evenement_location,
evenement.evenement_id_utilisateur
from evenement
where evenement.evenement_id_utilisateur = id_u$$

DROP PROCEDURE IF EXISTS `sp_evenement_get_from_date`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_get_from_date` (IN `dtstart` DATETIME)  NO SQL
select * from evenement where evenement_dtstart >= dtstart$$

DROP PROCEDURE IF EXISTS `sp_evenement_get_participant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_get_participant` (IN `id_ev` INT)  NO SQL
SELECT participant.id_utilisateur 
from participant 
where participant.id_evenement = id_ev$$

DROP PROCEDURE IF EXISTS `sp_evenement_set_participant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_set_participant` (IN `id_ev` INT, IN `id_u` INT)  NO SQL
insert into participant
(
    id_evenement,
    id_utilisateur
)
values 
(
    id_ev,
    id_u
)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id_demande` int(11) NOT NULL AUTO_INCREMENT,
  `demande_objet` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `demande_texte` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `demande_date_creation` datetime NOT NULL,
  `demande_date_echeance` datetime NOT NULL,
  `id_type_demande` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_demande`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_demande`, `demande_objet`, `demande_texte`, `demande_date_creation`, `demande_date_echeance`, `id_type_demande`, `id_utilisateur`) VALUES
(1, 'MongoDB', 'Installer MonboDB', '2019-07-03 00:00:00', '2019-07-31 00:00:00', 1, 1),
(2, 'Acc&egrave;s &agrave; GIT', 'Ouvrir un compte GIT et cr&eacute;er un repo', '2019-07-04 00:00:00', '2019-07-19 00:00:00', 1, 2),
(3, 'Changer la couleur', 'Remplacer le bleu par du vert', '2019-07-29 00:00:00', '2019-07-31 00:00:00', 1, NULL),
(4, 'Changer la couleur', 'Remplacer le bleu par du rouge', '2019-07-31 00:00:00', '2019-07-31 00:00:00', 1, 2),
(5, 'Dej 12h', 'Dej avec Maria', '2019-08-13 00:00:00', '2019-08-15 00:00:00', 2, 3),
(6, 'Se voir', '', '2019-10-11 00:00:00', '2019-10-14 00:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

DROP TABLE IF EXISTS `equipe`;
CREATE TABLE IF NOT EXISTS `equipe` (
  `id_equipe` int(11) NOT NULL AUTO_INCREMENT,
  `equipe_nom` varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_equipe`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `equipe`
--

INSERT INTO `equipe` (`id_equipe`, `equipe_nom`) VALUES
(1, 'Equipe 1'),
(2, 'Equipe 2'),
(3, 'Equipe 3');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int(11) NOT NULL AUTO_INCREMENT,
  `evenement_tstamp` datetime NOT NULL,
  `evenement_dtstart` datetime NOT NULL,
  `evenement_dtend` datetime NOT NULL,
  `evenement_uid` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `evenement_subject` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `evenement_description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `evenement_location` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `evenement_id_utilisateur` int(11) NOT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id_evenement`, `evenement_tstamp`, `evenement_dtstart`, `evenement_dtend`, `evenement_uid`, `evenement_subject`, `evenement_description`, `evenement_location`, `evenement_id_utilisateur`) VALUES
(1, '2019-08-14 00:00:00', '2019-09-02 12:00:00', '2019-09-02 14:30:00', '98325546156425216', 'Dej', 'Dejeuner', 'Rzsto Epok', 2),
(2, '2019-08-14 00:00:00', '2019-08-30 11:00:00', '2019-08-30 12:00:00', '98325546156425217', 'Meeting prepa', 'ODJ ', 'Salle Sargasse', 2),
(3, '2019-08-21 00:00:00', '2019-10-14 10:00:00', '2019-10-14 11:00:00', '8f7eac40-c392-11e9-9aba-7cb518597541', 'Meeting', 'R&eacute;union de service', 'Salle Rambo', 1),
(4, '2019-11-04 00:00:00', '2019-11-18 14:00:00', '2019-11-18 16:00:00', '73c38fac-c393-11e9-9aba-7cb518597541', 'Formation JQuery', '2h de MOC sur JQuery', 'Amphi A', 2),
(5, '2019-08-31 14:35:00', '2019-08-30 10:00:00', '2019-08-30 09:00:00', '8ed1a6e6-cbfc-11e9-b2a1-5b899f7fa0a4', 'Concert ', 'Concerto en ut', 'Salle Mozart', 3),
(12, '2019-08-31 22:13:00', '2019-09-01 14:00:00', '2019-09-01 15:00:00', '8e5661a4-cc3c-11e9-b2a1-5b899f7fa0a4', 'Parcours', 'Parcours 9 trous', 'Golf Cabourg', 3),
(11, '2019-08-31 15:45:00', '2019-08-31 11:00:00', '2019-08-31 12:00:00', '646c491c-cc06-11e9-b2a1-5b899f7fa0a4', 'R&eacute;petition', 'Orchestre', 'Salle Bizet', 3);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id_evenement` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id_evenement`, `id_utilisateur`) VALUES
(11, 2),
(11, 1),
(12, 2);

-- --------------------------------------------------------

--
-- Structure de la table `type_demande`
--

DROP TABLE IF EXISTS `type_demande`;
CREATE TABLE IF NOT EXISTS `type_demande` (
  `id_type_demande` int(11) NOT NULL AUTO_INCREMENT,
  `type_demande_label` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_type_demande`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `type_demande`
--

INSERT INTO `type_demande` (`id_type_demande`, `type_demande_label`) VALUES
(1, 'Simple demande'),
(2, 'Rendez-vous'),
(3, 'Appel'),
(4, 'Document');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_nom` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `utilisateur_login` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `utilisateur_pwd` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `utilisateur_email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `utilisateur_creation` datetime NOT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `utilisateur_nom`, `utilisateur_login`, `utilisateur_pwd`, `utilisateur_email`, `utilisateur_creation`) VALUES
(1, 'Cosette', 'cosette', 'b1str0t', 'cosette@miserables.com', '2019-07-01 00:00:00'),
(2, 'Thenardier', 'thenard', 'xpl01tant', 'thenardier@cafe.miserables.com', '2019-07-01 00:00:00'),
(3, 'Steeve', 'ste', '123', 'steeve@teamup.org', '2019-07-25 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_equipe`
--

DROP TABLE IF EXISTS `utilisateur_equipe`;
CREATE TABLE IF NOT EXISTS `utilisateur_equipe` (
  `id_utilisateur` int(11) NOT NULL,
  `id_equipe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `utilisateur_equipe`
--

INSERT INTO `utilisateur_equipe` (`id_utilisateur`, `id_equipe`) VALUES
(1, 1),
(3, 1),
(2, 0),
(2, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
