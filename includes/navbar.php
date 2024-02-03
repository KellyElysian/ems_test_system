<?php
// Automatically brings the config file
require 'includes/config.php';

// Regular SESSION variables for security reasons
$member_id = $_SESSION['member_id'];
$user_id = $_SESSION['user_id']

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body>
    <nav>
        <a href="home.php">Home</a>
        <a href="">Events</a>
        <?php
        // Checks if the member_id is set meaning the user is logged in and has a profile
        // If not, show the login button, if they are logged in, shows the logout button instead
        if (!isset($member_id)) {
            echo '<a href="login.php" class="nav_button">Login</a>';
        } else {
            echo '<a href="logout.php" class="nav_button">Logout</a>';
        }
        ?>
    </nav>
</body>

</html>