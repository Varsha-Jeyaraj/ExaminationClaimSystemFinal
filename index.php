<?php
session_start();
$error_message = '';

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the error message after displaying it
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Claim System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="index-page">
    <div class="login-container">
        
        <h2>Examination Claim System</h2>
        <img src="University Logo.png" alt="University">

        <form action="login.php" method="POST">



        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']); // Sanitize for display
            echo "<div class='alert alert-success'>$message</div>";
        }
        ?>

 
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block w-100">Login</button>
           
        
        </form>
        <br><br><br>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
<?php
    echo "<br><br><br><br><br>"; 
    include 'footer.php'; 
?>



