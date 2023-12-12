<!DOCTYPE html>
<html>
<head>
	<title>OJT</title>
	<link rel="stylesheet" type="text/css" href="/web-systems-development/ServerSide/css/style_server_index.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="/web-systems-development/ServerSide/img/imageedit_2_7058122710.png">
	<div class="container">
		<div class="img">
			<img src="/web-systems-development/ServerSide/img/bg-removebg-preview.png">
		</div>
		<div class="login-content">
			<form id="login-form" method="POST" action="/web-systems-development/ServerSide/html/server_index.php" class="login-form">
				<img src="/web-systems-development/ServerSide/img/Saint_Louis_University_PH_Logo.svg.png">
				<h2 class="title">Welcome</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">	
           		   		<input type="text" id="username" name="username" placeholder="Username">
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<input type="password" id="password" name="password" placeholder="Password">
            	   </div>
            	</div>
				<div class="input-div role">
					<div class="i">
						<i class="fas fa-user"></i>
					</div>
					<div class="div">
						<select id="user-type" name="user-type">
							<option value="Administrator">Administrator</option>
						</select>
					</div>
				</div>
            	<a href="#">Forgot Password?</a>
            	<input type="submit" class="btn" value="Login">

				<label class="remember-me">
					<input type="checkbox" name="remember" id="remember">
					Remember me
				</label>
            </form>
        </div>
    </div>
</body>
</html>

<?php
session_start();
require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php");
//require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");
// Adjust the path as needed to your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['user-type'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is an admin or adviser
    if ($userType === 'Administrator') {
        // Prepare a statement for getting user data from the database
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
				echo "<script>alert('Incorrect username or password.');</script>";
            }
        } else {
			echo "<script>alert('Incorrect username or password.');</script>";
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