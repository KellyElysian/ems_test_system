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
        // Checks if the user is an admin, if they are, display a otherwise hidden button for creating events
        if ($user_role == "Admin") {
            echo '
            <form action="https://cgi.luddy.indiana.edu/~keldong/ems/createEvent.php" method="POST">
                <input type="submit" name="create_event" class="create_event" value="Create Event">
            </form>
            ';
        }

        // Grabbing all future and current events from the database based on their date start
        $events = mysqli_query($db_connection, "SELECT title, DATE_FORMAT() AS date_start
        FROM e_Event WHERE DATE_FORMAT(dateTimeEnd, '%Y-%m-%d') >= CURDATE()");

        // Logic is a while loop that keep looping through each row until the end
        while ($event_info = mysqli_fetch_assoc($events)) {
            // Storing all event information as variables (Only title, time start, and end, and location.)
            $title = $event_info['title'];
            $date_start = $event_info['date_start'];
            $time_start = $event_info['time_start'];
            $date_end = $event_info['date_end'];
            $time_end = $event_info['time_end'];
            $location = $event_info['location'];
            $details = $event_info['details'];

            // Displaying all the informati
        }
        ?>
    </div>
</body>

</html>