<?php
include "../db.php";
session_start();
include 'appointmentsFunction.php';
$myDb = new DB("Tandartsdb");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Controleer of de ingelogde gebruiker een patiënt is
$userId = $_SESSION['user_id'];
$query = $myDb->execute("SELECT * FROM users WHERE userID = ?", [$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Verkrijg de gegevens van het formulier
$datetime = $_POST['datetime'];
$tandartsID = $_POST['tandarts'];
$userID = $_SESSION['user_id'];

// Maak een afspraak aan
$appointment = new Appointment($myDb);
try {
    $appointment->insertAppointment($datetime, 'Afspraak met tandarts', $userID);
    echo "Afspraak succesvol gemaakt.";
} catch (Exception $e) {
    echo "Er is een fout opgetreden: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Bevestiging</title>
    <link rel="stylesheet" href="./styles/Appointments.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="select_date.php">Selecteer Datum</a>
        <a href="profiel.php">Mijn account</a>
        <a href="logout.php">Logout</a>
    </div>

    <main>
        <div class="register-container">
            <h2>Afspraak Bevestiging</h2>
            <p><?php echo isset($message) ? $message : ''; ?></p>
            <a href="select_date.php">Terug naar afspraak maken</a>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
