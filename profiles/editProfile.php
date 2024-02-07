<?php
// Automatically brings the config file
// This needs to be done to bring the absolute path to the includes directory file that is needed
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

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
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
    echo
    '
    <script>
        alert("Ask an admin to reactivate your member status!");
    </script>
    ';
    die();
}

$edit_submit = $_POST['edit_submit'];
if (!isset($edit_submit)) {
    $_SESSION['edit_id'] = $_POST['user_id'];
}

// UserID that is being edited
$view_id = $_SESSION['edit_id'];

// Grabbing all essential details to use for later use
$profile_query = mysqli_query($db_connection, "SELECT m.id AS mid, u.email AS email, u.siteRole AS role, m.firstName AS firstname, m.lastName AS lastname,
m.points AS points, m.status AS status, DATE_FORMAT(i.dateSignedUp, '%b %e, %Y') AS dateSign, i.notes AS notes
FROM e_User AS u
JOIN e_Member AS m ON m.uid = u.uid
JOIN e_Info AS i ON i.member_id = m.id
WHERE u.uid = $view_id");
$p_array = mysqli_fetch_assoc($profile_query);

// Member id
$mem_id = $p_array['mid'];

// Assigning all information to appropriate information
$fname = $p_array['firstname'];
$lname = $p_array['lastname'];
$name = $fname . " " . $lname;
$email = $p_array['email'];
$role = $p_array['role'];
$points = $p_array['points'];
// Used to determine what profile type is being dealt with. 1 = Active, 0 = Inactive
// Each type will show a different profile page based on that.
$status = $p_array['status'];
$radio_select = $status == 1 ? $act = "checked" : $inact = "checked";
$dateSigned = $p_array['dateSign'];
$notes = strlen($p_array['notes']) != 0 ? $p_array['notes'] : "No additional notes at the moment.";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name . "'s Profile"; ?></title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body id="edit">
    <?php
    require $dir . '/includes/navbar.php';
    ?>
    <div class="container">
        <?php
        // If the confirm edit button is not clicked yet 
        if (!isset($edit_submit)) {
        ?>
            <h2>Editing <?php echo $name; ?>'s Profile</h2>
            <form method="POST">
                <h3 class="basic_header">Basic Information</h3>
                <div class="input_blocks">
                    <label for="firstname">First Name: </label>
                    <br>
                    <input type="text" name="firstname" minlength="1" maxlength="20" pattern="^[a-zA-Z]+$" value="<?php echo $fname; ?>" required>
                </div>
                <div class="input_blocks">
                    <label for="lastname">Last Name: </label>
                    <br>
                    <input type="text" name="lastname" minlength="1" maxlength="30" pattern="^[a-zA-Z]+$" value="<?php echo $lname; ?>" required>
                </div>
                <?php
                // If they are an admin, allow the following form fields to show
                if ($user_role == "Admin") {
                ?>
                    <div class="input_blocks">
                        <label for="points">Points: </label>
                        <br>
                        <input type="number" name="points" min="1" pattern="^[1-9]+$" value="<?php echo $points; ?>" required>
                    </div>
                    <div class="input_blocks">
                        <label for="points">Activity Status:</label>
                        <br>
                        <input type="radio" name="activity" value="1" <?php echo $act; ?> required>
                        <label for="active">Active</label>
                        <input type="radio" name="activity" value="0" <?php echo $inact; ?> required>
                        <label for="Inactive">Inactive</label>
                    </div>

                    <h3 class="admin_header">Admin Information</h3>
                    <div class="input_blocks">
                        <label for="notes">Additional Notes: </label>
                        <br>
                        <textarea name="notes" id="notes" cols="20" rows="7" class="note_area" required><?php echo $notes; ?></textarea>
                    </div>

                <?php
                }
                ?>
                <div class="input_blocks radio_block">
                    <label for="agreement">Confirm the edits you have made:</label>
                    <div class="radio_container">
                        <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                        <label for="yesTerms">Yes</label>
                        <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                        <label for="noTerms">No</label>
                    </div>
                    <button type="submit" name="edit_submit" id="edit_submit" class="edit_button" style="display: none;">Submit Edit</button>
                </div>


            </form>
        <?php
            // If the edit submit button has been submitted.
        } else {

            // This function is used to purely "sanitize" or clean up inputs before submitting them into the database
            function san_input($input)
            {
                $sani = trim($input);
                $sani = stripslashes($sani);
                $sani = htmlspecialchars($sani);

                return $sani;
            }

            // Assigning all inputs to variables
            $e_fname = isset($_POST['firstname']) ? san_input($_POST['firstname']) : null;
            $e_lname = isset($_POST['lastname']) ? san_input($_POST['lastname']) : null;
            $e_points = isset($_POST['points']) ? san_input($_POST['points']) : null;
            $e_status = $_POST['activity'];
            $e_notes  = isset($_POST['notes']) ? san_input($_POST['notes']) : null;

            // Update statements to update all information
            mysqli_query($db_connection, "UPDATE e_Member SET firstname = '$e_fname' WHERE id = $mem_id");
            mysqli_query($db_connection, "UPDATE e_Member SET lastname = '$e_lname' WHERE id = $mem_id");
            mysqli_query($db_connection, "UPDATE e_Member SET points = $e_points WHERE id = $mem_id");
            mysqli_query($db_connection, "UPDATE e_Member SET status = $e_status WHERE id = $mem_id");
            mysqli_query($db_connection, "UPDATE e_Info SET notes = '$e_notes' WHERE member_id = $mem_id");

            // Adding the edit into the edit history
            mysqli_query($db_connection, "INSERT INTO e_Member_Edit (editor_id, member_edited, editTime) VALUES
            ($member_id, $mem_id, NOW())");

            // Tells the user that the changes has been made
            echo '
            <script>
                alert("The edits has been successfully processed.");
            </script>
            ';

            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/profiles/profile.php');
            die();
        }
        ?>

    </div>

    <script>
        // JS for making the submit button appear and disappear based on current user choice
        function radioHandler(src) {
            var button = document.getElementById("edit_submit");

            if (src.value == "Yes") {
                button.style.display = "block";
            } else if (src.value == "No") {
                button.style.display = "none";
            }
        }
    </script>
</body>

</html>