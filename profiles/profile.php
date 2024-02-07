<?php
// Automatically brings the config file
// This needs to be done to bring the absolute path to the includes directory file that is needed
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// This file is one that contains the use of an existing framework used to convert links from regular text input
require $dir . '/frameworks/links.php';

// Default Permissions
if ($member_status == "Active") {
    if (isset($_SESSION['user_id'])) {
        if (!isset($_SESSION['member_id'])) {
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
            die();
        }
    } else {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
        die();
    }
} else {
    echo
    '
    <script>
        alert("Ask an admin to reactivate your member status!");
    </script>
    ';
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
    die();
}

// Checking if the session variable for specific profile is set, if it is, use that instead unsets it
// Grabbing the user's id that the profile is associated with
if (isset($_SESSION['edit_id'])) {
    $view_id = $_SESSION['edit_id'];
    unset($_SESSION['edit_id']);
} else {
    $view_id = $_POST['view_user_id'];
}


// Grabbing all essential details to use for later use
$profile_query = mysqli_query($db_connection, "SELECT m.id AS mid, u.email AS email, u.siteRole AS role, CONCAT(m.firstName, ' ', m.lastName) AS full_name,
m.points AS points, m.status AS status, DATE_FORMAT(i.dateSignedUp, '%b %e, %Y') AS dateSign, i.notes AS notes
FROM e_User AS u
JOIN e_Member AS m ON m.uid = u.uid
JOIN e_Info AS i ON i.member_id = m.id
WHERE u.uid = $view_id");
$p_array = mysqli_fetch_assoc($profile_query);

$mem_id = $p_array['mid'];
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
    <div class="overall_container">
        <div class="left_container">

        </div>
        <div class="mid_container">
            <h2><?php echo $name; ?></h2>
            <?php
            if ($user_role == "Admin" || $user_id == $view_id) {
                echo '
            <form class="edit_form" method="POST" action="https://cgi.luddy.indiana.edu/~keldong/ems/profiles/editProfile.php">
                <input type="hidden" value="' . $view_id . '" name="user_id">
                <button type="submit" class="edit_button">Edit Profile</button>
            </form>
            ';
            }
            ?>
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
                <?php
                if ($user_role == "Admin") {
                ?>
                    <div class="admin_info">
                        <h3>Admin Information</h3>
                        <p><span class="bold">Additional Notes:</span><br>
                            <?php echo make_clickable($notes); ?></p>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>
        <div class="right_container">
            <?php
            // By default, only the latest edit will be shown
            if (!isset($edit_history)) {
                // Grabbing information from database about the edit and the editor
                $edit_query = mysqli_query($db_connection, "SELECT editor_id AS e_id, DATE_FORMAT(editTime, '%c-%e-%Y %l:%i') AS edit_time FROM e_Member_Edit WHERE member_edited = $mem_id
                ORDER BY editTime
                LIMIT 1");
                $edit_array = mysqli_fetch_assoc($edit_array);
                $edit_date = $edit_array['edit_time'];
                $editor_id = $edit_array['e_id'];

                $editor_query = mysqli_query($db_connection, "SELECT CONCAT(firstName, ' ', lastName) AS fullname FROM e_Member WHERE id = $editor_id");
                $editor_arr = mysqli_fetch_assoc($editor_query);
                $editor_name = $editor_arr['fullname'];

                echo '
                <p class="last_edit">
                Last Edit Made By: <br>
                ' . $editor_name . ' at ' . $edit_Date . '
                </p>
                ';
            } else {
            }
            ?>
        </div>
    </div>
</body>

</html>