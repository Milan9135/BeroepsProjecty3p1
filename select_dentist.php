<?php
include 'db.php';
session_start();
include './Functions/appointmentsFunction.php';

$myDb = new DB("Tandartsdb");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verkrijg de geselecteerde datum en tijdstip
$selectedDateTime = $_POST['datetime'];

// Verkrijg tandartsen die beschikbaar zijn op het geselecteerde tijdstip
$query = $myDb->execute("
    SELECT t.tandartsID, t.Naam 
    FROM Tandarts t
    JOIN Tijdsloten ts ON t.userID = ts.userID
    WHERE ts.DatumTijd = ? AND ts.Beschikbaar = TRUE
", [$selectedDateTime]);
$tandartsen = $query->fetchAll(PDO::FETCH_ASSOC);
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
            <h2>Kies een Tandarts</h2>
            <form action="make_appointment.php" method="post">
                <input type="hidden" name="datetime" value="<?php echo htmlspecialchars($selectedDateTime); ?>">
                <div class="input-group">
                    <label for="tandarts">Tandarts</label>
                    <select id="tandarts" name="tandarts" required>
                        <?php foreach ($tandartsen as $tandarts): ?>
                            <option value="<?php echo $tandarts['tandartsID']; ?>">
                                <?php echo $tandarts['Naam']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Plan Afspraak</button>
            </form>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
