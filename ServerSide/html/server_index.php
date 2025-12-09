<?php
session_start();
require("../php/db.php");


// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid CSRF token.";
        header("Location: server_index.php");
        exit();
    }

    $userType = $_POST['user-type'];
    $username = $_POST['username'];
    $password = $_POST['password']; // This is the plain text password

    // Check if the user is an admin 
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

            // Now use password_verify to compare the plain text password with the hashed password
            if (password_verify($password, $row['password'])) {
                // Set session variables to indicate the user is logged in
                $_SESSION['loggedin'] = true;
                $_SESSION['userType'] = $userType;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['user_id'];

				 // Handle Remember Me
                if (!empty($_POST['remember'])) {
                    setcookie("admin_username", $username, time() + (86400 * 30), "/"); // 30 days
                } else {
                    setcookie("admin_username", "", time() - 3600, "/"); // delete cookie
                }
                // Redirect to a new page after successful login
                header("Location: server_home.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect username or password.";
            }
        } else {
            $_SESSION['error'] = "Incorrect username or password.";
        }

        $statement->close();
    } else {
        $_SESSION['error'] = "Invalid user type.";
    }

    header("Location: server_index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ADMIN - LOGIN PAGE</title>
    <link rel="stylesheet" href="../css/style_server_index.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <img class="wave" src="/web-systems-development/ServerSide/img/imageedit_2_7058122710.png">
    <div class="container">
        <div class="img">
            <img src="/web-systems-development/ServerSide/img/bg-removebg-preview.png">
        </div>
        <div class="login-content">
            <?php if (isset($_SESSION['error'])): ?>
                <script>alert("<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>");</script>
            <?php endif; ?>
            <form id="login-form" method="POST" action="" class="login-form">
                <img src="/web-systems-development/ServerSide/img/Saint_Louis_University_PH_Logo.svg.png">
                <h2 class="title">Welcome</h2>

                <div class="input-div one">
                    <div class="i"><i class="fas fa-user"></i></div>
                    <div class="div">
                        <input type="text" id="username" name="username" placeholder="Username" required
                               value="<?php echo isset($_COOKIE['admin_username']) ? $_COOKIE['admin_username'] : ''; ?>">
                    </div>
                </div>

                <div class="input-div pass">
                    <div class="i"><i class="fas fa-lock"></i></div>
                    <div class="div">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="input-div role">
                    <div class="i"><i class="fas fa-user"></i></div>
                    <div class="div">
                        <select id="user-type" name="user-type" required>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <a href="#">Forgot Password?</a>
                <input type="submit" class="btn" value="Login">

                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember"
                           <?php echo isset($_COOKIE['admin_username']) ? 'checked' : ''; ?>>
                    Remember me
                </label>
            </form>
        </div>
    </div>
</body>
</html>


