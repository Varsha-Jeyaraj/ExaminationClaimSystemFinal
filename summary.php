<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Database connection parameters

include 'config.php';
$user = $_SESSION['user'];
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get staff data and their courses

$sql = "SELECT staffName AS name, courseCode AS code, totalAmount AS amount 
        FROM form1";
$result = $conn->query($sql);


// Prepare data for rendering
$staff_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['name'];

        // Check if the staff member already exists in $staff_data
        if (!isset($staff_data[$name])) {
            $staff_data[$name] = [
                'name' => $row['name'],
                'courses' => [],
                'total' => 0 
            ];
        }

        // Add the course to the staff member's courses array
        $staff_data[$name]['courses'][] = [
            'code' => $row['code'],
            'amount' => $row['amount']
        ];

        // Add the course amount to the staff member's total
        $staff_data[$name]['total'] += $row['amount'];
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Summary Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
            background-image: url('https://wallpaper-house.com/data/out/9/wallpaper2you_339572.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            color: #fff;
        }
        .header {
            background-color: #007bff;

            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
            border: 2px solid rgba(0, 123, 255, 0.1);
        }
        .table th {
            background-color: #F8F9F9;
            color: #333333;
        }
        .table td {
            background-color: rgba(237, 246, 249, 0.4);
            color: #333333;
        }

    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
     <?php
    include 'navbar.php';
    ?>


    <!-- Main Content Area -->
    <div class="container form-container">
        <div class="header">
            <h1 class="form-title mb-1">Summary of Examination Claim</h1>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Course Code</th>
                    <th>Amount Per Course (Rs.)</th>
                    <th>Total Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_data as $staff): ?>
                    <?php foreach ($staff['courses'] as $index => $course): ?>
                        <tr>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?php echo count($staff['courses']); ?>">
                                    <?php echo htmlspecialchars($staff['name']); ?>
                                </td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($course['code']); ?></td>
                            <td>Rs. <?php echo htmlspecialchars($course['amount']); ?></td>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?php echo count($staff['courses']); ?>">
                                    Rs. <?php echo htmlspecialchars($staff['total']); ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
