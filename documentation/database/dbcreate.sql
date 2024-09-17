-- MySQL Script generated by MySQL Workbench
-- Mon Sep  9 11:21:28 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

-- -----------------------------------------------------
-- Schema Tandartsdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Tandartsdb
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `Tandartsdb` DEFAULT CHARACTER SET utf8 ;
USE `Tandartsdb` ;

-- -----------------------------------------------------
-- Table `Tandartsdb`.`Users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Users` (
  `userID` INT NOT NULL AUTO_INCREMENT,
  `Email` VARCHAR(225) NULL,
  `Wachtwoord` VARCHAR(225) NULL,
  `Usertype` ENUM("Patiënt", "Tandarts", "Admin") NULL DEFAULT 'Patiënt',
  PRIMARY KEY (`userID`));

-- -----------------------------------------------------
-- Table `Tandartsdb`.`Patiënt`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Patiënt` (
  `PatiëntID` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(225) NULL,
  `Geboortedatum` VARCHAR(225) NULL,
  `Telefoonnummer` VARCHAR(225) NULL,
  `Adres` VARCHAR(225) NULL,
  `userID` INT, -- Foreign key naar de Users-tabel
  PRIMARY KEY (`PatiëntID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`) ON DELETE CASCADE
);


-- -----------------------------------------------------
-- Table `Tandartsdb`.`Tandarts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Tandarts` (
  `tandartsID` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(225) NULL,
  `Geboortedarum` VARCHAR(225) NULL,
  `Telefoonnummer` VARCHAR(12) NULL,
  `Adres` VARCHAR(225) NULL,
  `Beoordeling` FLOAT NULL,
  `Specialisaties` VARCHAR(225) NULL,
  `Beschrijving` VARCHAR(225) NULL,
  `userID` INT, -- Foreign key naar de Users-tabel
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`) ON DELETE CASCADE,
  PRIMARY KEY (`tandartsID`));
  

-- -----------------------------------------------------
-- Table `Tandartsdb`.`Afspraken`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Afspraken` (
  `afspraakID` INT NOT NULL AUTO_INCREMENT,
  `DatumTijd` DATETIME(6) NULL,
  `Beschrijving` VARCHAR(225) NULL,
  `userID` INT NOT NULL,
  PRIMARY KEY (`afspraakID`),
  CONSTRAINT `fk_Afspraken_Users`
    FOREIGN KEY (`userID`)
    REFERENCES `Tandartsdb`.`Users` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Table `Tandartsdb`.`Tijdsloten`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Tijdsloten` (
  `slotID` INT NOT NULL AUTO_INCREMENT,
  `Tijd` TIME NOT NULL,
  `userID` INT NOT NULL, -- Foreign key naar de Tandarts-tabel
  FOREIGN KEY (`userID`) REFERENCES Tandarts(`userID`) ON DELETE CASCADE,
  PRIMARY KEY (`slotID`)
);

-- -----------------------------------------------------
-- Table `Tandartsdb`.`Behandelingen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Behandelingen` (
  `BehandelingenID` INT NOT NULL AUTO_INCREMENT,
  `userID` INT NOT NULL,
  `Beschrijving` VARCHAR(225) NULL,
  PRIMARY KEY (`BehandelingenID`),
  CONSTRAINT `fk_Behandelingen_Users1`
    FOREIGN KEY (`userID`)
    REFERENCES `Tandartsdb`.`Users` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `Tandartsdb`.`Verzekeringen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tandartsdb`.`Verzekeringen` (
  `VerzekeringenID` INT NOT NULL AUTO_INCREMENT,
  `userID` INT NOT NULL,
  `Beschrijving` VARCHAR(225) NULL,
  PRIMARY KEY (`VerzekeringenID`),
  CONSTRAINT `fk_Verzekeringen_Users1`
    FOREIGN KEY (`userID`)
    REFERENCES `Tandartsdb`.`Users` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);