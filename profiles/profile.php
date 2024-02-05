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
$profile_query = mysqli_query($db_connection, "SELECT u.email AS email, u.siteRole AS role, CONCAT(m.firstName, ' ', m.lastName) AS full_name,
m.points AS points, m.status AS status, DATE_FORMAT(i.dateSignedUp, '%b %e, %Y') AS dateSign, i.notes AS notes
FROM e_User AS u
JOIN e_Member AS m ON m.uid = u.uid
JOIN e_Info AS i ON i.member_id = m.id
WHERE u.uid = $view_id");
$p_array = mysqli_fetch_assoc($profile_query);

// Assigning all information to appropriate information
$name = $p_array['full_name'];
$email = $p_array['email'];
$role = $p_array['role'];
$points = $p_array['points'];
// Used to determine what profile type is being dealt with. 1 = Active, 0 = Inactive
// Each type will show a different profile page based on that.
$status = $p_array['status'];
$dateSigned = $p_array['dateSign'];
$notes = strlen($p_array['notes']) != 0 ? $p_array['notes'] : "No additional notes at the moment.";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <h2><?php echo $name; ?></h2>
        <div class="normal">
            <div class="normal_info">
                <h3>Basic Information</h3>
                <?php
                // If the profile is active
                if ($status == 1) {
                ?>
                    <p>Email: <?php echo $email; ?></p>
                    <p>Role: <?php echo $role; ?></p>
                    <p>Points: <?php echo $points; ?></p>
                    <p>Member Status: <?php
                                        $status_message = $status == 1 ? '<span class="active">Active</span>' : '<span class="inactive">Inactive</span>';
                                        echo $status_message;
                                        ?></p>
                    <p>Date Signed Up: <?php echo $dateSigned; ?></p>
                <?php
                } else {
                ?>

                <?php
                }
                ?>
            </div>
            <div class="admin_info">
                <p><span class="bold">Additional Notes:</span><br>
                    <?php echo $notes; ?></p>
            </div>
        </div>

    </div>
</body>

</html>