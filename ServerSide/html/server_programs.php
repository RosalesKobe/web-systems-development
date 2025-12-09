<?php
session_start();
require("../php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: server_index.php");
  exit;
}

$userID = $_SESSION['user_id'];
$firstName = '';
$lastName = '';

// Get logged-in admin name
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

// Fetch programs
$programsData = [];
$stmt = $db->prepare("
  SELECT o.program_id, o.administrator_id, a.firstName AS administrator_name, o.program_name, o.start_datee, o.end_date 
  FROM ojtprograms o
  JOIN admindetails a ON o.administrator_id = a.administrator_id
");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $programsData[] = $row;
}
$stmt->close();

// Fetch admin dropdown options
$adminOptions = [];
$stmt = $db->prepare("SELECT administrator_id, firstName, lastName FROM admindetails");
$stmt->execute();
$result = $stmt->get_result();
while ($admin = $result->fetch_assoc()) {
  $adminOptions[$admin['administrator_id']] = $admin['firstName'] . ' ' . $admin['lastName'];
}
$stmt->close();

// Add Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_program'])) {
  $administrator_id = (int) $_POST['administrator_id'];
  $program_name = trim($_POST['program_name']);
  $start_date = $_POST['start_datee'];
  $end_date = $_POST['end_date'];

  if ($end_date < $start_date) {
    $_SESSION['error_message'] = "End date must be later than the start date.";
    header("Location: server_programs.php");
    exit;
  }

  $stmt = $db->prepare("INSERT INTO ojtprograms (administrator_id, program_name, start_datee, end_date) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $administrator_id, $program_name, $start_date, $end_date);

  if ($stmt->execute()) {
    $_SESSION['success_message'] = "New program added successfully!";
  } else {
    $_SESSION['error_message'] = "Error adding program: " . $stmt->error;
  }
  $stmt->close();
  header("Location: server_programs.php");
  exit;
}

// Edit Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_program'])) {
  $program_id = (int) $_POST['program_id'];
  $administrator_id = (int) $_POST['administrator_id'];
  $program_name = trim($_POST['program_name']);
  $start_date = $_POST['start_datee'];
  $end_date = $_POST['end_date'];

  if ($end_date <= $start_date) {
    $_SESSION['error_message'] = "End date must be later than the start date.";
    header("Location: server_programs.php");
    exit;
  }

  $stmt = $db->prepare("UPDATE ojtprograms SET administrator_id = ?, program_name = ?, start_datee = ?, end_date = ? WHERE program_id = ?");
  $stmt->bind_param("isssi", $administrator_id, $program_name, $start_date, $end_date, $program_id);

  if ($stmt->execute()) {
    $_SESSION['success_message'] = "Program updated successfully!";
  } else {
    $_SESSION['error_message'] = "Error updating program: " . $stmt->error;
  }
  $stmt->close();
  header("Location: server_programs.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADMIN - PROGRAMS PAGE</title>
  <link rel="stylesheet" href="../css/style_server_programs.css">
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
        <li class="item active"><a href="server_programs.php">Programs</a></li>
        <li class="item"><a href="server_records.php">Records</a></li>
        <li class="item"><a href="server_feedbacks.php">Feedback</a></li>
        <li class="item"><a href="server_addIntern.php">Add Intern</a></li>
        <a href="server_logout.php" class="logout-button">Logout</a>
      </ul>
    </div>
  </div>

  <div class="page-content">
    <div class="header">Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!</div>

    <?php if (isset($_SESSION['success_message'])): ?>
      <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px;">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
      </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
      <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px;">
        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
      </div>
    <?php endif; ?>

    <div class="label-wrapper">
      <!-- Add Program Form -->
      <div id="addForm" style="display:none;">
        <h2>Add New Program</h2>
        <form action="server_programs.php" method="post">
          <label for="administrator_id">Administrator:</label>
          <select name="administrator_id" id="administrator_id">
            <?php foreach ($adminOptions as $id => $name): ?>
              <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="program_name">Program Name:</label>
          <input type="text" name="program_name" id="program_name" required>
          <label for="start_datee">Start Date:</label>
          <input type="date" name="start_datee" id="start_datee" required>
          <label for="end_date">End Date:</label>
          <input type="date" name="end_date" id="end_date" required>
          <input type="submit" name="add_program" value="Add Program">
          <button type="button" onclick="cancelAddProgram()">Cancel</button>
        </form>
      </div>

      <!-- Edit Program Form -->
      <div id="editForm" style="display:none;">
        <h2>Edit Program</h2>
        <form action="server_programs.php" method="post">
          <label>Program ID:</label>
          <input id="editForm-program_id" name="program_id" readonly>
          <input type="hidden" id="editForm-administrator_id" name="administrator_id">
          <label>Program Name:</label>
          <input type="text" id="editForm-program_name" name="program_name" required>
          <label>Start Date:</label>
          <input type="date" id="editForm-start_datee" name="start_datee" required>
          <label>End Date:</label>
          <input type="date" id="editForm-end_date" name="end_date" required>
          <input type="submit" name="edit_program" value="Update Program">
          <button type="button" onclick="cancelEditProgram()">Cancel</button>
        </form>
      </div>

      <div class="image-container">
        <img src="\web-systems-development\ServerSide\img\Saint_Louis_University_PH_Logo.svg.png" alt="Profile Image">
      </div>

      <!-- Programs Table -->
       <div class="table-container">
      <table>
        <tr>
          <th>Program ID</th>
          <th>Administrator</th>
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
              <button onclick="editProgram(
                '<?php echo $program['program_id']; ?>',
                '<?php echo $program['administrator_id']; ?>',
                '<?php echo htmlspecialchars(addslashes($program['program_name'])); ?>',
                '<?php echo $program['start_datee']; ?>',
                '<?php echo $program['end_date']; ?>'
              )">Edit</button>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($programsData)): ?>
          <tr><td colspan="6">No programs found.</td></tr>
        <?php endif; ?>
      </table>
        </div>
      <div class="add-button-container">
      <button type="button" onclick="addProgram()">Add New Program</button>
      </div>
    </div>
  </div>
</div>

<script>
function addProgram() {
  const form = document.getElementById('addForm');
  form.style.display = 'block';
  form.scrollIntoView({ behavior: 'smooth' });
}
function cancelAddProgram() {
  document.getElementById('addForm').style.display = 'none';
}
function editProgram(id, adminId, name, start, end) {
  document.getElementById('editForm-program_id').value = id;
  document.getElementById('editForm-administrator_id').value = adminId;
  document.getElementById('editForm-program_name').value = name;
  document.getElementById('editForm-start_datee').value = start;
  document.getElementById('editForm-end_date').value = end;
  const form = document.getElementById('editForm');
  form.style.display = 'block';
  form.scrollIntoView({ behavior: 'smooth' });
}
function cancelEditProgram() {
  document.getElementById('editForm').style.display = 'none';
}
</script>
</body>
</html>
