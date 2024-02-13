<?php
// Automatically brings the config file
require 'includes/config.php';

if ($_SESSION['reactivate'] == 1) {
    echo '
    <script>
        alert("Ask an admin to reactivate your member status!");
    </script>';
    unset($_SESSION['reactivate']);
}
if ($_SESSION['no_perms'] == 1) {
    echo '
    <script>
        alert("You do not have permissions to view that page!");
    </script>';
    unset($_SESSION['no_perms']);
}

/**
 * Following code block is reused from profile.php to determine if the logged in user should have
 * notices about their certications expiring soon.
 */
// Grabs the CPR certification information if available.
$cpr_cert_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign WHERE member_id = $member_id AND cert_id = 99");
$cpr_cert_arr = mysqli_fetch_assoc($cpr_cert_query);
// Following deals with setting the expiration date and current date to get days till one's cert expires
date_default_timezone_set('America/Indiana/Indianapolis');
$expireDateDisplay = new DateTimeImmutable($cpr_cert_arr['expireDate']);
$expireDate = new DateTime($cpr_cert_arr['expireDate']);
$dateNow = date('Y-m-d');
$dateTimeNow = new DateTime($dateNow);
$tillExpire = $expireDate->diff($dateTimeNow)->format("%a");

// Grabs the other certification needed for runs and stuff
$other_cert_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign AS ca
JOIN e_Cert AS c ON c.id = ca.cert_id
WHERE member_id = $member_id AND cert_id != 99");
$other_cert_arr = mysqli_fetch_assoc($other_cert_query);
$other_cert_name = $other_cert_arr['name'];
// Following deals with setting the expiration date and current date to get days till one's cert expires
date_default_timezone_set('America/Indiana/Indianapolis');
$o_expireDate = new DateTime($other_cert_arr['expireDate']);
$o_expireDateDisplay = new DateTimeImmutable(($other_cert_arr['expireDate']));
$o_dateNow = date('Y-m-d');
$o_dateTimeNow = new DateTime($o_dateNow);
$o_tillExpire = $o_expireDate->diff($o_dateTimeNow)->format("%a");


// Code block following with deal with storing a user's cert information in the session.
// Checks if both certs are in the system
if (mysqli_num_rows($cpr_cert_query) > 0 and mysqli_num_rows($other_cert_query) > 0) {
    // Check if both certs are not expired
    if ($tillExpire > 0 and $o_tillExpire > 0) {
        // If both are not expired, sets valid certs to 1 (true) and sets a session variable for tracking
        // what other cert besides the CPR the user has.
        $_SESSION['certs_valid'] = 1;
        $_SESSION['event_cert'] = $other_cert_arr['id'];
    } else {
        // If one is expired, sets valid certs to 0, which won't allot them to go on runs
        $_SESSION['certs_valid'] = 0;
    }
} else {
    // If one of the certs are not in the system yet, certs are not valid.
    $_SESSION['certs_valid'] = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Home main stylesheet -->
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php require 'includes/navbar.php'; ?>
    <div class="main_container">
        <div class="notice_container">
            <?php
            if (mysqli_num_rows($cpr_cert_query) > 0) {
                if ($tillExpire <= 30 and $tillExpire > 0) {
            ?>
                    <div class="warning_alert">
                        <h4>CPR certification is expiring soon</h4>
                        <p>Your CPR certification will expire in <?php echo $tillExpire . ' days on ' . $expireDateDisplay->format('n-j-y'); ?>.</p>
                    </div>
                <?php
                } else if ($tillExpire <= 0) {
                ?>
                    <div class="expired_alert">
                        <h4>CPR certification is expired now</h4>
                        <p>Your CPR certification expired on <?php echo $expireDateDisplay->format('n-j-y'); ?>.</p>
                        <p>Please ensure to renew it or you will be unable to go on runs!</p>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="warning_alert">
                    <h4>Your CPR certification is not yet set</h4>
                    <p>Please contact an admin as soon as possible to help set up your CPR certification.</p>
                </div>
                <?php
            }
            if (mysqli_num_rows($other_cert_query) > 0) {
                if ($o_tillExpire <= 30 and $o_tillExpire > 0) {
                ?>
                    <div class="warning_alert">
                        <h4><?php echo $other_cert_name; ?> certification is expiring soon</h4>
                        <p>Your <?php echo $other_cert_name; ?> certification will expire in <?php echo $o_tillExpire . ' days on ' . $o_expireDateDisplay->format('n-j-y'); ?>.</p>
                    </div>
                <?php
                } else if ($o_tillExpire <= 0) {
                ?>
                    <div class="expired_alert">
                        <h4><?php echo $other_cert_name; ?> certification is expired now</h4>
                        <p>Your <?php echo $other_cert_name; ?> certification expired on <?php echo $o_expireDateDisplay->format('n-j-y'); ?>.</p>
                        <p>Please ensure to renew it or you will be unable to go on runs!</p>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="warning_alert">
                    <h4>Your event/run certification is not yet set</h4>
                    <p>Please contact an admin as soon as possible to help set up your event/run certification.</p>
                </div>
            <?php
            }
            ?>
        </div>

        <h1>EMS Home Page</h1>

        <div class="info_container">
            <p>
                Welcome to this mock/prototype version of a EMS website.
                This is the home page.
            </p>
            <p>
                To proceed, please click the button login button on the navigation bar!
            </p>
        </div>
    </div>
</body>

</html>