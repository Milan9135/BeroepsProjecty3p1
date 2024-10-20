<?php
include 'db.php';
session_start();

$myDb = new DB();

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
$treatmentID = $_POST['treatmentID']; // Gebruik treatmentID hier

// Verkrijg de beschrijving van de behandeling op basis van de behandelingID
$treatmentDescriptionQuery = $myDb->execute("SELECT Beschrijving FROM Behandelingen WHERE BehandelingenID = ?", [$treatmentID]);
$treatmentDescription = $treatmentDescriptionQuery->fetchColumn(); // Haalt de beschrijving op

// Controleer of de beschrijving bestaat
if (!$treatmentDescription) {
    die("Fout: De beschrijving voor de behandeling met ID '$treatmentID' bestaat niet.");
}

// Verkrijg de behandeling beschrijving (optioneel, als je het wilt gebruiken)
$treatmentQuery = $myDb->execute("SELECT Beschrijving FROM Behandelingen WHERE BehandelingenID = ?", [$treatmentID]);
$treatment = $treatmentQuery->fetch(PDO::FETCH_ASSOC);

// Verkrijg de tandarts naam
$tandartsQuery = $myDb->execute("SELECT Naam FROM Tandarts WHERE tandartsID = ?", [$tandartsID]);
$tandarts = $tandartsQuery->fetch(PDO::FETCH_ASSOC);
$tandartsNaam = $tandarts['Naam'];

// Voeg de afspraak toe aan de database
$myDb->execute(
    "INSERT INTO Afspraken (Datum, Tijd, Beschrijving, BehandelingenID, userID, tandartsID) VALUES (?, ?, ?, ?, ?, ?)",
    [$date, $time, $treatmentDescription, $treatmentID, $userId, $tandartsID]
);
// Voeg een notificatie toe voor de gebruiker
$message = "Uw afspraak is succesvol gemaakt op $date om $time met tandarts $tandartsNaam.";
$myDb->execute("INSERT INTO Notificaties (userID, bericht) VALUES (?, ?)", [$userId, $message]);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Bevestiging</title>
    <link rel="stylesheet" href="./styles/Appointments.css">
    <script src="objects/navbar.js"></script>
</head>

<body>
    <div id="navbar">
        <nav class="navbar">
            <a id="placeholder" href="">a</a>
            <style>
                #placeholder {
                    opacity: 0;
                }
            </style>
        </nav>
    </div>

    <main>
        <div class="confirmation-container">
            <h2>Afspraak Bevestiging</h2>
            <p>Afspraak gemaakt op <strong><?php echo htmlspecialchars($date); ?></strong> om <strong><?php echo htmlspecialchars($time); ?></strong> met tandarts <strong><?php echo htmlspecialchars($tandartsNaam); ?></strong>.</p>
            <a href="afspraken.php" class="button">Afspraak inzien</a>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>

</html>