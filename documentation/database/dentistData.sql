-- Verbind met de juiste database
USE Tandartsdb;

-- Voeg een nieuwe tandarts toe aan de Tandarts-tabel
INSERT INTO Tandarts (Naam, Geboortedatum, Telefoonnummer, Adres, Beoordeling, Specialisaties, Beschrijving)
VALUES ('Lebron James', '1980-05-15', '0612345678', '123 Main Street, Amsterdam', 4.8, 'Orthodontist, lEdental', 'Experienced dentist specializing in orthodontics and cosmetic dentistry.');

-- Voeg tijdsloten toe aan de Tijdsloten-tabel
INSERT INTO Tijdsloten (DatumTijd, Beschikbaar) VALUES
('2024-09-20 09:00:00', TRUE),
('2024-09-20 09:30:00', TRUE),
('2024-09-20 10:00:00', TRUE),
-- Voeg meer tijdsloten toe zoals nodig
