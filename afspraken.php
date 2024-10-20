<?php
include 'db.php';
session_start();

$myDb = new DB();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Haal het userID van de ingelogde gebruiker op
$userId = $_SESSION['user_id'];

$userQuery = $myDb->execute("SELECT * FROM Users WHERE userID = ?", [$userId]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

// Haal de gemaakte afspraken van de patiënt op
if ($user['Usertype'] == 'Patiënt') {
    $afsprakenQuery = $myDb->execute("
        SELECT A.afspraakID, A.Datum AS afspraakDatum, A.Tijd AS afspraakTijd, B.Beschrijving AS behandeling, T.Naam AS tandarts
        FROM Afspraken A
        JOIN Behandelingen B ON A.BehandelingenID = B.BehandelingenID
        JOIN Tandarts T ON A.tandartsID = T.tandartsID
        WHERE A.userID = ? AND A.geannuleerd = 0 AND A.voltooid = 0
        ORDER BY A.Datum ASC, A.Tijd ASC
    ", [$userId]);

    $afspraken = $afsprakenQuery->fetchAll(PDO::FETCH_ASSOC);
}

$treatmentHistoryQuery = $myDb->execute("
    SELECT A.afspraakID, A.Datum AS afspraakDatum, A.Tijd AS afspraakTijd, B.Beschrijving AS behandeling, T.Naam AS tandarts
    FROM Afspraken A
    JOIN Behandelingen B ON A.BehandelingenID = B.BehandelingenID
    JOIN Tandarts T ON A.tandartsID = T.tandartsID
    WHERE A.userID = ? AND A.voltooid = 1
    ORDER BY A.Datum DESC, A.Tijd DESC
", [$userId]);

$treatmentHistory = $treatmentHistoryQuery->fetchAll(PDO::FETCH_ASSOC);

// Haal de afspraken van de tandarts op
if ($user['Usertype'] == 'Tandarts') {
    $tandartsIdQuery = $myDb->execute("SELECT tandartsID FROM Tandarts WHERE userID = ?", [$userId]);
    $tandarts = $tandartsIdQuery->fetch(PDO::FETCH_ASSOC);
    $tandartsID = $tandarts['tandartsID'];

    $afsprakenQuery = $myDb->execute("
        SELECT A.afspraakID, A.Datum AS afspraakDatum, A.Tijd AS afspraakTijd, B.Beschrijving AS behandeling, P.Naam AS patiënt
        FROM Afspraken A
        JOIN Behandelingen B ON A.BehandelingenID = B.BehandelingenID
        JOIN Patiënt P ON A.userID = P.userID
        WHERE A.tandartsID = ? AND A.geannuleerd = 0 AND A.voltooid = 0
        ORDER BY A.Datum ASC, A.Tijd ASC
    ", [$tandartsID]);

    $afspraken = $afsprakenQuery->fetchAll(PDO::FETCH_ASSOC);
}

// Verwerk het annuleren van een afspraak
if (isset($_POST['cancel_appointment'])) {
    $afspraakID = $_POST['afspraakID'];

    // Haal de userID van de afspraak op
    $afspraakQuery = $myDb->execute("SELECT userID FROM Afspraken WHERE afspraakID = ?", [$afspraakID]);
    $afspraak = $afspraakQuery->fetch(PDO::FETCH_ASSOC);

    // Markeer de afspraak als geannuleerd
    $myDb->execute("UPDATE Afspraken SET geannuleerd = 1 WHERE afspraakID = ?", [$afspraakID]);

    // Voeg een notificatie toe voor de patiënt
    if ($afspraak) {
        $patientUserID = $afspraak['userID'];
        $message = "Uw afspraak is geannuleerd door de tandarts.";
        $myDb->execute("INSERT INTO Notificaties (userID, bericht) VALUES (?, ?)", [$patientUserID, $message]);
    }
    // Voeg een notificatie toe
    $userID = $_SESSION['user_id'];  // Haal de ingelogde gebruiker op
    $message = "Uw afspraak is geannuleerd.";
    $myDb->execute("INSERT INTO Notificaties (userID, bericht) VALUES (?, ?)", [$userID, $message]);
    // Redirect naar de pagina om de wijzigingen weer te geven
    header('Location: afspraken.php');
    exit();
}
// voltooid
if (isset($_POST['complete_appointment'])) {
    $afspraakID = $_POST['afspraakID'];

    // Update de afspraak naar voltooid
    $myDb->execute("UPDATE Afspraken SET voltooid = 1 WHERE afspraakID = ?", [$afspraakID]);
     // Haal de userID van de afspraak op
     $afspraakQuery = $myDb->execute("SELECT userID FROM Afspraken WHERE afspraakID = ?", [$afspraakID]);
     $afspraak = $afspraakQuery->fetch(PDO::FETCH_ASSOC);
 
     // Voeg een notificatie toe voor de patiënt
     if ($afspraak) {
         $patientUserID = $afspraak['userID'];
         $message = "Uw afspraak is succesvol voltooid.";
         $myDb->execute("INSERT INTO Notificaties (userID, bericht) VALUES (?, ?)", [$patientUserID, $message]);
     }

    // Redirect naar de afsprakenpagina om de wijzigingen te tonen
    header('Location: afspraken.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Afspraken - Tandartspraktijk</title>
    <link rel="stylesheet" href="./styles/afspraak-annuleren.css">

    <script src="objects/navbar.js"></script>
</head>

<body>
    <div id="navbar">
        <nav class="navbar">
            <a id="placeholder" href="">a</a>
            <style>
                #placeholder {
                    opacity: 0;
                }
            </style>
        </nav>
    </div>

    <main>

        <div class="patientbutton">
            <br>
            <?php if ($user['Usertype'] == 'Patiënt'): ?>

                <div class="appointments-container">
                    <h2>Mijn Afspraken</h2>
                    <?php if (count($afspraken) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Tijd</th>
                                    <th>Behandeling</th>
                                    <th>Tandarts</th>
                                    <th>Actie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($afspraken as $afspraak): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($afspraak['afspraakDatum']); ?></td>
                                        <td><?php echo htmlspecialchars($afspraak['afspraakTijd']); ?></td>
                                        <td><?php echo htmlspecialchars($afspraak['behandeling']); ?></td>
                                        <td><?php echo htmlspecialchars($afspraak['tandarts']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <!-- Knop om de afspraak te wijzigen -->
                                                <form action="afspraakWijzigen.php" method="post">
                                                    <input type="hidden" name="afspraakID" id="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">
                                                    <button type="submit" name="edit_appointment">Wijzigen</button>
                                                </form>

                                                <!-- Knop om de afspraak te annuleren -->
                                                <form action="afspraken.php" method="post">
                                                    <input type="hidden" name="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">
                                                    <button type="submit" name="cancel_appointment">Annuleren</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Je hebt geen toekomstige afspraken.</p>
                    <?php endif; ?>
                </div>

                <div class="appointment-button" id="conobomama">
                    <a href="./createAppointments.php" id="yh"> Afspraak maken</a>
                </div>

                <div class="treatmentHistory">
                    <h2>Behandelgeschiedenis</h2>
                    <?php if (count($treatmentHistory) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Tijd</th>
                                    <th>Behandeling</th>
                                    <th><?php echo $user['Usertype'] == 'Patiënt' ? 'Tandarts' : 'Patiënt'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($treatmentHistory as $history): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($history['afspraakDatum']); ?></td>
                                        <td><?php echo htmlspecialchars($history['afspraakTijd']); ?></td>
                                        <td><?php echo htmlspecialchars($history['behandeling']); ?></td>
                                        <td><?php echo htmlspecialchars($user['Usertype'] == 'Patiënt' ? $history['tandarts'] : $history['patiënt']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Er is nog geen behandelgeschiedenis.</p>
                    <?php endif; ?>
                </div>

            <?php elseif ($user['Usertype'] == 'Tandarts'): ?>
                <h2>Afspraken Overzicht</h2>
                <?php if (count($afspraken) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Tijd</th>
                                <th>Behandeling</th>
                                <th>Patiënt</th>
                                <th>Actie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($afspraken as $afspraak): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($afspraak['afspraakDatum']); ?></td>
                                    <td><?php echo htmlspecialchars($afspraak['afspraakTijd']); ?></td>
                                    <td><?php echo htmlspecialchars($afspraak['behandeling']); ?></td>
                                    <td><?php echo htmlspecialchars($afspraak['patiënt']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <form action="afspraken.php" method="post">
                                                <input type="hidden" name="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">
                                                <button type="submit" name="complete_appointment">Voltooid</button>
                                            </form>
                                            <form action="afspraken.php" method="post">
                                                <input type="hidden" name="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">

                                                <button type="submit" name="cancel_appointment">Annuleren</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Er zijn geen toekomstige afspraken.</p>
                <?php endif; ?>
            <?php endif; ?>
            <br>
        </div>






    </main>


    <div class="footer">
        <p>&copy; 2024 Tandartspraktijk. Alle rechten voorbehouden.</p>
    </div>

</body>

</html>