<?php
session_start();
include 'db.php';

$myDb = new DB("Tandartsdb");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['wachtwoord'];

    // Input validation
    if (empty($email) || empty($password)) {
        echo "Vul alle velden in.";
    } else {
        try {
            // Prepare and execute query to find user
            $query = $myDb->execute("SELECT * FROM users WHERE Email = ?", [$email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            // Verify user exists and password is correct
            if ($user && password_verify($password, $user['Wachtwoord'])) {
                $_SESSION['user_id'] = $user['userID'];
                $_SESSION['user_type'] = $user['Usertype'];
                // header("Location: profiel.php"); // Redirect to profile page after login
                header("location: index.php");
                exit();
            } else {
                echo "Onjuiste inloggegevens.";
            }
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/tand.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
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
