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

// Haal de gemaakte afspraken van de patiënt op
$afsprakenQuery = $myDb->execute("
    SELECT A.afspraakID, A.afspraakDatum, A.afspraakTijd, B.Beschrijving AS behandeling, U.naam AS dokter
    FROM Afspraken A
    JOIN Behandelingen B ON A.behandelingID = B.behandelingID
    JOIN Users U ON A.dokterID = U.userID
    WHERE A.userID = ?
    AND A.geannuleerd = 0
    ORDER BY A.afspraakDatum ASC, A.afspraakTijd ASC
", [$userId]);

$afspraken = $afsprakenQuery->fetchAll(PDO::FETCH_ASSOC);

// Verwerk het annuleren van een afspraak
if (isset($_POST['cancel_appointment'])) {
    $afspraakID = $_POST['afspraakID'];
    
    // Markeer de afspraak als geannuleerd
    $myDb->execute("UPDATE Afspraken SET geannuleerd = 1 WHERE afspraakID = ?", [$afspraakID]);
    
    // Redirect naar de pagina om de wijzigingen weer te geven
    header('Location: afspraak_annuleren.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Afspraken - Tandartspraktijk</title>
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
        <div class="appointments-container">
            <h2>Mijn Afspraken</h2>
            <?php if (count($afspraken) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Behandeling</th>
                            <th>Dokter</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($afspraken as $afspraak): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($afspraak['afspraakDatum']); ?></td>
                                <td><?php echo htmlspecialchars($afspraak['afspraakTijd']); ?></td>
                                <td><?php echo htmlspecialchars($afspraak['behandeling']); ?></td>
                                <td><?php echo htmlspecialchars($afspraak['dokter']); ?></td>
                                <td>
                                    <form action="afspraak_annuleren.php" method="post">
                                        <input type="hidden" name="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">
                                        <button type="submit" name="cancel_appointment">Annuleren</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Je hebt geen toekomstige afspraken.</p>
            <?php endif; ?>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
