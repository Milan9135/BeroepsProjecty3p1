<?php
include 'db.php';
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

// Verkrijg geselecteerde datum en tijd uit de POST-data
$date = $_POST['date'];
$time = $_POST['time'];

// Verkrijg beschikbare tandartsen met tijdsloten
$tandartsQuery = $myDb->execute("
    SELECT DISTINCT t.tandartsID, t.Naam
    FROM Tijdsloten tl
    JOIN Tandarts t ON tl.userID = t.userID
    WHERE tl.Tijd = ?", [$time]);

$tandartsen = $tandartsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kies Tandarts - Tandartspraktijk</title>
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
            <h2>Kies een tandarts</h2>
            <form action="./Functions/make_appointment.php" method="post">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <div class="input-group">
                    <label for="dentist">Tandarts</label>
                    <select id="dentist" name="dentist" required>
                        <?php foreach ($tandartsen as $dentist): ?>
                            <option value="<?php echo $dentist['tandartsID']; ?>">
                                <?php echo $dentist['Naam']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Maak Afspraak</button>
            </form>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
