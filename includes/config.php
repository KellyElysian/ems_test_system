<?php
// Gets the upper level directory (above includes) and makes sure that the whole directory is saved as a session 
$dir = dirname(__DIR__, 1);
session_save_path($dir);

// Starts the session, which will be used for permission, change of user viewing experience, and other website features.
// Always include this at the beginning of every page incase, even if the page might not require it.
session_start();
session_regenerate_id(true);
// Information data
$db_connection = mysqli_connect("db.luddy.indiana.edu", "i494f23_keldong", "my+sql=i494f23_keldong", "i494f23_keldong");

// Assigning session variables
$member_id = $_SESSION['member_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Checks database connection
if (mysqli_connect_errno()) {
    echo "Connection Failed" . mysqli_connect_error();
    exit;
}
