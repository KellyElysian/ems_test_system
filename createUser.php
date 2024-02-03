<?php
// Automatically brings the config file
require 'includes/config.php';

// Default Permissions
// Checks if they're logged in
if (isset($_SESSION['login_id'])) {
    // Checks if they have created a profile (hence checking member_id session variable is set)
    if (isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
        die();
    }
}

$create_submit = $_POST['create'];

// Ensures user can not directly enter this createUser page if they haven't accepted the terms and conditions
if (!isset($_SESSION['term'])) {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login.php');
    die();
}
// unset($_SESSION['term']);
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

                    <input type="submit" name="create" class="submit register" value="Create User">
                    <br><br>
                </form>
            </div>
        </div>
    <?php
    }
    // If they have clicked "proceed", process the choices and conditionally perform what happens next 
    else {
        // Puts the users associated email into an array to use inarray method to check if the user that is signing up should have a role of an admin 
        $admin_emails = array("admin1@iu.edu", "admin2@iu.edu");

        // Variables assigning the inputs being received from the form above
        $in_email = isset($_POST['email']) ? $_POST['email'] : null;
        $in_password = isset($_POST['password']) ? $_POST['password'] : null;
        // Checks if the inputted email is in the list of admin emails. This is using a ternary operator
        $in_site_role = in_array($in_email, $admin_emails) ? "Admin" : "Member";

        //
    }
    ?>
</body>

</html>