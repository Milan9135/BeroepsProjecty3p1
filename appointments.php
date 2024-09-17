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
$query = $myDb->execute("SELECT * FROM users WHERE userID = ?", [$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user['Usertype'] !== 'Patiënt') {
    echo "Toegang geweigerd. Alleen patiënten kunnen een afspraak maken.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecteer Datum en Tijd - Tandartspraktijk</title>
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
            <h2>Selecteer Datum en Tijd</h2>
            <form action="select_dentist.php" method="post">
                <div class="input-group">
                    <label for="datetime">Datum en Tijd</label>
                    <input type="datetime-local" id="datetime" name="datetime" required>
                </div>
                <button type="submit">Volgende</button>
            </form>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
