-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 16 Novembre 2021 à 06:27
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `mtg_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

CREATE TABLE IF NOT EXISTS `cartes` (
  `idCarte` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `codeExtension` varchar(8) NOT NULL,
  `type` varchar(50) NOT NULL,
  `estTerrainBase` tinyint(1) NOT NULL,
  `rarete` varchar(10) NOT NULL,
  `coutConvertiMana` int(11) DEFAULT NULL,
  `coutManaTexte` varchar(16) NOT NULL,
  `forceCreature` int(11) DEFAULT NULL,
  `enduranceCreature` int(11) DEFAULT NULL,
  `texte` varchar(300) DEFAULT NULL,
  `urlImage` varchar(300) NOT NULL,
  PRIMARY KEY (`idCarte`),
  UNIQUE KEY `idCarte` (`idCarte`),
  KEY `idCarte_2` (`idCarte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
