<?php
include '../db.php';
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
    echo "Toegang geweigerd. Alleen patiënten kunnen een afspraak wijzigen.";
    exit();
}

if (!isset($_POST['date']) || !isset($_POST['dentist']) || !isset($_POST['time']) || !isset($_POST['treatmentID']) || !isset($_POST['appointmentID'])) {
    die("Fout: Niet alle velden zijn ingevuld.");
}

$selectedDate = $_POST['date'];
$tandartsID = $_POST['dentist'];
$selectedTime = $_POST['time'];
$selectedTreatmentID = $_POST['treatmentID'];
$appointmentID = $_POST['appointmentID'];

// Update de afspraak met de nieuwe gegevens
$updateQuery = "
    UPDATE Afspraken
    SET Datum = ?, Tijd = ?, tandartsID = ?, BehandelingenID = ?
    WHERE afspraakID = ? AND userID = ?
";
$myDb->execute($updateQuery, [
    $selectedDate,
    $selectedTime,
    $tandartsID,
    $selectedTreatmentID,
    $appointmentID,
    $userId
]);

echo "Afspraak succesvol gewijzigd.";
header('Location: ../afspraak_annuleren.php');
exit();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijzig Afspraak</title>
    <link rel="stylesheet" href="../styles/Appointments.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="afspraak_annuleren.php">Afspraken</a>
        <a href="profiel.php">Mijn account</a>
        <a href="logout.php">Logout</a>
    </div>

    <main>
        <div class="register-container">
            <h2>Kies Tandarts en Tijdslot</h2>

            <form action="./Functions/edit_appointment.php" method="post">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                <input type="hidden" name="dentist" value="<?php echo htmlspecialchars($tandartsID); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($selectedTime); ?>">
                <input type="hidden" name="treatmentID" value="<?php echo htmlspecialchars($selectedTreatmentID); ?>">
                <input type="hidden" name="appointmentID" value="<?php echo htmlspecialchars($appointmentID); ?>">

                <button type="submit">Wijzig Afspraak</button>
            </form>
        </div>
    </main>
</body>
</html>
