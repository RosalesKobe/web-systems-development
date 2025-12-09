<?php
session_start();
require("../php/db.php");

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: server_index.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch admin's name
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

// Filtering and sorting
$filterName = $_GET['filterName'] ?? '';
$sort = $_GET['sort'] ?? 'InternFirstName';
$order = ($_GET['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

// Whitelist for sorting columns
$allowedSorts = ['InternFirstName', 'InternLastName', 'email', 'classCode'];
if (!in_array($sort, $allowedSorts)) {
    $sort = 'InternFirstName';
}


// Define $query at the top level of the script
$query = "SELECT
    i.firstName AS InternFirstName,
    i.lastName AS InternLastName,
    i.email,
    i.classCode,
    a.firstName AS AdviserFirstName,
    a.lastName AS AdviserLastName,
    CASE
        WHEN ir.checklist_completed = 0 THEN 'Not yet Submitted'
        WHEN ir.checklist_completed = 1 THEN 'Submitted'
        ELSE 'Unknown Status'
    END AS RequirementsStatus
FROM interndetails i
JOIN adviserdetails a ON i.adviser_id = a.adviser_id
JOIN internshiprecords ir ON i.intern_id = ir.intern_id";

// Filter condition
if (!empty($filterName)) {
    $query .= " WHERE i.firstName LIKE CONCAT('%', ?, '%') OR i.lastName LIKE CONCAT('%', ?, '%')";
}

$query .= " ORDER BY $sort $order";
$stmt = $db->prepare($query);

if (!empty($filterName)) {
    $stmt->bind_param("ss", $filterName, $filterName);
}

$stmt->execute();
$result = $stmt->get_result();

$internsData = [];
while ($row = $result->fetch_assoc()) {
    $internsData[] = $row;
}

$stmt->close();

// Sorting helper functions
function sort_order($current_order) {
    return $current_order == 'ASC' ? 'desc' : 'asc';
}

function sort_link($column, $current_sort, $current_order) {
    $order = $column == $current_sort ? sort_order($current_order) : 'asc';
    return "server_interns.php?sort=$column&order=$order";
}

// Precomputed URLs
$firstNameSortUrl = sort_link('InternFirstName', $sort, $order);
$lastNameSortUrl = sort_link('InternLastName', $sort, $order);
$emailSortUrl = sort_link('email', $sort, $order);
$classCodeSortUrl = sort_link('classCode', $sort, $order);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADMIN - INTERNS PAGE</title>
    <link rel="stylesheet" href="../css/style_server_interns.css">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
</head>
<body>
<div class="task-manager">
    <div class="left-bar">
        <div class="upper-part"></div>
        <div class="left-content">
            <ul class="action-list">
                <li class="item"><a href="server_home.php">Home</a></li>
                <li class="item active"><a href="server_interns.php">Interns</a></li>
                <li class="item"><a href="server_advisers.php">Advisers</a></li>
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
    <div class="image-container">
        <img src="../img/Saint_Louis_University_PH_Logo.svg.png" alt="SLU Logo">
    </div>

    <div class="header">Welcome <?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?>!</div>

        <form action="server_interns.php" method="get">
            <label for="filterName">Filter by Name:</label>
            <input type="text" id="filterName" name="filterName" value="<?php echo htmlspecialchars($filterName); ?>">
            <input type="submit" value="Filter">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        </form>


<div class="table-container">
    <table>
        <tr>
            <th class="sortable"><a href="<?php echo $firstNameSortUrl; ?>">First Name</a></th>
            <th class="sortable"><a href="<?php echo $lastNameSortUrl; ?>">Last Name</a></th>
            <th class="sortable"><a href="<?php echo $emailSortUrl; ?>">Email</a></th>
            <th class="sortable"><a href="<?php echo $classCodeSortUrl; ?>">Class Code</a></th>
            <th>Adviser First Name</th>
            <th>Adviser Last Name</th>
            <th>Requirements Status</th>
        </tr>
        <?php if (empty($internsData)): ?>
            <tr><td colspan="7">No interns found.</td></tr>
        <?php else: ?>
            <?php foreach ($internsData as $intern): ?>
                <tr>
                    <td><?php echo htmlspecialchars($intern['InternFirstName']); ?></td>
                    <td><?php echo htmlspecialchars($intern['InternLastName']); ?></td>
                    <td><?php echo htmlspecialchars($intern['email']); ?></td>
                    <td><?php echo htmlspecialchars($intern['classCode']); ?></td>
                    <td><?php echo htmlspecialchars($intern['AdviserFirstName']); ?></td>
                    <td><?php echo htmlspecialchars($intern['AdviserLastName']); ?></td>
                    <td><?php echo htmlspecialchars($intern['RequirementsStatus']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>

    </div>
</div>
</body>
</html>


