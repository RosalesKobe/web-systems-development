<?php
session_start();
require("C:/wamp64/www/web-systems-development/ServerSide/php/db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: web-systems-development/ServerSide/html/server_index.php");
    exit;
}

$userID = $_SESSION['user_id'];
$firstName = '';
$lastName = '';

$detailsTable = 'admindetails';
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

$filterName = isset($_GET['filterName']) ? $_GET['filterName'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'InternFirstName';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

// Define $query at the top level of the script
$query = "SELECT 
        i.firstName AS InternFirstName,
        i.lastName AS InternLastName, 
        i.email, 
        i.address, 
        i.School, 
        i.other_intern_details,
        a.firstName AS AdviserFirstName,
        a.lastName AS AdviserLastName
    FROM 
        interndetails i
    JOIN 
        adviserdetails a ON i.adviser_id = a.adviser_id";

// Append the WHERE clause if a filter is set
if (!empty($filterName)) {
    $query .= " WHERE i.firstName LIKE CONCAT('%', ?, '%') OR i.lastName LIKE CONCAT('%', ?, '%')";
}

// Append the ORDER BY clause
$query .= " ORDER BY " . $sort . " " . $order;

// Prepare the statement
$stmt = $db->prepare($query);
if (!$stmt) {
    // Handle error here
    die('Prepare failed: ' . $db->error);
}

// Bind parameters if needed
if (!empty($filterName)) {
    $stmt->bind_param("ss", $filterName, $filterName);
}

// Execute the statement
if (!$stmt->execute()) {
    // Handle error here
    die('Execute failed: ' . $stmt->error);
}

// Fetch the results
$result = $stmt->get_result();
$internsData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $internsData[] = $row;
    }
} else {
    $internsData = [];
}

// Close the statement
$stmt->close();

// Define the sort_order and sort_link functions
function sort_order($current_order) {
    return $current_order == 'ASC' ? 'desc' : 'asc';
}

function sort_link($column, $current_sort, $current_order) {
  $order = $column == $current_sort ? sort_order($current_order) : 'asc';
  return "server_interns.php?sort=" . $column . "&order=" . $order;
}

// Use PHP to generate the URLs ahead of time
$firstNameSortUrl = sort_link('InternFirstName', $sort, $order);
$lastNameSortUrl = sort_link('InternLastName', $sort, $order);
$emailSortUrl = sort_link('email', $sort, $order);
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>ADMIN - INTERNS PAGE</title>
  <link rel="stylesheet" href="/web-systems-development/ServerSide/css/style_server_interns.css">

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
        <li class="item active">
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
        <li class="item">
          <a href="server_addIntern.php">Add Intern</a>
        </li>
        <a href="server_logout.php" class="logout-button">Logout</a>  
      </ul>
    </div>
  </div>
  <div class="page-content">
    <div class="header">Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!</div>
    <form action="server_interns.php" method="get">
      <label for="filterName">Filter by Name:</label>
      <input type="text" id="filterName" name="filterName" value="<?php echo isset($_GET['filterName']) ? htmlspecialchars($_GET['filterName']) : ''; ?>">
      <input type="submit" value="Filter">
    </form>

    <table>
      <tr>
      <th class="sortable" onmouseover="hovered('firstName')" onclick="sortColumn('firstName')">First Name</th>
<th class="sortable" onmouseover="hovered('lastName')" onclick="sortColumn('lastName')">Last Name</th>
<th class="sortable" onmouseover="hovered('email')" onclick="sortColumn('email')">Email</th>

        <th>Address</th>
        <th>School</th>
        <th>Other Intern Details</th>
        <th>Adviser First Name</th>
        <th>Adviser Last Name</th>
      </tr>
      <?php foreach ($internsData as $intern): ?>
        <tr>
          <td><?php echo htmlspecialchars($intern['InternFirstName']); ?></td>
          <td><?php echo htmlspecialchars($intern['InternLastName']); ?></td>
          <td><?php echo htmlspecialchars($intern['email']); ?></td>
          <td><?php echo htmlspecialchars($intern['address']); ?></td>
          <td><?php echo htmlspecialchars($intern['School']); ?></td>
          <td><?php echo htmlspecialchars($intern['other_intern_details']); ?></td>
          <td><?php echo htmlspecialchars($intern['AdviserFirstName']); ?></td>
          <td><?php echo htmlspecialchars($intern['AdviserLastName']); ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($internsData)): ?>
        <tr>
          <td colspan="8">No interns found.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>
</div>
<script>
var hoverFlags = { firstName: false, lastName: false, email: false };

function hovered(column) {
  // Set the hover flag for the column to true
  hoverFlags[column] = true;
}

function sortColumn(column) {
  // Check if the column was hovered over before sorting
  if (hoverFlags[column]) {
    var sortURL = '';
    switch(column) {
      case 'firstName':
        sortURL = '<?php echo sort_link('InternFirstName', $sort, $order); ?>';
        break;
      case 'lastName':
        sortURL = '<?php echo sort_link('InternLastName', $sort, $order); ?>';
        break;
      case 'email':
        sortURL = '<?php echo sort_link('email', $sort, $order); ?>';
        break;
    }
    window.location.href = sortURL;
  }
}

// Optional: Reset the hover flag when the mouse leaves the column header
function resetHover(column) {
  hoverFlags[column] = false;
}
</script>
</body>

</html>


