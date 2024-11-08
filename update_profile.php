<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Retrieve user information from the session
$user = $_SESSION['user'];
$username = $user['username'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $nic = isset($_POST['nic']) ? $_POST['nic'] : '';
    $new_username = isset($_POST['username']) ? $_POST['username'] : '';
    $designation = isset($_POST['designation']) ? $_POST['designation'] : '';

    // Prepare and bind the update statement
    $stmt = $conn->prepare("UPDATE userdetails SET name = ?, nic = ?, username = ?, designation = ? WHERE username = ?");
    $stmt->bind_param("sssss", $name, $nic, $new_username, $designation, $username);

    // Execute the statement
    if ($stmt->execute()) {
        // Update the session with the new information
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['username'] = $new_username;
        $_SESSION['user']['designation'] = $designation;

        
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        // Display an error message
        $error = $stmt->error;
        echo "<script>alert('Error: $error');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
