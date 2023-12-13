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

$feedbackData = [];

// Modified SQL query with JOIN to fetch intern last name
$stmt = $db->prepare("
  SELECT feedback_id, feedback.record_id, feedback_text, feedback_date, interndetails.lastName AS internLastName
  FROM feedback
  JOIN internshiprecords ON feedback.record_id = internshiprecords.record_id
  JOIN interndetails ON internshiprecords.intern_id = interndetails.intern_id
");

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbackData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_feedbacks.css">

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
        <li class="item">
          <a href="server_docs.php">Documents</a>         
        </li>
        <li class="item active">
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
 <!-- Table for displaying feedback data -->
<h2>Feedback Data</h2>
<table>
  <tr>
    <th>Feedback ID</th>
    <th>Intern Last Name</th> <!-- Change from "Record ID" to "Intern Last Name" -->
    <th>Feedback Text</th>
    <th>Feedback Date</th>
  </tr>
  <?php foreach ($feedbackData as $feedback): ?>
  <tr>
    <td><?php echo htmlspecialchars($feedback['feedback_id']); ?></td>
    <td><?php echo htmlspecialchars($feedback['internLastName']); ?></td> <!-- Use intern last name instead of record ID -->
    <td><?php echo htmlspecialchars($feedback['feedback_text']); ?></td>
    <td><?php echo htmlspecialchars($feedback['feedback_date']); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
  <!-- Form for adding new feedback data -->
  <h2>Add New Feedback</h2>
<form action="server_feedbacks.php" method="post">
  <label for="new_record_id">Record ID:</label>
  <input type="text" name="new_record_id" id="new_record_id">
  <label for="new_feedback_text">Feedback Text:</label>
  <textarea name="new_feedback_text" id="new_feedback_text"></textarea>
  <label for="new_feedback_date">Feedback Date:</label>
  <input type="date" name="new_feedback_date" id="new_feedback_date" value="<?php echo date('Y-m-d'); ?>" required>
  <input type="submit" name="add_feedback" value="Add Feedback">
</form>

<?php
// Handle adding new feedback data to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_feedback'])) {
  $newRecordId = filter_input(INPUT_POST, 'new_record_id', FILTER_SANITIZE_NUMBER_INT);
  $newFeedbackText = filter_input(INPUT_POST, 'new_feedback_text', FILTER_SANITIZE_STRING);
  $newFeedbackDate = filter_input(INPUT_POST, 'new_feedback_date', FILTER_SANITIZE_STRING);
  
  // Prepare the SQL and bind parameters for inserting new data
  $stmt = $db->prepare("INSERT INTO feedback (record_id, feedback_text, feedback_date) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $newRecordId, $newFeedbackText, $newFeedbackDate);

  // Execute and check for successful insertion
  if ($stmt->execute()) {
    echo "New feedback added successfully!";
    
    // Redirect to avoid form resubmission on page refresh
    $redirectURL = $_SERVER['PHP_SELF'];
    header("Location: $redirectURL");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}
?>
  </div>
</div>
</body>
</html>
