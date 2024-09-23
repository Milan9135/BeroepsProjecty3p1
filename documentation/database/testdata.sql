-- Insert test data into `Users`
INSERT INTO `Tandartsdb`.`Users` (Email, Wachtwoord, Usertype)
VALUES
('patient1@example.com', '$2y$10$ll0TV0x4IL9gxtndMlZf7OHHspiJcMJhDNae3PD5rwb1FBqtU0s0a', 'Patiënt'), -- Password: password123 (userID = 1)
('dentist1@example.com', '$2y$10$NnTMEUtIe0yJ4so7/d5LwuXMP5Rbi7XxUITaVNOFB78ghAgV5nhZS', 'Tandarts'), -- Password: password456 (userID = 2)
('admin1@example.com', '$2y$10$HgSFXuQsI6xqOrTu5Dgv5.rT7T2kOE1uI/lI4za4./W7Kj6VCmoFq', 'Admin'), -- Password: adminpass (userID = 3)
('patient2@example.com', '$2y$10$EwQ7KlU/LtEJcwfM0UPGM.ewoaIu2GCW3JCltSTez11Mm8ao/rr92', 'Patiënt'), -- Password: password789 (userID = 4)
('dentist2@example.com', '$2y$10$686wHQCeOQoCYgRpgkS4I.xYmn6rgbRo1LBxhaaPxmHBTDvvXF.0.', 'Tandarts'); -- Password: password111 (userID = 5)

-- Insert test data into `Patiënt`
INSERT INTO `Tandartsdb`.`Patiënt` (Naam, Geboortedatum, Telefoonnummer, Adres, userID)
VALUES
('John Doe', '1985-06-15', '0612345678', '123 Main St', 1), -- userID = 1 (patient1@example.com)
('Jane Smith', '1990-04-22', '0698765432', '456 Elm St', 4); -- userID = 4 (patient2@example.com)

-- Insert test data into `Tandarts`
INSERT INTO `Tandartsdb`.`Tandarts` (Naam, Geboortedatum, Telefoonnummer, Adres, Beoordeling, Specialisaties, Beschrijving, userID)
VALUES
('Dr. Emily Adams', '1975-09-20', '0612349876', '789 Oak Ave', 4.8, 'Orthodontics, General Dentistry', 'Experienced in complex cases', 2), -- userID = 2 (dentist1@example.com)
('Dr. Michael White', '1980-03-12', '0687654321', '321 Pine Rd', 4.5, 'Cosmetic Dentistry, General Dentistry', 'Focused on patient care and comfort', 5); -- userID = 5 (dentist2@example.com)

-- Insert test data into `Afspraken`
INSERT INTO `Tandartsdb`.`Afspraken` (DatumTijd, Beschrijving, userID)
VALUES
('2024-09-12 10:00:00', 'Regular check-up', 1), -- John Doe (patient1@example.com)
('2024-09-15 14:30:00', 'Teeth whitening', 2), -- Dr. Emily Adams (dentist1@example.com)
('2024-09-18 09:00:00', 'Filling cavity', 1), -- John Doe (patient1@example.com)
('2024-09-20 16:00:00', 'Root canal treatment', 4); -- Jane Smith (patient2@example.com)

-- Insert test data into `Behandelingen`
INSERT INTO `Tandartsdb`.`Behandelingen` (userID, Beschrijving)
VALUES
(1, 'Teeth cleaning and plaque removal'), -- John Doe (patient1@example.com)
(2, 'Teeth whitening'), -- Dr. Emily Adams (dentist1@example.com)
(1, 'Cavity filling'), -- John Doe (patient1@example.com)
(4, 'Root canal treatment'); -- Jane Smith (patient2@example.com)

-- Insert test data into `Verzekeringen`
INSERT INTO `Tandartsdb`.`Verzekeringen` (userID, Beschrijving)
VALUES
(1, 'Basic dental insurance plan'), -- John Doe (patient1@example.com)
(2, 'Premium dental insurance plan'), -- Dr. Emily Adams (dentist1@example.com)
(4, 'Comprehensive dental insurance'), -- Jane Smith (patient2@example.com)
(1, 'Basic dental insurance plan'); -- John Doe (patient1@example.com)
