<?php

include 'objects/user.php';
session_start();
include 'notifications.php';

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['userData'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TandartsPlatform</title>
    <link rel="stylesheet" href="styles/tand.css" />
    <link rel="stylesheet" href="styles/article_styling.css">
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

    <header>
        <h1>LEDental</h1>
        <?php
        if (isset($_SESSION['user_id'])) {
            if ($user->usertype == 'Patiënt') {
                echo "<h2>Welkom, " . $user->patiënt->name . "</h2>";
            } elseif ($user->usertype == 'Tandarts') {
                echo "<h2>Welkom, " . $user->tandarts->name . "</h2>";
            }
        }
        ?>
    </header>

    <main>
        <section class="articles-section">
            <article>
                <h2>Vind de juiste tandarts, eenvoudig en snel</h2>
                <p>
                    Bij Dentist Platform maken we het gemakkelijk om de perfecte
                    tandarts te vinden die bij jouw behoeften past. Of je nu op zoek
                    bent naar een periodieke controle, een specialistische behandeling,
                    of een spoedafspraak, wij verbinden je met de beste tandartsen in
                    jouw regio. Met slechts een paar klikken kun je beschikbare
                    tandartsen bekijken, hun beoordelingen lezen en direct een afspraak
                    boeken.
                </p>
            </article>

            <!-- Aangepast artikel met een knop -->
            <article>
                <h2>Maak een afspraak vandaag nog</h2>
                <p class="description-text">
                    Klik op de onderstaande knop om eenvoudig en snel een afspraak te maken
                    met een van onze ervaren tandartsen. Wij zorgen voor een zorgeloze en betrouwbare
                    ervaring van begin tot eind.
                </p>
                <div class="button-container">
                    <a href="createAppointments.php" class="appointment-button">Afspraak maken</a>
                </div>
            </article>

            <article>
                <h2>Tandartsafspraken op jouw voorwaarden</h2>
                <p>
                    Dentist Platform maakt het plannen van een tandartsafspraak flexibel
                    en stressvrij. Kies een tijd en datum die jou uitkomt, en beheer al
                    je afspraken gemakkelijk vanuit je persoonlijke account. Of je nu
                    een eenmalige afspraak nodig hebt of regelmatige controles wilt
                    plannen, onze gebruiksvriendelijke interface geeft je volledige
                    controle over je tandheelkundige zorg.
                </p>
            </article>
        </section>
    </main>
<script>showPopup()</script>
    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>

</body>

</html>