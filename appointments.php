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
    <nav class="navbar">
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
            <a href="profile.php">Profiel</a> <!-- Add this link to go to the profile page -->
            <a href="appointments.php">Afspraken</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <main>
        <div class="register-container">
            <h2>Selecteer een datum en tijd</h2>
            <form action="select_dentist.php" method="post">
                <div class="input-group">
                    <label for="date">Datum</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="input-group">
                    <label for="time">Tijd</label>
                    <select id="time" name="time" required>
                        <?php
                        // Verkrijg tijdsloten voor de dag
                        $timeSlots = $myDb->execute("SELECT DISTINCT Tijd FROM Tijdsloten")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($timeSlots as $slot) {
                            echo "<option value=\"{$slot['Tijd']}\">{$slot['Tijd']}</option>";
                        }
                        ?>
                    </select>
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