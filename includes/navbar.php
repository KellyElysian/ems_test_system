<?php
// Automatically brings the config file
require 'includes/config.php';

// Regular SESSION variables for security reasons
$member_id = $_SESSION['member_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

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
        <a href="eventsBoard.php">Events</a>
        <a href="">Announcements</a>
        <?php
        // Based on logged in user's role, they may see different navigation bars
        if ($user_role == "Admin") {
            echo '
            <a href="">Directories</a>
            ';
        }

        if (isset($member_id)) {
            echo '<a href="">Profile</a>';
        }

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