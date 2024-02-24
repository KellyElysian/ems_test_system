<?php
// Automatically brings the config file
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';


// Default Permissions
// Checks if they're logged in
if (isset($_SESSION['user_id'])) {
    // Checks if they have created a profile (hence checking member_id session variable is set)
    if (isset($_SESSION['member_id'])) {
        header("Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php");
        die();
    }
}

$reg_submit = $_POST['reg'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registering Member</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body id="member">
    <?php
    require $dir . '/includes/navbar.php';
    // Checks if the user has submitted the page on the form, if they haven't display the form
    if (!isset($reg_submit)) {
    ?>
        <div class="container">
            <div class="form_container">
                <div>
                    <h2>Member Registration Form</h2>
                    <form method="POST">
                        <div>
                            <label for="firstname">First Name:</label>
                            <input type="text" name="firstname" minlength="1" maxlength="20" pattern="^[a-zA-Z]+$" required>
                        </div>

                        <div>
                            <label for="firstname">Last Name:</label>
                            <input type="text" name="lastname" minlength="1" maxlength="30" pattern="^[a-zA-Z]+$" required>
                        </div>

                        <div>
                            <label for="agreement">By choosing "Yes", you agree to the previous <br>
                                terms and conditions and the rules of our agency.</label>
                            <br>
                            <div class="radio_container">
                                <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                                <label for="yesTerms">Yes</label>
                                <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                                <label for="noTerms">No</label>
                            </div>
                        </div>

                        <input type="submit" name="reg" class="submit register" id="reg" style="display: none;" value="Finish Registration">
                        <br><br>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    // If they have clicked "Finish Registration", processes the inputs on the same page
    else {
        // Variables assigning the inputs being received from the form above
        $in_first_name = isset($_POST['firstname']) ? $_POST['firstname'] : null;
        $in_last_name = isset($_POST['lastname']) ? $_POST['lastname'] : null;
        // Default 'points' in point system
        $default_points = 200;

        // Inserting it into mySQL database
        mysqli_query($db_connection, "INSERT INTO e_Member (firstName, lastName, points, status, uid) VALUES 
        ('$in_first_name', '$in_last_name', $default_points, 1, $user_id)");

        // Grab the newly inserted member's id and fill in additional info about them
        $member_id_query = mysqli_query($db_connection, "SELECT id FROM e_Member WHERE uid = $user_id");
        $member_info_array = mysqli_fetch_assoc($member_id_query);
        // Assigns the session's logged in member to this id.
        $_SESSION['member_id'] = $member_info_array['id'];

        // Inserts the additional information about the member
        $id = $_SESSION['member_id'];
        mysqli_query($db_connection, "INSERT INTO e_Info (member_id, dateSignedUp) VALUES
        ($id, CURDATE())");

        // Grabbing the user's role and assigning it to a session variable.
        $user_id_results = mysqli_query($db_connection, "SELECT siteRole FROM e_User WHERE uid=$user_id");
        $user_info_array = mysqli_fetch_assoc($user_id_results);
        $_SESSION['role'] = $user_info_array['siteRole'];

        // Tells user that the sign_up was successful and redirects them the member signup page
        echo '
        <div class="form_container">
            <h3 class="sign_up">Member signup successful. Redirecting to home page.</h3>
        </div>
        ';

        header("Refresh: 3; URL=https://cgi.luddy.indiana.edu/~keldong/ems/home.php");
        die();
    }
    ?>

    <script>
        // JS for making the submit button appear and disappear based on current user choice
        function radioHandler(src) {
            var button = document.getElementById("reg");

            if (src.value == "Yes") {
                button.style.display = "block";
            } else if (src.value == "No") {
                button.style.display = "none";
            }
        }
    </script>
</body>

</html>

</html>