<?php
session_start();
date_default_timezone_set('Asia/Colombo');

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user=$_SESSION['user'];

include 'config.php';

    if ($user['usertype']== "Head")
        include 'navbar-head.php'; 
    else
        include 'navbar-staff.php';


$user = $_SESSION['user'];
$staffName = $user['name'];

$academicYear = $_POST['academicYear'] ?? '';
$semester = $_POST['semester'] ?? '';

// Handle form submission only for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statusForm'])) {
    $action = $_POST['action'];
    $status = ($action === 'approve') ? "Approved" : "Declined";
    $comment = isset($_POST['comment']) ? $_POST['comment'] : "Approved";
    $currentDate = date("Y-m-d H:i:s");

    // Insert into approvalStatus table
    $insert_sql = "INSERT INTO approvalstatus (name, dateTime, status, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $staffName, $currentDate, $status, $comment);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated to $status successfully!";
    } else {
        $_SESSION['message'] = "Failed to update status: " . $stmt->error;
    }
    $stmt->close();

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    
    <script>
        function submitForm() {
            document.getElementById("filterForm").submit();
        }
    </script>
</head>
<body>
    <div class="content">
        <div class="header">
            <h2>Payment Details for Setting and Moderating</h2>
        </div>

        <!-- Filter Form -->
        <form method="POST" action="staffView.php" id="filterForm">
            <div class="mb-3">
                <label for="academicYear" class="form-label">Academic Year:</label>
                <input type="text" id="academicYear" name="academicYear" class="form-control" placeholder="Enter Academic Year (e.g., 2021/2022)" value="<?php echo htmlspecialchars($academicYear); ?>" oninput="submitForm()">
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester:</label>
                <select id="semester" name="semester" class="form-select" onchange="submitForm()">
                    <option value="">Select Semester</option>
                    <option value="sem1" <?php echo ($semester == 'sem1') ? 'selected' : ''; ?>>Semester I</option>
                    <option value="sem2" <?php echo ($semester == 'sem2') ? 'selected' : ''; ?>>Semester II</option>
                </select>
            </div>
        </form>

        <?php
            $grandTotal = 0;
            $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
            $staff_stmt = $conn->prepare($staff_details_sql);
            $staff_stmt->bind_param("s", $staffName);
            $staff_stmt->execute();
            $staff_details = $staff_stmt->get_result()->fetch_assoc();
            echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
            $staff_stmt->close();

            $stmt = $conn->prepare("SELECT courseCode, examType, preparationType, essayDuration, essayAmount, mcqCount, mcqAmount, pageCount, typingAmount, totalAmount FROM form1 WHERE staffName = ? AND academicYear = ? AND semester = ?");
            $stmt->bind_param("sss", $staffName, $academicYear, $semester);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<table class="table table-striped"><thead><tr><th>Course Code</th><th>Exam Type</th><th>Preparation Type</th><th>Essay Duration</th><th>Amount for Essay</th><th>MCQ Count</th><th>Amount for MCQ</th><th>Pages Count</th><th>Amount for Typing</th><th>Total Amount</th></tr></thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['courseCode']}</td><td>{$row['examType']}</td><td>{$row['preparationType']}</td><td>{$row['essayDuration']}</td><td>{$row['essayAmount']}</td><td>{$row['mcqCount']}</td><td>{$row['mcqAmount']}</td><td>{$row['pageCount']}</td><td>{$row['typingAmount']}</td><td>{$row['totalAmount']}</td></tr>";
                    $grandTotal += $row['totalAmount'];
                }
                echo "<tr><td colspan='9' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
                echo '</tbody></table>';
            } else {
                echo "<p>No records found.</p>";
            }
            $stmt->close();
        ?>
        
        <?php if (isset($_SESSION['message'])): ?>
            <p class="alert alert-success"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Status Update Form -->
        <div class="d-flex justify-content-end mt-3">
            <form action="staffView.php" method="POST" name="statusForm">
                <input type="hidden" name="statusForm" value="1">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-success me-2">Approve</button> 
            </form>

            <form action="staffView.php" method="POST" name="statusForm">
                <input type="hidden" name="statusForm" value="1">
                <input type="hidden" name="action" value="decline">
                <button type="button" class="btn btn-danger" onclick="showCommentBox()">Decline</button>
                
                <div id="comment-box" style="display: none; margin-top: 10px;">
                    <label for="comment">Please provide the corrections to be made as comments here:</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control mt-2" oninput="toggleOkButton()" required></textarea>
                    <button type="submit" class="btn btn-primary mt-2" id="ok-button" style="display: none;">OK</button>
                </div>
            </form>
        </div>

        <script>
            function showCommentBox() {
                document.getElementById('comment-box').style.display = 'block';
            }

            function toggleOkButton() {
                const commentBox = document.getElementById('comment');
                const okButton = document.getElementById('ok-button');
                okButton.style.display = commentBox.value.trim() ? 'block' : 'none';
            }
        </script>

        <br><br><br>
        <div class="comment-section">
            <h5>Added Comments:</h5>
            <?php
                $comments_sql = "SELECT comment, DATE_FORMAT(dateTime, '%Y-%m-%d %H:%i:%s') AS formatted_date FROM approvalstatus WHERE name = ? ORDER BY dateTime DESC";
                $comments_stmt = $conn->prepare($comments_sql);
                $comments_stmt->bind_param("s", $staffName);
                $comments_stmt->execute();
                $comments_result = $comments_stmt->get_result();

                if ($comments_result->num_rows > 0) {
                    while ($row = $comments_result->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<div class='comment-date'>{$row['formatted_date']}</div>";
                        echo "<div class='comment-text'>{$row['comment']}</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No comments added yet.</p>";
                }
                $comments_stmt->close();
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
