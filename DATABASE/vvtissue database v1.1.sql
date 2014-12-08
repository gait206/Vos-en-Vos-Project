-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 27 nov 2014 om 13:23
-- Serverversie: 5.6.13
-- PHP-versie: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `vvtissue`
--
CREATE DATABASE IF NOT EXISTS `vvtissue` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `vvtissue`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bestelling`
--

CREATE TABLE IF NOT EXISTS `bestelling` (
  `bestelnr` int(11) NOT NULL,
  `besteldatum` date NOT NULL,
  `bezorgdatum` date NOT NULL,
  `status` varchar(25) NOT NULL,
  `opmerking` varchar(80) DEFAULT NULL,
  `klantnr` int(11) NOT NULL,
  PRIMARY KEY (`bestelnr`),
  UNIQUE KEY `bestelnr_UNIQUE` (`bestelnr`),
  KEY `fk_Bestelling_Klant1_idx` (`klantnr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bestelregel`
--

CREATE TABLE IF NOT EXISTS `bestelregel` (
  `bestelnr` int(11) NOT NULL,
  `productnr` int(11) NOT NULL,
  `aantal` int(11) NOT NULL,
  `prijs` double NOT NULL,
  PRIMARY KEY (`bestelnr`,`productnr`),
  UNIQUE KEY `bestelnr_UNIQUE` (`bestelnr`),
  UNIQUE KEY `productnr_UNIQUE` (`productnr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker`
--

CREATE TABLE IF NOT EXISTS `gebruiker` (
  `email` varchar(100) NOT NULL,
  `wachtwoord` varchar(150) CHARACTER SET utf8 NOT NULL,
  `level` varchar(25) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
--

CREATE TABLE IF NOT EXISTS `klant` (
  `klantnr` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `bedrijfsnaam` varchar(45) NOT NULL,
  `kvknummer` int(11) NOT NULL,
  `btwnummer` varchar(14) NOT NULL,
  `voornaam` varchar(45) NOT NULL,
  `achternaam` varchar(45) NOT NULL,
  `telnummer` varchar(11) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `mobnummer` varchar(13) DEFAULT NULL,
  `adres` varchar(30) NOT NULL,
  `plaats` varchar(30) NOT NULL,
  `postcode` varchar(7) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`),
  UNIQUE KEY `kvknummer_UNIQUE` (`kvknummer`),
  UNIQUE KEY `btwnummer_UNIQUE` (`btwnummer`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_Klant_Gebruiker_idx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `productnr` int(11) NOT NULL,
  `productnaam` varchar(30) NOT NULL,
  `merk` varchar(25) NOT NULL,
  `categorie` varchar(25) NOT NULL,
  `omschrijving` varchar(200) NOT NULL,
  `afbeelding` longblob,
  `prijs` double NOT NULL,
  `BTW` int(11) NOT NULL,
  `voorraad` int(11) NOT NULL,
  PRIMARY KEY (`productnr`),
  UNIQUE KEY `productnr_UNIQUE` (`productnr`),
  UNIQUE KEY `productnaam_UNIQUE` (`productnaam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `email` varchar(100) NOT NULL,
  `token` varchar(150) CHARACTER SET utf8 NOT NULL,
  `ip` varchar(45) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `voorraad`
--

CREATE TABLE IF NOT EXISTS `voorraad` (
  `productnr` int(11) NOT NULL,
  `voorraad` int(11) NOT NULL,
  PRIMARY KEY (`productnr`),
  UNIQUE KEY `productnr_UNIQUE` (`productnr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `bestelling`
--
ALTER TABLE `bestelling`
  ADD CONSTRAINT `fk_Bestelling_Klant1` FOREIGN KEY (`klantnr`) REFERENCES `klant` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `bestelregel`
--
ALTER TABLE `bestelregel`
  ADD CONSTRAINT `fk_Bestelregel_Product1` FOREIGN KEY (`productnr`) REFERENCES `product` (`productnr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Bestelregel_Bestelling1` FOREIGN KEY (`bestelnr`) REFERENCES `bestelling` (`bestelnr`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD CONSTRAINT `fk_Klant_Gebruiker` FOREIGN KEY (`email`) REFERENCES `gebruiker` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_table1_Gebruiker1` FOREIGN KEY (`email`) REFERENCES `gebruiker` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `voorraad`
--
ALTER TABLE `voorraad`
  ADD CONSTRAINT `fk_Voorraad_Product1` FOREIGN KEY (`productnr`) REFERENCES `product` (`productnr`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
