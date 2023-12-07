<?php
session_start();
//require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php"); // Adjust the path as needed
require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL to check intern credentials
    $statement = $db->prepare("SELECT * FROM users WHERE username = ? AND user_type = 'Intern'");
    $statement->bind_param("s", $username);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Assuming passwords are hashed before storing in the database
        if ($password === $user['password']) {
            // Successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['userType'] = 'Intern';
            $_SESSION['username'] = $username;
            echo "Login successful";
        } else {
            // Incorrect password
            http_response_code(401); // Unauthorized
            echo "Incorrect username or password.";
        }
    } else {
        // User not found
        http_response_code(401); // Unauthorized
        echo "User not found.";
    }

    $statement->close();
} else {
    // Not a POST request
    http_response_code(400); // Bad Request
    echo "Invalid request.";
}
?>