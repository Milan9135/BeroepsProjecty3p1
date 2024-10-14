<?php
session_start();
include 'db.php';

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Patiënt') {
    header("Location: login.php"); // Redirect to login page if not logged in or not a patient
    exit();
}

$db = new DB();

try {
    // Fetch all dentists (tandartsen) with their details, including email
    $query = "SELECT t.TandartsID, t.Naam, YEAR(CURDATE()) - YEAR(t.Geboortedatum) AS Leeftijd,
            t.Telefoonnummer, u.Email, t.Beoordeling, t.Specialisaties, t.Beschrijving
            FROM Tandarts t
            JOIN Users u ON t.userID = u.userID"; // Join with Users to get email
    $tandartsen = $db->select($query);
} catch (Exception $e) {
    $message = "Fout bij het ophalen van tandartsen: " . $e->getMessage();
    $tandartsen = []; // Initialize an empty array in case of error
}

// Get selected dentist info if a selection is made
$selectedTandarts = null;
if (isset($_POST['tandarts_id'])) {
    foreach ($tandartsen as $tandarts) {
        if ($tandarts['TandartsID'] == $_POST['tandarts_id']) {
            $selectedTandarts = $tandarts;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tandartsen Informatie</title>
    <link rel="stylesheet" href="styles/tand.css">
    <link rel="stylesheet" href="styles/tandarts-info.css">
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
        <h1 style="text-align:center;">Kies een Tandarts</h1>

        <form method="post">
            <div class="selector-container">
                <select id="tandartsSelect" name="tandarts_id" onchange="this.form.submit()">
                    <option value="">-- Selecteer een Tandarts --</option>
                    <?php if (isset($tandartsen)) : ?>
                        <?php foreach ($tandartsen as $tandarts) : ?>
                            <option value="<?php echo $tandarts['TandartsID']; ?>" <?php echo isset($selectedTandarts) && $selectedTandarts['TandartsID'] == $tandarts['TandartsID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tandarts['Naam']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </form>

        <?php if ($selectedTandarts) : ?>
            <div class="info-container" id="tandartsInfo">
                <h2 id="tandartsNaam"><?php echo htmlspecialchars($selectedTandarts['Naam']); ?></h2>
                <p><strong>Leeftijd:</strong> <span id="tandartsLeeftijd"><?php echo $selectedTandarts['Leeftijd']; ?></span></p>
                <p><strong>Telefoonnummer:</strong> <span id="tandartsTelefoon"><?php echo htmlspecialchars($selectedTandarts['Telefoonnummer']); ?></span></p>
                <p><strong>Email:</strong> <span id="tandartsEmail"><?php echo htmlspecialchars($selectedTandarts['Email']); ?></span></p>
                <p><strong>Beoordeling:</strong> <span id="tandartsBeoordeling"><?php echo htmlspecialchars($selectedTandarts['Beoordeling']); ?></span></p>
                <p><strong>Specialisaties:</strong> <span id="tandartsSpecialisaties"><?php echo htmlspecialchars($selectedTandarts['Specialisaties']); ?></span></p>
                <p><strong>Beschrijving:</strong> <span id="tandartsBeschrijving"><?php echo htmlspecialchars($selectedTandarts['Beschrijving']); ?></span></p>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>&copy; 2024 TandartsPlatform</p>
    </footer>

</body>

</html>
