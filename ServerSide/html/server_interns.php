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

$firstName = '';
$lastName = '';

// Determine the table to query based on user type
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

// Initialize $internsData
$internsData = [];
$stmt = $db->prepare("SELECT 
        i.firstName AS InternFirstName,
        i.lastName AS InternLastName, 
        i.email, 
        i.address, 
        i.School, 
        i.other_intern_details,
        a.firstName AS AdviserFirstName,
        a.lastName AS AdviserLastName
    FROM 
        interndetails i
    JOIN 
        adviserdetails a ON i.adviser_id = a.adviser_id
");
$stmt->execute();
$result = $stmt->get_result();

// Store the data in an array to use later in the HTML
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $internsData[] = $row;
    }
} else {
    $internsData = []; // Set $internsData as an empty array if no results
}
$stmt->close();

?>




<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_interns.css">

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
        <li class="item active">
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
          <a href="server_feedbacks.php">Feedback</a>
        </li>
        <li class="item">
          <a href="server_addIntern.php">Add Intern</a>
        </li>
        <a href="server_logout.php" class="logout-button">Logout</a>  
      </ul>
    </div>
  </div>
  <div class="page-content">
  <div class="header">Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!</div>
  <div class="content-categories">
      <div class="label-wrapper">
        </div>    
        <div class="image-container">
    <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
  </div>
  </div>

      <table>
        <tr>

          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Address</th>
          <th>School</th>
          <th>Other Intern Details</th>
          <th>Adviser First Name</th>
          <th>Adviser Last Name</th>
        </tr>
        <?php foreach ($internsData as $intern): ?>
          <tr>
          <td><?php echo htmlspecialchars($intern['InternFirstName']); ?></td>
          <td><?php echo htmlspecialchars($intern['InternLastName']); ?></td>
          <td><?php echo htmlspecialchars($intern['email']); ?></td>
          <td><?php echo htmlspecialchars($intern['address']); ?></td>
          <td><?php echo htmlspecialchars($intern['School']); ?></td>
          <td><?php echo htmlspecialchars($intern['other_intern_details']); ?></td>
          <td><?php echo htmlspecialchars($intern['AdviserFirstName']); ?></td>
          <td><?php echo htmlspecialchars($intern['AdviserLastName']); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($internsData)): ?>
          <tr>
            <td colspan="6">No programs found.</td>
          </tr>
        <?php endif; ?>
      </table>

    
  </div>
</div>
<script>
</script>
</body>
</html>


