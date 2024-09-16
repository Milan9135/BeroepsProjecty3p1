<?php
include 'db.php';
$myDb = new DB("Tandartsdb");

class Appointment
{
    private $dbh;

    public function __construct(DB $dbh)
    {
        $this->dbh = $dbh;
    }

    // Voeg een afspraak toe aan de Afspraken-tabel
    public function insertAppointment($dateTime, $description, $userId)
    {
        try {
            $this->dbh->execute("INSERT INTO Afspraken (DatumTijd, Beschrijving, userID) VALUES (?, ?, ?)", [$dateTime, $description, $userId]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Fout bij het toevoegen van de afspraak: " . $e->getMessage());
        }
    }

    // Haal alle afspraken op voor een bepaalde gebruiker
    public function getAppointmentsByUserId($userId)
    {
        try {
            $query = $this->dbh->execute("SELECT * FROM Afspraken WHERE userID = ?", [$userId]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Fout bij het ophalen van afspraken: " . $e->getMessage());
        }
    }
}
?>
