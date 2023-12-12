<?php
$servername = "localhost"; // Server name, usually localhost (127.0.0.1)
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "teampogi"; // Your database name

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>