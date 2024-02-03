<?php
// Automatically brings the config file
require 'includes/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Font Stylesheet -->
    <link href="https://fonts.googleapis.com/css2?family=Jaldi:wght@400;700&family=Mulish:ital,wght@0,300;0,400;0,700;1,300;1,700&family=Nunito:ital,wght@0,300;1,600&family=Overlock:wght@400;700;900&family=PT+Sans:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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