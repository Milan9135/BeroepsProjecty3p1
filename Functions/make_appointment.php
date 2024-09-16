<?php
session_start();
include 'appointmentsFunction.php';

$myDb = new DB("Tandartsdb");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slotID = $_POST['slot'];
    $description = $_POST['reason'];
    $userId = $_SESSION['user_id'];

    try {
        // Maak een instantie van de Appointment-klasse
        $appointment = new Appointment($myDb);
        
        // Voeg de afspraak toe aan de database
        $appointment->insertAppointment($slotID, $description, $userId);
        
        // Markeer het tijdslot als niet beschikbaar
        $appointment->updateTimeSlotAvailability($slotID, false);

        // Stel een succesbericht in
        $message = "Afspraak succesvol ingepland!";
    } catch (Exception $e) {
        // Stel een foutbericht in bij uitzondering
        $message = "Fout bij het inplannen van de afspraak: " . $e->getMessage();
    }
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
        <a href="appointments.php">Afspraken</a>
        <a href="profiel.php">Mijn account</a>
        <a href="logout.php">Logout</a>
    </div>

    <main>
        <div class="register-container">
            <h2>Afspraak Bevestiging</h2>
            <p><?php echo isset($message) ? $message : ''; ?></p>
            <a href="appointments.php">Terug naar afspraken</a>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
