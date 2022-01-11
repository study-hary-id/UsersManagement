<?php
/* Database credentials. Assuming you are running MySQL server with default setting. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phptest');
define('DB_PASSWORD', '4Uth3nt1c4t10N_');
define('DB_NAME', 'notes');

/* Attempt to connect to MySQL database. */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection, add connection handler.
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>