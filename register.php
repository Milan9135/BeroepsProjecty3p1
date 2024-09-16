<?php
include "registerFunction.php";

$user = new user($myDb);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["naam"]) || empty($_POST["email"]) || empty($_POST["wachtwoord"])) {
        echo "Vul alles in";
    } else {
        try {
          // Wachtwoord hashen voordat het opgeslagen wordt
          $hashedPassword = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
            
          // Gebruiker invoegen met het gehashte wachtwoord
          $user->insertUser($_POST['email'], $hashedPassword);
          $toegevoegd = '<p>Je bent succesvol geregistreerd!</p>';
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
    <link rel="stylesheet" href="styles/register.css" />
</head>

<body>

<nav class="navbar">
        <a href="index.php">Home</a>

        <?php 
        if (isset($_SESSION['user_id'])) {
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo '<a href="login.php">Login</a>';
        }
        ?>

        <?php 
        if (isset($_SESSION['user_id'])) {
            echo '<a href="profiel.php">Mijn account</a>';
        } else {
            echo '<a href="register.php">Register</a>';
        }
        ?>

    </nav>

    <main>
        <div class="register-container">
            <h2>Registreren</h2>
            <form id="registerForm" method="POST">
                <div class="input-group">
                    <label for="username">Naam</label>
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
                
                <button type="submit">Registreren</button>


                <?php

                if (isset($toegevoegd) && !empty($toegevoegd)) {
                    echo '<p style="color: black; font-size: 1.25em;
                                            font-weight: bold;
                                            text-align: center;
                                            margin-top: 20px;
                                            padding: 10px;
                                            border-radius: 8px;
                                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            Succesvol geregistreert</p>';

            


                }

                ?>


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