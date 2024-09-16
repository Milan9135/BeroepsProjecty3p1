<?php
include 'db.php';
$myDb = new DB("Tandartsdb");
class user
{

    private $dbh;

    public function __construct(DB $dbh)
    {
        $this->dbh = $dbh;
    }
    public function insertUser($email, $wachtwoord, $usertype)
    {

        return $this->dbh->execute("INSERT INTO users VALUES (null, ?, ?, ?)", [$email, $wachtwoord, $usertype]);
        
    }

    public function insertUserAndPatient($email, $wachtwoord, $usertype, $naam, $geboortedatum, $telefoonnummer, $adres)
    {
        try {
            // Voeg de gebruiker toe aan de Users-tabel
            $this->dbh->execute("INSERT INTO users (Email, Wachtwoord, Usertype) VALUES (?, ?, ?)", [$email, $wachtwoord, $usertype]);

            // Haal het laatste ingevoegde userID op
            $userID = $this->dbh->lastInsertId();

            // Controleer of het userID correct is opgehaald
            if ($userID) {
                // Voeg de patiëntgegevens toe aan de Patiënt-tabel, gekoppeld aan het userID
                $this->dbh->execute("INSERT INTO Patiënt (Naam, Geboortedatum, Telefoonnummer, Adres, userID) VALUES (?, ?, ?, ?, ?)", 
                [$naam, $geboortedatum, $telefoonnummer, $adres, $userID]);
                return true;
            } else {
                throw new Exception("Het toevoegen van de gebruiker is mislukt.");
            }
        } catch (Exception $e) {
            throw new Exception("Fout bij het registreren van de gebruiker en patiënt: " . $e->getMessage());
        }
    }
}
