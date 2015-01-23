CREATE DATABASE  IF NOT EXISTS `gertjen149_vv` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `gertjen149_vv`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: vvtissue
-- ------------------------------------------------------
-- Server version	5.6.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anderadres`
--

DROP TABLE IF EXISTS `anderadres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anderadres` (
  `bestelnr` int(11) NOT NULL,
  `plaats` varchar(30) DEFAULT NULL,
  `adres` varchar(30) DEFAULT NULL,
  `postcode` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`bestelnr`),
  CONSTRAINT `fk_anderadres_Bestelling1` FOREIGN KEY (`bestelnr`) REFERENCES `bestelling` (`bestelnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anderadres`
--

LOCK TABLES `anderadres` WRITE;
/*!40000 ALTER TABLE `anderadres` DISABLE KEYS */;
/*!40000 ALTER TABLE `anderadres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bestelling`
--

DROP TABLE IF EXISTS `bestelling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bestelling` (
  `bestelnr` int(11) NOT NULL AUTO_INCREMENT,
  `besteldatum` date NOT NULL,
  `bezorgdatum` date NOT NULL,
  `status` varchar(25) NOT NULL,
  `opmerking` varchar(400) DEFAULT NULL,
  `klantnr` int(11) NOT NULL,
  `transactieref` varchar(45) NOT NULL,
  `betaald` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`bestelnr`),
  UNIQUE KEY `bestelnr_UNIQUE` (`bestelnr`),
  UNIQUE KEY `transactieref_UNIQUE` (`transactieref`),
  KEY `fk_Bestelling_Klant1_idx` (`klantnr`),
  CONSTRAINT `fk_Bestelling_Klant1` FOREIGN KEY (`klantnr`) REFERENCES `klant` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestelling`
--

