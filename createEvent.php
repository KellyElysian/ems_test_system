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
    header('Location: https://cgi.luddy.indiana.edu/~keldong/ems/home.php');
    die();
}

$event_submit = $_POST['event_submit'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating an Event</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<body id="event">
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <?php
        if (!isset($event_submit)) {
        ?>
            <div class="form_container">
                <div>
                    <h2>Create Event Form</h2>
                    <form method="POST">
                        <p class="headers">Please enter basic event information below:</p>
                        <div>
                            <label for="title">Event Title:</label>
                            <input type="text" name="title" minlength="1" maxlength="50" pattern="^[a-zA-Z1-9]+$" required>
                        </div>
                        <div>
                            <label for="title">Start Date & Time:</label>
                            <input type="datetime-local" name="date_time_start" required>
                        </div>
                        <div>
                            <label for="title">End Date & Time:</label>
                            <input type="datetime-local" name="date_time_end" required>
                        </div>

                        <p class="headers">Please enter location details below:</p>
                        <div>
                            <label for="title">Street:</label>
                            <input type="text" name="street" pattern="^[a-zA-Z1-9]+$" required>
                        </div>
                        <div>
                            <label for="title">City:</label>
                            <input type="text" name="city" pattern="^[a-zA-Z]+$" required>
                        </div>
                        <div>
                            <label for="title">Zip:</label>
                            <input type="text" name="zip" pattern="^[0-9]*$" required>
                        </div>

                        <p class="headers">Please enter event details below:</p>
                        <div>
                            <label for="title">Details & Description of the event:</label>
                            <br>
                            <textarea name="details" id="details_box" cols="30" rows="10" class="details_box" required></textarea>
                        </div>

                        <div>
                            <label for="agreement">Are all the details of the event confirmed to be correct?</label>
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
            // Input Variables
            $evt_title = isset($_POST['title']) ? $_POST['title'] : null;
            $int_start = isset($_POST['date_time_start']) ? $_POST['date_time_start'] : null;
            $evt_start = str_replace("T", " ", $int_start);
            $int_end = isset($_POST['date_time_end']) ? $_POST['date_time_end'] : null;
            $evt_end = str_replace("T", " ", $int_end);

            $evt_str = isset($_POST['street']) ? $_POST['street'] : null;
            $evt_city = isset($_POST['city']) ? $_POST['city'] : null;
            $evt_zip = isset($_POST['zip']) ? $_POST['zip'] : null;
            $evt_location = $evt_str . ", " . $evt_city . ", " . $evt_zip;

            $evt_details = isset($_POST['details']) ? $_POST['details'] : null;

            // Inserting the event into the database
            mysqli_query($db_connection, "INSERT INTO e_Event (title, dateTimeStart, dateTimeEnd, location, details) VALUES
            ('$evt_title', '$evt_start', '$evt_end', '$evt_location', '$evt_details')");

            // Tells the admin that event creation was successful and redirects them
            echo '
            <div class="form_container">
                <h3 class="complete">Event creation successful. Redirecting to event page.</h3>
            </div>
            ';

            header('Refresh: 3; URL=https://cgi.luddy.indiana.edu/~keldong/ems/eventsBoard.php');
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