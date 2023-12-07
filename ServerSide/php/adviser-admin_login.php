<?php
session_start();
require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php");

// Adjust the path as needed to your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['user-type'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is an admin or adviser
    if (in_array($userType, ['Administrator', 'Adviser'])) {
        // Prepare a statement for getting user data from the database
        $statement = $db->prepare("SELECT password FROM users WHERE username = ? AND user_type = ?");
        $statement = $db->prepare("SELECT user_id, password FROM users WHERE username = ? AND user_type = ?");
        $statement->bind_param("ss", $username, $userType);
        $statement->execute();

        // Check for errors in statement execution
        if ($statement->errno) {
            echo "Error executing statement: " . $statement->error;
            exit();
        }

        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Compare plain text password with stored password
            if ($password === $row['password']) {
                // Set session variables to indicate the user is logged in
                $_SESSION['loggedin'] = true;
                $_SESSION['userType'] = $userType;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['user_id'];
                // Redirect to a new page after successful login
                header("Location: http://localhost/web-systems-development/ServerSide/html/server_home.php");
                exit();
            } else {
                echo "Incorrect username or password.";
                echo "Username: $username, UserType: $userType <br>";
                echo "SQL Query: " . $statement->sqlstate . "<br>";
            }
        } else {
            echo "Incorrect username or password.";
            echo "Username: $username, UserType: $userType <br>";
        }

        // Close the statement
        $statement->close();
    } else {
        echo "Invalid user type.";
        
    }
} else {
    echo "Invalid request method.";
}
?>