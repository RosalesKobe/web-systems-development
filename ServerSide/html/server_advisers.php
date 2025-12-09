<?php
session_start();
require("../php/db.php");

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: server_index.php");
    exit;
}

$userID = $_SESSION['user_id'];

// Fetch admin name
$firstName = '';
$lastName = '';
$stmt = $db->prepare("SELECT firstName, lastName FROM admindetails WHERE user_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
}
$stmt->close();

// Fetch advisers
$adviserData = [];
$stmt = $db->prepare("SELECT firstName, lastName, email, School, address FROM adviserdetails");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $adviserData[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADMIN - ADVISERS PAGE</title>
    <link rel="stylesheet" href="../css/style_server_advisers.css">
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
                <li class="item active"><a href="server_advisers.php">Advisers</a></li>
                <li class="item"><a href="server_admins.php">Administrators</a></li>
                <li class="item"><a href="server_programs.php">Programs</a></li>
                <li class="item"><a href="server_records.php">Records</a></li>
                <li class="item"><a href="server_feedbacks.php">Feedback</a></li>
                <li class="item"><a href="server_addIntern.php">Add Intern</a></li>
                <a href="server_logout.php" class="logout-button">Logout</a>
            </ul>
        </div>
    </div>

    <div class="page-content">
<div class="top-header">
  <div class="header">
    Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!
  </div>
      <div class="content-categories">
      <div class="label-wrapper"></div>
    </div>
  <div class="image-container">
    <img src="../img/Saint_Louis_University_PH_Logo.svg.png" alt="SLU Logo">
  </div>
</div>

        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>School</th>
                <th>Address</th>
            </tr>
            <?php if (empty($adviserData)): ?>
                <tr>
                    <td colspan="5">No advisers found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($adviserData as $adviser): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($adviser['firstName']); ?></td>
                        <td><?php echo htmlspecialchars($adviser['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($adviser['email']); ?></td>
                        <td><?php echo htmlspecialchars($adviser['School']); ?></td>
                        <td><?php echo htmlspecialchars($adviser['address']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
