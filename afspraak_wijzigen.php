<?php
include 'db.php';
session_start();

$myDb = new DB("Tandartsdb");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Haal het userID van de ingelogde gebruiker op
$userId = $_SESSION['user_id'];

$userQuery = $myDb->execute("SELECT * FROM Users WHERE userID = ?", [$userId]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

$appointmentID = $_POST['afspraakID'];

if ($user['Usertype'] == 'Tandarts') {
    $patientIDQuery = $myDb->execute("SELECT userID FROM Afspraken WHERE afspraakID = ?", [$appointmentID]);
    $patientID = $patientIDQuery->fetchColumn(); // Haalt de userID op
}

$datum = $myDb->execute("SELECT Datum FROM Afspraken WHERE afspraakID = ?", [$appointmentID])->fetchColumn();

// Fetch the BehandelingenID (it returns an associative array, so we access 'BehandelingenID')
$behandelingRow = $myDb->execute("SELECT BehandelingenID FROM Afspraken WHERE afspraakID = ?", [$appointmentID])->fetch(PDO::FETCH_ASSOC);
// Get the actual BehandelingenID from the array
$behandelingID = $behandelingRow['BehandelingenID'];
// Now use this BehandelingenID to fetch the Beschrijving (description)
$behandelingBeschrijving = $myDb->execute("SELECT Beschrijving FROM Behandelingen WHERE BehandelingenID = ?", [$behandelingID])->fetchColumn();


// Verkrijg beschikbare behandelingen zonder duplicaten
$behandelingen = $myDb->execute("SELECT DISTINCT Beschrijving FROM Behandelingen")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kies Datum en Behandeling</title>
    <link rel="stylesheet" href="./styles/Appointments.css">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="afspraken.php">Afspraken</a>
        <a href="profiel.php">Mijn account</a>
        <a href="logout.php">Logout</a>
    </div>

    <main>
        <div class="register-container">
            <h2>Kies Datum en Behandeling</h2>


            <?php if ($user['Usertype'] == 'Patiënt'): ?>
                <form action="./functions/select_dentist_and_time.php" method="post">
                <?php elseif ($user['Usertype'] == 'Tandarts'): ?>
                    <form action="./dentist_editAppointment.php" method="post">
                    <input type="hidden" name="patientID" value="<?php echo htmlspecialchars($patientID); ?>">

                    <?php endif; ?>



                    <input type="hidden" name="appointmentID" value="<?php echo htmlspecialchars($appointmentID); ?>">

                    <div class="input-group">
                        <label for="date">Datum:</label>
                        <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($datum) ? $datum : ''; ?>">
                    </div>

                    <div class="input-group">
                        <label for="treatment">Behandeling:</label>
                        <select id="treatment" name="treatmentID" required>
                            <option value="" disabled selected><?php echo isset($behandelingBeschrijving) ? htmlspecialchars($behandelingBeschrijving) : 'Selecteer een behandeling'; ?></option>

                            <?php foreach ($behandelingen as $behandeling): ?>
                                <option value="<?php echo htmlspecialchars(trim($behandeling['Beschrijving'])); ?>">
                                    <?php echo htmlspecialchars(trim($behandeling['Beschrijving'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit">Volgende</button>
                    </form>
        </div>
    </main>

    <script>
        // Stel de minimale waarde van het datumveld in op vandaag
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);
    </script>
</body>

</html>