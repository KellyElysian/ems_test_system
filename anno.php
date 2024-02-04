<?php
// Automatically brings the config file
require 'includes/config.php';

// This file is one that contains the use of an existing framework used to convert links from regular text input
require 'frameworks/links.php';

// Processing the information about the annnoucemnt here because it is more appropriate and will allow for preloading
// of event information before user sees anything on the specifc page.

// Using the hidden field id value that was sent to get the rest of the announcement information.
$anno_id = $_POST['anno_id'];

// Query that uses the anno_id that was retrieved to find the specific announcement
$anno_query = mysqli_query($db_connection, "SELECT id, title, DATE_FORMAT(dateTimeMade, '%b %e, %y') AS date_made, 
DATE_FORMAT(dateTimeMade, '%l:%i %p') AS time_made, details
FROM e_Announcement
WHERE id = $anno_id");
// Stores the information in a single array because this should be only one specific announcement
$anno_array = mysqli_fetch_assoc($anno_query);

// Announcement information
$title = $anno_array['title'];
$date = $anno_array['date_made'];
$time = $anno_array['time_made'];
$details = $anno_array['details'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/anno.css">
</head>

<body id="spec">
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <h2><?php echo $title; ?></h2>
        <p><?php echo $date . " | " . $time; ?></p>
        <p><?php echo make_clickable($details); ?></p>
    </div>
</body>

</html>