<?php
// Automatically brings the config file
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// This file is one that contains the use of an existing framework used to convert links from regular text input
require $dir . '/frameworks/links.php';

// Default Permissions for announcements
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
    die();
}

// Processing the information about the annnoucemnt here because it is more appropriate and will allow for preloading
// of announcement information before user sees anything on the specifc page.

// Using the hidden field id value that was sent to get the rest of the announcement information.
$anno_id = $_POST['anno_id'];
$creator_name = $_POST['anno_creator'];

// Query that uses the anno_id that was retrieved to find the specific announcement
$anno_query = mysqli_query($db_connection, "SELECT id, title, DATE_FORMAT(dateTimeMade, '%b %e, %y') AS date_made, 
DATE_FORMAT(dateTimeMade, '%H:%i') AS time_made, details
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
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/anno.css">
</head>

<body id="spec">
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <h2><?php echo $title; ?></h2>
        <p class="create_container">Made by <?php echo "<span class='creator'>$creator_name</span>"; ?></p>
        <p class="datetime_container"><?php echo $date . " | " . $time; ?></p>
        <p><?php echo make_clickable($details); ?></p>
    </div>
</body>

</html>