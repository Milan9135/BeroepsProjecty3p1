<?php

session_start();
include 'db.php';

// Include the User class
include "objects/user.php";

$db = new DB("Tandartsdb");

$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['wachtwoord'];

    // Input validation
    if (empty($email) || empty($password)) {
        $message = "Vul alle velden in.";
    } else {
        try {
            // Prepare and execute query to find user
            $query = "SELECT * FROM Users WHERE Email = ?";
            $userResult = $db->select($query, [$email]);

            // Check if a user was found
            if (!empty($userResult)) {
                $user = $userResult[0];  // Get the first result

                // Verify if the password is correct
                if (password_verify($password, $user['Wachtwoord'])) {
                    $_SESSION['user_id'] = $user['userID'];
                    $_SESSION['user_type'] = $user['Usertype'];

                    // Get user data based on Usertype
                    $userType = $user['Usertype'];
                    $extraData = null;

                    // Based on Usertype, fetch relevant details
                    if ($userType == 'Patiënt') {
                        $query = "SELECT p.*, u.Email, u.Usertype 
                                  FROM Tandartsdb.Patiënt p 
                                  JOIN Tandartsdb.Users u ON p.userID = u.userID 
                                  WHERE p.userID = ?";
                        $extraData = $db->select($query, [$user['userID']]);

                        if (!empty($extraData)) {
                            $extraData = $extraData[0];  // Get the first result if multiple rows
                        }

                        // Debugging: Output patient data
                        echo "<pre>Fetched Patiënt Data: ";
                        print_r($extraData);
                        echo "</pre>";

                    } elseif ($userType == 'Tandarts') {
                        $query = "SELECT t.*, u.Email, u.Usertype 
                                  FROM Tandartsdb.Tandarts t 
                                  JOIN Tandartsdb.Users u ON t.userID = u.userID 
                                  WHERE t.userID = ?";
                        $extraData = $db->select($query, [$user['userID']]);

                        if (!empty($extraData)) {
                            $extraData = $extraData[0];  // Get the first result if multiple rows
                        }

                        // Debugging: Output tandarts data
                        echo "<pre>Fetched Tandarts Data: ";
                        print_r($extraData);
                        echo "</pre>";
                    }

                    // Debugging: Output fetched user details and extraData before object creation
                    echo "<pre>User Details: ";
                    print_r($user);
                    echo "</pre>";

                    echo "<pre>Extra Data: ";
                    print_r($extraData);
                    echo "</pre>";

                    // Create a new User object with the additional data
                    $userObject = new User(
                        $user['userID'],
                        $user['Email'],
                        $user['Usertype'],
                        $extraData
                    );

                    // Debugging: Output created user object to check if tandarts or patiënt is correctly populated
                    echo "<pre>Created User Object: ";
                    print_r($userObject);
                    echo "</pre>";

                    // Store the User object in the session
                    $_SESSION['userData'] = $userObject;

                    // Redirect to index.php after successful login
                    header("Location: index.php");
                    exit();
                } else {
                    $message = "Onjuiste inloggegevens.";
                }
            } else {
                $message = "Onjuiste inloggegevens.";
            }
        } catch (Exception $e) {
            $message = "Fout: " . $e->getMessage();
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
            <p id="message"><?php echo $message ?></p>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>