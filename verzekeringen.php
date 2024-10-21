<?php
session_start();
include 'db.php'; // Includes the DB class

// Initialize the database connection
$db = new DB();

// Assume user is logged in and we have the userID stored in session
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to update your insurance information.";
    exit();
}

// Fetch the user ID from the session
$userID = $_SESSION['user_id'];

// Fetch all existing insurance details for the user
$sql = "SELECT * FROM Verzekeringen WHERE userID = ?";
$existingInsurance = $db->select($sql, [$userID]);

$message = '';

// Process form submission (for updating, adding, and deleting insurance)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add a new insurance
    if (isset($_POST['add'])) {
        $newInsuranceDesc = $_POST['newInsuranceDesc'];
        if (!empty($newInsuranceDesc)) {
            $sql = "INSERT INTO Verzekeringen (userID, Beschrijving) VALUES (?, ?)";
            $db->execute($sql, [$userID, $newInsuranceDesc]);
            $message = "New insurance added successfully!";
        } else {
            $message = "Please enter a valid insurance description.";
        }
    }

    // Update existing insurance
    if (isset($_POST['update'])) {
        foreach ($_POST['insuranceDesc'] as $insuranceID => $description) {
            $sql = "UPDATE Verzekeringen SET Beschrijving = ? WHERE VerzekeringenID = ? AND userID = ?";
            $db->update($sql, [$description, $insuranceID, $userID]);
        }
        $message = "Verzekering informatie bijgewerkt!";
    }

    // Delete an insurance
    if (isset($_POST['delete'])) {
        $insuranceIDToDelete = $_POST['delete'];
        $sql = "DELETE FROM Verzekeringen WHERE VerzekeringenID = ? AND userID = ?";
        $db->delete($sql, [$insuranceIDToDelete, $userID]);
        $message = "Verzekering successvol verwijderd!";
    }

    // Refresh the page to fetch updated insurance data
    $existingInsurance = $db->select("SELECT * FROM Verzekeringen WHERE userID = ?", [$userID]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Insurance Information</title>
    <script src="objects/navbar.js"></script>
    <link rel="stylesheet" href="styles/tand.css">
    <link rel="stylesheet" href="styles/verzekeringen.css">
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
        <h1>Verzekering informatie</h1>
        <div class="form-container">

            <!-- Form for updating or deleting existing insurances -->
            <form method="POST" action="">
                <?php if (!empty($existingInsurance)) : ?>
                    <?php foreach ($existingInsurance as $insurance) : ?>
                        <div class="input-group">
                            <label for="insuranceDesc_<?php echo $insurance['VerzekeringenID']; ?>">Verzekering beschrijving:</label>
                            <input type="text" id="insuranceDesc_<?php echo $insurance['VerzekeringenID']; ?>"
                                name="insuranceDesc[<?php echo $insurance['VerzekeringenID']; ?>]"
                                value="<?php echo $insurance['Beschrijving']; ?>" required>
                            <button type="submit" name="delete" value="<?php echo $insurance['VerzekeringenID']; ?>" class="delete-btn">Verwijder</button>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="update">Pas veranderingen toe</button>
                <?php else : ?>
                    <p>Geen verzekering informatie gevonden. Voeg rechts een nieuwe toe.</p>
                <?php endif; ?>
            </form>

            <!-- Form for adding new insurance -->
            <form method="POST" action="">
                <div class="input-group">
                    <label for="newInsuranceDesc">Voeg verzekering toe:</label>
                    <input type="text" id="newInsuranceDesc" name="newInsuranceDesc" placeholder="Beschrijving">
                </div>
                <button type="submit" name="add">Voeg toe</button>
                <div class="message"><?php echo $message; ?></div>
            </form>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>

</body>

</html>