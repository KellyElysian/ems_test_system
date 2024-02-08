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
</head>

<body>

</body>

</html>