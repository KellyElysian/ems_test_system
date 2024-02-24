<?php
// Automatically brings the config file
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// Default Permissions
// Checks if they're logged in
if ($member_status == "Active") {
    if (isset($_SESSION['role'])) {
        // Checks if they have created a profile (hence checking member_id session variable is set)
        if ($_SESSION['role'] != "Admin") {
            $_SESSION['no_perms'] = 1;
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
            die();
        }
    } else {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
        die();
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
    die();
}

// Fetching pre-fill data about the announcement
$anno_id = $_POST['anno_id'];
$anno_query = mysqli_query($db_connection, "SELECT * FROM e_Announcement WHERE id = $anno_id");
$anno_arr = mysqli_fetch_assoc($anno_query);
// Variables prefill with prior announcement values
$title = $anno_arr['title'];
$details = $anno_arr['details'];

$anno_edit_submit = $_POST['anno_edit_submit'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing Announcement</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body id="anno">
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <?php
        if (!isset($anno_edit_submit)) {
        ?>
            <div class="form_container">
                <div class="inner_container">
                    <h2>Editing Announcement Form</h2>
                    <form method="POST">
                        <div class="anno_divs title">
                            <label for="title">Announcement Title: </label>
                            <br>
                            <textarea name="title" id="title_box" cols="15" rows="5" class="title_box" required><?php echo $title; ?></textarea>
                        </div>
                        <div class="anno_divs">
                            <label for="details">Announcement Details:</label>
                            <br>
                            <textarea name="details" id="details_box" cols="30" rows="10" class="details_box" required><?php echo $details; ?></textarea>
                        </div>

                        <div>
                            <label for="agreement">Are all the edits of the announcement confirmed?</label>
                            <br>
                            <div class="radio_container">
                                <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                                <label for="yesTerms">Yes</label>
                                <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                                <label for="noTerms">No</label>
                            </div>
                        </div>

                        <input type="hidden" name="anno_id" value="<?php echo $anno_id; ?>">
                        <input type="submit" name="anno_edit_submit" class="submit e_ano" id="edit_submit" style="display: none;" value="Confirm Edits">
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
            $anno_title = isset($_POST['title']) ? nl2br(san_input($_POST['title'])) : null;
            $anno_details = isset($_POST['details']) ? nl2br(san_input($_POST['details'])) : null;

            // Updating the old values
            mysqli_query($db_connection, "UPDATE e_Announcement SET title = '$anno_title' WHERE id = $anno_id");
            mysqli_query($db_connection, "UPDATE e_Announcement SET details = '$anno_details' WHERE id = $anno_id   ");

            // Adding an entry to showing the person who just edited the specific announcement
            mysqli_query($db_connection, "INSERT INTO e_Anno_Edit (member_id, anno_id, editTime) VALUES
            ($member_id, $anno_id, NOW())");

            // Redirects user
            $_SESSION['temp_anno_id'] = $anno_id;
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/annos/anno.php');
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

    <?php
    // Reason why it needs to placed down here is because it'll interfere with editing process if it's placed
    // at the beginning. This still performs the necessary function of redirecting if no edit_anno is set.

    // If an a user comes to this page directly without an anno id, redirects them back to the annoBoard
    if (!isset($_POST['edit_anno'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/annos/annoBoard.php');
        die();
    }
    ?>
</body>

</html>