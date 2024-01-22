<?php
session_start();
require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php");
//require("/Applications/XAMPP/xamppfiles/htdocs/web-systems-development/ServerSide/php/db.php");

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

// Fetch adviser details
$adviserOptions = '';
$adviserQuery = "SELECT adviser_id, firstName, lastName FROM adviserdetails ORDER BY lastName, firstName";
$result = $db->query($adviserQuery);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $adviserId = $row['adviser_id'];
        $adviserName = $row['firstName'] . ' ' . $row['lastName'];
        $adviserOptions .= "<option value='{$adviserId}'>{$adviserName}</option>";
    }
} else {
    $adviserOptions = '<option value="">No advisers found</option>';
}

// Fetch supervisor details
$supervisorOptions = '';
$supervisorQuery = "SELECT supervisor_id, firstName, lastName FROM supervisordetails ORDER BY lastName, firstName";
$supervisorResult = $db->query($supervisorQuery);

if ($supervisorResult->num_rows > 0) {
    while($row = $supervisorResult->fetch_assoc()) {
        $supervisorId = $row['supervisor_id'];
        $supervisorName = $row['firstName'] . ' ' . $row['lastName'];
        $supervisorOptions .= "<option value='{$supervisorId}'>{$supervisorName}</option>";
    }
} else {
    $supervisorOptions = '<option value="">No supervisors found</option>';
}

// Fetch company details
$companyOptions = '';
$companyQuery = "SELECT company_id, companyName FROM company ORDER BY companyName";
$companyResult = $db->query($companyQuery);

if ($companyResult->num_rows > 0) {
    while($row = $companyResult->fetch_assoc()) {
        $companyId = $row['company_id'];
        $companyName = $row['companyName'];
        $companyOptions .= "<option value='{$companyId}'>{$companyName}</option>";
    }
} else {
    $companyOptions = '<option value="">No companies found</option>';
}


// Function to look up ID by first and last name
function lookupAdviserIdByFullName($db, $adviserFirstName, $adviserLastName) {
  $query = "SELECT adviser_id FROM adviserdetails WHERE firstName = ? AND lastName = ?";
  $stmt = $db->prepare($query);
  if (!$stmt) {
    die("Error preparing statement: " . $db->error);
  }
  $stmt->bind_param("ss", $adviserFirstName, $adviserLastName); // Use "ss" for two string parameters
  $stmt->execute();
  $result = $stmt->get_result();
  $adviserId = null;
  if ($row = $result->fetch_assoc()) {
      $adviserId = $row['adviser_id']; 
  }
  $stmt->close();
  return $adviserId;
}

function lookupSupervisorIdByFullName($db, $supervisorFirstName, $supervisorLastName) {
  $query = "SELECT supervisor_id FROM supervisordetails WHERE firstName = ? AND lastName = ?";
  $stmt = $db->prepare($query);
  if (!$stmt) {
    die("Error preparing statement: " . $db->error);
  }
  $stmt->bind_param("ss", $supervisorFirstName, $supervisorLastName);
  $stmt->execute();
  $result = $stmt->get_result();
  $supervisorId = null;
  if ($row = $result->fetch_assoc()) {
      $supervisorId = $row['supervisor_id']; 
  }
  $stmt->close();
  return $supervisorId;
}

function lookupCompanyIdByName($db, $companyName) {
  $query = "SELECT company_id FROM company WHERE companyName = ?";
  $stmt = $db->prepare($query);
  if (!$stmt) {
    die("Error preparing statement: " . $db->error);
  }
  $stmt->bind_param("s", $companyName);
  $stmt->execute();
  $result = $stmt->get_result();
  $companyId = null;
  if ($row = $result->fetch_assoc()) {
      $companyId = $row['company_id'];
  }
  $stmt->close();
  return $companyId;
}

// Check if a POST request with a file upload has been made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['csv_file'])) {
  $csvFile = $_FILES['csv_file']['tmp_name'];
  if (($handle = fopen($csvFile, 'r')) !== FALSE) {
      fgetcsv($handle); // Skip the header line

      while (($row = fgetcsv($handle)) !== FALSE) {
          list($username, $password, $adviserFullName, $supervisorFullName, $companyName, $firstName, $lastName, $email, $classCode) = $row;

          // Separate the full names into first and last names
          list($adviserFirstName, $adviserLastName) = explode(' ', $adviserFullName, 2);
          list($supervisorFirstName, $supervisorLastName) = explode(' ', $supervisorFullName, 2);
          
          $adviserId = lookupAdviserIdByFullName($db, $adviserFirstName, $adviserLastName);
          $supervisorId = lookupSupervisorIdByFullName($db, $supervisorFirstName, $supervisorLastName);
          $companyId = lookupCompanyIdByName($db, $companyName);

          if ($adviserId === null || $supervisorId === null || $companyId === null) {
              // Handle error: one of the entities was not found by name
              // You might want to log this error or notify the user
              continue; // Skip this row or handle as appropriate
          }

          // Insert into `users` table
          $insertUserQuery = "INSERT INTO users (username, password, user_type) VALUES (?, ?, 'Intern')";
          $stmt = $db->prepare($insertUserQuery);
          $passwordHash = password_hash($password,  PASSWORD_BCRYPT, ["version" => '2b']); // Hash the password
          $stmt->bind_param("ss", $username, $passwordHash);
          $stmt->execute();
          $lastUserId = $stmt->insert_id;
          $stmt->close();

          // Insert into `interndetails` table
          $insertInternDetailsQuery = "INSERT INTO interndetails (user_id, adviser_id, supervisor_id, company_id, firstName, lastName, email, classCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = $db->prepare($insertInternDetailsQuery);
          $stmt->bind_param("iiiissss", $lastUserId, $adviserId, $supervisorId, $companyId, $firstName, $lastName, $email, $classCode);
          $stmt->execute();
          $stmt->close();   
      }
      fclose($handle);
  }
  // Redirect or display a success message
  echo "Bulk upload successful!";
  // Redirect to a confirmation page or back to the form
  // header('Location: success_page.php');
  exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize form data
  $username = $db->real_escape_string($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ["version" => '2b']);
  $userType = 'Intern'; // Set user type to 'Intern' by default
  $adviserId = $db->real_escape_string($_POST['adviser_id']);
  $supervisorId = $db->real_escape_string($_POST['supervisor_id']);
  $companyId = $db->real_escape_string($_POST['company_id']);
  $firstName = $db->real_escape_string($_POST['first_name']);
  $lastName = $db->real_escape_string($_POST['last_name']);
  $email = $db->real_escape_string($_POST['email']);
  $classCode = $db->real_escape_string($_POST['classCode']);
  // $requirements = $db->real_escape_string($_POST['requirements']);

  // Check if the username or email already exists
