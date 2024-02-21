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

$event_submit = $_POST['event_submit'];

// Timezone
date_default_timezone_set('America/Indiana/Indianapolis');

// Date Start and End Validation
if (isset($event_submit)) {
    $dt_start = new DateTime($_POST['date_time_start']);
    $dt_end = new DateTime($_POST['date_time_end']);

    $dt_start = $dt_start->format('Y-m-d H:i:s');
    $dt_end = $dt_end->format('Y-m-d H:i:s');

    if ($dt_start >= $dt_end) {
        $dt_error = 1;
        unset($event_submit);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating an Event</title>
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body id="event">
    <?php require $dir . '/includes/navbar.php'; ?>
    <div class="container">
        <?php
        if (!isset($event_submit)) {
        ?>
            <div class="form_container">
                <div class="inner_container">
                    <h2>Create Event Form</h2>
                    <form method="POST">
                        <p class="headers">Please enter basic event information below:</p>
                        <div class="text_box">
                            <label for="title">Event Title:</label>
                            <br>
                            <textarea name="title" id="title_box" cols="30" rows="5" class="title_box" required><?php echo $_POST['title']; ?></textarea>
                        </div>
                        <div>
                            <label for="starttime">Start Date & Time:</label>
                            <input type="datetime-local" name="date_time_start" value="<?php echo $_POST['date_time_start']; ?>" required>
                            <?php
                            if (isset($dt_error)) {
                            ?>
                                <span class="error">Please ensure the start time is before end time!</span>
                            <?php } ?>
                        </div>
                        <div>
                            <label for="endtime">End Date & Time:</label>
                            <input type="datetime-local" name="date_time_end" value="<?php echo $_POST['date_time_end']; ?>" required>
                        </div>
                        <div>
                            <label for="points">Points:</label>
                            <input type="number" name="points" value="<?php echo $_POST['points']; ?>" min="0" max="1500" required>
                        </div>
                        <!--*****************
                        *********************
                        *****************-->
                        <p class="headers">Please enter signup details below:</p>
                        <div>
                            <label for="supervisors">Supervisors (Max):</label>
                            <input type="number" name="super_max" value="<?php echo $_POST['super_max']; ?>" min="0" max="10" required>
                        </div>
                        <div class="slot_container">
                            <label for="endtime" class="margin-label">EMTs (Max):</label>
                            <span class="margin-shift">Mains:</span>
                            <input type="number" name="emt_main" value="<?php echo $_POST['emt_main']; ?>" min="0" max="20" class="smaller" required>
                            <span class="margin-shift">Reserves:</span>
                            <input type="number" name="emt_res" value="<?php echo $_POST['emt_res']; ?>" min="0" max="20" class="smaller" required>
                        </div>
                        <div class="slot_container">
                            <label for="endtime" class="margin-label">FRs (Max):</label>
                            <span class="margin-shift">Mains:</span>
                            <input type="number" name="fr_main" value="<?php echo $_POST['fr_main']; ?>" min="0" max="20" class="smaller" required>
                            <span class="margin-shift">Reserves:</span>
                            <input type="number" name="fr_res" value="<?php echo $_POST['fr_res']; ?>" min="0" max="20" class="smaller" required>
                        </div>
                        <!--*****************
                        *********************
                        *****************-->
                        <p class="headers">Please enter location details below:</p>
                        <div>
                            <label for="street">Street:</label>
                            <input type="text" name="street" pattern="^[a-zA-Z1-9 ]+$" value="<?php echo $_POST['street']; ?>" required>
                        </div>
                        <div>
                            <label for="city">City:</label>
                            <input type="text" name="city" pattern="^[a-zA-Z ]+$" value="<?php echo $_POST['city']; ?>" required>
                        </div>
                        <div>
                            <label for="zip">Zip:</label>
                            <input type="text" name="zip" pattern="^[0-9]*$" value="<?php echo $_POST['zip']; ?>" required>
                        </div>
                        <!--*****************
                        *********************
                        *****************-->
                        <p class="headers">Please enter event details below:</p>
                        <div class="text_box">
                            <label for="details">Details & Description of the event:</label>
                            <br>
                            <textarea name="details" id="details_box" cols="30" rows="10" class="details_box" required><?php echo $_POST['details']; ?></textarea>
                        </div>

                        <div>
                            <label for="agreement">Are all the details of the event confirmed?</label>
                            <br>
                            <div class="radio_container">
                                <input type="radio" name="agree" id="agreeNo" value="Yes" onchange="radioHandler(this)" required>
                                <label for="yesTerms">Yes</label>
                                <input type="radio" name="agree" id="termsYes" value="No" onchange="radioHandler(this)" checked>
                                <label for="noTerms">No</label>
                            </div>
                        </div>

                        <input type="submit" name="event_submit" class="submit evt" id="event_submit" style="display: none;" value="Create Event">
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
            $evt_title = isset($_POST['title']) ? $_POST['title'] : null;
            $int_start = isset($_POST['date_time_start']) ? san_input($_POST['date_time_start']) : null;
            $evt_start = str_replace("T", " ", $int_start);
            $int_end = isset($_POST['date_time_end']) ? san_input($_POST['date_time_end']) : null;
            $evt_end = str_replace("T", " ", $int_end);
            $evt_pt = isset($_POST['points']) ? san_input($_POST['points']) : null;

            $evt_str = isset($_POST['street']) ? san_input($_POST['street']) : null;
            $evt_city = isset($_POST['city']) ? san_input($_POST['city']) : null;
            $evt_zip = isset($_POST['zip']) ? san_input($_POST['zip']) : null;
            $evt_location = $evt_str . ", " . $evt_city . ", " . $evt_zip;

            $evt_details = isset($_POST['details']) ? san_input($_POST['details']) : null;

            // Inserting the event into the database
            mysqli_query($db_connection, "INSERT INTO e_Event (title, points, dateTimeStart, dateTimeEnd, location, details, closed) VALUES
            ('$evt_title', $evt_pt, '$evt_start', '$evt_end', '$evt_location', '$evt_details', 0)");

            // Connecting the newly created event to its creator
            $new_eventID = mysqli_insert_id($db_connection);
            mysqli_query($db_connection, "INSERT INTO e_Event_Create 
            (event_id, mem_id, timeMade) VALUES
            ($new_eventID, $member_id, NOW())");

            // Slots for each role
            $evt_spr = isset($_POST['super_max']) ? $_POST['super_max'] : null;
            $evt_emt_main = isset($_POST['emt_main']) ? $_POST['emt_main'] : null;
            $evt_emt_res = isset($_POST['emt_res']) ? $_POST['emt_res'] : null;
            $evt_fr_main = isset($_POST['fr_main']) ? $_POST['fr_main'] : null;
            $evt_fr_res = isset($_POST['super_max']) ? $_POST['super_max'] : null;

            mysqli_query($db_connection, "INSERT INTO e_Event_Slots
            (maxSPR, maxEMT, maxFR, resEMT, resFR, event_id) VALUES
            ($evt_spr, $evt_emt_main, $evt_emt_res, $evt_fr_main, $evt_fr_res, $new_eventID)");

            // Inserts into the slot details table

            header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/events/eventsBoard.php');
            die();
        }
        ?>
    </div>

    <script>
        // JS for making the submit button appear and disappear based on current user choice
        function radioHandler(src) {
            var button = document.getElementById("event_submit");

            if (src.value == "Yes") {
                button.style.display = "block";
            } else if (src.value == "No") {
                button.style.display = "none";
            }
        }
    </script>
</body>

</html>