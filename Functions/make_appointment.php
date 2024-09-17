<?php
include "../db.php";
session_start();

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

// Verkrijg de gegevens van het formulier
$date = $_POST['date'];
$time = $_POST['time'];
$tandartsID = $_POST['dentist'];
$treatmentID = $_POST['treatment'];

// Verkrijg de behandeling beschrijving
$treatmentQuery = $myDb->execute("SELECT Beschrijving FROM Behandelingen WHERE BehandelingenID = ?", [$treatmentID]);
$treatment = $treatmentQuery->fetch(PDO::FETCH_ASSOC);

// Verkrijg de tandarts naam
$tandartsQuery = $myDb->execute("SELECT Naam FROM Tandarts WHERE tandartsID = ?", [$tandartsID]);
$tandarts = $tandartsQuery->fetch(PDO::FETCH_ASSOC);
$tandartsNaam = $tandarts['Naam'];

// Voeg de afspraak toe aan de database
$myDb->execute("INSERT INTO Afspraken (Datum, Tijd, Beschrijving, userID, tandartsID) VALUES (?, ?, ?, ?, ?)",
    [$date, $time, $treatment, $userId, $tandartsID]);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Bevestiging</title>
    <link rel="stylesheet" href="../styles/Appointments.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="appointments.php">Afspraken</a>
        <a href="profiel.php">Mijn account</a>
        <a href="logout.php">Logout</a>
    </div>

    <main>
        <div class="confirmation-container">
            <h2>Afspraak Bevestiging</h2>
            <p>Afspraak gemaakt op <strong><?php echo htmlspecialchars($date); ?></strong> om <strong><?php echo htmlspecialchars($time); ?></strong> met tandarts <strong><?php echo htmlspecialchars($tandartsNaam); ?></strong>.</p>
            <a href="index.php" class="button">Terug naar Home</a>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
