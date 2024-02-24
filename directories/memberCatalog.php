<?php
// Automatically brings the config file
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// Default Permissions
// Checks if they're logged in
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    } else {
        if ($member_status != "Active") {
            $_SESSION['reactivate'] = 1;
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
            die();
        } else {
            if ($user_role != "Admin") {
                $_SESSION['no_perms'] = 1;
                header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
                die();
            }
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
    <title>Member Catalog</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/directory.css">
</head>

<body id="member">
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <?php
        // A query that gets the basic information about every member
        $member_query = mysqli_query($db_connection, "SELECT u.uid AS user_id, m.firstName AS fname, m.lastName AS lname, u.email AS email, DATE_FORMAT(i.dateSignedUp, '%c-%e-%y')AS dateSign
        FROM e_User AS u
        JOIN e_Member AS m ON m.uid = u.uid
        JOIN e_Info AS i ON i.member_id = m.id");

        $id_index = 0;
        // Displays every member
        while ($member_info = mysqli_fetch_assoc($member_query)) {
            $fname = $member_info['fname'];
            $lname = $member_info['lname'];
            $email = $member_info['email'];
            $date = $member_info['dateSign'];
            $user_id = $member_info['user_id'];

            // This is a incrementing index number that is used to keep all member's hidden form field to yield different ids
            $id_index++;

            echo '<div class="member_block">';
            echo '
            <form method="POST" action="https://cgi.luddy.indiana.edu/~keldong/ems/profiles/profile.php" class="invis" id="member' . $id_index . '" name="member' . $id_index . '">
                <input type="hidden" value="' . $user_id . '" name="view_user_id"/> 
            </form>
            ';
            echo "
            <button value='$id_index' form='member" . $id_index . "'><p class='button_p'>$fname<br>$lname</p></button>
            <div class='add_info'>
                <p>$email</p>
                <p>Signed Up: $date</p>
            </div>
            ";
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>