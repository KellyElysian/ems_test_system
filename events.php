<?php
// Automatically brings the config file
require 'includes/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/events.css">
</head>

<body>
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <?php
        if ($user_role == "Admin") {
            echo '
            <form action="createEvent.php" method="POST">
                <input type="submit" name="create_event" class="create_event" value="Create Event">
            </form>
            ';
        }
        ?>
    </div>
</body>

</html>