-- Voeg gebruikers toe aan de Users-tabel
INSERT INTO `Tandartsdb`.`Users` (`Email`, `Wachtwoord`, `Usertype`) VALUES
('lebron.james@example.com', 'hashed_password1', 'Tandarts'),
('john.smith@example.com', 'hashed_password2', 'Tandarts'),
('jane.doe@example.com', 'hashed_password3', 'Tandarts'),
('alice.johnson@example.com', 'hashed_password4', 'Tandarts');

-- Voeg tandartsen toe aan de Tandarts-tabel
INSERT INTO `Tandartsdb`.`Tandarts` (`Naam`, `Geboortedatum`, `Telefoonnummer`, `Adres`, `Beoordeling`, `Specialisaties`, `Beschrijving`, `userID`) VALUES
('LeBron James', '1984-12-30', '123-456-7890', '123 Main St, Los Angeles, CA', 4.8, 'Algemene Tandheelkunde', 'Ervaren tandarts met een passie voor sport.', 1),
('John Smith', '1975-05-15', '098-765-4321', '456 Elm St, New York, NY', 4.5, 'Orthodontie', 'Gespecialiseerd in orthodontie en tandcorrectie.', 2),
('Jane Doe', '1980-07-22', '555-123-4567', '789 Pine St, San Francisco, CA', 4.7, 'Cosmetische Tandheelkunde', 'Expert in cosmetische tandheelkunde en esthetische behandelingen.', 3),
('Alice Johnson', '1990-03-10', '555-987-6543', '321 Oak St, Seattle, WA', 4.6, 'Preventieve Tandheelkunde', 'Gespecialiseerd in preventieve zorg en tandverzorging.', 4);

-- Voeg tijdsloten toe voor de tandartsen
INSERT INTO `Tandartsdb`.`Tijdsloten` (`Tijd`, `tandartsID`) VALUES
('08:00:00', 1), ('09:00:00', 1), ('10:00:00', 1), ('11:00:00', 1), ('12:00:00', 1), -- Voor LeBron James
('11:00:00', 2), ('12:00:00', 2), ('13:00:00', 2), ('14:00:00', 2), ('15:00:00', 2), -- Voor John Smith
('14:00:00', 3), ('15:00:00', 3), ('16:00:00', 3), ('17:00:00', 3), ('18:00:00', 3), -- Voor Jane Doe
('17:00:00', 4), ('18:00:00', 4), ('19:00:00', 4), ('20:00:00', 4), ('21:00:00', 4); -- Voor Alice Johnson

-- Voeg behandelingen toe voor elke tandarts
INSERT INTO `Tandartsdb`.`Behandelingen` (`userID`, `Beschrijving`) VALUES
(1, 'Controle'),  
(1, 'Vulling'),  
(2, 'Orthodontie'),  
(3, 'Cosmetische Tandheelkunde'),  
(3, 'Wortelkanaalbehandeling'),  
(4, 'Preventieve Tandheelkunde');
