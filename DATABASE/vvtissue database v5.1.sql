-- MySQL Script generated by MySQL Workbench
-- 12/15/14 15:20:27
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
  `klantnr` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `wachtwoord` VARCHAR(150) NOT NULL,
  `level` VARCHAR(25) NOT NULL DEFAULT 'Gebruiker',
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  PRIMARY KEY (`klantnr`),
  UNIQUE INDEX `klantnr_UNIQUE` (`klantnr` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Klant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Klant` (
  `klantnr` INT NOT NULL AUTO_INCREMENT,
  `bedrijfsnaam` VARCHAR(45) NOT NULL,
  `kvknummer` INT(11) NOT NULL,
  `btwnummer` VARCHAR(25) NOT NULL,
  `voornaam` VARCHAR(45) NOT NULL,
  `achternaam` VARCHAR(45) NOT NULL,
  `telnummer` VARCHAR(11) NOT NULL,
  `mobnummer` VARCHAR(13) NULL DEFAULT NULL,
  `adres` VARCHAR(30) NOT NULL,
  `plaats` VARCHAR(30) NOT NULL,
  `postcode` VARCHAR(7) NOT NULL,
  UNIQUE INDEX `klantnr_UNIQUE` (`klantnr` ASC),
  PRIMARY KEY (`klantnr`),
  CONSTRAINT `fk_Klant_Gebruiker1`
    FOREIGN KEY (`klantnr`)
    REFERENCES `vvtissue`.`Gebruiker` (`klantnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Bestelling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Bestelling` (
  `bestelnr` INT(11) NOT NULL AUTO_INCREMENT,
  `besteldatum` DATE NOT NULL,
  `bezorgdatum` DATE NOT NULL,
  `status` VARCHAR(25) NOT NULL,
  `opmerking` VARCHAR(80) NULL DEFAULT NULL,
  `klantnr` INT NOT NULL,
  `transactieref` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`bestelnr`),
  UNIQUE INDEX `bestelnr_UNIQUE` (`bestelnr` ASC),
  INDEX `fk_Bestelling_Klant1_idx` (`klantnr` ASC),
  UNIQUE INDEX `transactieref_UNIQUE` (`transactieref` ASC),
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
  `productnr` VARCHAR(45) NOT NULL,
  `productnaam` VARCHAR(45) NOT NULL,
  `EAN` VARCHAR(45) NOT NULL,
  `merk` VARCHAR(25) NOT NULL,
  `categorie` VARCHAR(25) NOT NULL,
  `omschrijving` VARCHAR(400) NOT NULL,
  `afbeelding` LONGBLOB NULL,
  `prijs` DOUBLE NOT NULL,
  `voorraad` INT NOT NULL,
  `kleur` VARCHAR(45) NOT NULL,
  `hoogte` INT NOT NULL,
  `breedte` INT NOT NULL,
  `lengte` INT NOT NULL,
  `fabrikant` VARCHAR(45) NOT NULL,
  `verpakking` VARCHAR(45) NULL,
  `certificaten` VARCHAR(100) NULL,
  `inhoud` VARCHAR(45) NOT NULL,
  `materiaal` VARCHAR(45) NOT NULL,
  UNIQUE INDEX `productnr_UNIQUE` (`productnr` ASC),
  UNIQUE INDEX `productnaam_UNIQUE` (`productnaam` ASC),
  UNIQUE INDEX `EAN_UNIQUE` (`EAN` ASC),
  PRIMARY KEY (`productnr`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`Bestelregel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`Bestelregel` (
  `bestelnr` INT NOT NULL,
  `productnr` INT NOT NULL,
  `aantal` INT NOT NULL,
  PRIMARY KEY (`bestelnr`, `productnr`),
  INDEX `fk_Bestelregel_Product1_idx` (`productnr` ASC),
  CONSTRAINT `fk_Bestelregel_Bestelling1`
    FOREIGN KEY (`bestelnr`)
    REFERENCES `vvtissue`.`Bestelling` (`bestelnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bestelregel_Product1`
    FOREIGN KEY (`productnr`)
    REFERENCES `vvtissue`.`Product` (`productnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `vvtissue`.`token`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`token` (
  `klantnr` INT NOT NULL,
  `token` VARCHAR(150) NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE INDEX `klantnr_UNIQUE` (`klantnr` ASC),
  CONSTRAINT `fk_token_Gebruiker1`
    FOREIGN KEY (`klantnr`)
    REFERENCES `vvtissue`.`Gebruiker` (`klantnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vvtissue`.`recovery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`recovery` (
  `klantnr` INT NOT NULL,
  `datum` DATETIME NOT NULL,
  `token` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`klantnr`),
  UNIQUE INDEX `klantnr_UNIQUE` (`klantnr` ASC),
  CONSTRAINT `fk_recovery_Gebruiker1`
    FOREIGN KEY (`klantnr`)
    REFERENCES `vvtissue`.`Gebruiker` (`klantnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vvtissue`.`anderadres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vvtissue`.`anderadres` (
  `bestelnr` INT(11) NOT NULL,
  `plaats` VARCHAR(30) NULL,
  `adres` VARCHAR(30) NULL,
  `postcode` VARCHAR(7) NULL,
  PRIMARY KEY (`bestelnr`),
  CONSTRAINT `fk_anderadres_Bestelling1`
    FOREIGN KEY (`bestelnr`)
    REFERENCES `vvtissue`.`Bestelling` (`bestelnr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
