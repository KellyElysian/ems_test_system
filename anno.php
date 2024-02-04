<?php
// Automatically brings the config file
require 'includes/config.php';

// Processing the information about the annnoucemnt here because it is more appropriate and will allow for preloading
// of event information before user sees anything on the specifc page.

// Using the hidden field id value that was sent to get the rest of the announcement information.
$anno_id = $_POST['id'];

$anno_query = mysqli_query($db_connection, "SELECT * FROM e_Announcement WHERE id = $anno_id");
// Stores the information in a single array because this should be only one specific announcement
$anno_array = mysqli_fetch_assoc($anno_query);

// Announcement information
$title = $anno_array['title'];
$date

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>