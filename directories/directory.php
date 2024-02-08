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
    <title>Directory Catalogs</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/directory.css">
</head>

<body>
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">

        <div class="options">
            <h2 class="catalog_headers">Members</h2>
            <p class="catalog_desc">Click on the button below to view the catalog of all users that are registered within this system.</p>
            <form action="https://cgi.luddy.indiana.edu/~keldong/ems/directories/memberCatalog.php" method="post" class="form_block">
                <button class="catalog_button">Member Catalog</button>
            </form>
        </div>

        <div class="options">
            <h2 class="catalog_headers">Events</h2>
            <p class="catalog_desc">Click on the button below to view the catalog of all events (past or future) that are registered within this system.</p>
            <form action="https://cgi.luddy.indiana.edu/~keldong/ems/directories/eventCatalog.php" method="post" class="form_block">
                <button class="catalog_button">Event Catalog</button>
            </form>
        </div>

    </div>
</body>

</html>