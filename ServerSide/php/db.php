<?php
$servername = "localhost"; // Server name,
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "ojt-websys"; // Your database name

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>