<?php
// Automatically brings the config file
require 'includes/config.php';

// Default Permissions
// Checks if they're logged in
if (isset($_SESSION['user_id'])) {
    // Checks if they have created a profile (hence checking member_id session variable is set)
    if (isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
        die();
    } else {
        // If they force themselves onto this page and they've already created their user account but not their member, 
        // then redirects them to that page
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/createMember.php');
        die();
    }
}

$create_submit = $_POST['create'];

// Ensures user can not directly enter this createUser page if they haven't accepted the terms and conditions
if (!isset($_SESSION['term'])) {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login.php');
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registering User</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<body id="user">
    <?php
    require 'includes/navbar.php';
    // Checks if the user has submitted the page on the form, if they haven't display the form
    if (!isset($create_submit)) {
    ?>
        <div class="form_container">
            <div>
                <h2>User Registration Form</h2>
                <form method="POST">
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" minlength="1" maxlength="25" required>
                    </div>

                    <div>
                        <label for="terms">Password:</label>
                        <input type="text" name="password" placeholder="Please enter your password here." pattern="[a-zA-Z0-9]+" minlength="6" maxlength="25" required>
                    </div>

                    <input type="submit" name="create" class="submit register" value="Sign Up">
                    <br><br>
                </form>
            </div>
        </div>
    <?php
    }
    // If they have clicked "Sign Up", process the inputs into the mySQL database and redirects them to the createMember page
    else {
        // Puts the users associated email into an array to use inarray method to check if the user that is signing up should have a role of an admin 
        $admin_emails = array("admin1@iu.edu", "admin2@iu.edu");

        // Variables assigning the inputs being received from the form above
        $in_email = isset($_POST['email']) ? $_POST['email'] : null;
        $in_password = isset($_POST['password']) ? $_POST['password'] : null;
        // Checks if the inputted email is in the list of admin emails. This is using a ternary operator
        $in_site_role = in_array($in_email, $admin_emails) ? "Admin" : "Member";

        // Inserting it into mySQL database
        $insert_user = mysqli_query($db_connection, "INSERT INTO e_Users (email, password, siteRole) VALUES ($in_email, $in_password, $in_site_role)");

        // Grabs the registered user's id from the database and stores it as a session variable to be used everywhere
        $user_id_results = mysqli_query($db_connection, "SELECT uid FROM e_Users WHERE email='$in_email'");
        $user_info_array = mysqli_fetch_assoc($user_id_results);
        $_SESSION['user_id'] = $user_info_array['id'];

        // Tells user that the sign_up was successful and redirects them the member signup page
        echo '
        <div class="form_container">
            <h3 class="sign_up">User signup successful. Redirecting to member signup.</h3>
        </div>
        ';

        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/createMember.php');
        die();
    }
    ?>
</body>

</html>