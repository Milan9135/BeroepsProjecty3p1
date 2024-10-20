<?php
include 'db.php'; // Zorg ervoor dat je je databaseverbinding hier opneemt

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    // Maak verbinding met de database
    $db = new DB();

    // Haal de notificaties op voor de ingelogde gebruiker
    $sql = "SELECT * FROM Notificaties WHERE userID = :userID AND gelezen = 0 ORDER BY timestamp DESC";
    $notificaties = $db->select($sql, [':userID' => $userID]);

    // Voeg de notificaties toe aan een pop-up
    if ($notificaties) {
        echo '<div class="popup-overlay" id="popupOverlay"></div>';
        echo '<div class="popup" id="notificationPopup">';
        echo '<h3>Notificaties</h3>';
        foreach ($notificaties as $notificatie) {
            echo '<div class="notification">';
            echo '<p>' . htmlspecialchars($notificatie['bericht']) . '</p>';
            echo '</div>';
        }
        echo '<button class="notiButton" onclick="markAllAsRead()">Markeren als gelezen</button>';
        echo '</div>';
    }
}

// Voeg een functie toe om een notificatie als gelezen te markeren
if (isset($_POST['notificatieID'])) {
    $notificatieID = $_POST['notificatieID'];
    $sql = "UPDATE Notificaties SET gelezen = 1 WHERE notificatieID = :notificatieID";
    $db->update($sql, [':notificatieID' => $notificatieID]);

    
}
// Voeg een functie toe om alle notificaties als gelezen te markeren
if (isset($_POST['markAllAsRead'])) {
    $sql = "UPDATE Notificaties SET gelezen = 1 WHERE userID = :userID";
    $db->update($sql, [':userID' => $userID]);
}
?>

<script>
function showPopup() {
    document.getElementById('notificationPopup').style.display = 'block';
    document.getElementById('popupOverlay').style.display = 'block';
}

function closePopup() {
    document.getElementById('notificationPopup').style.display = 'none';
    document.getElementById('popupOverlay').style.display = 'none';
}

function markAsRead(notificatieID) {
    fetch('notifications.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'notificatieID=' + notificatieID
    }).then(response => {
        if (response.ok) {
            closePopup(); // Sluit de pop-up
            location.reload(); // Herlaad de pagina om de notificaties bij te werken
        }
    });
}
function markAllAsRead() {
    fetch('notifications.php', { // Zorg ervoor dat dit de juiste bestandsnaam is
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'markAllAsRead=true' // Stuur de actie
    }).then(response => {
        if (response.ok) {
            closePopup(); // Sluit de pop-up
            location.reload(); // Herlaad de pagina om de notificaties bij te werken
        }
    });
}

// Roep showPopup() aan wanneer je de pop-up wilt tonen, bijvoorbeeld na een bepaalde actie of event.
showPopup(); // Voeg deze aanroep toe waar nodig
</script>
