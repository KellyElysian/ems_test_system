<?php
// Starts the session, which will be used for permission, change of user viewing experience, and other website features.
// Always include this at the beginning of every page incase, even if the page might not require it.
session_start();
session_regenerate_id(true);
// Information data
$db_connection = mysqli_connect("db.luddy.indiana.edu", "i494f23_keldong", "my+sql=i494f23_keldong", "i494f23_keldong");
// Checks database connection
if (mysqli_connect_errno()) {
    echo "Connection Failed" . mysqli_connect_error();
    exit;
}