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
    <title>Home Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Home main stylesheet -->
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php
    // Loads the navigation bar
    require 'includes/navbar.php';
    ?>
    <div class="main_container">
        <h1>EMS Home Page</h1>

        <div class="info_container">
            <p>
                Welcome to this mock/prototype version of a EMS website.
                This is the home page.
            </p>
            <p>
                To proceed, please click the button login button on the navigation bar!
            </p>
        </div>
    </div>
</body>

</html>