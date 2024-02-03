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
    } else {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/createProfile.php');
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
        <div class="form_container">
            <div>
                <form method="POST">
                    <div>
                        <label for="email">Email: </label>
                        <input type="email" minlength="5" maxlength="25" required>
                    </div>

                    <div>
                        <label for="password">Password:</label>
                        <input type="password" minlength="6" maxlength="25" required>
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