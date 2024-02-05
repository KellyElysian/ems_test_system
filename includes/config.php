<?php
// Gets the upper level directory (above includes) and makes sure that the whole directory is saved as a session 
$dir = dirname(__DIR__, 1);
session_save_path($dir);

// Set session garbage collection probability to 1 and divisor to 100 (1% chance)
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Set session lifetime to a shorter duration (e.g., 30 minutes)
ini_set('session.gc_maxlifetime', 900);

// Logs the session out if the activity is done and regenerates it. 
$inactive_timeout = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_timeout)) {
    // Session has expired, destroy it
    session_unset();
    session_destroy();

    // Redirect to login page or any other desired action
    header("Location: https://cgi.luddy.indiana.edu/~keldong/ems/login/login.php");
    exit();
}

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
