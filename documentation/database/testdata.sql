-- Insert test data into `Users`
INSERT INTO `Tandartsdb`.`Users` (Email, Wachtwoord, Usertype)
VALUES
('patient1@example.com', 'password123', 'Patiënt'),
('dentist1@example.com', 'password456', 'Tandarts'),
('admin1@example.com', 'adminpass', 'Admin'),
('patient2@example.com', 'password789', 'Patiënt'),
('dentist2@example.com', 'password111', 'Tandarts');

-- Insert test data into `Patiënt`
INSERT INTO `Tandartsdb`.`Patiënt` (Naam, Geboortedatum, Telefoonnummer, Adres)
VALUES
('John Doe', '1985-06-15', '0612345678', '123 Main St'),
('Jane Smith', '1990-04-22', '0698765432', '456 Elm St');

-- Insert test data into `Tandarts`
INSERT INTO `Tandartsdb`.`Tandarts` (Naam, Geboortedarum, Telefoonnummer, Adres, Beoordeling, Specialisaties, Beschrijving)
VALUES
('Dr. Emily Adams', '1975-09-20', '0612349876', '789 Oak Ave', 4.8, 'Orthodontics, General Dentistry', 'Experienced in complex cases'),
('Dr. Michael White', '1980-03-12', '0687654321', '321 Pine Rd', 4.5, 'Cosmetic Dentistry, General Dentistry', 'Focused on patient care and comfort');

-- Insert test data into `Afspraken`
INSERT INTO `Tandartsdb`.`Afspraken` (DatumTijd, Beschrijving, userID)
VALUES
('2024-09-12 10:00:00', 'Regular check-up', 1),
('2024-09-15 14:30:00', 'Teeth whitening', 2),
('2024-09-18 09:00:00', 'Filling cavity', 1),
('2024-09-20 16:00:00', 'Root canal treatment', 4);

-- Insert test data into `Behandelingen`
INSERT INTO `Tandartsdb`.`Behandelingen` (userID, Beschrijving)
VALUES
(1, 'Teeth cleaning and plaque removal'),
(2, 'Teeth whitening'),
(1, 'Cavity filling'),
(4, 'Root canal treatment');

-- Insert test data into `Verzekeringen`
INSERT INTO `Tandartsdb`.`Verzekeringen` (userID, Beschrijving)
VALUES
(1, 'Basic dental insurance plan'),
(2, 'Premium dental insurance plan'),
(4, 'Comprehensive dental insurance'),
(1, 'Basic dental insurance plan');
