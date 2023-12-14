<?php
ob_start(); // Start output buffering at the very beginning of the script
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

$recordIdsQuery = "SELECT record_id FROM internshiprecords";
$recordIdsResult = $db->query($recordIdsQuery);
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>ADMIN - FEEDBACKS PAGE</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_feedbacks.css">
  <style>
    body {
  font-family: 'Arial', sans-serif;
  background-color: #f4f4f4;
  padding: 20px;
}

form {
  background-color: #ffffff;
  max-width: 600px;
  margin: 40px auto;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

h2 {
  color: #333;
  margin-bottom: 20px;
}

label {
  display: block;
  margin-top: 10px;
  color: #666;
}

input[type="text"],
input[type="date"],
textarea,
input[type="submit"],
select {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box; /* Makes sure padding doesn't affect width */
}

input[type="submit"] {
  background-color: #5cb85c;
  color: white;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
  background-color: #4cae4c;
}

input[type="date"]::before {
  content: attr(value), !important;
}

textarea {
  height: 100px;
  resize: vertical; /* Allows user to resize textarea vertically */
}

/* Responsive layout for smaller screens */
@media (max-width: 768px) {
  form {
    width: 90%;
    margin: 20px auto;
  }
}

    </style>
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
  <form action="server_feedbacks.php" method="post" onsubmit="return confirm('Are you sure you want to add this feedback?');">
<label for="new_record_id">Record ID:</label>
  <select name="new_record_id" id="new_record_id" required>
    <option value="">Select a Record ID</option>
    <?php
    if ($recordIdsResult->num_rows > 0) {
        while($row = $recordIdsResult->fetch_assoc()) {
            echo '<option value="'.htmlspecialchars($row['record_id']).'">'.htmlspecialchars($row['record_id']).'</option>';
        }
    }
    ?>
  </select>
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
    
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();

      // Redirect to prevent form resubmission
      header('Location: server_feedbacks.php');
      exit();
  }
  ob_end_flush();
?>
  </div>
</div>
</body>
</html>
