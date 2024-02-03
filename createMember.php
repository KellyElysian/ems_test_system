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
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login.php');
    die();
}

// Common SESSION variables that are always used.
$user_id = $_SESSION['user_id'];

$member_submit = $_POST['member'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registering Member</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<body id="member">
    <?php
    require 'includes/navbar.php';
    // Checks if the user has submitted the page on the form, if they haven't display the form
    if (!isset($member_submit)) {
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
                            <label for="agreement">By choosing yes, you agreed to the previous <br>
                                terms and conditions and the rules of our agency.</label>
                            <br>
                            <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                            <label for="yesTerms">Yes</label>
                            <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                            <label for="noTerms">No</label>
                        </div>

                        <input type="submit" name="member" class="submit register" id="member" value="Finish Registration">
                        <br><br>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    // If they have clicked "Finish Registration", 
    else {
    }
    ?>

    <script>
        // JS for disabling and enabling button based on user choice
        function radioHandler(src) {
            let submitButton = document.getElementById('member');

            if (src.value == "Yes") {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }
    </script>
</body>

</html>

</html>