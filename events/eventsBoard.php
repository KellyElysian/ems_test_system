<?php
// Automatically brings the config file
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// Default permissions
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    } else {
        if ($member_status != "Active") {
            $_SESSION['reactivate'] = 1;
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
            die();
        }
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/events.css">
</head>

<body>
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <?php
        // Checks if the user is an admin, if they are, display a otherwise hidden button for creating events
        if ($user_role == "Admin") {
            echo '
            <form action="https://cgi.luddy.indiana.edu/~keldong/ems/events/createEvent.php" method="POST">
                <input type="submit" class="create_event" value="Create Event">
            </form>
            ';
        }

        // Grabbing all future and current events from the database based on their date start
        $events = mysqli_query($db_connection, "SELECT id, title, DATE_FORMAT(dateTimeStart, '%b %e, %y') AS date_start, DATE_FORMAT(dateTimeStart, '%k:%i') AS time_start,
        DATE_FORMAT(dateTimeEnd, '%b %e, %y') AS date_end, DATE_FORMAT(dateTimeEnd, '%k:%i') AS time_end, location
        FROM e_Event WHERE DATE_FORMAT(dateTimeEnd, '%Y-%m-%d') >= CURDATE()
        ORDER BY date_start");

        // Logic is a while loop that keep looping through each row until the end
        while ($event_info = mysqli_fetch_assoc($events)) {
            // Storing all event information as variables (Only title, time start, and end, and location.)
            $title = $event_info['title'];
            $date_start = $event_info['date_start'];
            $time_start = $event_info['time_start'];
            $date_end = $event_info['date_end'];
            $time_end = $event_info['time_end'];
            $location = $event_info['location'];

            // Gathering creator of the event info
            $id = $event_info['id'];
            $name_q = mysqli_query($db_connection, "SELECT CONCAT(m.firstName, ' ', m.lastName) AS fullname, DATE_FORMAT(ec.timeMade, '%b %e, %y %k:%i') AS tm FROM e_Event_Create AS ec
            JOIN e_Member AS m ON m.id = ec.mem_id
            WHERE ec.event_id = $id");
            $name_arr = mysqli_fetch_assoc($name_q);
            $fullname = $name_arr['fullname'];
            $time = $name_arr['tm'];

            // Displaying all the information
            echo "
            <div class='evt_container'>
                <h4 class='header'>$title</h4>
                <p>Created by $fullname at $time</p>";
            if ($date_start == $date_end) {
                echo "
                <p class='datetime'>$date_end | $time_start - $time_end</p>
                ";
            } else {
                echo "
                <p class='datetime'>$date_end | $time_start - $date_end $time_end</p>
                ";
            }

            echo "
                <p class='location'>$location</p>
                <a href='event.php' class='detail_link'>Click here for details and sign up</a>
            </div>
            ";
        }
        ?>
    </div>
</body>

</html>