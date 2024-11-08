<?php
include 'config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $academicYear = isset($_POST['academicYear']) ? $_POST['academicYear'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $staffName = isset($_POST['StaffName']) ? $_POST['StaffName'] : '';
    $courseCode = isset($_POST['CourseCode']) ? $_POST['CourseCode'] : '';
    $examType = isset($_POST['ExamType']) ? $_POST['ExamType'] : '';
    $preparationType = isset($_POST['PreparationType']) ? $_POST['PreparationType'] : '';
    $essayDuration = isset($_POST['EssayDuration']) ? (double)$_POST['EssayDuration'] : 0.0;
    $essayAmount = isset($_POST['EssayPayment']) ? (double)str_replace('Rs. ', '', $_POST['EssayPayment']) : 0.0;
    $mcqCount = isset($_POST['MCQcount']) ? (int)$_POST['MCQcount'] : 0;
    $mcqAmount = isset($_POST['MCQpayment']) ? (double)str_replace('Rs. ', '', $_POST['MCQpayment']) : 0.0;
    $pageCount = isset($_POST['PageCount']) ? (int)$_POST['PageCount'] : 0;
    $typingAmount = isset($_POST['TypingPayment']) ? (double)str_replace('Rs. ', '', $_POST['TypingPayment']) : 0.0;
    $totalAmount = isset($_POST['TotalAmount']) ? (double)str_replace('Rs. ', '', $_POST['TotalAmount']) : 0.0;

    if (empty($staffName) || empty($courseCode)) {
        die("Staff Name and Course Code are required fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO form1 (academicYear, semester, staffName, courseCode, examType, preparationType, essayDuration ,essayAmount, mcqCount, mcqAmount,pageCount, typingAmount, totalAmount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssidididd", $academicYear, $semester, $staffName, $courseCode, $examType, $preparationType, $essayDuration, $essayAmount, $mcqCount, $mcqAmount, $pageCount, $typingAmount, $totalAmount);

    // Execute the statement


    if ($stmt->execute()) {
    echo "<script>alert('Examination Claim Form I submitted successfully!');</script>";
    header("Location: payment.php");
    } else {
    $error = $stmt->error;
    echo "<script>alert('Error: $error');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<br>


