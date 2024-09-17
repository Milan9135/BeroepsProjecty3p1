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

// Verkrijg beschikbare behandelingen zonder duplicaten
$behandelingen = $myDb->execute("SELECT DISTINCT Beschrijving FROM Behandelingen")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecteer Datum - Tandartspraktijk</title>
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
            <h2>Maak een afspraak</h2>
            <form action="select_dentist.php" method="post">
                <div class="input-group">
                    <label for="date">Datum</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="input-group">
                    <label for="treatment">Behandeling</label>
                    <select id="treatment" name="treatment" required>
                        <option value="">Selecteer een behandeling</option>
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

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
