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

$event_submit = $_POST['event'];
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
        <div class="form_container">
            <div>
                <h2>Create Event Form</h2>
                <form method="POST">
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
                </form>
            </div>
        </div>
    </div>
</body>

</html>