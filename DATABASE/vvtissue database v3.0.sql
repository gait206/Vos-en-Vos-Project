-- MySQL Script generated by MySQL Workbench
-- 12/10/14 13:05:54
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema vvtissue
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `vvtissue` DEFAULT CHARACTER SET latin1 ;
USE `vvtissue` ;

-- -----------------------------------------------------
-- Table `vvtissue`.`Gebruiker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Gebruiker` (
  `email` VARCHAR(100) NOT NULL,
  `wachtwoord` VARCHAR(150) NOT NULL,
  `level` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Klant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Klant` (
  `klantnr` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `bedrijfsnaam` VARCHAR(45) NOT NULL,
  `kvknummer` INT(11) NOT NULL,
  `btwnummer` VARCHAR(14) NOT NULL,
  `voornaam` VARCHAR(45) NOT NULL,
  `achternaam` VARCHAR(45) NOT NULL,
  `telnummer` VARCHAR(11) BINARY NOT NULL,
  `mobnummer` VARCHAR(13) NULL DEFAULT NULL,
  `adres` VARCHAR(30) NOT NULL,
  `plaats` VARCHAR(30) NOT NULL,
  `postcode` VARCHAR(7) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE INDEX `klantnr_UNIQUE` (`klantnr` ASC),
  UNIQUE INDEX `kvknummer_UNIQUE` (`kvknummer` ASC),
  UNIQUE INDEX `btwnummer_UNIQUE` (`btwnummer` ASC),
  INDEX `fk_Klant_Gebruiker_idx` (`email` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  CONSTRAINT `fk_Klant_Gebruiker`
    FOREIGN KEY (`email`)
    REFERENCES `vvtissue`.`Gebruiker` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Bestelling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Bestelling` (
  `bestelnr` INT(11) NOT NULL,
  `besteldatum` DATE NOT NULL,
  `bezorgdatum` DATE NOT NULL,
  `status` VARCHAR(25) NOT NULL,
  `opmerking` VARCHAR(80) NULL DEFAULT NULL,
  `klantnr` INT NOT NULL,
  PRIMARY KEY (`bestelnr`),
  UNIQUE INDEX `bestelnr_UNIQUE` (`bestelnr` ASC),
  INDEX `fk_Bestelling_Klant1_idx` (`klantnr` ASC),
  CONSTRAINT `fk_Bestelling_Klant1`
    FOREIGN KEY (`klantnr`)
    REFERENCES `vvtissue`.`Klant` (`klantnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Product` (
  `productid` INT NOT NULL AUTO_INCREMENT,
  `productnr` VARCHAR(45) NOT NULL,
  `productnaam` VARCHAR(45) NOT NULL,
  `merk` VARCHAR(25) NOT NULL,
  `categorie` VARCHAR(25) NOT NULL,
  `omschrijving` VARCHAR(400) NOT NULL,
  `afbeelding` LONGBLOB NULL DEFAULT NULL,
  `prijs` DOUBLE NOT NULL,
  `voorraad` INT NOT NULL,
  UNIQUE INDEX `productnr_UNIQUE` (`productnr` ASC),
  UNIQUE INDEX `productnaam_UNIQUE` (`productnaam` ASC),
  UNIQUE INDEX `productid_UNIQUE` (`productid` ASC),
  PRIMARY KEY (`productid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Bestelregel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Bestelregel` (
  `bestelnr` INT NOT NULL,
  `productnr` INT NOT NULL,
  `aantal` INT NOT NULL,
  `prijs` DOUBLE NOT NULL,
  PRIMARY KEY (`bestelnr`, `productnr`),
  UNIQUE INDEX `bestelnr_UNIQUE` (`bestelnr` ASC),
  UNIQUE INDEX `productnr_UNIQUE` (`productnr` ASC),
  CONSTRAINT `fk_Bestelregel_Bestelling1`
    FOREIGN KEY (`bestelnr`)
    REFERENCES `vvtissue`.`Bestelling` (`bestelnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bestelregel_Product1`
    FOREIGN KEY (`productnr`)
    REFERENCES `vvtissue`.`Product` (`productid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`token`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`token` (
  `email` VARCHAR(100) NOT NULL,
  `token` VARCHAR(150) NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  CONSTRAINT `fk_table1_Gebruiker1`
    FOREIGN KEY (`email`)
    REFERENCES `vvtissue`.`Gebruiker` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vvtissue`.`recovery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`recovery` (
  `email` VARCHAR(100) NOT NULL,
  `datum` DATETIME NOT NULL,
  `token` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `fk_recovery_Gebruiker1`
    FOREIGN KEY (`email`)
    REFERENCES `vvtissue`.`Gebruiker` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
