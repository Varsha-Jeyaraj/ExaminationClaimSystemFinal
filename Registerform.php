<?php 
session_start();


if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}


$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">

    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px; 
            margin: 0 auto; 
            padding: 20px;
            background-color: #fff; 
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        }
    </style>
    <script>
        // Display an alert if there's an error or success message in the URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get('error');
            const successMessage = urlParams.get('success');

            if (errorMessage) {
                alert(errorMessage);
            } else if (successMessage) {
                alert(successMessage);
            }
        };
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container form-container">
        <h2 class="text-center">Registration Form</h2>
        <form action="userRegister.php" method="post">
            <div class="mb-3">
                <input type="text" name="Name" class="form-control" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nic" class="form-control" placeholder="NIC No" maxlength="12" required>
            </div>
            <div class="mb-3">
                <input type="text" name="designation" class="form-control" placeholder="Designation" required>
            </div>
            <div class="mb-3">
                <select id="usertype" name="usertype" class="form-select" required>
                    <option selected disabled value="">User Type</option>
                    <option>Management Assistant</option>
                    <option>Staff</option>
                    <option>Head</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
    
    <!-- Bootstrap JS (Optional: for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
