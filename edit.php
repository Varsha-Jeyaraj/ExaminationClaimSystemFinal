<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];
include 'config.php';


if (isset($_GET['id'])) {
    $claimID = $_GET['id'];

    $sql = "SELECT * FROM form1 WHERE claimID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $claimID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $staffName = $row['staffName'];
        $courseCode = $row['courseCode'];
        $examType = $row['examType'];
        $preparationType = $row['preparationType'];
        $essayDuration = $row['essayDuration'];
        $essayAmount = $row['essayAmount'];
        $mcqCount = $row['mcqCount'];
        $mcqAmount = $row['mcqAmount'];
        $pageCount = $row['pageCount'];
        $typingAmount = $row['typingAmount'];
        $supervisionAmount = $row['supervisionAmount'];
        $totalAmount = $row['totalAmount'];
    } else {
        echo "Record not found.";
        exit;
    }
} else {
    echo "Invalid access.";
    exit;
}

// After form submission, handle the update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve posted values
    $courseCode = $_POST['courseCode'];
    $examType = $_POST['examType'];
    $preparationType = $_POST['preparationType'];
    $essayDuration = $_POST['essayDuration'];
    $essayAmount = floatval(str_replace('Rs. ', '', $_POST['essayAmount']));
    $mcqCount = $_POST['mcqCount'];
    $mcqAmount = floatval(str_replace('Rs. ', '', $_POST['mcqAmount']));
    $pageCount = $_POST['pageCount'];
    $typingAmount = floatval(str_replace('Rs. ', '', $_POST['typingAmount']));
    $supervisionAmount = floatval(str_replace('Rs. ', '', $_POST['supervisionAmount']));
    $totalAmount = floatval(str_replace('Rs. ', '', $_POST['totalAmount']));

    // SQL query to update the record
    $update_sql = "UPDATE form1 SET courseCode=?, examType=?, preparationType=?, essayDuration=?, essayAmount=?, mcqCount=?, mcqAmount=?, pageCount=?, typingAmount=?, supervisionAmount=?, totalAmount=? WHERE claimID=?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssssdsdsddds",
        $courseCode,
        $examType,
        $preparationType,
        $essayDuration,
        $essayAmount,
        $mcqCount,
        $mcqAmount,
        $pageCount,
        $typingAmount,
        $supervisionAmount,
        $totalAmount,
        $claimID
    );

    if ($stmt->execute()) {
        //echo "Record updated successfully.";
        header("Location: payment.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
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
</head>
<body>
    <?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Edit Exam Claim of <?php echo $staffName ?></h2>
    <form method="POST" action=" ">
        
        <div class="mb-3">
            <label for="courseCode" class="form-label">Course Code:</label>
            <input type="text" id="courseCode" name="courseCode" class="form-control" placeholder="Enter Course Code" value="<?php echo htmlspecialchars($courseCode ?? ''); ?>" required>
        </div>

        <fieldset class="mb-3">
            <legend class="col-form-label pt-0">Exam Type:</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="examType" id="theory" value="Theory" <?php if (($examType ?? '') == 'Theory') echo 'checked'; ?>>
                <label class="form-check-label" for="theory">Theory</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="examType" id="practical" value="Practical" <?php if (($examType ?? '') == 'Practical') echo 'checked'; ?>>
                <label class="form-check-label" for="practical">Practical</label>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend class="col-form-label pt-0">Setting or Moderating:</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="preparationType" id="Setting" value="Setting" <?php if (($preparationType ?? '') == 'Setting') echo 'checked'; ?>>
                <label class="form-check-label" for="Setting">Setting</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="preparationType" id="Moderating" value="Moderating" <?php if (($preparationType ?? '') == 'Moderating') echo 'checked'; ?>>
                <label class="form-check-label" for="Moderating">Moderating</label>
            </div>
        </fieldset>


        <div class="mb-3">
            <label for="essayDuration" class="form-label">Essay Duration:</label>
            <select id="essayDuration" name="essayDuration" class="form-select" required>
                <option disabled value="">Select Duration</option>
                <option value="1 Hour" <?php if (($essayDuration ?? '') == '1') echo 'selected'; ?>>1 Hour</option>
                <option value="2 Hours" <?php if (($essayDuration ?? '') == '2') echo 'selected'; ?>>2 Hours</option>
                <option value="3 Hours" <?php if (($essayDuration ?? '') == '3') echo 'selected'; ?>>3 Hours</option>
            </select>
        </div>

        
        <div class="mb-3">
            <label for="essayAmount" class="form-label">Essay Amount:</label>
            <input type="text" id="essayAmount" name="essayAmount" class="form-control" value="<?php echo htmlspecialchars($essayAmount ?? ''); ?>" readonly>
        </div>

       
        <div class="mb-3">
            <label for="mcqCount" class="form-label">Number of MCQ Questions:</label>
            <input type="number" id="mcqCount" name="mcqCount" class="form-control" min="0" placeholder="Number of Questions" value="<?php echo htmlspecialchars($mcqCount ?? ''); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="mcqAmount" class="form-label">MCQ Amount:</label>
            <input type="text" id="mcqAmount" name="mcqAmount" class="form-control" value="<?php echo htmlspecialchars($mcqAmount ?? ''); ?>" readonly>
        </div>

        
        <div class="mb-3">
            <label for="pageCount" class="form-label">Typing:</label>
            <select id="pageCount" name="pageCount" class="form-select" required>
                <option disabled value="">Select Pages</option>
                <option value="1 Page" <?php if (($pageCount ?? '') == '1') echo 'selected'; ?>>1 Page</option>
                <option value="2 Pages" <?php if (($pageCount ?? '') == '2') echo 'selected'; ?>>2 Pages</option>
                <option value="3 Pages" <?php if (($pageCount ?? '') == '3') echo 'selected'; ?>>3 Pages</option>
                <option value="4 Pages" <?php if (($pageCount ?? '') == '4') echo 'selected'; ?>>4 Pages</option>
                <option value="5 Pages" <?php if (($pageCount ?? '') == '5') echo 'selected'; ?>>5 Pages</option>
            </select>
        </div>

        
        <div class="mb-3">
            <label for="typingAmount" class="form-label">Typing Amount:</label>
            <input type="text" id="typingAmount" name="typingAmount" class="form-control" value="<?php echo htmlspecialchars($typingAmount ?? ''); ?>" readonly>
        </div>

        
        <div class="mb-3">
            <label for="supervisionAmount" class="form-label">Packeting Supervision:</label>
            <input type="text" id="supervisionAmount" name="supervisionAmount" class="form-control" value="<?php echo htmlspecialchars($supervisionAmount ?? 'Rs. 0'); ?>" readonly>
        </div>

        
        <div class="mb-3">
            <label for="totalAmount" class="form-label">Total Amount (Rs.):</label>
            <input type="text" id="totalAmount" name="totalAmount" class="form-control" value="<?php echo htmlspecialchars($totalAmount ?? ''); ?>" readonly>
        </div>

       
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="payment.php" class="btn btn-secondary">Cancel</a>
    </form>

   


    <script>
    function updateTotalAmount() {
    const essayAmount = parseFloat(document.getElementById('essayAmount').value.replace('Rs. ', '')) || 0;
    const mcqAmount = parseFloat(document.getElementById('mcqAmount').value.replace('Rs. ', '')) || 0;
    
    const isSettingSelected = document.getElementById('Setting').checked;
    const isModeratingSelected = document.getElementById('Moderating').checked;

    const supervisionAmount = isSettingSelected ? 100 : 0;
    let typingAmount = 0; // Initialize typing amount

    // Reset typing amount if Moderating is selected
    if (isModeratingSelected) {
        document.getElementById('typingAmount').value = "Rs. 0";
    } else if (isSettingSelected) {
        typingAmount = parseFloat(document.getElementById('typingAmount').value.replace('Rs. ', '')) || 0;
    }

    if (isSettingSelected) {
        const pages = parseInt(document.getElementById('pageCount').value) || 0; // Get page count
        typingAmount = pages * 100; // Calculate typing amount
        document.getElementById('typingAmount').value = typingAmount ? `Rs. ${typingAmount}` : '';
    }

    let totalAmount = essayAmount + mcqAmount + supervisionAmount + typingAmount;

    document.getElementById('totalAmount').value = `Rs. ${totalAmount}`;
    document.getElementById('supervisionAmount').value = `Rs. ${supervisionAmount}`;
}

// Event listeners
document.getElementById('Setting').addEventListener('change', updateTotalAmount);
document.getElementById('Moderating').addEventListener('change', updateTotalAmount);

document.getElementById('essayDuration').addEventListener('change', function() {
    const duration = parseInt(this.value);
    const ratePerHour = 400;
    const essayAmount = duration * ratePerHour;
    document.getElementById('essayAmount').value = essayAmount ? `Rs. ${essayAmount}` : '';
    updateTotalAmount();
});

document.getElementById('mcqCount').addEventListener('change', function() {
    const count = parseInt(this.value);
    const ratePerQs = 50;
    const mcqAmount = count * ratePerQs;
    document.getElementById('mcqAmount').value = mcqAmount ? `Rs. ${mcqAmount}` : '';
    updateTotalAmount();
});

document.getElementById('pageCount').addEventListener('change', function() {
    const pages = parseInt(this.value);
    const ratePerPage = 100;
    const typingAmount = pages * ratePerPage;
    document.getElementById('typingAmount').value = typingAmount ? `Rs. ${typingAmount}` : '';
    updateTotalAmount();
});

// Initial call to update amounts
updateTotalAmount();
</script>



    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

