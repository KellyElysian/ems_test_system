<?php
// Automatically brings the config file
require 'includes/config.php';

// Default Permissions
// Checks if they're logged in
if (isset($_SESSION['role'])) {
    // Checks if they have created a profile (hence checking member_id session variable is set)
    if ($_SESSION['role'] != "Admin") {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
        die();
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login.php');
    die();
}

$anno_submit = $_POST['anno_submit'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Announcement</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<body id="anno">
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <?php
        if (!isset($anno_submit)) {
        ?>
            <div class="form_container">
                <div>
                    <h2>Make Announcement Form</h2>
                    <form method="POST">
                        <div class="anno_divs title">
                            <label for="title">Announcement Title: </label>
                            <br>
                            <textarea name="title" id="title_box" cols="15" rows="5" class="title_box" required></textarea>
                        </div>
                        <div class="anno_divs">
                            <label for="details">Announcement Details:</label>
                            <br>
                            <textarea name="details" id="details_box" cols="30" rows="10" class="details_box" required></textarea>
                        </div>

                        <div>
                            <label for="agreement">Are all the details of the announcement confirmed?</label>
                            <br>
                            <div class="radio_container">
                                <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                                <label for="yesTerms">Yes</label>
                                <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                                <label for="noTerms">No</label>
                            </div>
                        </div>

                        <input type="submit" name="anno_submit" class="submit evt" id="anno_submit" style="display: none;" value="Create Event">
                    </form>
                </div>
            </div>
        <?php
        } else {

            // This function is used to purely "sanitize" or clean up inputs before submitting them into the database
            function san_input($input)
            {
                $sani = trim($input);
                $sani = stripslashes($sani);
                $sani = htmlspecialchars($sani);

                return $sani;
            }

            // Input Variables
            $anno_title = isset($_POST['title']) ? san_input($_POST['title']) : null;
            $anno_details = isset($_POST['details']) ? san_input($_POST['details']) : null;

            // Inserting the announcement into the database
            mysqli_query($db_connection, "INSERT INTO e_Announcement (title, dateTimeMade, details) VALUES
            ('$anno_title', NOW(), '$anno_details')");

            // Tells the admin that announcement has been made and redirects them back to the announcements page
            echo '
            <div class="form_container">
                <h3 class="complete">Announcement was made. Redirecting to announcement page.</h3>
            </div>
            ';

            header('Refresh: 2; URL=https://cgi.luddy.indiana.edu/~keldong/ems/annoBoard.php');
            die();
        }
        ?>
    </div>

    <script>
        // JS for making the submit button appear and disappear based on current user choice
        function radioHandler(src) {
            var button = document.getElementById("anno_submit");

            if (src.value == "Yes") {
                button.style.display = "block";
            } else if (src.value == "No") {
                button.style.display = "none";
            }
        }
    </script>
</body>

</html>