<?php
// Automatically brings the config file
require 'includes/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/anno.css">
</head>

<body>
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <?php
        // Checks if the user is an admin, if they are, display a otherwise hidden button for creating events
        if ($user_role == "Admin") {
            echo '
            <form action="https://cgi.luddy.indiana.edu/~keldong/ems/createAnno.php" method="POST" class="anno_button">
                <input type="submit" class="create_anno" value="Make an Announcement">
            </form>
            ';
        }

        // Grabbing all announcements and ordering them all by newest one (date first then time)
        $annos = mysqli_query($db_connection, "SELECT id, title, DATE_FORMAT(dateTimeMade, '%b %e, %y') AS date_made, 
        DATE_FORMAT(dateTimeMade, '%h:%i %p') AS time_made
        FROM e_Announcement
        ORDER BY date_made, time_made DESC");

        // Logic is a while loop that keep looping through each row until the end
        while ($anno_info = mysqli_fetch_assoc($annos)) {
            // Storing all event information as variables (Only title, time start, and end, and location.)
            $anno_id = $anno_info['id'];
            $title = $anno_info['title'];
            $date_made = $anno_info['date_made'];
            $time_made = $anno_info['time_made'];

            // Displaying all the information
            echo "
            <div class='evt_container'>
                <h4 class='header'>$title</h4>";
            echo "
                <p class='datetime'>$date_made | $time_made </p>
                <form id='anno_form' method='POST' action='anno.php'>
                    <input type='hidden' name='anno_id' value='$anno_id'>
                    <button type='submit' class='sub_button'>Click here for announcement details</button>
                </form>
            </div>
            ";
        }
        ?>
    </div>
</body>

</html>