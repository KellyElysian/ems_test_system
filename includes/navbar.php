<?php
// Automatically brings the config file
require 'config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body>
    <nav>
        <?php
        $currentFile = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        ?>
        <img src="
        <?php
        // Helps with showing the ems logo based on what file the navbar is being shown in.
        // This is due to home.php being the only top-level directory file while everything else is in a subdirectory.
        if ($currentFile == "home") {
            echo 'images/star_of_life.png';
        } else {
            echo '../images/star_of_life.png';
        }
        ?>" alt="EMS Logo" class="ems_logo">
        <div class="a_con">
            <a href="https://cgi.luddy.indiana.edu/~keldong/ems/home.php">Home</a>
            <a href="https://cgi.luddy.indiana.edu/~keldong/ems/events/eventsBoard.php">Events</a>
            <a href="https://cgi.luddy.indiana.edu/~keldong/ems/annos/annoBoard.php">Announcements</a>
            <?php
            // Based on logged in user's role, they may see different navigation bars
            if ($user_role == "Admin") {
                echo '
            <a href="https://cgi.luddy.indiana.edu/~keldong/ems/directories/directory.php">Directories</a>
            ';
            }

            if (isset($member_id)) {
                echo '
            <form id="profileForm" action="https://cgi.luddy.indiana.edu/~keldong/ems/profiles/profile.php" method="POST" class="nav_profile_form">
                <input type="hidden" value="' . $user_id . '" name="view_user_id">
                <a href="#" onclick="submitForm()" class="profile_a">Profile</a>
            </form>';
            }

            // Checks if the member_id is set meaning the user is logged in and has a profile
            // If not, show the login button, if they are logged in, shows the logout button instead
            if (!isset($member_id)) {
                echo '<a href="https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php" class="nav_button">Login</a>';
            } else {
                echo '<a href="https://cgi.luddy.indiana.edu/~keldong/ems/login/logout.php" class="nav_button">Logout</a>';
            }
            ?>
        </div>
    </nav>

    <script>
        function submitForm() {
            document.getElementById("profileForm").submit();
        }
    </script>
</body>

</html>