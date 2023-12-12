<?php
session_start();
require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php"); // Adjust the path as needed
//require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: web-systems-development/ServerSide/html/server_index.php"); // redirect to login if not logged in
    exit;
}

$userID = $_SESSION['user_id'];
$userType = $_SESSION['userType'];

$lastName = '';
$firstName = '';

$detailsTable = 'admindetails';


// Query for the last name if a details table has been identified
if (!empty($detailsTable)) {
    $stmt = $db->prepare("SELECT firstName, lastName FROM $detailsTable WHERE user_id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
    }
    $stmt->close();
}

// Fetch adviser details
$adviserOptions = '';
$adviserQuery = "SELECT adviser_id, firstName, lastName FROM adviserdetails ORDER BY lastName, firstName";
$result = $db->query($adviserQuery);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $adviserId = $row['adviser_id'];
        $adviserName = $row['firstName'] . ' ' . $row['lastName'];
        $adviserOptions .= "<option value='{$adviserId}'>{$adviserName}</option>";
    }
} else {
    $adviserOptions = '<option value="">No advisers found</option>';
}

// Check if form data is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize form data
  $username = $db->real_escape_string($_POST['username']);
  $password = $db->real_escape_string($_POST['password']);
  $userType = 'Intern'; // Set user type to 'Intern' by default
  $adviserId = $db->real_escape_string($_POST['adviser_id']);
  $firstName = $db->real_escape_string($_POST['first_name']);
  $lastName = $db->real_escape_string($_POST['last_name']);
  $email = $db->real_escape_string($_POST['email']);
  $school = $db->real_escape_string($_POST['school']);
  $address = $db->real_escape_string($_POST['address']);
  $otherDetails = $db->real_escape_string($_POST['other_intern_details']);

  // Insert into 'users' table
  $insertUserQuery = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
  $stmt = $db->prepare($insertUserQuery);
  $stmt->bind_param("sss", $username, $password, $userType);
  
  if ($stmt->execute()) {
      $lastUserId = $stmt->insert_id; // Get the last inserted user id

      // Insert into 'interndetails' table
      $insertInternDetailsQuery = "INSERT INTO interndetails (user_id, adviser_id, firstName, lastName, email, address, School, other_intern_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($insertInternDetailsQuery);
      $stmt->bind_param("iissssss", $lastUserId, $adviserId, $firstName, $lastName, $email, $address, $school, $otherDetails);
      
      if ($stmt->execute()) {
          echo "New intern added successfully";
      } else {
          echo "Error: " . $stmt->error;
      }
  } else {
      echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

$db->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_addIntern.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="task-manager">
  <div class="left-bar">
    <div class="upper-part">
    </div>
    <div class="left-content">
      <ul class="action-list">
        <li class="item">
          <a href="server_home.php">Home</a>
        </li>
        <li class="item">
          <a href="server_interns.php">Interns</a>
        </li>
        <li class="item">
          <a href="server_advisers.php">Advisers</a>
        </li>
        <li class="item">
          <a href="server_admins.php">Administrators</a>
        </li>
        <li class="item">
          <a href="server_programs.php">Programs</a>          
        </li>
        <li class="item">
          <a href="server_records.php">Records</a>    
        </li>
        <li class="item">
          <a href="server_docs.php">Documents</a>         
        </li>
        <li class="item">
          <a href="server_feedbacks.php">Feedback</a>
        </li>
        <li class="item active">
          <a href="server_addIntern.php">Add User</a>
        </li>
        <a href="server_logout.php" class="logout-button">Logout</a>
      </ul>
    </div>
  </div>
  <div class="page-content">
    <div class="header">Add Intern</div>
    <form id="add-user-form" method="POST" action="/web-systems-development/ServerSide/html/server_addIntern.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="user-type">User Type</label>
        <input type="text" id="user-type" name="user_type" value="Intern" readonly>
      </div>
      <div class="form-group">
  <label for="adviser-id">Adviser Name</label>
  <select id="adviser-id" name="adviser_id" required>
    <?php echo $adviserOptions; ?>
  </select>
</div>
      <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first_name" required>
      </div>
      <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last_name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="school">School</label>
        <input type="text" id="school" name="school">
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address">
      </div>
      <div class="form-group">
        <label for="other-details">Other Intern Details</label>
        <textarea id="other-details" name="other_intern_details"></textarea>
      </div>
      <div class="form-group">
        <input type="submit" class="btn" value="Add User">
      </div>
    </form>
  </div>
</div>
</body>
</html>