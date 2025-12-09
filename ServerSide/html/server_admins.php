<?php
session_start();
require("../php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: server_index.php");
  exit;
}

$userID = $_SESSION['user_id'];
$userType = $_SESSION['userType'];

$lastName = '';
$firstName = '';

$detailsTable = 'admindetails';

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

$adminData = [];

$stmt = $db->prepare("
    SELECT ad.firstName, ad.lastName, ad.email, ad.address, ad.other_administrator_details, com.companyName
    FROM admindetails ad
    JOIN company com ON ad.company_id = com.company_id
");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $adminData[] = $row;
    }
} else {
    $adminData = [];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADMIN - ADMINS PAGE</title>
  <link rel="stylesheet" href="../css/style_server_admins.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
</head>
<body>
<div class="task-manager">
  <div class="left-bar">
    <div class="upper-part"></div>
    <div class="left-content">
      <ul class="action-list">
        <li class="item"><a href="server_home.php">Home</a></li>
        <li class="item"><a href="server_interns.php">Interns</a></li>
        <li class="item"><a href="server_advisers.php">Advisers</a></li>
        <li class="item active"><a href="server_admins.php">Administrators</a></li>
        <li class="item"><a href="server_programs.php">Programs</a></li>
        <li class="item"><a href="server_records.php">Records</a></li>
        <li class="item"><a href="server_feedbacks.php">Feedback</a></li>
        <li class="item"><a href="server_addIntern.php">Add Intern</a></li>
        <a href="server_logout.php" class="logout-button">Logout</a>
      </ul>
    </div>
  </div>
  <div class="page-content">
    <div class="header">
      Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!
    </div>
    <div class="content-categories">
      <div class="label-wrapper"></div>
    </div>
    <div class="image-container">
      <img src="../img/Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
    </div>

    <table>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Company Name</th>
      </tr>
      <?php foreach ($adminData as $admin): ?>
        <tr>
          <td><?php echo htmlspecialchars($admin['firstName']); ?></td>
          <td><?php echo htmlspecialchars($admin['lastName']); ?></td>
          <td><?php echo htmlspecialchars($admin['email']); ?></td>
          <td><?php echo htmlspecialchars($admin['address']); ?></td>
          <td><?php echo htmlspecialchars($admin['companyName']); ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($adminData)): ?>
        <tr>
          <td colspan="5">No administrators found.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>
</div>
</body>
</html>
