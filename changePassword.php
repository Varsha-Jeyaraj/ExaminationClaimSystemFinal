<?php
session_start();
include 'config.php'; // Database configuration file
include 'navbar-staff.php'; // Optional, includes the navigation bar

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Fetch the username from the session data
$username = $_SESSION['user']['username'];
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate input
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "New passwords do not match.";
    } else {
        // Query the database for the user's current password
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        $stmt->close();

        // Verify old password and hash new password if old password is correct
        if (password_verify($oldPassword, $dbPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $update_sql = "UPDATE users SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashedPassword, $username);

            // Check for successful update and redirect with success message
            if ($update_stmt->execute()) {
                session_destroy(); // Log out the user after password change
                header('Location: index.php?message=Password changed successfully. Please log in with your new password.');
                exit();
            } else {
                $errors[] = "Error updating password.";
            }
        } else {
            $errors[] = "Old password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Optional: Add custom styling -->
</head>
<body>
    <div class="container mt-5">
        <h2>Change Password</h2>

        <!-- Display any error messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Password Change Form -->
        <form method="POST">
            <div class="mb-3 col-md-4">
                <label for="oldPassword" class="form-label">Old Password:</label>
                <input type="password" name="oldPassword" id="oldPassword" class="form-control" required>
            </div>
            
            <div class="mb-3 col-md-4">
                <label for="newPassword" class="form-label">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" class="form-control" required>
            </div>
            
            <div class="mb-3 col-md-4">
                <label for="confirmPassword" class="form-label">Confirm New Password:</label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>

    <!-- Bootstrap JS (Optional for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
