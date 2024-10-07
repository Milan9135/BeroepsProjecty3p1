<?php
session_start();
include 'db.php';

// Check if the user is logged in and is a dentist
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Tandarts') {
    header("Location: login.php"); // Redirect to login page if not logged in or not a dentist
    exit();
}

$db = new DB("Tandartsdb");

try {
    // Fetch all patients
    $query = "SELECT p.Naam, p.Geboortedatum, p.Telefoonnummer, p.Adres, u.Email 
            FROM Patiënt p
            JOIN Users u ON p.userID = u.userID";
    $patients = $db->select($query);

    if (!$patients) {
        $message = "Geen patiënten gevonden.";
    }
} catch (Exception $e) {
    $message = "Fout bij het ophalen van patiënten: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lijst van Patiënten</title>
    <link rel="stylesheet" href="styles/tand.css">
    <link rel="stylesheet" href="styles/table.css">
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
        <h1 style="text-align:center;">Lijst van Geregistreerde Patiënten</h1>

        <!-- Table Container -->
        <div class="table-container">
            <?php if (isset($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Geboortedatum</th>
                            <th>Telefoonnummer</th>
                            <th>Adres</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($patient['Naam']); ?></td>
                                <td><?php echo htmlspecialchars($patient['Geboortedatum']); ?></td>
                                <td><?php echo htmlspecialchars($patient['Telefoonnummer']); ?></td>
                                <td><?php echo htmlspecialchars($patient['Adres']); ?></td>
                                <td><?php echo htmlspecialchars($patient['Email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>
</body>

</html>