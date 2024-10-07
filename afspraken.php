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

// Haal de gemaakte afspraken van de patiënt op
if ($user['Usertype'] == 'Patiënt') {
    $afsprakenQuery = $myDb->execute("
        SELECT A.afspraakID, A.Datum AS afspraakDatum, A.Tijd AS afspraakTijd, B.Beschrijving AS behandeling, T.Naam AS tandarts
        FROM Afspraken A
        JOIN Behandelingen B ON A.BehandelingenID = B.BehandelingenID
        JOIN Tandarts T ON A.tandartsID = T.tandartsID
        WHERE A.userID = ? AND A.geannuleerd = 0
        ORDER BY A.Datum ASC, A.Tijd ASC
    ", [$userId]);

    $afspraken = $afsprakenQuery->fetchAll(PDO::FETCH_ASSOC);
}
// Haal de afspraken van de tandarts op
if ($user['Usertype'] == 'Tandarts') {
    $afsprakenQuery = $myDb->execute("
        SELECT A.afspraakID, A.Datum AS afspraakDatum, A.Tijd AS afspraakTijd, B.Beschrijving AS behandeling, P.Naam AS patiënt
        FROM Afspraken A
        JOIN Behandelingen B ON A.BehandelingenID = B.BehandelingenID
        JOIN Patiënt P ON A.userID = P.userID
        WHERE A.tandartsID = ? AND A.geannuleerd = 0
        ORDER BY A.Datum ASC, A.Tijd ASC
    ", [$user['userID']]);

    $afspraken = $afsprakenQuery->fetchAll(PDO::FETCH_ASSOC);
}

// Verwerk het annuleren van een afspraak
if (isset($_POST['cancel_appointment'])) {
    $afspraakID = $_POST['afspraakID'];

    // Markeer de afspraak als geannuleerd
    $myDb->execute("UPDATE Afspraken SET geannuleerd = 1 WHERE afspraakID = ?", [$afspraakID]);

    // Redirect naar de pagina om de wijzigingen weer te geven
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
    <link rel="stylesheet" href="./styles/afspraak-annuleren1.css">
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
                                                <form action="afspraak_wijzigen.php" method="post">
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
                    <a href="./appointments.php" id="yh"> Afspraak maken</a>
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
                                            <form action="afspraak_wijzigen.php" method="post">
                                                <input type="hidden" name="afspraakID" id="afspraakID" value="<?php echo $afspraak['afspraakID']; ?>">

                                                <button type="submit" name="edit_appointment">Wijzigen</button>
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