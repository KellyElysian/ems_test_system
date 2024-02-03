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
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/createMember.php');
        die();
    }
}

$login_submit = $_POST['login'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>
    <?php
    require 'includes/navbar.php';
    ?>
    <div class="container">
        <?php
        // Deals with login logic prior because if user enters the wrong credentials, it'll display form without needing to redundantly use two
        // sections of a isset() where the form is present in both.
        if (isset($login_submit)) {
            // Login input variables
            $log_email = isset($_POST['login_email']) ? $_POST['login_email'] : null;
            $log_password = isset($_POST['login_password']) ? $_POST['login_password'] : null;

            // Checks if the inputted email exists in the system
            $email_query = mysqli_query($db_connection, "SELECT * FROM e_User WHERE email = '$log_email'");
            $email_array = mysqli_fetch_assoc($email_query);
            // Checks using a method that returns the number of rows (aka outputs) the query returned
            // Could also use $email_array['email'] == $log_email to check if the emails exists.
            if (mysqli_num_rows($email_query) > 0) {
                // Checks the password using the email array
                if ($email_array['password'] == $log_password) {
                    // Grabbing the member associated with the logged in user's id
                    $id = $email_array['uid'];
                    $user_profile_query = mysqli_query($db_connection, "SELECT * FROM e_Member WHERE uid = $id");
                    $user_profile_array = mysqli_fetch_assoc($user_profile_query);

                    // Assigning session variables for when the user logs in.
                    $_SESSION['user_id'] = $email_array['uid'];
                    $_SESSION['role'] = $email_array['siteRole'];
                    $_SESSION['member_id'] = $user_profile_array['id'];

                    echo '<p class="success">Login successful, you will be redirected to the home page shortly.</p>';

                    header('Refresh: 2; URL=https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
                    die();
                } else {
                    $password_check = true;
                }
            } else {
                $email_check = true;
            }
        }
        ?>
        <div class="form_container">
            <div>
                <form method="POST">
                    <?php
                    if (isset($email_check)) {
                        echo '<p class="error">Error: Email is not in our system.</p>';
                    } else if (isset($password_check)) {
                        echo '<p class="error">Error: Password is incorrect.</p>';
                    }
                    ?>

                    <div>
                        <label for="email">Email: </label>
                        <input type="email" name="login_email" minlength="5" maxlength="25" required>
                    </div>

                    <div>
                        <label for="password">Password:</label>
                        <input type="password" name="login_password" minlength="6" maxlength="25" required>
                    </div>

                    <input type="submit" name="login" class="submit login" value="Sign In">
                    <br><br>
                </form>
            </div>
            <div class="creation">
                <form action="terms.php" method="POST">
                    <div>
                        <label for="first_time">First Time User? Register Here</label>
                    </div>
                    <input type="submit" name="terms" class="submit register" value="Register">
                </form>
            </div>
        </div>
    </div>
</body>

</html>