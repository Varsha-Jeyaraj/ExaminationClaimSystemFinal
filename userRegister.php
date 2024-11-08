<?php
include 'config.php';

// Retrieve form data
$name = $_POST['Name'] ?? '';
$nic = $_POST['nic'] ?? '';
$designation = $_POST['designation'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$usertype = $_POST['usertype'] ?? '';

// Check for empty fields
if (empty($name) || empty($nic) || empty($designation) || empty($username) || empty($password) || empty($usertype)) {
    header('Location: registerform.php?error=' . urlencode('All fields are required!'));
    exit;
}

// Check if the username already exists
$checkQuery = "SELECT COUNT(*) FROM userdetails WHERE username = ? UNION SELECT COUNT(*) FROM users WHERE username = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ss", $username, $username);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();

if ($count > 0) {
    $checkStmt->close();
    header('Location: registerform.php?error=' . urlencode('  User registered not successfully!  Username already exists!'));
    exit;
}
$checkStmt->close();

// Prepare and execute the first SQL query
$sql1 = "INSERT INTO userdetails (username, name, nic, designation) VALUES (?, ?, ?, ?)";
$statement1 = $conn->prepare($sql1);

if (!$statement1) {
    header('Location: registerform.php?error=' . urlencode("Statement preparation failed: " . $conn->error));
    exit;
}

$statement1->bind_param("ssss", $username, $name, $nic, $designation);

if (!$statement1->execute()) {
    header('Location: registerform.php?error=' . urlencode("Insert Data Error: " . $statement1->error));
    exit;
}

// Prepare and execute the second SQL query
$sql2 = "INSERT INTO users (username, password, usertype) VALUES (?, ?, ?)";
$statement2 = $conn->prepare($sql2);

if (!$statement2) {
    header('Location: registerform.php?error=' . urlencode("Statement preparation failed: " . $conn->error));
    exit;
}

$statement2->bind_param("sss", $username, $password, $usertype);

if (!$statement2->execute()) {
    header('Location: registerform.php?error=' . urlencode("Insert Data Error: " . $statement2->error));
    exit;
}

// Close the statements
$statement1->close();
$statement2->close();

// Success message
header('Location: registerform.php?success=' . urlencode('User registered successfully!'));
exit;
?>
