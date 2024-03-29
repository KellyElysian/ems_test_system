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

// Processing the information about the annnoucemnt here because it is more appropriate and will allow for preloading
// of announcement information before user sees anything on the specifc page.

// Using the hidden field id value that was sent to get the rest of the announcement information.
// Or uses session variable 'temp_anno_id' from editAnno to showcase the edited anno.
if (isset($_SESSION['temp_anno_id'])) {
    $anno_id = $_SESSION['temp_anno_id'];
    // Unsets the session variable after since it's temporary.
    unset($_SESSION['temp_anno_id']);
} else {
    $anno_id = $_POST['anno_id'];
}

// Finding the creator
$creator_query = mysqli_query($db_connection, "SELECT CONCAT(m.firstName, ' ', m.lastName) AS fullname FROM e_Member AS m
JOIN e_Anno_Creator AS ac ON ac.member_id = m.id
WHERE ac.anno_id = $anno_id");
$creator_arr = mysqli_fetch_assoc($creator_query);
$creator_name = $creator_arr['fullname'];

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

// Gathering edit information about the last edit made (most recent)
$edit_query = mysqli_query($db_connection, "SELECT CONCAT(m.firstName, ' ', m.lastName) AS fullname,
DATE_FORMAT(editTime, '%b %e, %y | %H:%i') AS edit_time FROM e_Anno_Edit AS ae
JOIN e_Member AS m ON m.id = ae.member_id
WHERE anno_id = $anno_id
ORDER BY editTime DESC
LIMIT 1");
$edit_rows = mysqli_num_rows($edit_query);
if ($edit_rows > 0) {
    $edit_arr = mysqli_fetch_assoc($edit_query);
    $edit_name = $edit_arr['fullname'];
    $edit_time = $edit_arr['edit_time'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/anno.css">
</head>

<body id="spec">
    <?php require $dir . '/includes/navbar.php'; ?>

    <div class="container">
        <?php
        if ($user_role == "Admin") {
        ?>
            <div class="header-and-button">
                <h2><?php echo $title; ?></h2>
                <form action="editAnno.php" method="POST">
                    <input type="hidden" name="anno_id" value="<?php echo $anno_id; ?>">
                    <button name="edit_anno" value="1" class="edit_button">Edit Announcement</button>
                </form>
            </div>
        <?php
        }
        ?>
        <p class="create_container">Made by <?php echo "<span class='creator'>$creator_name</span>"; ?></p>
        <p class="datetime_container"><?php echo $date . " | " . $time; ?></p>
        <?php
        if ($edit_rows > 0) {
        ?>
            <p class="edit_container">Last edited by <span class="bold"><?php echo $edit_name . "</span><br>at " . $edit_time; ?></p>
        <?php
        }
        ?>
        <p><?php echo make_clickable($details); ?></p>
    </div>
</body>

</html>