<?php
// Automatically brings the config file
require 'includes/config.php';

// Default Permissions for announcements
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/createMember.php');
        die();
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login.php');
    die();
}

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
        DATE_FORMAT(dateTimeMade, '%H:%i') AS time_made
        FROM e_Announcement
        ORDER BY date_made, time_made DESC");

        // Logic is a while loop that keep looping through each row until the end
        while ($anno_info = mysqli_fetch_assoc($annos)) {
            // Storing all announcement information as variables
            $anno_id = $anno_info['id'];
            $title = $anno_info['title'];
            $date_made = $anno_info['date_made'];
            $time_made = $anno_info['time_made'];

            // Getting the member/admin who created the announcement
            $creator_query = mysqli_query($db_connection, "SELECT firstName, lastName FROM e_Member AS m
            JOIN e_Anno_Creator AS ac ON ac.member_id = m.id
            JOIN e_Announcement AS a ON a.id = ac.anno_id
            WHERE ac.anno_id = $anno_id");
            $name_array = mysqli_fetch_assoc($creator_query);
            $creator_name = $name_array['firstName'] . " " . $name_array['lastName'];

            // Displaying all the information about the announcement
            echo "
            <div class='anno_container'>
                <h4 class='header'>$title</h4>";
            echo '
                <p> Made by <span class="creator">' . $creator_name . '</span></p>
                <p class="datetime">' . $date_made . ' | ' . $time_made . '</p>
                <form method="POST" action="anno.php">
                    <input type="hidden" name="anno_creator" value="' . $creator_name . '">
                    <input type="hidden" name="anno_id" value="' . $anno_id . '">
                    <button type="submit" class="sub_button">Click here for announcement details</button>
                </form>
            </div>
            ';
        }
        ?>
    </div>
</body>

</html>