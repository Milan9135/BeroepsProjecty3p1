<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TandartsPlatform</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/article_styling.css">
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
    <header>
        <h1>TandartsPlatform?</h1>
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

            <article>
                <h2>Transparante afspraken en betrouwbare tandartsen</h2>
                <p>
                    We begrijpen hoe belangrijk het is om een tandarts te kiezen waar je
                    je prettig bij voelt. Daarom biedt Dentist Platform gedetailleerde
                    profielen van tandartsen, inclusief ervaring, specialisaties en
                    beoordelingen van patiënten. Zo kun je met vertrouwen een afspraak
                    maken, wetende dat je in goede handen bent. Geen verrassingen,
                    alleen heldere afspraken en betrouwbare zorg.
                </p>
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

    <footer class="footer">
        <p>&copy; 2020 TandartsPlatform</p>
    </footer>
</body>

</html>