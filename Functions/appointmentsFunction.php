<?php

$myDb = new DB("Tandartsdb");

class Appointment
{
    private $dbh;

    public function __construct(DB $dbh)
    {
        $this->dbh = $dbh;
    }

    // Voeg een afspraak toe aan de Afspraken-tabel
    public function insertAppointment($slotID, $description, $userId)
    {
        try {
            // Verkrijg de datum en tijd van het tijdslot
            $query = $this->dbh->execute("SELECT DatumTijd FROM Tijdsloten WHERE slotID = ?", [$slotID]);
            $slot = $query->fetch(PDO::FETCH_ASSOC);
            $dateTime = $slot['DatumTijd'];

            // Voeg de afspraak toe
            $this->dbh->execute("INSERT INTO Afspraken (DatumTijd, Beschrijving, userID) VALUES (?, ?, ?)", [$dateTime, $description, $userId]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Fout bij het toevoegen van de afspraak: " . $e->getMessage());
        }
    }

    // Haal alle beschikbare tijdsloten op
    public function getAvailableTimeSlots()
    {
        try {
            $query = $this->dbh->execute("SELECT * FROM Tijdsloten WHERE Beschikbaar = TRUE");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Fout bij het ophalen van tijdsloten: " . $e->getMessage());
        }
    }

    // Werk de beschikbaarheid van een tijdslot bij
    public function updateTimeSlotAvailability($slotID, $availability)
    {
        try {
            $this->dbh->execute("UPDATE Tijdsloten SET Beschikbaar = ? WHERE slotID = ?", [$availability, $slotID]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Fout bij het bijwerken van de tijdslot beschikbaarheid: " . $e->getMessage());
        }
    }
}

?>
