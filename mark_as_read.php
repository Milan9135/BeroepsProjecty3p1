<?php
// mark_as_read.php
require 'db.php'; // Zorg ervoor dat je hier je databaseverbinding laadt
require 'notifications.php'; // Laad het notificatiesysteem

if (isset($_POST['notificatieID'])) {
    $notificatieID = $_POST['notificatieID'];

    // Maak een instantie van het notificatiesysteem
    $notificationSystem = new NotificationSystem(new DB());
    $notificationSystem->markAsRead($notificatieID);

    // Redirect terug naar de pagina
    header('Location: index.php'); // Pas dit aan naar de juiste pagina
    exit();
}
?>
