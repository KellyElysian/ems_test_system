<?php
// Automatically brings the config file
// This needs to be done to bring the absolute path to the includes directory file that is needed
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// This file is one that contains the use of an existing framework used to convert links from regular text input
require $dir . '/frameworks/links.php';

// Default Permissions
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

// Checking if the session variable for specific profile is set, if it is, use that instead unsets it, otherwise user the user_id given
// by the form from wherever the user clicked from.
// Grabbing the user's id that the profile is associated with
if (isset($_SESSION['edit_id'])) {
    $view_id = $_SESSION['edit_id'];
    unset($_SESSION['edit_id']);
} else {
    $view_id = $_POST['view_user_id'];
}

// Extra conditional error check
if ($view_id == "") {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
    die();
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

// Grabs the CPR certification information if available.
$cpr_cert_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign WHERE member_id = $mem_id AND cert_id = 99");
$cpr_cert_arr = mysqli_fetch_assoc($cpr_cert_query);
// Following deals with setting the expiration date and current date to get days till one's cert expires
date_default_timezone_set('America/Indiana/Indianapolis');
$expireDate = new DateTime($cpr_cert_arr['expireDate']);
$dateNow = date('Y-m-d');
$dateTimeNow = new DateTime($dateNow);
$tillExpire = $expireDate->diff($dateTimeNow)->format("%a");


// Grabs the other certification needed for runs and stuff
$other_cert_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign AS ca
JOIN e_Cert AS c ON c.id = ca.cert_id
WHERE member_id = $mem_id AND cert_id != 99");
$other_cert_arr = mysqli_fetch_assoc($other_cert_query);
$other_cert_name = $other_cert_arr['name'];
// Following deals with setting the expiration date and current date to get days till one's cert expires
date_default_timezone_set('America/Indiana/Indianapolis');
$o_expireDate = new DateTime($other_cert_arr['expireDate']);
$o_dateNow = date('Y-m-d');
$o_dateTimeNow = new DateTime($o_dateNow);
$o_tillExpire = $o_expireDate->diff($o_dateTimeNow)->format("%a");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="../css/default.css">
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
                        // Atm, being inactive shows nothing since they're inactive.
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
            <div class="certs_con">
                <h3>Certification Information</h3>
                <div class="inner_cert_con">
                    <?php
                    if ($status == 1) {
                    ?>
                        <div class="certs cpr-cert">
                            <h4>CPR Certification</h4>
                            <p>Issued Date: <?php echo $cpr_cert_arr['startDate'] == "" ? "Not Set" : $cpr_cert_arr['startDate']; ?></p>
                            <p>Expiration Date: <?php echo $cpr_cert_arr['expireDate'] == "" ? "Not Set" : $cpr_cert_arr['expireDate']; ?></p>
                            <p>Days till expiration:<span class="<?php
                                                                    if ($tillExpire > 30) {
                                                                        echo "safe";
                                                                    } else if ($tillExpire <= 30 and $tillExpire > 0) {
                                                                        echo "soon";
                                                                    } else {
                                                                        echo "expired";
                                                                    }
                                                                    ?>">
                                    <?php echo $tillExpire; ?>
                                </span>
                            </p>
                        </div>
                        <div class="certs other-cert">
                            <h4> <?php echo $other_cert_name == "" ? "Other " : $other_cert_name; ?> Certification</h4>
                            <p>Issued Date: <?php echo $other_cert_arr['startDate'] == "" ? "Not Set" : $other_cert_arr['startDate']; ?></p>
                            <p>Expiration Date: <?php echo $other_cert_arr['expireDate'] == "" ? "Not Set" : $other_cert_arr['expireDate']; ?></p>
                            <p>Days till expiration:<span class="<?php
                                                                    if ($o_tillExpire > 30) {
                                                                        echo "safe";
                                                                    } else if ($o_tillExpire <= 30 and $o_tillExpire > 0) {
                                                                        echo "soon";
                                                                    } else {
                                                                        echo "expired";
                                                                    }
                                                                    ?>">
                                    <?php echo $o_tillExpire; ?>
                                </span>
                            </p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>
        <div class="right_container">
            <?php
            // By default, only the latest edit will be shown

            // Grabbing information from database about the edit and the editor, limits to the latest (ORDER BY time and LIMIT 1) edit made to the member
            $edit_query = mysqli_query($db_connection, "SELECT editor_id, DATE_FORMAT(editTime, '%c-%e-%Y %H:%i') AS edit_time FROM e_Member_Edit
                    WHERE member_edited = $mem_id
                    ORDER BY editTime DESC
                    LIMIT 1");
            $editing_arr = mysqli_fetch_assoc($edit_query);
            $post_edit_date = $editing_arr["edit_time"];
            $editor_id = $editing_arr["editor_id"];

            // Finding the editor's name
            $editor_query = mysqli_query($db_connection, "SELECT CONCAT(firstName, ' ', lastName) AS fullname FROM e_Member WHERE id = $editor_id");
            $editor_arr = mysqli_fetch_assoc($editor_query);
            $editor_name = $editor_arr['fullname'];
            // echo mysqli_num_rows($editor_query);


            // Ternary string operator
            $edit_string = mysqli_num_rows($editor_query) > 0 ? $editor_name . ' at ' . $post_edit_date : 'No last edit present.';

            // Initial view of the edit profile
            echo '<button id="view_single" value="1" onclick="fullEditHistory(this)">View Full Edit History</button>';
            echo '
                <p class="last_edit" id="single_edit">
                Last Edit Made By: <br>
                ' . $edit_string . '
                </p>
                ';

            // Full history after the clickable link is clicked
            echo '<button id="view_full" value="2" onclick="fullEditHistory(this)">View Latest Edit</button>';

            echo '<div id="full_history">';
            // Grabs all edits that have been made to the member and order them chronologically
            $multi_edit_query = mysqli_query($db_connection, "SELECT editor_id AS e_id, DATE_FORMAT(editTime, '%c-%e-%Y %H:%i') AS edit_time FROM e_Member_Edit
                    WHERE member_edited = $mem_id
                    ORDER BY editTime DESC");

            // If there is no edit at all, display default
            if (mysqli_num_rows($multi_edit_query) == 0) {
                echo '
                <p class="last_edit" id="single_edit">
                Last Edit Made By: <br>
                No edits are made on this member.
                </p>
                ';
            }

            // Loops through all entries in the edit history table and grabs the names of the editor also and displays them.
            while ($edit_arr = mysqli_fetch_assoc($multi_edit_query)) {
                $m_edit_time = $edit_arr['edit_time'];
                $m_editor_id = $edit_arr['e_id'];

                $m_editor_query = mysqli_query($db_connection, "SELECT CONCAT(firstName, ' ', lastName) AS fullname FROM e_Member WHERE id = $m_editor_id");
                $m_editor_arr = mysqli_fetch_assoc($m_editor_query);
                $m_editor_name = $m_editor_arr['fullname'];

                $edit_string = $m_editor_name . ' at ' . $m_edit_time;
                echo '
                <p id="single_edit">
                Last Edit Made By: <br>
                ' . $edit_string . '
                </p>
                ';
            }

            echo '</div>';
            ?>
        </div>
    </div>

    <script>
        // Hides element on window loading
        window.onload = function hide() {
            document.getElementById("full_history").style.display = 'none';
            document.getElementById("view_full").style.display = 'none';
        }

        function fullEditHistory(elem) {
            let single = document.getElementById("single_edit");
            let view_full = document.getElementById("view_full");
            let view_single = document.getElementById("view_single");
            let full = document.getElementById("full_history");

            if (elem.value == 1) {
                view_single.style.display = 'none';
                single.style.display = 'none';
                view_full.style.display = 'block';
                full.style.display = 'block';
            } else {
                view_single.style.display = 'block';
                single.style.display = 'block';
                view_full.style.display = 'none';
                full.style.display = 'none';
            }
        }
    </script>
</body>

</html>