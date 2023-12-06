<?php
// Start the session to store user data once they're authenticated
session_start();

// Check if we have POST data (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the user type, username, and password from the form
    $userType = $_POST['user-type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the user is a supervisor or adviser
    if (in_array($userType, ['supervisor', 'adviser'])) {
        // Temporary check for username and password
        if ($username == "admin" && $password == "admin") {
            // Set session variables to indicate the user is logged in
            $_SESSION['loggedin'] = true;
            $_SESSION['userType'] = $userType;
            $_SESSION['username'] = $username;
            
            // Redirect to a new page (e.g., dashboard.php) after successful login
            header("Location: /9374-cs312-web_dev-finalsactivity/ServerSide/html/server_home.html");
            exit();
        } else {
            // If the provided credentials are wrong
            echo "Incorrect username or password.";
        }
    }
}
?>