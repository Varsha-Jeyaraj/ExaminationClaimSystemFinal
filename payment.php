<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

include 'config.php'; // Your database configuration

// Retrieve user information from the session
$user = $_SESSION['user'];


// Handle staff selection
$selected_staff = $_POST['staff'] ?? 'all';
$academicYear = $_POST['academicYear'] ?? '';
$semester = $_POST['semester'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    
</head>
<body>
    <!-- Top Navigation Bar -->
     <?php
    include 'navbar.php';
    ?>

<div class="content">
    <div class="header">
        <h2>Payment Details for Staff</h2>
    </div>
    
    <!-- Form to Select Staff Member -->
    <form method="POST" action="payment.php" class="mb-4">
    <div class="mb-3">
        <!-- Academic Year Input -->
        <label for="academicYear" class="form-label">Academic Year:</label>
        <input type="text" id="academicYear" name="academicYear" class="form-control" required placeholder="Enter Academic Year (e.g., 2021/2022)" value="<?php echo htmlspecialchars($academicYear); ?>">
    </div>
    <div class="mb-3">
        <!-- Semester Selection -->
        <label for="semester" class="form-label">Semester:</label>
        <select id="semester" name="semester" class="form-select" required>
            <option value="">Select Semester</option>
            <option value="sem1" <?php echo ($semester == 'sem1') ? 'selected' : ''; ?>>Semester I</option>
            <option value="sem2" <?php echo ($semester == 'sem2') ? 'selected' : ''; ?>>Semester II</option>
        </select>
    </div>
        <div class="mb-3">
            <label for="staff" class="form-label">Select Staff:</label>
            <select id="staff" name="staff" class="form-select" onchange="this.form.submit()">
                <option value="all" <?php echo ($selected_staff == 'all') ? 'selected' : ''; ?>>All</option>
                <option value="Dr. S. Mahesan" <?php echo ($selected_staff == 'Dr. S. Mahesan') ? 'selected' : ''; ?>>Dr. S. Mahesan</option>
                <option value="Dr. E. Y. A. Charles" <?php echo ($selected_staff == 'Dr. E. Y. A. Charles') ? 'selected' : ''; ?>>Dr. E. Y. A. Charles</option>
                <option value="Dr. K. Thabotharan" <?php echo ($selected_staff == 'Dr. K. Thabotharan') ? 'selected' : ''; ?>>Dr. K. Thabotharan</option>
                <option value="Prof. A. Ramanan" <?php echo ($selected_staff == 'Prof. A. Ramanan') ? 'selected' : ''; ?>>Prof. A. Ramanan</option>
                <option value="Mr. S. Suthakar" <?php echo ($selected_staff == 'Mr. S. Suthakar') ? 'selected' : ''; ?>>Mr. S. Suthakar</option>
                <option value="Dr. (Mrs.) B. Mayurathan" <?php echo ($selected_staff == 'Dr. (Mrs.) B. Mayurathan') ? 'selected' : ''; ?>>Dr. (Mrs.) B. Mayurathan</option>
                <option value="Prof. M. Siyamalan" <?php echo ($selected_staff == 'Prof. M. Siyamalan') ? 'selected' : ''; ?>>Prof. M. Siyamalan</option>
                <option value="Dr. K. Sarveswaran" <?php echo ($selected_staff == 'Dr. K. Sarveswaran') ? 'selected' : ''; ?>>Dr. K. Sarveswaran</option>
                <option value="Dr. S. Shriparen" <?php echo ($selected_staff == 'Dr. S. Shriparen') ? 'selected' : ''; ?>>Dr. S. Shriparen</option>
                <option value="Dr. T. Kokul" <?php echo ($selected_staff == 'Dr. T. Kokul') ? 'selected' : ''; ?>>Dr. T. Kokul</option>
                <option value="Dr. (Ms.) J. Samantha Tharani" <?php echo ($selected_staff == 'Dr. (Ms.) J. Samantha Tharani') ? 'selected' : ''; ?>>Dr. (Ms.) J. Samantha Tharani</option>
                <option value="Dr. (Ms.) R. Nirthika" <?php echo ($selected_staff == 'Dr. (Ms.) R. Nirthika') ? 'selected' : ''; ?>>Dr. (Ms.) R. Nirthika</option>
                <option value="Ms. M. Mayuravaani" <?php echo ($selected_staff == 'Ms. M. Mayuravaani') ? 'selected' : ''; ?>>Ms. M. Mayuravaani</option>
            </select>
        </div>
    </form>

    <?php
    

    // If "All" is selected, get all staff payment details grouped by staff
    if ($selected_staff == 'all') {
        $staff_sql = "SELECT * FROM form1 WHERE academicYear = ? AND semester = ? ORDER BY staffName";
        $stmt = $conn->prepare($staff_sql);
        $stmt->bind_param("ss", $academicYear, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $current_staff = '';
            while ($row = $result->fetch_assoc()) {
                if ($current_staff != $row['staffName']) {
                    if ($current_staff != '') {
                        echo "<tr><td colspan='10' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
                        echo '</tbody></table><br>';
                    }
                    $current_staff = $row['staffName'];
                    $grandTotal = 0;

                    $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
                    $staff_stmt = $conn->prepare($staff_details_sql);
                    $staff_stmt->bind_param("s", $current_staff);
                    $staff_stmt->execute();
                    $staff_details = $staff_stmt->get_result()->fetch_assoc();
                    echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
                    $staff_stmt->close();

                    echo '<table class="table table-striped"><thead><tr><th>Course Code</th><th>Exam Type</th><th>Preparation Type</th><th>Essay Duration</th><th>Amount for Essay</th><th>MCQ Count</th><th>Amount for MCQ</th><th>Pages Count</th><th>Amount for Typing</th><th>Supervision Amount</th><th>Total Amount</th></tr></thead><tbody>';
                }

                $grandTotal += $row['totalAmount'];

                echo "
                    <tr>
                        <td>{$row['courseCode']}</td>
                        <td>{$row['examType']}</td>
                        <td>{$row['preparationType']}</td>
                        <td>{$row['essayDuration']}</td>
                        <td>{$row['essayAmount']}</td>
                        <td>{$row['mcqCount']}</td>
                        <td>{$row['mcqAmount']}</td>
                        <td>{$row['pageCount']}</td>
                        <td>{$row['typingAmount']}</td>
                        <td>{$row['supervisionAmount']}</td>
                        <td>{$row['totalAmount']}</td>
                        <td><a href='edit.php?id={$row['claimID']}' class='btn btn-primary btn-sm me-2'>Edit</a></td>
                        <td><a href='delete.php?id={$row['claimID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>
                    </tr>";
            }
            echo "<tr><td colspan='10' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
            echo '</tbody></table>';
        } else {
            echo "<p>No records found.</p>";
        }
        $stmt->close();
    } else {
        $grandTotal = 0;
        $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
        $staff_stmt = $conn->prepare($staff_details_sql);
        $staff_stmt->bind_param("s", $selected_staff);
        $staff_stmt->execute();
        $staff_details = $staff_stmt->get_result()->fetch_assoc();
        echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
        $staff_stmt->close();

        $stmt = $conn->prepare("SELECT * FROM form1 WHERE staffName = ? AND academicYear = ? AND semester = ?");
        $stmt->bind_param("sss", $selected_staff, $academicYear, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table table-striped"><thead><tr><th>Course Code</th><th>Exam Type</th><th>Preparation Type</th><th>Essay Duration</th><th>Amount for Essay</th><th>MCQ Count</th><th>Amount for MCQ</th><th>Pages Count</th><th>Amount for Typing</th><th>Supervision Amount</th><th>Total Amount</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['courseCode']}</td><td>{$row['examType']}</td><td>{$row['preparationType']}</td><td>{$row['essayDuration']}</td><td>{$row['essayAmount']}</td><td>{$row['mcqCount']}</td><td>{$row['mcqAmount']}</td><td>{$row['pageCount']}</td><td>{$row['typingAmount']}</td><td>{$row['supervisionAmount']}</td><td>{$row['totalAmount']}</td>
                <td><a href='edit.php?id={$row['claimID']}' class='btn btn-primary btn-sm me-2'>Edit</a></td>
                <td><a href='delete.php?id={$row['claimID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td></tr>";
                $grandTotal += $row['totalAmount'];
            }
            echo "<tr><td colspan='10' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
            echo '</tbody></table>';
        } else {
            echo "<p>No records found for this staff.</p>";
        }
        $stmt->close();
    }
    ?>
</div>
<!-- Bootstrap JS Bundle (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>