LOCK TABLES `bestelling` WRITE;
/*!40000 ALTER TABLE `bestelling` DISABLE KEYS */;
INSERT INTO `bestelling` VALUES (2,'2015-01-08','2015-01-08','In Behandeling','N.V.T.',3,'WEB-1420705144x091904','ja'),(3,'2015-01-08','2015-01-08','In Behandeling','N.V.T.',3,'WEB-1420705221x092021','ja'),(4,'2015-01-08','2015-01-08','In Behandeling','N.V.T.',3,'WEB-1420705284x092124','ja');
/*!40000 ALTER TABLE `bestelling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bestelregel`
--

DROP TABLE IF EXISTS `bestelregel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bestelregel` (
  `bestelnr` int(11) NOT NULL,
  `productnr` varchar(45) NOT NULL,
  `aantal` int(11) NOT NULL,
  PRIMARY KEY (`bestelnr`,`productnr`),
  KEY `fk_Bestelregel_Product1_idx` (`productnr`),
  CONSTRAINT `fk_Bestelregel_Bestelling1` FOREIGN KEY (`bestelnr`) REFERENCES `bestelling` (`bestelnr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bestelregel_Product1` FOREIGN KEY (`productnr`) REFERENCES `product` (`productnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestelregel`
--

LOCK TABLES `bestelregel` WRITE;
/*!40000 ALTER TABLE `bestelregel` DISABLE KEYS */;
INSERT INTO `bestelregel` VALUES (2,'10473',5),(2,'16967',4),(2,'23160',5),(3,'95303',6),(3,'95325',3),(3,'95340',7),(3,'95395',4),(4,'15605',2),(4,'34401',3),(4,'95307',5),(4,'95315',4);
/*!40000 ALTER TABLE `bestelregel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geblokkeerd`
--

DROP TABLE IF EXISTS `geblokkeerd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geblokkeerd` (
  `klantnr` int(11) NOT NULL,
  `poging` int(11) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`),
  CONSTRAINT `fk_geblokkeerd_Gebruiker1` FOREIGN KEY (`klantnr`) REFERENCES `gebruiker` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geblokkeerd`
--

LOCK TABLES `geblokkeerd` WRITE;
/*!40000 ALTER TABLE `geblokkeerd` DISABLE KEYS */;
/*!40000 ALTER TABLE `geblokkeerd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gebruiker`
--

DROP TABLE IF EXISTS `gebruiker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gebruiker` (
  `klantnr` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `wachtwoord` varchar(150) NOT NULL,
  `level` varchar(25) NOT NULL DEFAULT 'Gebruiker',
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gebruiker`
--

LOCK TABLES `gebruiker` WRITE;
/*!40000 ALTER TABLE `gebruiker` DISABLE KEYS */;
INSERT INTO `gebruiker` VALUES (2,'admin@admin.nl','$6$rounds=5000$kHXByqfkxdlQh0um$6/pMEvhfbylI8lnPb1nuhhRlYygj5DW7Ifgs4Tc6lafk/vNIWv7hg6K9zP1iugzK.AcQWvRUfGnfntfkWYctO.','Admin'),(3,'gebruiker@gebruiker.nl','$6$rounds=5000$otY8OZnG4Kbjz3vU$bhr.kaBefmrEV0ZFdE.fXXphp3NnLEwnxqImc/rSuD6yCw8UURZBQC3rADchrp/VY3COK/NwlG6FYmFPqRDR4.','Gebruiker');
/*!40000 ALTER TABLE `gebruiker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `klant`
--

DROP TABLE IF EXISTS `klant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `klant` (
  `klantnr` int(11) NOT NULL AUTO_INCREMENT,
  `bedrijfsnaam` varchar(45) NOT NULL,
  `kvknummer` int(11) NOT NULL,
  `btwnummer` varchar(25) NOT NULL,
  `voornaam` varchar(45) NOT NULL,
  `achternaam` varchar(45) NOT NULL,
  `telnummer` varchar(11) NOT NULL,
  `mobnummer` varchar(13) DEFAULT NULL,
  `adres` varchar(30) NOT NULL,
  `plaats` varchar(30) NOT NULL,
  `postcode` varchar(7) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`),
  CONSTRAINT `fk_Klant_Gebruiker1` FOREIGN KEY (`klantnr`) REFERENCES `gebruiker` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `klant`
--

LOCK TABLES `klant` WRITE;
/*!40000 ALTER TABLE `klant` DISABLE KEYS */;
INSERT INTO `klant` VALUES (2,'Admin and co',12312312,'NL876543210B01','Admin','von adminness','1234567890','1234567890','Adminstraat 21','Adminsfoort','1234AB'),(3,'gebruikers en co',12312312,'NL876543210B01','gebruiker@gebruiker','gebruiker von gebruiker','1234567890','1234567890','gebruikersstraat 32','Gebruikersdam','3816RE');
/*!40000 ALTER TABLE `klant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `productnr` varchar(45) NOT NULL,
  `productnaam` varchar(45) NOT NULL,
  `EAN` varchar(45) NOT NULL,
  `merk` varchar(25) NOT NULL,
  `categorie` varchar(25) NOT NULL,
  `subcategorie` varchar(45) NOT NULL,
  `omschrijving` varchar(1000) DEFAULT NULL,
  `prijs` double NOT NULL,
  `voorraad` int(11) NOT NULL,
  `afbeelding` longblob,
  `kleur` varchar(45) DEFAULT NULL,
  `hoogte` int(11) DEFAULT NULL,
  `breedte` int(11) DEFAULT NULL,
  `lengte` int(11) DEFAULT NULL,
  `fabrikant` varchar(45) DEFAULT NULL,
  `verpakking` varchar(45) DEFAULT NULL,
  `certificaten` varchar(100) DEFAULT NULL,
  `inhoud` varchar(45) DEFAULT NULL,
  `materiaal` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`productnr`),
  UNIQUE KEY `productnaam_UNIQUE` (`productnaam`),
  UNIQUE KEY `EAN_UNIQUE` (`EAN`),
  UNIQUE KEY `productnr_UNIQUE` (`productnr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES ('10473',' Katrin Classic Toiletpapier 200 2 lgs 48 rol','7316970104734','Katrin','Papier','Toiletpapier','Katrin Classic Toiletpapier 200 is zacht en effectief 2-laags toiletpapier met een langdurig en hoog absorptiepercentage, huidvriendelijk.\r\n\r\nâ€¢ Hoge kwaliteit wc-papier\r\nâ€¢ Rol past in bijna alle gangbare dispensers',12.5,0,'../administratie/img/toiletpapier200.jpg','Wit',0,10,25,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','12 x 4 rollen','Papier'),('10483',' Katrin Classic Toiletpapier 400 2 lgs 42 rol','7316970104833','Katrin','Papier','Toiletpapier','Katrin Classic Toiletpapier 400 is zacht en effectief 2-laags toiletpapier met een langdurig en hoog absorptiepercentage en huidvriendelijk.\r\n\r\nâ€¢ Hoge kwaliteit wc-papier\r\nâ€¢ Rol past in bijna alle gangbare dispensers',22.95,0,'../administratie/img/toiletpapier400.jpg','Wit',0,11,48,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','7 x 6 rollen','Papier'),('15605',' Katrin Plus Systeem Toiletpapier 2 lgs 36 ro','6414300156055','Katrin','Papier','Toiletpapier','Katrin Plus Systeem Toiletpapier is zacht voor de huid en comfortabel in gebruik.\r\n\r\nâ€¢ Wit, extra zacht 2-laags toiletpapier\r\nâ€¢ Zacht voor de huid en comfortabel in gebruik\r\nâ€¢ Nordic Swan-ecolabel',54.75,0,'../administratie/img/toiletpapiersysteem.jpg','Wit',0,13,85,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, ISEGA ecolabel, iso9001 certified, iso14001 certified','36 rollen','Papier'),('16967',' Katrin Plus Toiletpapier 143 3 lgs 42 rol','0','Katrin','Papier','Toiletpapier','â€¢ Premium kwaliteit toiletpapier',23.95,0,'../administratie/img/toiletpapierplus.jpg','Wit',0,11,18,'MetsÃ¤ Tissue GmbH','7 x 6 rollen','iso9001 certified, iso14001 certified','6 rollen','Papier'),('19502',' Katrin Plus Bulk Pack Toiletpapier 2 lgs','7316970195022','Katrin','Papier','Toiletpapier','Katrin Plus Bulk Pack Toiletpapier is een hoog kwaliteit wit toiletpapier, extreem zacht en aangenaam om te gebruiken. De interleaved vellen beperken het verbruik.\r\n\r\nâ€¢ Hoge kwaliteit wit toiletpapier\r\nâ€¢ Extreem zacht en aangenaam om te gebruiken\r\nâ€¢ Interleaved vellen beperken het verbruik',35.2,0,'../administratie/img/toiletpapierbulk.jpg','Wit',0,108,208,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','30 x 300 vel','Papier'),('23160',' Katrin Classic Kitchen 50 2 lgs 28 rol','7316970231607','Katrin','Papier','Keukenrollen','Katrin Classic Kitchen 50 is een hoge kwaliteit keukenrol met een goede absorptiecapaciteit, zacht en pluisarm.\r\n\r\nâ€¢ Hoge kwaliteit huishoudelijke handdoeken\r\nâ€¢ Zeer goede absorptiecapaciteit \r\nâ€¢ Zacht en pluisarm papier zorgt voor goede resultaten\r\nâ€¢ Rol past in bijna alle gangbare dispensers',18.95,0,'../administratie/img/keukenrol2laags.jpg','Wit',0,11,11,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','7 x 4 rollen','Papier'),('34401',' Katrin Plus One-Stop L3 3 lgs 1890 st','6414300344018','Katrin','Papier','Vouwhanddoeken','Katrin Plus One-Stop is een premium zachte handdoek met een luxe gevoel. Het papier is zacht voor een aangename en hygiÃ«nisch drogen van handen met een snelle, hoge absorptie capaciteit. De Touch-free verstrekking voorkomt kruisbesmetting en met gecontroleerd handdoek verbruik door het afgeven van Ã©Ã©n handdoek. Het groot papier formaat zorgt ervoor dat Ã©Ã©n handdoek genoeg is voor het grondig drogen van de handen.\r\n\r\nâ€¢ Premium zachte handdoek voor luxe gevoel\r\nâ€¢ Zacht papier voor een aangename en hygiÃ«nisch drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit \r\nâ€¢ Hoge sterkte voor effectief drogen van handen\r\nâ€¢ Touch-free verstrekking voorkomt kruisbesmetting\r\nâ€¢ Gecontroleerd handdoek verbruik door het afgeven van Ã©Ã©n handdoek\r\nâ€¢ Groot papier formaat zorgt ervoor dat Ã©Ã©n handdoek genoeg is voor het grondig drogen van de handen',49.95,0,'../administratie/img/vouwhanddoek1890.jpg','Wit',0,235,340,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, ISEGA ecolabel, iso9001 certified, iso14001 certified','21 x 90 vellen','Papier'),('34525',' Katrin Classic One-Stop M2 2 lgs 3045 st','6414300355251','Katrin','Papier','Vouwhanddoeken','Katrin Classic One-Stop M2 heeft een snelle en hoge absorptie capaciteit. De touch-free verstrekking voorkomt kruisbesmetting en de handdoeken worden gecontroleerd vrijgegeven dankzij de One-Stop vouw.\r\n\r\nâ€¢ Zacht papier voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit \r\nâ€¢ Touch-free verstrekking voorkomt kruisbesmetting\r\nâ€¢ One-Stop = gecontroleerd consumptie',32.5,0,'../administratie/img/vouwhanddoek3045.jpg','Wit',0,235,250,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','3045 vellen','Papier'),('34528',' Katrin Classic One-Stop M2 2 lgs 3366 st','6414300345282','Katrin','Papier','Vouwhanddoeken','Katrin Classic One-Stop M2 heeft een snelle en hoge absorptie capaciteit. De touch-free verstrekking voorkomt kruisbesmetting en de handdoeken worden gecontroleerd vrijgegeven dankzij de One-Stop vouw.\r\n\r\nâ€¢ Zacht papier voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit \r\nâ€¢ Touch-free verstrekking voorkomt kruisbesmetting\r\nâ€¢ One-Stop = gecontroleerd consumptie',34.5,0,'../administratie/img/vouwhanddoek3366.jpg','Wit',0,206,250,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','3360 vellen','Papier'),('36180',' Katrin Classic ZZ Vouwhanddoeken 2 lgs 3000 ','5903683361800','Katrin','Papier','Vouwhanddoeken','Katrin Classic One-Stop ZZ Vouwhanddoeken hebben een snelle en hoge absorptie capaciteit. De touch-free verstrekking voorkomt kruisbesmetting en de vouwhanddoeken worden gecontroleerd vrijgegeven dankzij de ZZ vouw.\r\n\r\nâ€¢ Zacht papier voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit \r\nâ€¢ Touch-free verstrekking voorkomt kruisbesmetting\r\nâ€¢ ZZ-vouw = gecontroleerd consumptie',34.95,0,'../administratie/img/vouwhanddoek3000.jpg','Wit',0,232,230,'MetsÃ¤ Tissue GmbH','Doos','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','3000 vellen','Papier'),('46010',' Katrin Classic Systeem Rol M2 2 lgs 6 rol','6414300460107','Katrin','Papier','Handdoekrollen','Katrin Classic Systeem Rol M2 heeft dik, zacht papier voor het hygiÃ«nisch drogen van handen. De platte vellen van een optimaal formaat verminderen afval en de snel absorptiepercentage vermindert verbruik.\r\n\r\nâ€¢ Dik, zacht papier voor hygiÃ«nisch drogen van handen\r\nâ€¢ Wit 2-laag tissue\r\nâ€¢ Extra snelle absorptie en hoge absorptiecapaciteit\r\nâ€¢ Sterk voor efficiÃ«nt drogen\r\nâ€¢ Swan- en ISEGA-ecolabel\r\nâ€¢ 680 vellen',59.95,0,'../administratie/img/handdoekrol2laagssysteem.jpg','Wit',0,19,160,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, ISEGA ecolabel, iso9001 certified, iso14001 certified','6 rollen','Papier'),('46422',' Katrin Classic XL3 Poetsrol blauw 3 lgs 2 ro','7316970464227','Katrin','Papier','Poetsrollen','Katrin Classic XL3 Poetsrol is goed kwaliteit papier geschikt voor algemene doeleinden. De sterke 3-laags structuur beschermt bij het afvegen van oneffen en scherpe oppervlakken en heeft een zeer goed absorptievermogen voor een effectieve absorptie van olie en de verwijdering van vetten.\r\n\r\nâ€¢ Goed kwaliteit papier\r\nâ€¢ Sterke 3-laags structuur\r\nâ€¢ Zeer goed absorptievermogen \r\nâ€¢ Effectieve absorptie van olie en vetten',28.95,0,'../administratie/img/poetsrol3laagsblauw.jpg','Blauw',0,34,190,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','2 rollen','Papier'),('46440',' Katrin Classic M2 Handdoekrol blauw 2 lgs 6 ','7316970464401','Katrin','Papier','Handdoekrollen','Katrin Classic M2 handdoekrol blauw is sterk en heeft een snelle en hoge absorptie capaciteit om effectief handen te drogen.\r\n\r\nâ€¢ Zacht papier voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Sterk voor effectief drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit voor volledige tevredenheid van de gebruiker',28.95,0,'../administratie/img/handdoekrol2laagsblauw.jpg','Blauw',0,20,150,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','6 rollen','Papier'),('48191',' Katrin Classic M2 Handdoekrol 2 lgs 6 rol','geen','Katrin','Papier','Handdoekrollen','Katrin Classic M2 handdoekrol is sterk en heeft een snelle en hoge absorptie capaciteit om effectief handen te drogen.\r\n\r\nâ€¢ Zachte tissue voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Sterk voor effectief drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit voor volledige tevredenheid van de gebruiker',23.4,0,'../administratie/img/handdoekrol2laags.jpg','Wit',0,20,152,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','6 rollen','Papier'),('48504',' Katrin Classic M Handdoekrol 1 lgs 6 rol','7316970485048','Katrin','Papier','Handdoekrollen','Katrin Classic M handdoekrol is sterk en heeft een snelle en hoge absorptie capaciteit om effectief handen te drogen.\r\n\r\nâ€¢ Zachte tissue voor het hygiÃ«nisch drogen van handen\r\nâ€¢ Sterk voor effectief drogen van handen\r\nâ€¢ Snelle, hoge absorptie capaciteit voor volledige tevredenheid van de gebruiker',24.55,0,'../administratie/img/handdoekrol1laags.jpg','Natuurlijk wit',0,20,300,'MetsÃ¤ Tissue GmbH','Folie','Nordic Swan ecolabel, iso9001 certified, iso14001 certified','6 rollen','Tissue'),('57711',' Katrin Plus Facial 2 lgs 40 x 100 st','7316970577118','Katrin','Papier','Vouwhanddoeken','Katrin Plus Facial is een wit en zacht 2-laags tissue papier met een hoge absorptiecapaciteit en verpakt in praktische kartonnen doos, die Ã©Ã©n vel tegelijk verdeelt.\r\n\r\nâ€¢ Witte en zachte 2-laags tissue papier\r\nâ€¢ Hoge absorptiecapaciteit\r\nâ€¢ Verpakt in praktische kartonnen doos, verdeelt Ã©Ã©n vel tegelijk',32.95,0,'../administratie/img/vouwhanddoekfacial.jpg','Wit',0,205,210,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','40 x 100 vellen','Papier'),('95303',' Katrin Systeem Handdoekrol Dispensers','6414300953005','Katrin','Dispensers','Handdoekroldispensers','De Katrin Systeem Handdoekrol Dispenser geeft schone vellen van een ideaal formaat voor het drogen van handen uit, waardoor het verbruik en afval worden geminimaliseerd. Al het papier op de rol wordt gebruikt. Afhankelijk van de gebruikers kunnen verschillende papierkwaliteiten worden leverd.\r\n\r\nDe Katrin systeem handdoekrol dispenser is zeer functioneel met vele slimme functies. De dispenser is betrouwbaar en gemakkelijk te gebruiken.\r\n\r\nHet papierformaat is instelbaar tot een optimale papierformaat. De Katrin systeem handdoekrol dispenser heeft een hoge capaciteit (450-690 vellen per rol) en is gemakkelijk te vullen. Verder is de dispenser onderhoudsvrij en geschikt voor alle omgevingen.\r\n\r\nâ€¢ Gratis via bruikleenovereenkomst!\r\nâ€¢ Zeer functioneel met vele slimme functies\r\nâ€¢ Betrouwbaar\r\nâ€¢ Gemakkelijk te gebruiken\r\nâ€¢ Optimale papierformaat\r\nâ€¢ Hoge capaciteit: 450-690 vellen per rol \r\nâ€¢ Gemakkelijk te vullen\r\nâ€¢ Ontworpen om al het papier zorgen op de rol te gebruiken',85,0,'../administratie/img/handdoekroldispensersysteem.jpg','Licht grijs',370,340,230,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95307',' Katrin Systeem Touchfree Handdoekrol Dispens','6414300953074','Katrin','Dispensers','Handdoekroldispensers','Een dispenser met een grote capaciteit is van essentieel belang om de bezoekers tevreden te houden. De Katrin Systeem Elektrische handdoekdispenser is een elektronische dispenser die automatisch vellen uitgeeft van een ideaal formaat voor het drogen van handen.\r\n\r\nEÃ©n rol volstaat voor het 530 keer drogen van handen. De handdoeken komen snel en gelijkmatig naar buiten - perfect voor veelbezochte toiletten.\r\n\r\nâ€¢ Geeft het volgende papier vel automatisch\r\nâ€¢ Betrouwbare\r\nâ€¢ Snelle papieruitgave\r\nâ€¢ Optimale papierformaat\r\nâ€¢ Hoge capaciteit - 800 vellen op een rol genoeg voor 400-530 droge handen\r\nâ€¢ Gemakkelijk bij te vullen\r\nâ€¢ Zeer laag stroomverbruik\r\nâ€¢ Gecontroleerde\r\nâ€¢ Voor veel sanitair verkeer',125,0,'../administratie/img/handdoekroldispensertouchfree.jpg','Licht grijs',420,297,235,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95315',' Katrin Vouwhanddoek Dispenser M','6414300953159','Katrin','Dispensers','Vouwhanddoekdispensers','Het ontwerp van de afsluitbare Katrin Vouwhanddoek dispensers zorgt ervoor dat alle vouwhanddoeken worden gebruikt.\r\n\r\nFrisse, zachte papieren vouwhanddoeken worden Ã©Ã©n voor Ã©Ã©n aangeboden. De dispensers zijn voorzien van een kijkvenster, zodat het personeel makkelijk kan zien wanneer er bijgevuld moet worden. Deze robuuste dispensers kunnen zelfs in de meest veeleisende omgevingen worden gebruikt.\r\n\r\nDe Katrin vouwhanddoek dispenser M kan worden gevuld met twee pakken handdoeken voordat de dispenser leeg loopt. De Katrin vouwhanddoek dispenser M is compatibel met Katrin C-vouw, Non Stop en One Stop handdoeken en het easy-flow ribben en de nieuw ontworpen openinge staan garant voor een optimale handdoekuitgifte.\r\n\r\nâ€¢ Eenvoudig te gebruiken\r\nâ€¢ Eenvoudig bij te vullen\r\nâ€¢ Eenvoudig aan de wand te monteren\r\nâ€¢ Schuine uitgiftelatten en een handige opening voor extra betrouwbaarheid\r\nâ€¢ Compatibel met Katrin C-vouw, Non Stop en One Stop handdoeken\r\nâ€¢ Twee handdoek pakken',35,0,'../administratie/img/vouwhanddoekdispenserm.jpg','Licht grijs',389,305,132,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95325',' Katrin Centre Feed Handdoekrol Dispenser S','6414300953258','Katrin','Dispensers','Handdoekroldispensers','De Katrin Centerfeed Handdoekrol Dispenser S is bijzonder compact en zeer geschikt voor kleine toiletten. De dispenser is eenvoudig bij te vullen met heel veel papier en voorzien van veilige afscheurstrips met afgeschermde zaagtanden.\r\n\r\nâ€¢ Nauwkeurig afscheuren\r\nâ€¢ Minimale stofvorming\r\nâ€¢ Pas zelfs in een kleinere ruimte\r\nâ€¢ Afgeschermde zaagtanden voor extra veiligheid\r\nâ€¢ Gemakkelijk te vullen',24.95,0,'../administratie/img/handdoekroldispensers.jpg','Licht grijs',336,184,167,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95330',' Katrin Centre Feed Handdoekrol Dispenser M','6414300953302','Katrin','Dispensers','Handdoekroldispensers','De Katrin Centerfeed Handdoekrol Dispenser M is zeer geschikt voor industriÃ«le toiletten of werkplaatsen. De dispenser is eenvoudig bij te vullen met heel veel papier en voorzien van veilige afscheurstrips met afgeschermde zaagtanden.\r\n\r\nâ€¢ Nauwkeurig afscheuren\r\nâ€¢ Minimale stofvorming\r\nâ€¢ Pas zelfs in een kleinere ruimte\r\nâ€¢ Afgeschermde zaagtanden voor extra veiligheid',29.95,0,'../administratie/img/handdoekroldispenserm.jpg','Licht grijs',388,260,230,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95335',' Katrin Gigant S Toiletpapier Dispenser','6414300953357','Katrin','Dispensers','Toiletpapierdispensers','De Katrin Gigant S dispenser biedt een uitstekende betrouwbaarheid. Dankzij een aantal slimme functies is er altijd toiletpapier aanwezig. Door het platte ontwerp is de dispenser eenvoudig te plaatsen, zelfs in kleine toiletten.\r\n\r\nDe dispenser beschikt over een kijkvenster, waardoor u kunt zien of het papier moet worden bijgevuld. Dit vereenvoudigt het bijvullen en onderhoud.\r\n\r\nâ€¢ Vernuftig ingebouwde afscheur- en losfunctie\r\nâ€¢ Gemakkelijk te vullen\r\nâ€¢ Veilig cutting edge design\r\nâ€¢ Ruimtebesparend ontwerp ideaal zelfs voor in de kleinste toiletten',39.9,0,'../administratie/img/toiletdispensers.jpg','Licht grijs',261,260,135,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95340',' Katrin Gigant L Toiletpapier Dispenser','6414300953401','Katrin','Dispensers','Toiletpapierdispensers','De Katrin Gigant L dispenser biedt een uitstekende betrouwbaarheid. Dankzij een aantal slimme functies is er altijd toiletpapier aanwezig. Door het platte ontwerp is de dispenser eenvoudig te plaatsen, zelfs in kleine toiletten.\r\n\r\nDe dispenser beschikt over een kijkvenster, waardoor u kunt zien of het papier moet worden bijgevuld. Dit vereenvoudigt het bijvullen en onderhoud.\r\n\r\nâ€¢ Vernuftig ingebouwde afscheur- en losfunctie\r\nâ€¢ Gemakkelijk te vullen\r\nâ€¢ Veilig cutting edge design\r\nâ€¢ Ruimtebesparend ontwerp ideaal zelfs voor in de kleinste toiletten\r\nâ€¢ Max. roldiameter voor Gigant L 290 mm',39.9,0,'../administratie/img/toiletdispenserl.jpg','Licht grijs',358,340,141,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95345',' Katrin Systeem Toiletpapier Dispenser','6414300953456','Katrin','Dispensers','Toiletpapierdispensers','De Katrin Systeem Toiletroldispenser is een dispenser met een grote capaciteit (1360-1840 vellen), die ervoor zorgt dat er altijd papier in het toilet aanwezig is.\r\n\r\nDe toiletroldispenser bevat twee rollen en kan worden bijgevuld voordat de rol op is. De reserve rollen valt omlaag als het laatste blad van de eerste rol is gebruikt. Een handige papierrem voorkomt te veel draaien van de rol en vermindert papierverspilling. Het zijn vaak de kleine details die zorgen voor een goede indruk van het toilet!\r\n\r\nâ€¢ Hoge capaciteit zorgt ervoor dat er altijd papier is\r\nâ€¢ Papierrem\r\nâ€¢ Eenvoudig en snel te vullen\r\nâ€¢ Betrouwbaar\r\nâ€¢ Geschikt voor twee rollen\r\nâ€¢ Hoge capaciteit - 1360-1600\r\nâ€¢ De reserve rollen valt omlaag als het laatste blad van de eerste rol is gebruikt\r\nâ€¢ Geschikt voor alle omgevingen',35,0,'../administratie/img/toiletdispensersysteem.jpg','Licht grijs',270,150,165,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95350',' Katrin Gevouwen Toiletpapier Dispenser','6414300953500','Katrin','Dispensers','Toiletpapierdispensers','Katrin gevouwen toiletpapier dispenser is de dispenser voor bulkpack toiletpapier. De Katrin gevouwen toiletpapier dispenser biedt Ã©Ã©n vel per keer aan kortom betrouwbaar en economisch.\r\n\r\nâ€¢ Betrouwbare functionaliteit\r\nâ€¢ Biedt Ã©Ã©n vel per keer aan',55,0,'../administratie/img/toiletdispensergevouwen.jpg','Licht grijs',305,157,135,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95370',' Katrin HygiÃ«nische Afvalbak 5 L','6414300953708','Katrin','Dispensers','Afvalbakken','Katrin DameshygiÃ«ne is een discreet systeem dat in ieder damestoilet aanwezig zou moeten zijn. De hygiÃ«nische afvalbak dient voor zowel hygiÃ«nische zakken als afsluitbare afvalzakken. De dispenser voor hygiÃ«nische zakken heeft een nieuwe, verbeterde constructie waardoor deze nog eenvoudiger bij te vullen en te gebruiken is.\r\n\r\nDe Katrin hygiÃ«nische afvalbak heeft een uniek twee-in-een concept, nl. de Katrin hygiÃ«nische zakken en de afsluitbare Katrin hygiÃ«nische afvalzakken aan de binnenkant van de dispenser.\r\n\r\nâ€¢ Compact ontwerp\r\nâ€¢ Bespaart ruimte\r\nâ€¢ Volledige service voor gebruikers en onderhoudspersoneel',19.8,0,'../administratie/img/afvalbak5l.jpg','Licht grijs',285,285,114,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95375',' Katrin HygiÃ«nische Zakkenhouder','6414300953753','Katrin','Dispensers','Afvalbakken','Katrin hygiÃ«nische zakkenhouder is een discreet systeem dat in ieder damestoilet aanwezig zou moeten zijn.\r\nDe dispenser voor hygiÃ«nische zakken heeft een nieuwe, verbeterde constructie waardoor deze nog eenvoudiger bij te vullen en te gebruiken is.\r\n\r\nâ€¢ Standaarduitrusting voor iedere damestoilet\r\nâ€¢ HygiÃ«nische zakken zitten altijd stevig op zijn plaats\r\nâ€¢ Eenvoudig bij te vullen',9.9,0,'../administratie/img/zakkenhouder.jpg','Licht grijs',148,102,29,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95380',' Katrin Facial Tissue Dispenser','6414300953807','Katrin','Dispensers','Vouwhanddoekdispensers','De Katrin Facial Tissue (gezichtsdoekjes) dispenser is gemakkelijk bij te vullen en de dispenser beidt Ã©Ã©n vel per keer aan.\r\n\r\nâ€¢ Dispenser voor doos met 100 gezichtsdoekjes\r\nâ€¢ Eenvoudig bij te vullen\r\nâ€¢ Dispenser biedt Ã©Ã©n vel per keer aan',19.95,0,'../administratie/img/vouwhanddoekdispenserfacial.jpg','Licht grijs',70,267,133,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95385',' Katrin Afvalkorf 40 L','6414300953852','Katrin','Dispensers','Afvalbakken','De afvalbak van Katrin is een eenvoudige oplossing voor veelbezochte toiletten. De Katrin afvalkorf 40 L is een hoge volume container met een grote opening die helpt de toilet netjes te houden. Het robuust frame zorgt voor een lange levensduur, zelfs in veeleisende omgevingen en de Katrin afvalbak is eenvoudig te legen.\r\n\r\nâ€¢ Hoge capaciteit\r\nâ€¢ Robuust frame\r\nâ€¢ Goed in veeleisende omgevingen\r\nâ€¢ Eenvoudig te legen\r\nâ€¢ Formaat: 40 L',19.9,0,'../administratie/img/afvalbak40l.jpg','Licht grijs',500,320,250,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95390',' Katrin Afvalbak 25 L','6414300953906','Katrin','Dispensers','Afvalbakken','De afvalbak van Katrin is een eenvoudige oplossing voor veelbezochte toiletten. De Katrin afvalbak heeft een capaciteit van 25 liter en is uitgerust met een no-touch deksel. Een dicht deksel houdt het afval uit het zicht en maakt uw toilet eleganter. Het robuust frame zorgt voor een lange levensduur, zelfs in veeleisende omgevingen en de Katrin afvalbak is eenvoudig te legen.\r\n\r\nâ€¢ Hoge capaciteit\r\nâ€¢ HygiÃ«nische no-touch deksel\r\nâ€¢ Werkt goed met of zonder deksel\r\nâ€¢ Eenvoudig te legen\r\nâ€¢ Formaat: 25 L',23.6,0,'../administratie/img/afvalbak25l.jpg','Licht grijs',550,330,230,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic'),('95395',' Katrin Afvalbak 50 L','6414300953951','Katrin','Dispensers','Afvalbakken','De afvalbak van Katrin is een eenvoudige oplossing voor veelbezochte toiletten. De Katrin afvalbak heeft een capaciteit van 50 liter en is uitgerust met een no-touch deksel. Een dicht deksel houdt het afval uit het zicht en maakt uw toilet eleganter. Het robuust frame zorgt voor een lange levensduur, zelfs in veeleisende omgevingen en de Katrin afvalbak is eenvoudig te legen.\r\n\r\nâ€¢ Hoge capaciteit\r\nâ€¢ HygiÃ«nische no-touch deksel\r\nâ€¢ Werkt goed met of zonder deksel\r\nâ€¢ Eenvoudig te legen\r\nâ€¢ Formaat: 50 L',32.1,0,'../administratie/img/afvalbak50l.jpg','Licht grijs',550,420,280,'MetsÃ¤ Tissue GmbH','Doos','iso9001 certified, iso14001 certified','1 ','ABS-plastic');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recovery`
--

DROP TABLE IF EXISTS `recovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recovery` (
  `klantnr` int(11) NOT NULL,
  `datum` int(11) NOT NULL,
  `token` varchar(150) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`),
  CONSTRAINT `fk_recovery_Gebruiker1` FOREIGN KEY (`klantnr`) REFERENCES `gebruiker` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recovery`
--

LOCK TABLES `recovery` WRITE;
/*!40000 ALTER TABLE `recovery` DISABLE KEYS */;
/*!40000 ALTER TABLE `recovery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `klantnr` int(11) NOT NULL,
  `token` varchar(150) NOT NULL,
  `ip` varchar(45) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE KEY `klantnr_UNIQUE` (`klantnr`),
  CONSTRAINT `fk_token_Gebruiker1` FOREIGN KEY (`klantnr`) REFERENCES `gebruiker` (`klantnr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` VALUES (2,'$6$rounds=5000$AklwW/aZTYKnzrKc$GSNLGW4tAUeeR1z9ZYF5gSxtygLwxaGoOguRP7lK97w.WxZzAt6CMBr2hhPIPaM5HbrcblSeVP2mjLcZa44wi/','::1');
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-08  9:28:16
