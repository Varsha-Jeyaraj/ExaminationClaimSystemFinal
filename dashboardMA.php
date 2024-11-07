<?php
// Start the session
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Retrieve user information from the session
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #0056b3;

        }
        .header {
            background-color: rgba(0, 123, 255, 0.8); /* Semi-transparent primary color */
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9); /* White background for cards */
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .text-center{

            
        }

        /* New add */
        .carousel-item {
            height: 80vh;
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .carousel-item h1 {
            font-size: 3rem;
        }
        .carousel-item p {
            font-size: 1.2rem;
        }
        .carousel-item .btn {
            background-color: transparent;
            border: 1px solid white;
            color: white;
        }
        .about-section {
            background-color: #e7f8ff;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
    
        }
        .about-section h2 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            color: #343a40;
            font-size: 2rem;
        }
        .about-section h5 {
            font-family: 'Open Sans', sans-serif;
            color: #6c757d;
            margin-top: 1rem;
            font-size: 1.1rem;
            line-height: 1.6;
            font-weight: 400;
        }

        .footer {
            background-color: #007bff; 
            color: #fff;
            text-align: center; 
            padding: 15px 0;
            font-size: 0.9em;
            position: fixed;
            width: 100%;
            bottom: 0;
}

    </style>
</head>
<body>
 <!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">U O J | D C S</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" 
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">  
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($user['usertype']." : ".$user['name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- New add -->
   <!-- Hero Section with Carousel -->
   <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="3400" data-pause="false">
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url('DashIMG1.jpg'); display: flex; justify-content:center; align-items:center;">
                <h4>WELCOME TO</h4>
                <h1>Examination Claim System</h1>
                <p>Department of Computer Science at the University of Jaffna.</p>
               
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url('DashIMG2.jpg'); display: flex; justify-content:center; align-items:center;">
                <h4>WELCOME TO</h4>
                <h1>Examination Claim System</h1>
                <p>Department of Computer Science at the University of Jaffna.</p>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url('DashIMG4.jpg'); display: flex; justify-content:center; align-items:center;">
                <h4>WELCOME TO</h4>
                <h1>Examination Claim System</h1>
                <p>Department of Computer Science at the University of Jaffna.</p>
                
            </div>
        </div>

    </div>

   <!-- About Area -->
    <div class="container my-5">
        <div class="about-section">
            <h2>About</h2>
            <h5>
                This system is intended to streamline the payment process for 
                Professors and Lecturers (both permanent and visiting) involved in setting, moderating, marking 
                examination papers and conducting examinations. It also includes the payment for Technical 
                Officers and Lab attendants who are involved in the examination process.
            </h5>
        </div>
    </div>
   

    <!-- Main Content Area -->
    <div class="container mt-5">
        <!-- Header Section -->
        
        <!-- Additional Links Card -->
        <div class="row text-center mt-4">
        <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form 1-Preparation</h5>
                        <a href="Form_1.php" class="btn btn-custom">Fill Form</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form 2-Evaluation</h5>
                        <a href="Form_2.php" class="btn btn-custom">Fill Form</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form 3-Conducting</h5>
                        <a href="Form_3.php" class="btn btn-custom">Fill Form</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment Details</h5>
                        <a href="payment.php" class="btn btn-custom">Go to Payment Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Summary</h5>
                        <a href="summary.php" class="btn btn-custom">View Summary</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Approval Status</h5>
                        <a href="approval.php" class="btn btn-custom">View Approved</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add User</h5>
                        <a href="userREgister.php" class="btn btn-custom">Add New User</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Password Settings</h5>
                        <a href="changePassword.php" class="btn btn-custom">Change Password</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payrate Changes</h5>
                        <a href="" class="btn btn-custom">Update rates</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Optional JavaScript for button click
        document.querySelectorAll('.carousel-item .btn').forEach(button => {
            button.addEventListener('click', function() {
                alert('You clicked a button!');
            });
        });
    </script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
