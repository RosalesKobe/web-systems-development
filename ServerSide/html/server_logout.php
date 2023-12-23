<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: /web-systems-development/ServerSide/html/server_index.php");
exit;
?>