// Check if the username already exists in the 'users' table
$checkUsernameQuery = "SELECT username FROM users WHERE username = ?";
$usernameStmt = $db->prepare($checkUsernameQuery);
if ($usernameStmt === false) {
    die("Failed to prepare the statement: " . htmlspecialchars($db->error));
}

// Bind parameters and execute the statement for username check
$usernameStmt->bind_param("s", $username);
if (!$usernameStmt->execute()) {
    die("Execute failed: " . htmlspecialchars($usernameStmt->error));
}

$usernameResult = $usernameStmt->get_result();
$usernameStmt->close();

if ($usernameResult->num_rows > 0) {
    echo "Error: Username already exists";
    exit; // Stop the script if username already exists
}

// Check if the email already exists in the 'interndetails' table
$checkEmailQuery = "SELECT email FROM interndetails WHERE email = ?";
$emailStmt = $db->prepare($checkEmailQuery);
if ($emailStmt === false) {
    die("Failed to prepare the statement: " . htmlspecialchars($db->error));
}

// Bind parameters and execute the statement for email check
$emailStmt->bind_param("s", $email);
if (!$emailStmt->execute()) {
    die("Execute failed: " . htmlspecialchars($emailStmt->error));
}

$emailResult = $emailStmt->get_result();
$emailStmt->close();

if ($emailResult->num_rows > 0) {
    echo "Error: Email already exists";
    exit; // Stop the script if email already exists
}
 else {
      // Proceed with inserting new user since username and email are unique
      $insertUserQuery = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
      $stmt = $db->prepare($insertUserQuery);
      $stmt->bind_param("sss", $username, $password, $userType);
      
      if ($stmt->execute()) {
          $lastUserId = $stmt->insert_id; // Get the last inserted user id

          // Insert into 'interndetails' table
          $insertInternDetailsQuery = "INSERT INTO interndetails (user_id, adviser_id, supervisor_id, company_id, firstName, lastName, email, classCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = $db->prepare($insertInternDetailsQuery);
          $stmt->bind_param("iiiissss", $lastUserId, $adviserId, $supervisorId, $companyId, $firstName, $lastName, $email, $classCode);        
          
          if ($stmt->execute()) {
              echo "New intern added successfully";
          } else {
              echo "Error: " . $stmt->error;
          }
          $stmt->close();
      } else {
          echo "Error: " . $stmt->error;
      }
  }
  $db->close();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADMIN - ADD INTERN PAGE</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_addIntern.css">
  <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
          <a href="server_feedbacks.php">Feedback</a>
        </li>
        <li class="item active">
          <a href="server_addIntern.php">Add Intern</a>
        </li>
        <a href="server_logout.php" class="logout-button">Logout</a>
      </ul>
    </div>
  </div>
  <div class="page-content">
    <div class="header">Add Intern</div>
    <form id="add-user-form" method="POST" action="/web-systems-development/ServerSide/html/server_addIntern.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="user-type">User Type</label>
        <input type="text" id="user-type" name="user_type" value="Intern" readonly>
      </div>
      <div class="form-group">
  <label for="adviser-id">Adviser Name</label>
  <select id="adviser-id" name="adviser_id" required>
    <?php echo $adviserOptions; ?>
  </select>
</div>
<div class="form-group">
  <label for="supervisor-id">Supervisor Name</label>
  <select id="supervisor-id" name="supervisor_id" required>
    <?php echo $supervisorOptions; ?>
  </select>
</div>
<div class="form-group">
  <label for="company-id">Company Name</label>
  <select id="company-id" name="company_id" required>
    <?php echo $companyOptions; ?>
  </select>
</div>
      <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first_name" required>
      </div>
      <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last_name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="class-code">Class Code</label>
        <input type="text" id="classCode" name="classCode">
      </div>
      <div class="form-group">
        <input type="submit" class="btn" value="Add User">
      </div>
    </form>
    <form id="bulk-upload-form" method="POST" action="/web-systems-development/ServerSide/html/server_addIntern.php" enctype="multipart/form-data">
    <div class="form-group">
      <label for="csv-file">Upload Interns CSV</label>
      <input type="file" id="csv-file" name="csv_file" accept=".csv" required>
    </div>
    <div class="form-group">
      <input type="submit" class="btn" value="Upload CSV">
    </div>
  </form>
</div>
  </div>
</div>
</body>
</html>