<?php
session_start();
//require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php"); // Adjust the path as needed
require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: web-systems-development/Index/index.html"); // redirect to login if not logged in
  exit;
}

$userID = $_SESSION['user_id'];
$userType = $_SESSION['userType'];

$lastName = '';

// Determine the table to query based on user type
if ($userType === 'Adviser') {
  $detailsTable = 'adviserdetails';
} elseif ($userType === 'Administrator') {
  $detailsTable = 'admindetails';
} else {
  // Add logic for Adviser or any other user types
}

// Query for the last name if a details table has been identified
if (!empty($detailsTable)) {
  $stmt = $db->prepare("SELECT lastName FROM $detailsTable WHERE user_id = ?");
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $lastName = $row['lastName'];
  }
  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server.css">

</head>
<body>
<link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
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
        <li class="item active">
          <a href="server_docs.php">Documents</a>         
        </li>
        <li class="item">
          <a href="server_feedbacks.php">Feedback</a>
        </li>
        <a href="server_logout.php" class="logout-button">Logout</a>  
      </ul>
    </div>
  </div>
  <div class="page-content">
  <div class="header">Welcome sa home page "<?php echo htmlspecialchars($lastName); ?>" !!!</div>
    <div class="content-categories">
      <div class="label-wrapper">
      </div>
    </div>
    <div class="image-container">
    <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
  </div>
  </div>
</div>
</body>
</html>
