<?php
ob_start();
session_start();
require("../php/db.php"); 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: server_index.php");
    exit;
}

$userID = $_SESSION['user_id'];

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

// Fetch feedback data
$feedbackData = [];
$stmt = $db->prepare("
  SELECT f.feedback_id, f.feedback_text, f.feedback_date, 
         CONCAT(i.firstName, ' ', i.lastName) AS internFullName
  FROM feedback f
  JOIN internshiprecords r ON f.record_id = r.record_id
  JOIN interndetails i ON r.intern_id = i.intern_id
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $feedbackData[] = $row;
}
$stmt->close();

// Fetch records for dropdown
$recordOptions = [];
$stmt = $db->prepare("
  SELECT r.record_id, r.record_status, CONCAT(i.firstName, ' ', i.lastName) AS internFullName
  FROM internshiprecords r
  JOIN interndetails i ON r.intern_id = i.intern_id
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $recordOptions[$row['record_id']] = $row['internFullName'] . " — Record #" . $row['record_id'] . " — " . $row['record_status'];
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ADMIN - FEEDBACKS PAGE</title>
<link rel="stylesheet" href="../css/style_server_feedbacks.css">
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
      <li class="item"><a href="server_admins.php">Administrators</a></li>
      <li class="item"><a href="server_programs.php">Programs</a></li>
      <li class="item"><a href="server_records.php">Records</a></li>
      <li class="item active"><a href="server_feedbacks.php">Feedback</a></li>
      <li class="item"><a href="server_addIntern.php">Add Intern</a></li>
      <a href="server_logout.php" class="logout-button">Logout</a>
    </ul>
  </div>
</div>

<div class="page-content">
<div class="header">Welcome <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>!</div>

<div class="image-container">
<img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
</div>

<h2>Feedback Data</h2>
<table>
<tr>
  <th>Feedback ID</th>
  <th>Intern Name</th>
  <th>Feedback Text</th>
  <th>Feedback Date</th>
</tr>
<?php foreach ($feedbackData as $feedback): ?>
<tr>
  <td><?php echo htmlspecialchars($feedback['feedback_id']); ?></td>
  <td><?php echo htmlspecialchars($feedback['internFullName']); ?></td>
  <td><?php echo htmlspecialchars($feedback['feedback_text']); ?></td>
  <td><?php echo htmlspecialchars($feedback['feedback_date']); ?></td>
</tr>
<?php endforeach; ?>
<?php if (empty($feedbackData)): ?>
<tr><td colspan="4">No feedback found.</td></tr>
<?php endif; ?>
</table>

<form action="server_feedbacks.php" method="post" onsubmit="return confirm('Are you sure you want to add this feedback?');">
<label for="new_record_id">Record:</label>
<select name="new_record_id" id="new_record_id" required>
  <option value="">Select a Record</option>
  <?php foreach ($recordOptions as $recordId => $label): ?>
    <option value="<?php echo htmlspecialchars($recordId); ?>"><?php echo htmlspecialchars($label); ?></option>
  <?php endforeach; ?>
</select>

<label for="new_feedback_text">Feedback Text:</label>
<textarea name="new_feedback_text" id="new_feedback_text" required></textarea>

<label for="new_feedback_date">Feedback Date:</label>
<input type="date" name="new_feedback_date" id="new_feedback_date" value="<?php echo date('Y-m-d'); ?>" required>

<input type="submit" name="add_feedback" value="Add Feedback">
</form>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_feedback'])) {
    $newRecordId = filter_input(INPUT_POST, 'new_record_id', FILTER_SANITIZE_NUMBER_INT);
    $newFeedbackText = filter_input(INPUT_POST, 'new_feedback_text', FILTER_SANITIZE_STRING);
    $newFeedbackDate = filter_input(INPUT_POST, 'new_feedback_date', FILTER_SANITIZE_STRING);

    $stmt = $db->prepare("INSERT INTO feedback (record_id, feedback_text, feedback_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $newRecordId, $newFeedbackText, $newFeedbackDate);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "New feedback added successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
    header('Location: server_feedbacks.php');
    exit;
}
ob_end_flush();
?>
</div>
</div>

</body>
</html>