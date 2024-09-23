-- Create the database
CREATE DATABASE IF NOT EXISTS `Tandartsdb` DEFAULT CHARACTER SET utf8;
USE `Tandartsdb`;

-- Create Users table
CREATE TABLE IF NOT EXISTS `Users` (
  `userID` INT NOT NULL AUTO_INCREMENT,
  `Email` VARCHAR(225) NULL,
  `Wachtwoord` VARCHAR(225) NULL,
  `Usertype` ENUM("Patiënt", "Tandarts", "Admin") NULL DEFAULT 'Patiënt',
  PRIMARY KEY (`userID`)
);

-- Create Patiënt table
CREATE TABLE IF NOT EXISTS `Patiënt` (
  `PatiëntID` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(225) NULL,
  `Geboortedatum` VARCHAR(225) NULL,
  `Telefoonnummer` VARCHAR(225) NULL,
  `Adres` VARCHAR(225) NULL,
  `userID` INT,
  PRIMARY KEY (`PatiëntID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`) ON DELETE CASCADE
);

-- Create Tandarts table
CREATE TABLE IF NOT EXISTS `Tandarts` (
  `tandartsID` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(225) NULL,
  `Geboortedatum` VARCHAR(225) NULL,
  `Telefoonnummer` VARCHAR(12) NULL,
  `Adres` VARCHAR(225) NULL,
  `Beoordeling` FLOAT NULL,
  `Specialisaties` VARCHAR(225) NULL,
  `Beschrijving` VARCHAR(225) NULL,
  `userID` INT,
  PRIMARY KEY (`tandartsID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`) ON DELETE CASCADE
);

-- Create Behandelingen table
CREATE TABLE IF NOT EXISTS `Behandelingen` (
  `BehandelingenID` INT NOT NULL AUTO_INCREMENT,
  `userID` INT NOT NULL,
  `Beschrijving` VARCHAR(225) NULL,
  PRIMARY KEY (`BehandelingenID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`)
);

-- Create Afspraken table
CREATE TABLE IF NOT EXISTS `Afspraken` (
  `afspraakID` INT NOT NULL AUTO_INCREMENT,
  `Datum` DATE NOT NULL,
  `Tijd` TIME NOT NULL,
  `Beschrijving` VARCHAR(225) NULL,
  `geannuleerd` TINYINT(1) NOT NULL DEFAULT 0,
  `userID` INT NOT NULL,
  `tandartsID` INT NOT NULL,
  `BehandelingenID` INT NOT NULL,
  PRIMARY KEY (`afspraakID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`),
  FOREIGN KEY (`tandartsID`) REFERENCES Tandarts(`tandartsID`),
  FOREIGN KEY (`BehandelingenID`) REFERENCES Behandelingen(`BehandelingenID`)
);

-- Create Tijdsloten table
CREATE TABLE IF NOT EXISTS `Tijdsloten` (
  `slotID` INT NOT NULL AUTO_INCREMENT,
  `Tijd` TIME NOT NULL,
  `userID` INT NOT NULL,
  PRIMARY KEY (`slotID`),
  FOREIGN KEY (`userID`) REFERENCES Tandarts(`userID`) ON DELETE CASCADE
);

-- Create Verzekeringen table
CREATE TABLE IF NOT EXISTS `Verzekeringen` (
  `VerzekeringenID` INT NOT NULL AUTO_INCREMENT,
  `userID` INT NOT NULL,
  `Beschrijving` VARCHAR(225) NULL,
  PRIMARY KEY (`VerzekeringenID`),
  FOREIGN KEY (`userID`) REFERENCES Users(`userID`)
);
