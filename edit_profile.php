<?php
include 'config.php';
// Start the session
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Retrieve user information from the session
$user = $_SESSION['user'];
$username = $user['username']; 

// Query to retrieve user details for the logged-in user
$sql = "SELECT name, username, nic, designation FROM userdetails WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user details were found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $email = $row["username"];
    $nic = $row["nic"];
    $designation = $row["designation"];
    
    
} else {
    echo "User details not found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            padding-top: 56px;
        }
        .profile-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }

        
    </style>
</head>
<body>


<?php 
    if ($user['usertype']== "Management Assistant")
        include 'navbar.php'; 
    else
        include 'navbar-staff.php';
?>


<!-- Edit Profile Section -->
<div class="container mt-5">
    <div class="card">
        <div class="profile-header text-center mb-5">
            <h3 class="text-center">Edit Profile</h3>
        </div>
        
        <form action="update_profile.php" method="POST" class="mt-4">
            
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <label for="nic" class="form-label">NIC Number</label>
            <input type="text" class="form-control" id="nic" name="nic" 
                   value="<?php echo htmlspecialchars($nic); ?>" required>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <label for="username" class="form-label">Email</label>
            <input type="email" class="form-control" id="username" name="username" 
                   value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <label for="designation" class="form-label">Designation</label>
            <input type="text" class="form-control" id="designation" name="designation" 
                   value="<?php echo htmlspecialchars($designation); ?>" required>
        </div>
    </div>
</div>

    
           
            
            

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-custom">Save Changes</button>
                <a href="profile.php" class="btn btn-custom ms-3" style="background-color: #be0000; color: white; border-radius: 20px; padding: 10px 20px;">Cancel</a>

            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS Bundle (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
