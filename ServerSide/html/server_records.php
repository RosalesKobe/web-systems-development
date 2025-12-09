<?php
session_start();
require("../php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: server_index.php");
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

// Initialize $internRecords
$internRecords = [];
$stmt = $db->prepare("SELECT 
        r.record_id AS RecordID,
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  // Get form data
  $internId = $_POST['internId'];
  $adviserId = $_POST['adviserId'];
  $programId = empty($_POST['programId']) ? null : $_POST['programId'];
  $supervisorId = $_POST['supervisorId'];
  $administratorId = $_POST['administratorId'];
  $hoursCompleted = $_POST['hoursCompleted'];
  $hoursRemaining = $_POST['hoursRemaining'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $recordStatus = $_POST['recordStatus'];


  // Check if a record for the same intern already exists
  $stmt = $db->prepare("SELECT * FROM internshiprecords WHERE intern_id = ?");
  $stmt->bind_param("i", $internId);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
      // Record exists, set error message
      $_SESSION['error_message'] = "A record for this intern already exists.";
      header("Location: server_records.php");
      exit;
  }
  $stmt->close();


      // Automatically set status to 'Completed' if hours remaining is 0 or negative
// Check if start_date is before end_date
if (strtotime($startDate) >= strtotime($endDate)) {
    $_SESSION['error_message'] = "The start date must be before the end date.";
    header("Location: server_records.php");
    exit;
}

// Validate hours remaining
if ($hoursRemaining < 0) {
    $_SESSION['error_message'] = "Hours remaining cannot be negative.";
    header("Location: server_records.php");
    exit;
}

// Only after passing the check
if ($hoursRemaining == 0) {
    $recordStatus = "Completed";
}
// Only insert after validations pass
$stmt = $db->prepare("INSERT INTO internshiprecords (intern_id, adviser_id, program_id, administrator_id, hours_completed, hours_remaining, start_date, end_date, record_status, supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiiiissssi", $internId, $adviserId, $programId, $administratorId, $hoursCompleted, $hoursRemaining, $startDate, $endDate, $recordStatus, $supervisorId);

 // Execute the query and check for errors
 if ($stmt->execute()) {
  $_SESSION['success_message'] = "New record created successfully";
} else {
  $_SESSION['error_message'] = "Error: " . $stmt->error;
}
$stmt->close();

// Redirect to the same page with a GET request
header("Location: server_records.php");
exit;
}





// Fetch Intern IDs
$internIds = [];
$query = "SELECT intern_id, firstName, lastName FROM interndetails";
if ($result = $db->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $internIds[$row['intern_id']] = $row['firstName'] . ' ' . $row['lastName'];
    }
}

// Fetch Adviser IDs
$adviserIds = [];
$query = "SELECT adviser_id, firstName, lastName FROM adviserdetails";
if ($result = $db->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $adviserIds[$row['adviser_id']] = $row['firstName'] . ' ' . $row['lastName'];
    }
}

// Fetch Administrator IDs
$administratorIds = [];
$query = "SELECT administrator_id, firstName, lastName FROM admindetails";
if ($result = $db->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $administratorIds[$row['administrator_id']] = $row['firstName'] . ' ' . $row['lastName'];
    }
}

?>






















<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>ADMIN - RECORDS PAGE</title>
  <link rel="stylesheet" href="../css/style_server_records.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
.alert {
  padding: 12px;
  margin: 15px 0;
  border-radius: 5px;
  font-weight: bold;
  text-align: center;
}

.alert.success {
  background-color: #d4edda;
  color: #155724;
}

.alert.error {
  background-color: #f8d7da;
  color: #721c24;
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
        <li class="item active">
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
    </div>
    <div class="image-container">
    <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
  </div>
  <table>
  <tr>
    <th>Record ID</th>
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
    <td><?php echo htmlspecialchars($intern['RecordID']); ?></td>
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
      <td colspan="8">No records found.</td>
    </tr>
  <?php endif; ?>
</table>
<!-- Button to Open the Modal -->
<button type="button" id="myBtn">Add New Internship Record</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <form action="server_records.php" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <h2>Add Internship Record</h2>
    <label for="internId">Intern Name:</label>
<select id="internId" name="internId" required>
    <?php foreach ($internIds as $id => $name): ?>
        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
    <?php endforeach; ?>
</select><br>

<label for="adviserId">Adviser Name:</label>
<select id="adviserId" name="adviserId" required>
    <?php foreach ($adviserIds as $id => $name): ?>
        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
    <?php endforeach; ?>
</select><br>

<label for="administratorId">Administrator Name:</label>
<select id="administratorId" name="administratorId" required>
    <?php foreach ($administratorIds as $id => $name): ?>
        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
    <?php endforeach; ?>
</select><br>

<label for="hoursCompleted">Hours Completed:</label>
<input type="number" id="hoursCompleted" name="hoursCompleted" value="0" readonly><br>

    <label for="hoursRemaining">Hours Remaining:</label>
    <input type="number" id="hoursRemaining" name="hoursRemaining" value="100" readonly min="0"> 
    <!-- change value of "100" to required ojt hours -->

    <label for="programId">Program:</label>
<select id="programId" name="programId" required>
  <?php if (!empty($programIds)): ?>
    <?php foreach ($programIds as $id => $program): ?>
      <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($program); ?></option>
    <?php endforeach; ?>
  <?php else: ?>
      <option disabled selected>No programs available</option>
  <?php endif; ?>
</select><br>
    <input type="hidden" id="supervisorId" name="supervisorId" value="1">

    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate" name="startDate" required><br>

    <label for="endDate">End Date:</label>
    <input type="date" id="endDate" name="endDate" required><br>

    <label for="recordStatus">Record Status:</label>
    <select id="recordStatus" name="recordStatus">
        <option value="In Progress">In Progress</option>
    </select><br>

    <input type="submit" name="submit" value="Add Record">
    </form>
  </div>
</div>
  </div>
</div>
<script>
  // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
  </script>

<?php if (isset($_SESSION['success_message'])): ?>
  <div class="alert success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
  <div class="alert error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>
</body>
</html>
