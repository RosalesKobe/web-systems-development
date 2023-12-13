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

// Initialize $programsData
$programsData = [];

$stmt = $db->prepare("SELECT o.program_id, o.administrator_id, a.firstName AS administrator_name, o.program_name, o.start_datee, o.end_date FROM ojtprograms o
                      JOIN admindetails a ON o.administrator_id = a.administrator_id");
$stmt->execute();
$result = $stmt->get_result();


// Store the data in an array to use later in the HTML
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $programsData[] = $row;
    }
} else {
    $programsData = []; // Set $programsData as an empty array if no results
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_program'])) {
  // Sanitize and validate the input
  $administrator_id = filter_input(INPUT_POST, 'administrator_id', FILTER_SANITIZE_NUMBER_INT);
  $program_name = filter_input(INPUT_POST, 'program_name', FILTER_SANITIZE_STRING);
  $start_date = filter_input(INPUT_POST, 'start_datee', FILTER_SANITIZE_STRING);
  $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);

  // Prepare the SQL and bind parameters
  $stmt = $db->prepare("INSERT INTO ojtprograms (administrator_id, program_name, start_datee, end_date) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $administrator_id, $program_name, $start_date, $end_date);

  // Execute and check for successful insertion
  if ($stmt->execute()) {
      $message = "New program added successfully!";
      
      // Close the statement
      $stmt->close();

      // Redirect to prevent form resubmission
      header('Location: server_programs.php');
      exit();
  } else {
      $message = "Error: " . $stmt->error;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_program'])) {
  // Sanitize and validate the input
  $program_id = filter_input(INPUT_POST, 'program_id', FILTER_SANITIZE_NUMBER_INT);
  $administrator_id = filter_input(INPUT_POST, 'administrator_id', FILTER_SANITIZE_NUMBER_INT);
  $program_name = filter_input(INPUT_POST, 'program_name', FILTER_SANITIZE_STRING);
  $start_date = filter_input(INPUT_POST, 'start_datee', FILTER_SANITIZE_STRING); // Corrected field name
  $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);

  // Prepare the SQL and bind parameters for updating
  $stmt = $db->prepare("UPDATE ojtprograms SET administrator_id = ?, program_name = ?, start_datee = ?, end_date = ? WHERE program_id = ?");
  $stmt->bind_param("isssi", $administrator_id, $program_name, $start_date, $end_date, $program_id);

  // Execute and check for successful update
  if ($stmt->execute()) {
      echo "Program updated successfully!";
  } else {
      echo "Error updating program: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TEAMPOGI OJT ADMIN MOD</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_programs.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="task-manager">
  <div class="left-bar">
    <div class="upper-part">
      <!-- Optionally, you could put some content here like a logo or a user profile snippet -->
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
        <li class="item active">
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
     </div>
      <div class="label-wrapper">
        <!-- Add Program Form -->
        <div id="addForm" style="display:none;">
          <h2>Add New Program</h2>
          <form action="server_programs.php" method="post">
          <input type="hidden" name="program_id" id="program_id">
            <label for="administrator_id">Administrator ID:</label>
            <input type="text" name="administrator_id" id="administrator_id">
            <label for="program_name">Program Name:</label>
            <input type="text" name="program_name" id="program_name">
            <label for="start_datee">Start Date:</label>
            <input type="date" name="start_datee" id="start_datee">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date">
            <input type="submit" name="add_program" value="Add Program">
            <button type="button" onclick="cancelAddProgram()">Cancel</button>
          </form>
        </div>

        <!-- Edit Program Form -->
        <div id="editForm" style="display:none;">
          <h2>Edit Program</h2>
          <form action="server_programs.php" method="post">
     <input id="editForm-program_id" name="program_id">
     <input type="hidden" id="editForm-administrator_id" name="administrator_id">
     <input type="text" id="editForm-program_name" name="program_name">
     <input type="date" id="editForm-start_datee" name="start_datee">
     <input type="date" id="editForm-end_date" name="end_date">
     <input type="submit" name="edit_program" value="Update Program">
        <!-- Add Cancel button -->
        <button type="button" onclick="cancelEditProgram()">Cancel</button>
       </form>

        </div>
        <div class="image-container">
     <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
     </div>
      <!-- Table for displaying program data -->
      <table>
     <tr>
    <th>Program ID</th>
    <th>Administrator Name</th>
    <th>Program Name</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($programsData as $program): ?>
    <tr>
      <td><?php echo htmlspecialchars($program['program_id']); ?></td>
      <td><?php echo htmlspecialchars($program['administrator_name']); ?></td>
      <td><?php echo htmlspecialchars($program['program_name']); ?></td>
      <td><?php echo htmlspecialchars($program['start_datee']); ?></td>
      <td><?php echo htmlspecialchars($program['end_date']); ?></td>
      <td>
        <!-- Edit button for each row -->
        <button type="button" onclick="editProgram('<?php echo $program['program_id']; ?>', '<?php echo $program['administrator_id']; ?>', '<?php echo htmlspecialchars(addslashes($program['program_name'])); ?>', '<?php echo $program['start_datee']; ?>', '<?php echo $program['end_date']; ?>')">Edit</button>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (empty($programsData)): ?>
    <tr>
      <td colspan="6">No programs found.</td>
    </tr>
  <?php endif; ?>
</table>
      <!-- Add button below the table -->
      <button type="button" onclick="addProgram()">Add New Program</button>
    
  </div>
</div>
<script>
function addProgram() {
  var form = document.getElementById('addForm');
  form.style.display = form.style.display === 'none' ? 'block' : 'none';

    // Scroll to the add form
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function editProgram(programId, administratorId, programName, startDate, endDate) {
  document.getElementById('editForm-program_id').value = programId;
  document.getElementById('editForm-administrator_id').value = administratorId;
  document.getElementById('editForm-program_name').value = programName;
  document.getElementById('editForm-start_datee').value = startDate;
  document.getElementById('editForm-end_date').value = endDate;

  var form = document.getElementById('editForm');
  form.style.display = 'block';

  // Scroll to the edit form
  form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function cancelEditProgram() {
  var form = document.getElementById('editForm');
  form.style.display = 'none';
}

function cancelAddProgram() {
  var form = document.getElementById('addForm');
  form.style.display = 'none';
}
</script>
</body>
</html>

