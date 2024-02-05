<?php
// Automatically brings the config file
// This needs to be done to bring the absolute path to the includes directory file that is needed
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// Default Permissions
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
    die();
}

// Grabbing the user's id that the profile is associated with
$view_id = $_POST['view_user_id'];

// Grabbing all essential details to use for later use
$profile_query = mysqli_query($db_connection, "SELECT u.email, u.siteRole AS role, CONCAT(m.firstName, ' ', m.lastName) AS full_name,
m.points, m.status, i.dateSignedUp AS dateSign, i.notes
FROM e_User AS u
JOIN e_Member AS m ON m.uid = u.uid
JOIN e_Info AS i ON i.member_id = m.id
WHERE u.uid = $view_id");
$p_array = mysqli_fetch_assoc($profile_query);

// Assigning all information to appropriate information
$name = $p_array['full_name'];
$email = $p_array['u.email'];
$role = $p_array['role'];
$points = $p_array['m.points'];
// Used to determine what profile type is being dealt with. 1 = Active, 0 = Inactive
// Each type will show a different profile page based on that.
$status = $p_array['m.status'];
$dateSigned = $p_array['dateSign'];
$notes = $p_array['i.notes'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="../css/default.css">
    <!-- Navbar.css needs to be reloaded due to the directory levels being different -->
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <div class="normal">
        </div>

    </div>
</body>

</html>