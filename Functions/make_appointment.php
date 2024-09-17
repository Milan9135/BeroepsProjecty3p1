<?php
include "../db.php";
session_start();
include './Functions/appointmentsFunction.php';

$myDb = new DB("Tandartsdb");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Controleer of de ingelogde gebruiker een patiënt is
$userId = $_SESSION['user_id'];
$query = $myDb->execute("SELECT * FROM Users WHERE userID = ?", [$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user['Usertype'] !== 'Patiënt') {
    echo "Toegang geweigerd. Alleen patiënten kunnen een afspraak maken.";
    exit();
}

// Verkrijg geselecteerde datum, tijd en tandartsID uit de POST-data
$date = $_POST['date'];
$time = $_POST['time'];
$tandartsID = $_POST['dentist'];

// Maak de afspraak aan
$myDb->execute("
    INSERT INTO Afspraken (Datum, Tijd, userID, tandartsID)
    VALUES (?, ?, ?, ?)", [$date, $time, $userId, $tandartsID]);

echo "Afspraak gemaakt op {$date} om {$time} met tandartsID {$tandartsID}.";
?>