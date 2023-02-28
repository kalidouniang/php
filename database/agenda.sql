-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 16 août 2019 à 16:23
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
CREATE DATABASE IF NOT EXISTS `teamup` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `teamup`;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id_evenement`, `evenement_tstamp`, `evenement_dtstart`, `evenement_dtend`, `evenement_uid`, `evenement_subject`, `evenement_description`, `evenement_location`, `evenement_id_utilisateur`) VALUES
(1, '2019-08-14 00:00:00', '2019-09-02 12:00:00', '2019-09-02 14:00:00', '98325546156425216', 'Dej', 'Dejeuner', 'Resto Epok', 1),
(2, '2019-08-14 00:00:00', '2019-08-30 11:00:00', '2019-08-30 12:00:00', '98325546156425217', 'Meeting prepa', 'ODJ ', 'Salle Sargasse', 2);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id_evenement` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- --------------------------------------------------------

--
-- Procedures stockees
--

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_get_from_date`(IN `dtstart` DATETIME)
    NO SQL
select * from evenement where evenement_dtstart >= dtstart$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_evenement_add`(IN `subject` VARCHAR(500), IN `description` VARCHAR(500), IN `location` VARCHAR(500), IN `tstamp` DATETIME, IN `dtstart` DATETIME, IN `dtend` DATETIME, IN `id_utilisateur` INT)
BEGIN
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
DELIMITER ;
