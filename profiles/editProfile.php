<?php
// Automatically brings the config file
// This needs to be done to bring the absolute path to the includes directory file that is needed
$dir = dirname(__DIR__, 1);
require $dir . '/includes/config.php';

// Default Permissions
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['member_id'])) {
        header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/createMember.php');
        die();
    } else {
        if ($member_status != "Active") {
            $_SESSION['reactivate'] = 1;
            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
            die();
        }
    }
} else {
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php');
    die();
}

// Checking if user submitted the form
$edit_submit = $_POST['edit_submit'];
// If they haven't, make sure the current edited user is correct
if (!isset($edit_submit)) {
    $_SESSION['edit_id'] = $_POST['user_id'];
}

// Php server-side validation on certification date inputs
if (isset($edit_submit)) {
    $cp_start = $_POST['c_start'];
    $cp_expire = $_POST['c_expire'];
    $run_start = $_POST['r_start'];
    $run_expire = $_POST['r_expire'];

    $cur_date = date("Y-m-d");
    // If both inputted start dates are after the expiration date
    if (($cp_start >= $cp_expire) and ($run_start >= $run_expire)) {
        $cp_error = 1;
        $run_error = 1;
        unset($edit_submit);
    } else if ($cp_start >= $cp_expire) {
        $cp_error = 1;
        unset($edit_submit);
    } else if ($run_start >= $run_expire) {
        $run_error = 1;
        unset($edit_submit);
    }
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

// Member ID
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

// Following section deals purely with certifications and allowing the admin to edit them appropriately
// If the earlier validation check is passed
if (!isset($cp_error) and !isset($run_error)) {
    /**
     * Certification ID Number Legend
     * 99 = CPR
     * 100 = First Responder (FR)
     * 101 = EMT-B
     * 102 = EMT-A
     * 103 = Paramedic (PM)
     */
    // CPR Certification
    $cpr_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign 
    WHERE member_id = $mem_id AND cert_id = 99");
    // If there is already an entry, then none of the relevant fields should be null.
    /**
     * Idea being that in order to submit certs on the edit page, they must have both the CPR and Run (aka event or other) certification date
     * fields be valid inputs, so in the end, checking for the CPR cert is also checking for the other cert.
     */
    if (mysqli_num_rows($cpr_query) > 0) {
        $renewel = 1;

        $cpr_arr = mysqli_fetch_assoc($cpr_query);
        $cp_start = $cpr_arr['startDate'];
        $cp_expire = $cpr_arr['expireDate'];

        // Other certification. Only the highest one matters since it's a hierarchal system. Plus they should only have their highest in the system
        // The editing below will delete their old cert if they get a new one that is higher in the hierarchy.
        $run_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign 
        WHERE member_id = $mem_id AND cert_id != 99");
        $run_arr = mysqli_fetch_assoc($run_query);
        $run_start = $run_arr['startDate'];
        $run_expire = $run_arr['expireDate'];

        // Deals with prechecking the correct radio button if their 'other' cert is set
        $run_cert_id = $run_arr['cert_id'];
        switch ($run_cert_id) {
            case 100:
                $fr_radio = 'checked';
                break;
            case 101:
                $emtb_radio = 'checked';
                break;
            case 102:
                $emta_radio = 'checked';
                break;
            case 103:
                $para_radio = 'checked';
                break;
        }
    } else {
        $fr_radio = 'checked';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=100%">
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
            <form method="POST" name="formMain" id="formMain">
                <h3 class="basic_header">Basic Information</h3>

                <!-- BASIC -->
                <section class="basic_container">
                    <div class="basic_section_one">
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
                    </div>
                    <div class="basic_section_two">
                        <div class="input_blocks">
                            Test Fill
                        </div>
                    </div>
                </section>

                <!-- ADMIN -->
                <h3 class="admin_header">Admin Information</h3>
                <section class="admin_container">

                    <div class="admin_section_one">
                        <div class="input_blocks">
                            <label for="notes">Additional Notes: </label>
                            <br>
                            <textarea name="notes" id="notes" cols="20" rows="7" class="note_area" required><?php echo $notes; ?></textarea>
                        </div>
                        <div class="input_blocks">
                            <label for="cpr_issue">
                                <?php
                                if (isset($renewel)) echo 'CPR Cert Renewed Date:';
                                else echo 'CPR Cert Issue Date:';
                                ?>
                            </label>
                            <input type="date" name="c_start" id="c_start" value="<?php echo $cp_start; ?>" required>
                            <?php
                            // Error message appears
                            if (isset($cp_error)) {
                                echo '<span class="error">The issue/renewal date must come before the expiration date!</span>';
                            }
                            ?>
                        </div>
                        <div class="input_blocks">
                            <label for="cpr_issue">CPR Cert Expiration Date:</label>
                            <input type="date" name="c_expire" id="c_expire" value="<?php echo $cp_expire; ?>" required>
                        </div>
                    </div>
                    <div class="admin_section_two">
                        <div class="input_blocks">
                            <label for="certs">Event Certification:</label><br>
                            <div class="cert_radios">
                                <input type="radio" name="cert" id="cert" value="100" required <?php echo $fr_radio; ?> />
                                <label for="fres">First Responder</label><br>
                                <input type="radio" name="cert" id="cert" value="101" <?php echo $emtb_radio; ?> />
                                <label for="emt-b">EMT-B</label><br>
                                <input type="radio" name="cert" id="cert" value="102" <?php echo $emta_radio; ?> />
                                <label for="emt-a">EMT-A</label><br>
                                <input type="radio" name="cert" id="cert" value="103" <?php echo $para_radio; ?> />
                                <label for="paramedic">Paramedic</label>
                            </div>
                        </div>
                        <div class="input_blocks">
                            <label for="issue">
                                <?php
                                if (isset($renewel)) echo 'Renewed Date:';
                                else echo 'Issue Date:';
                                ?>
                            </label>
                            <input type="date" name="r_start" id="r_start" value="<?php echo $run_start; ?>" required>
                            <?php
                            // Error message appears
                            if (isset($run_error)) {
                                echo '<span class="error">The issue/renewal date must come before the expiration date!</span>';
                            }
                            ?>
                        </div>
                        <div class="input_blocks">
                            <label for="issue">Expiration Date:</label>
                            <input type="date" name="r_expire" id="r_expire" value="<?php echo $run_expire; ?>" required>
                        </div>
                    </div>
                <?php
                        }
                ?>
                </section>

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

            // Adding first time certificate entries or updating previous ones that were issued.
            /**
             * Certification ID Number Legend
             * 99 = CPR
             * 100 = First Responder (FR)
             * 101 = EMT-B
             * 102 = EMT-A
             * 103 = Paramedic (PM)
             */

            // CPR Certification
            $cpr_query = mysqli_query($db_connection, "SELECT * FROM e_Cert_Assign 
            WHERE member_id = $mem_id AND cert_id = 99");
            // Meaning entry is already in, refer to the php code block dealing with this for full details
            if (mysqli_num_rows($cpr_query) > 0) {
                // Updating the existing entry

                // Updating the CPR Certificate
                $cp_start = $_POST['c_start'];
                $cp_expire = $_POST['c_expire'];
                mysqli_query($db_connection, "UPDATE e_Cert_Assign SET startDate = '$cp_start', expireDate = '$cp_expire' 
                WHERE cert_id = 99 AND member_id = $mem_id");

                // Updating the other Certificate
                /**
                 * Need to first check which cert is already in the system in case they upgraded or got another cert
                 * If the certs aren't the same, delete the old one and insert this new one.
                 */
                $run_start = $_POST['r_start'];
                $run_expire = $_POST['r_expire'];
                $run_cert_id = $_POST['cert'];

                // Finding old cert ID
                $cert_check = mysqli_query($db_connection, "SELECT cert_id FROM e_Cert_Assign 
                WHERE member_id = $mem_id AND cert_id != 99");
                $cert_check_arr = mysqli_fetch_assoc($cert_check);
                $old_cert_id = $cert_check_arr['cert_id'];

                // Checking the new id compared to the old one
                if ($old_cert_id == $run_cert_id) {
                    // If it's still the same, update.
                    mysqli_query($db_connection, "UPDATE e_Cert_Assign SET startDate = '$run_start', expireDate = '$run_expire'
                    WHERE cert_id = $run_cert_id AND member_id = $mem_id");
                } else {
                    // If it's different, proceed with the process mentioned above
                    mysqli_query($db_connection, "DELETE FROM e_Cert_Assign WHERE cert_id = $old_cert_id AND member_id = $mem_id");

                    mysqli_query($db_connection, "INSERT INTO e_Cert_Assign (cert_id, member_id, startDate, expireDate) VALUES
                    ($run_cert_id, $mem_id, '$run_start', '$run_expire')");
                }
            } else {
                // Adding the inputted certificates because no entry for the user currently exists

                // Adding the CPR Certificate
                $cp_start = $_POST['c_start'];
                $cp_expire = $_POST['c_expire'];
                $cc = mysqli_query($db_connection, "INSERT INTO e_Cert_Assign (cert_id, member_id, startDate, expireDate) VALUES
                (99, $mem_id, '$cp_start', '$cp_expire')");

                // Adding the other Certificate for events
                $run_start = $_POST['r_start'];
                $run_expire = $_POST['r_expire'];
                $run_cert_id = $_POST['cert'];
                mysqli_query($db_connection, "INSERT INTO e_Cert_Assign (cert_id, member_id, startDate, expireDate) VALUES
                ($run_cert_id, $mem_id, '$run_start', '$run_expire')");
            }

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

        // Checking for radio button change, shows new blank dates if the cert is not the same
        let oldCert = document.querySelector('#cert:checked').value;
        let oldStart = document.querySelector('#r_start').value;
        let oldExpire = document.querySelector('#r_expire').value;

        let certRadios = document.querySelectorAll('#cert');
        for (let i = 0; i < certRadios.length; i++) {
            certRadios[i].addEventListener('change', () => {
                let newCert = document.querySelector('#cert:checked').value;
                if (newCert == oldCert) {
                    document.querySelector('#r_start').value = oldStart;
                    document.querySelector('#r_expire').value = oldExpire;
                } else {
                    document.querySelector('#r_start').value = "";
                    document.querySelector('#r_expire').value = "";
                }
            })
        }
    </script>
</body>

</html>