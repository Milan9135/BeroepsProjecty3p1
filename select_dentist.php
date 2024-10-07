<?php
include 'db.php';
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

if (!isset($_POST['date']) || !isset($_POST['treatment'])) {
    header('Location: appointments.php');
    exit();
}

$selectedDate = $_POST['date'];
$selectedTreatmentDescription = $_POST['treatment'];

// Verkrijg de behandeling-ID op basis van de beschrijving
$treatmentQuery = $myDb->execute("SELECT BehandelingenID FROM Behandelingen WHERE Beschrijving = ?", [$selectedTreatmentDescription]);
$treatment = $treatmentQuery->fetch(PDO::FETCH_ASSOC);
$selectedTreatmentID = $treatment ? $treatment['BehandelingenID'] : null;

// Controleer of de behandeling bestaat
if (!$selectedTreatmentID) {
    die("Fout: De behandeling met de beschrijving '$selectedTreatmentDescription' bestaat niet.");
}

// Verkrijg tandartsen die de geselecteerde behandeling aanbieden
$tandartsen = $myDb->execute("
    SELECT DISTINCT t.tandartsID, t.Naam
    FROM Tandarts t
    JOIN Behandelingen b ON t.userID = b.userID
    WHERE b.BehandelingenID = ?", [$selectedTreatmentID])->fetchAll(PDO::FETCH_ASSOC);

// Verkrijg tijdsloten voor de geselecteerde tandarts
$tandartsID = isset($_POST['dentist']) ? $_POST['dentist'] : null;
$tijdsloten = [];
if ($tandartsID) {
    $tijdsloten = $myDb->execute("SELECT Tijd FROM Tijdsloten WHERE tandartsID = ?", [$tandartsID])->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecteer Tandarts en Tijdslot - Tandartspraktijk</title>
    <link rel="stylesheet" href="./styles/Appointments.css">
    <script src="objects/navbar.js"></script>
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
            <h2>Selecteer een tandarts en tijdslot</h2>
            <form action="select_dentist.php" method="post">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                <input type="hidden" name="treatment" value="<?php echo htmlspecialchars($selectedTreatmentDescription); ?>">
                <input type="hidden" name="treatmentID" value="<?php echo htmlspecialchars($selectedTreatmentID); ?>"> <!-- Voeg dit toe -->
                
                <div class="input-group">
                    <label for="dentist">Kies een tandarts:</label>
                    <select id="dentist" name="dentist" onchange="this.form.submit()" required>
                        <option value="">Selecteer een tandarts</option>
                        <?php foreach ($tandartsen as $tandarts): ?>
                            <option value="<?php echo htmlspecialchars($tandarts['tandartsID']); ?>" <?php echo ($tandartsID == $tandarts['tandartsID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tandarts['Naam']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if ($tandartsID): ?>
                <form action="./Functions/make_appointment.php" method="post">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                    <input type="hidden" name="dentist" value="<?php echo htmlspecialchars($tandartsID); ?>">
                    <input type="hidden" name="treatment" value="<?php echo htmlspecialchars($selectedTreatmentDescription); ?>">
                    <input type="hidden" name="treatmentID" value="<?php echo htmlspecialchars($selectedTreatmentID); ?>"> <!-- Voeg dit toe -->

                    <div class="input-group">
                        <label for="time">Kies een tijdslot:</label>
                        <select id="time" name="time" required>
                            <option value="">Selecteer een tijdslot</option>
                            <?php foreach ($tijdsloten as $slot): ?>
                                <option value="<?php echo htmlspecialchars($slot['Tijd']); ?>">
                                    <?php echo htmlspecialchars($slot['Tijd']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit">Maak Afspraak</button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
