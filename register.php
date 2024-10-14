<?php
include "./Functions/registerFunction.php";

$user = new user($myDb);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["naam"]) || empty($_POST["email"]) || empty($_POST["wachtwoord"]) || empty($_POST["geboortedatum"]) || empty($_POST["telefoonnummer"]) || empty($_POST["adres"])) {
        echo "Vul alle velden in";
    } else {
        try {
            // Wachtwoord hashen voordat het opgeslagen wordt
            $hashedPassword = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);

            // Voeg gebruiker toe aan de Users-tabel
            $user->insertUser($_POST['email'], $hashedPassword, 'Patiënt');

            // Haal het laatste ingevoegde userID op
            $userID = $myDb->lastInsertId();

            // Voeg patiëntgegevens toe aan de Patiënt-tabel met het opgehaalde userID
            $myDb->execute("INSERT INTO Patiënt (Naam, Geboortedatum, Telefoonnummer, Adres, userID) VALUES (?, ?, ?, ?, ?)", 
                [$_POST['naam'], $_POST['geboortedatum'], $_POST['telefoonnummer'], $_POST['adres'], $userID]);

            $toegevoegd = '<p>Je bent succesvol geregistreerd!</p>';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if (isset($toegevoegd) && !empty($toegevoegd)) {
    $message = "Succesvol geregistreerd";
}

?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registreren</title>
    <link rel="stylesheet" href="styles/register.css" />
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
    <div class="register-container">
        <h2>REGISTREREN</h2>
        <form id="registerForm" method="POST">
            <div class="input-group">
                <label for="naam">Naam</label>
                <input type="text" id="naam" name="naam" required />
            </div>
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required />
            </div>
            <div class="input-group">
                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required />
            </div>
            <div class="input-group">
                <label for="geboortedatum">Geboortedatum</label>
                <input type="date" id="geboortedatum" name="geboortedatum" required />
            </div>
            <div class="input-group">
                <label for="telefoonnummer">Telefoonnummer</label>
                <input type="text" id="telefoonnummer" name="telefoonnummer" required />
            </div>
            <div class="input-group">
                <label for="adres">Adres</label>
                <input type="text" id="adres" name="adres" required />
            </div>

            <button type="submit">Registreren</button>

        </form>
        <p id="message"><?php echo $message ?></p>
    </div>
    </main>


    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>
    
</body>

</html>