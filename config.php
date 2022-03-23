<?php

/*
    Database credentials.
    Assuming you are running MySQL server with default setting.
*/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'school_payment_db');

/* Attempt to connect to MySQL database. */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

/* Check connection, add connection handler. */
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// echo "Database connected succesfully.";
?>