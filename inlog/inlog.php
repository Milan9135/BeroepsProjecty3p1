<?php
$host = "localhost";
$dbname = "Tandartsdb";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$naam = "Lebron james";
$geboortedatum = "1980-05-15";
$email = "lebronjamse@gmail.com";
$telefoonnummer = "0612345678";
$adres = "123 Main Street, Amsterdam";
$beoordeling = 4.8;
$specialisaties = "Orthodontist, lEdental";
$beschrijving = "Experienced dentist specializing in orthodontics and cosmetic dentistry.";

$sql = "INSERT INTO Tandarts (Naam, Geboortedarum, Telefoonnummer, Adres, Beoordeling, Specialisaties, Beschrijving)
        VALUES ('$naam', '$geboortedatum', '$telefoonnummer', '$adres', '$beoordeling', '$specialisaties', '$beschrijving')";

if ($conn->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="tand.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="./indexs.html/">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>

    <main>
        <div class="register-container">
            <h2>INLOGGEN</h2>
            <form id="registerForm" method="POST">
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required />
                </div>
                <div class="input-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="wachtwoord" name="wachtwoord" required />
                </div>
                <button type="submit">Inloggen</button>
            </form>
            <p id="message"></p>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
