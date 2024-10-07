<?php // open the session so the checks can be made
session_start();
?>

<nav class="navbar">
    <a href="index.php">Home</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
        <a href="profile.php">Profiel</a> <!-- Add this link to go to the profile page -->
        <a href="afspraken.php">Afspraken</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<?php // close the session
session_write_close();
?>