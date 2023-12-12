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
if ($userType === 'Adviser') {
  $detailsTable = 'adviserdetails';
} elseif ($userType === 'Administrator') {
  $detailsTable = 'admindetails';
} else {
  // Add logic for Adviser or any other user types
}

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

// Initialize $internRecords
$internRecords = [];
$stmt = $db->prepare("SELECT 
        d.firstName AS InternFirstName, 
        d.lastName AS InternLastName,
        r.hours_completed AS hours_rendered, 
        r.hours_remaining AS hours_remaining, 
        r.start_date AS sd, 
        r.end_date AS ed, 
        r.record_status AS record_status
    FROM 
        internshiprecords r
    INNER JOIN 
        interndetails d ON r.intern_id = d.intern_id;
");
$stmt->execute();
$result = $stmt->get_result();

// Store the data in an array to use later in the HTML
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $internRecords[] = $row;
    }
} else {
    $internRecords = []; // Set $internRecords as an empty array if no results
}
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_records.css">

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
        <li class="item active">
          <a href="server_records.php">Records</a>    
        </li>
        <li class="item">
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
  <div class="header">Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!</div>
    <div class="content-categories">
      <div class="label-wrapper">
      </div>
    </div>
    <div class="image-container">
    <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
  </div>
  <table>
        <tr>

          <th>First Name</th>
          <th>Last Name</th>
          <th>Hours Completed</th>
          <th>Hours Remaining</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Record Status</th>
        </tr>
        <?php foreach ($internRecords as $intern): ?>
          <tr>
          <td><?php echo htmlspecialchars($intern['InternFirstName']); ?></td>
          <td><?php echo htmlspecialchars($intern['InternLastName']); ?></td>
          <td><?php echo htmlspecialchars($intern['hours_rendered']); ?></td>
          <td><?php echo htmlspecialchars($intern['hours_remaining']); ?></td>
          <td><?php echo htmlspecialchars($intern['sd']); ?></td>
          <td><?php echo htmlspecialchars($intern['ed']); ?></td>
          <td><?php echo htmlspecialchars($intern['record_status']); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($internRecords)): ?>
          <tr>
            <td colspan="6">No programs found.</td>
          </tr>
        <?php endif; ?>
      </table>
  </div>
</div>
</body>
</html>
