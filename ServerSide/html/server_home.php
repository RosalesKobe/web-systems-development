<?php
session_start();
//require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php"); // Adjust the path as needed
require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");

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

// Get the count of interns
$stmt = $db->prepare("SELECT COUNT(*) as intern_count FROM interndetails");
$stmt->execute();
$result = $stmt->get_result();
$intern_result = $result->fetch_assoc();
$intern_count = $intern_result['intern_count'];

// Get the count of advisers
$stmt = $db->prepare("SELECT COUNT(*) as adviser_count FROM adviserdetails");
$stmt->execute();
$result = $stmt->get_result();
$adviser_result = $result->fetch_assoc();
$adviser_count = $adviser_result['adviser_count'];

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_home.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
<div class="task-manager">
  <div class="left-bar">
    <div class="upper-part">
    </div>
    <div class="left-content">
      <ul class="action-list">
        <li class="item active">
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
    </div>
    <div class="image-container">
    <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
  </div>
  <canvas id="myChart" width="400" height="400"></canvas>
  <script>
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ['Interns', 'Advisers'],
              datasets: [{
                  label: 'Count',
                  data: [<?php echo $intern_count; ?>, <?php echo $adviser_count; ?>],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });
    </script>
  </div>
</div>
</body>
</html>
