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
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff; /* Bootstrap primary color */
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            margin-left: -20px;
            margin-right: -20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        /* Optional: Custom styles for quick actions card */
        .card {
            margin-top: 20px;
        }
        .form-container {
        max-width: 700px; /* Set max-width to 600px */
        margin: 0 auto; /* Center the form */
    }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Main Content Area -->
    <div class="container form-container">
        <div class="header">
            <h1 class="form-title mb-1">Examination Claim Form II</h1>
            <h3 class="form-title mb-1">Evaluation</h3>
        </div>
        <br><br>

        <form action="submit.php" method="post">
            <!-- Staff Name -->
            <div class="mb-3">
                <label for="staff_name" class="form-label">Staff Name</label>
                <input type="text" class="form-control" id="staff_name" name="staff_name" placeholder="Enter your name" required>
            </div>

            <!-- Type of Examination -->
            <div class="mb-3">
                <label for="exam_type" class="form-label">Type of Examination</label>
                <select class="form-select" id="exam_type" name="exam_type" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="Theory">Theory</option>
                    <option value="Practical">Practical</option>
                </select>
            </div>

            <!-- Exam Date -->
            <div class="mb-3">
                <label for="exam_date" class="form-label">Date</label>
                <input type="date" class="form-control" id="exam_date" name="exam_date" required>
            </div>

            <!-- Start and End Time -->
            <div class="row">
                <div class="md-6 mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
               
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                </div>
            </div>

            <!-- Number of Students -->
            <div class="mb-3">
                <label class="form-label">Number of Students</label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="num_students_proper" class="form-label">Proper</label>
                        <input type="number" class="form-control" id="num_students_proper" name="num_students_proper" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="num_students_repeat" class="form-label">Repeat</label>
                        <input type="number" class="form-control" id="num_students_repeat" name="num_students_repeat" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="reset" class="btn btn-secondary btn-danger reset-btn">Reset</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (Optional: for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
