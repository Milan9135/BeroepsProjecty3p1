<?php
include "registerFunction.php";


$user = new user($myDb);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["naam"]) || empty($_POST["email"]) || empty($_POST["wachtwoord"])) {
        echo "Vul alles in";
    } else {
        try {
            $user->insertUser($_POST['email'], $_POST['wachtwoord']);
            echo "toegevoegd";
        } catch (Exception $e) {
            'Error: ' . $e->getMessage();
        }
    }
}


?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registreren</title>
    <link rel="stylesheet" href="register.css" />
</head>

<body>

    <nav class="navbar">
        <a href="index.html">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>

    <main>
        <div class="register-container">
            <h2>Registreren</h2>
            <form id="registerForm" method="POST">
                <div class="input-group">
                    <label for="username">naam</label>
                    <input type="text" id="naam" name="naam" required />
                </div>
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required />
                </div>
                <div class="input-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="wachtwoord" name="wachtwoord" required />
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Bevestig Wachtwoord</label>
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        required />
                </div>
                <button type="submit">Registreren</button>
            </form>
            <p id="message"></p>
        </div>
    </main>

    <script src="script.js"></script>
</body>

</html>