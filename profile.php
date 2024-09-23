<?php
// Include the User and related classes
include 'objects/user.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userData'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Retrieve the user data from the session
$user = $_SESSION['userData'];

// Include the DB
include 'db.php';
$db = new DB("Tandartsdb");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect updated data from form
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Update the user's details in the database based on their user type
    if ($user->usertype == 'Patiënt') {
        $sql = "UPDATE Patiënt SET Naam = ?, Adres = ?, Telefoonnummer = ? WHERE PatiëntID = ?";
        $db->execute($sql, [$name, $address, $phone, $user->patiënt->patiëntID]);
    } else if ($user->usertype == 'Tandarts') {
        $sql = "UPDATE Tandarts SET Naam = ?, Adres = ?, Telefoonnummer = ? WHERE tandartsID = ?";
        $db->execute($sql, [$name, $address, $phone, $user->tandarts->tandartsID]);
    }

    // Update the email in the Users table (common to all)
    $db->execute("UPDATE Users SET Email = ? WHERE userID = ?", [$email, $user->id]);

    // Update the session data
    $user->email = $email;
    $user->name = $name;
    $user->address = $address;
    $user->phone = $phone;

    if ($user->usertype == 'Patiënt') {
        $user->patiënt->name = $name;
        $user->patiënt->address = $address;
        $user->patiënt->phone = $phone;
    } else if ($user->usertype == 'Tandarts') {
        $user->tandarts->name = $name;
        $user->tandarts->address = $address;
        $user->tandarts->phone = $phone;
    }

    $_SESSION['userData'] = $user; // Update session with new data

    $message = "Je profiel is succesvol bijgewerkt!";
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel Bewerken</title>
    <link rel="stylesheet" href="styles/tand.css">
</head>

<body>

    <nav class="navbar">
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
            <a href="profile.php">Profiel</a>
            <a href="afspraak_annuleren.php">Afspraken</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <header>
        <h1>Profiel Bewerken</h1>
    </header>

    <main>
        <form method="POST" action="profile.php">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
            </div>
            <div class="input-group">
                <label for="name">Naam</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->usertype == 'Patiënt' ? $user->patiënt->name : $user->tandarts->name) ?>" required>
            </div>
            <div class="input-group">
                <label for="address">Adres</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($user->usertype == 'Patiënt' ? $user->patiënt->address : $user->tandarts->address) ?>" required>
            </div>
            <div class="input-group">
                <label for="phone">Telefoonnummer</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user->usertype == 'Patiënt' ? $user->patiënt->phone : $user->tandarts->phone) ?>" required>
            </div>
            <button type="submit">Profiel Bijwerken</button>

            <p><?= $message ?></p>

        </form>
    </main>

    <footer class="footer">
        <p>&copy; 2024 TandartsPlatform</p>
    </footer>
</body>

</html>
