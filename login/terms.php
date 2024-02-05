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
    } else {
        // If they force themselves onto this page and they've already created their user account but not their member, 
        // then redirects them to that page
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    }
}

$term_submit = $_POST['term'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body id="terms">
    <?php
    require $dir . '/includes/navbar.php';
    ?>
    <div class="container">
        <?php
        // Checks if the user has submitted the page on the form, if they haven't display the form
        if (!isset($term_submit)) {
        ?>
            <div class="form_container">
                <div>
                    <form method="POST">
                        <div>
                            <p class="doc">
                                Terms and Conditions 1
                            </p>
                            <label for="terms">Do you accept?</label>
                            <input type="radio" name="terms1" id="terms" value="Yes" required>
                            <label for="yesTerms">Yes</label>
                            <input type="radio" name="terms1" id="terms" value="No">
                            <label for="noTerms">No</label>
                        </div>

                        <div>
                            <p class="doc">
                                Terms and Conditions 2
                            </p>
                            <label for="terms">Do you accept?</label>
                            <input type="radio" name="terms2" id="terms" value="Yes" required>
                            <label for="yesTerms">Yes</label>
                            <input type="radio" name="terms2" id="terms" value="No">
                            <label for="noTerms">No</label>
                        </div>

                        <div>
                            <p class="doc">
                                Terms and Conditions 3
                            </p>
                            <label for="terms">Do you accept?</label>
                            <input type="radio" name="terms3" id="terms" value="Yes" required>
                            <label for="yesTerms">Yes</label>
                            <input type="radio" name="terms3" id="terms" value="No">
                            <label for="noTerms">No</label>
                        </div>

                        <input type="submit" name="term" class="submit register" value="Proceed">
                        <br><br>
                    </form>
                </div>
            </div>
        <?php
        }
        // If they have clicked "proceed", process the choices and conditionally perform what happens next 
        else {
            // Assigning the choices to an array that stores all the choices
            $terms = array($_POST['terms1'], $_POST['terms2'], $_POST['terms3']);

            // Checks if "No" exists in the array, resulting in either user can continue to the next page or get denied.
            if (in_array("No", $terms)) {
                echo '<p class="denied">You chose "No" to one of our Terms and Conditions. 
                Therefore, you are unable to progress further in the sign-up process.</p>';
                echo '<p class="denied">You will be redirected to our home page in 5 seconds.</p>';

                header('Refresh: 5; URL=https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
                die();
            } else {
                $_SESSION['term'] = 1;
                header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createUser.php');
                die();
            }
        }
        ?>
    </div>
</body>

</html>