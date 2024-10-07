<?php // open the session so the checks can be made

include 'user.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $userdata = $_SESSION['userData'];
}
?>

<nav class="navbar">
    <a href="index.php">Home</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($userdata->usertype == "Patiënt"): ?>
            <!-- If the user is a patient, show the following links -->
            <a href="logout.php">Logout</a>
            <a href="profile.php">Profiel</a>
            <a href="afspraken.php">Afspraken</a>
            <a href="dentist_info.php">Tandartsen</a>
        <?php elseif ($userdata->usertype == "Tandarts"): ?>
            <!-- If the user is a tandarts, show the following links -->
            <a href="logout.php">Logout</a>
            <a href="profile.php">Profiel</a>
            <a href="afspraken.php">Afspraken</a>
            <a href="list_patients.php">Patiënten</a>
        <?php endif; ?>
    <?php else: ?>
            <!-- If the user is not logged in, show the following links -->
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<?php // close the session
session_write_close();
?>