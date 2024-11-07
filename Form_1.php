
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

$sql = "SELECT type, ratePerHour, ratePerQs, ratePerPage, supervisionAmount FROM payrates";
$result = $conn->query($sql);

// Check if records were found and store them in variables
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["type"] === "setting") {
            $ratePerHour_setting = $row["ratePerHour"];
            $ratePerQs_setting = $row["ratePerQs"];
            $ratePerPage_setting = $row["ratePerPage"];
            $supervisionAmount_setting = $row["supervisionAmount"];
        } elseif ($row["type"] === "moderating") {
            $ratePerHour_moderating = $row["ratePerHour"];
            $ratePerQs_moderating = $row["ratePerQs"];
            $ratePerPage_moderating = $row["ratePerPage"];
            $supervisionAmount_moderating = $row["supervisionAmount"];
        }
    }
} else {
    echo "No records found";
}

// Close the connection
$conn->close();

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

    <!-- Main Content Area -->
    <div class="container form-container" id= "formContainer">
        <div class="header">
            <h1 class="form-title mb-1">Examination Claim Form I</h1>
            <h3 class="form-title mb-1">Preparation of Question Paper</h3>
        </div>
        <br>

        <!-- Display Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                alert("<?php echo htmlspecialchars($_SESSION['success']); ?>");
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <script>
                alert("<?php echo htmlspecialchars($_SESSION['error']); ?>");
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

    
        <form action="submit-claim.php" method="post">   
        <div class="mb-3">
                <label for="academicYear" class="form-label">Academic Year:</label>
                <input type="text" id="academicYear" name="academicYear" class="form-control" placeholder="Enter Academic Year" required>
            </div>

            <fieldset class="mb-3">
                <legend class="col-form-label pt-0">Semester</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="semester" id="sem1" value="sem1">
                    <label class="form-check-label" for="sem1">
                        Semester I
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="semester" id="sem2" value="sem2">
                    <label class="form-check-label" for="sem2">
                        Semester II
                    </label>
                </div>
            </fieldset>        
            <!-- Staff Name -->
            <div class="mb-3">
                <label for="StaffName" class="form-label">Staff Name:</label>
                <select id="StaffName" name="StaffName" class="form-select" required>
                    <option selected disabled value="">Select Staff Name</option>
                    <option>Dr. S. Mahesan</option>
                    <option>Dr. E. Y. A. Charles</option>
                    <option>Dr. K. Thabotharan</option>
                    <option>Prof. A. Ramanan</option>
                    <option>Dr. (Mrs.) B. Mayurathan</option>
                    <option>Mr. S. Suthakar</option>
                    <option>Prof. M. Siyamalan</option>
                    <option>Dr. S. Shriparen</option>
                    <option>Dr. K. Sarveswaran</option>
                    <option>Dr. T. Kokul</option>
                    <option>Dr. (Ms.) J. Samantha Tharani</option>
                    <option>Dr. (Ms.) R. Nirthika</option>
                    <option>Ms. M. Mayuravaani</option>
                </select>
            </div>
            
            
            
            <!-- Year and Semester -->
            <div class="mb-3">
                <label for="CourseCode" class="form-label">Course Code:</label>
                <input type="text" id="CourseCode" name="CourseCode" class="form-control" placeholder="Enter Course Code" required>
            </div>
                
            
            
            
            
            <!-- Exam Type -->
            <fieldset class="mb-3">
                <legend class="col-form-label pt-0">Exam Type:</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ExamType" id="theory" value="Theory">
                    <label class="form-check-label" for="theory">
                        Theory
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ExamType" id="practical" value="Practical">
                    <label class="form-check-label" for="practical">
                        Practical
                    </label>
                </div>
            </fieldset>

            <!-- Setting or Moderating -->
            <fieldset class="mb-3">
                <legend class="col-form-label pt-0">Setting or Moderating:</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="PreparationType" id="Setting" value="Setting">
                    <label class="form-check-label" for="Setting">
                        Setting
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="PreparationType" id="Moderating" value="Moderating">
                    <label class="form-check-label" for="Moderating">
                        Moderating
                    </label>
                </div>
            </fieldset>
            
            <!-- Essay Duration -->
            <div class="mb-3">
                <label for="EssayDuration" class="form-label">Essay Duration:</label>
                <select id="EssayDuration" name="EssayDuration" class="form-select" required>
                    <option selected disabled value="">Select Duration</option>
                    <option>1 Hour</option>
                    <option>2 Hours</option>
                    <option>3 Hours</option>
                </select>
            </div>
            
            <!-- Amount for essay-->
            <div class="mb-3">
            <label for="EssayPayment" class="form-label">Essay Amount :</label>
            <input type="text" id="EssayPayment" name="EssayPayment" class="form-control" readonly>
            </div>

            <!-- Number of MCQ Questions -->
            <div class="mb-3">
                <label for="MCQcount" class="form-label">Number of MCQ Questions:</label>
                <input type="number" id="MCQcount" name="MCQcount" class="form-control" min="0" placeholder="Number of Questions" required>
            </div>

            <div class="mb-3">
            <label for="MCQpayment" class="form-label"> MCQ Amount :</label>
            <input type="text" id="MCQpayment" name="MCQpayment" class="form-control" readonly>
            </div>
            
            <!-- Typing Pages -->
            <div class="mb-3">
                <label for="PageCount" class="form-label">Typing:</label>
                <select id="PageCount" name="PageCount" class="form-select" required>
                    <option selected disabled value="">Select Pages</option>
                    <option>1 Page</option>
                    <option>2 Pages</option>
                    <option>3 Pages</option>
                    <option>4 Pages</option>
                    <option>5 Pages</option>
                </select>
            </div>

            <div class="mb-3">
            <label for="TypingPayment" class="form-label">Typing Amount :</label>
            <input type="text" id="TypingPayment" name="TypingPayment" class="form-control" readonly>
            </div>
            <!-- Packeting Supervision -->
            <div class="mb-3">
                <label for="supervisionAmount" class="form-label">Packeting Supervision:</label>
                <input type="text" id="supervisionAmount" name="supervisionAmount" class="form-control" readonly>
            </div>


            <!-- Total Amount -->
            <div class="mb-3">
                <label for="TotalAmount" class="form-label">Total Amount (Rs.):</label>
                <input type="text" id="TotalAmount" name="TotalAmount" class="form-control" readonly>
            </div>

            
            
            <!-- Submit and Reset Buttons -->
            <div class="row">
            <div class="col-md-12 mb-3">
                <button type="submit" class="btn btn-primary me-2 submit-btn">Submit</button>
                <button type="reset" class="btn btn-secondary btn-danger reset-btn" onclick="scrollToTop()">Reset</button>
                </div>
            </div>

            <script>
            // Rates from PHP variables
            const ratePerHourSetting = <?php echo json_encode($ratePerHour_setting); ?>;
            const ratePerHourModerating = <?php echo json_encode($ratePerHour_moderating); ?>;
            const ratePerQsSetting = <?php echo json_encode($ratePerQs_setting); ?>;
            const ratePerQsModerating = <?php echo json_encode($ratePerQs_moderating); ?>;
            const ratePerPageSetting = <?php echo json_encode($ratePerPage_setting); ?>;
            const ratePerPageModerating = <?php echo json_encode($ratePerPage_moderating); ?>;
            const supervisionAmountSetting = <?php echo json_encode($supervisionAmount_setting); ?>;
            const supervisionAmountModerating = <?php echo json_encode($supervisionAmount_moderating); ?>;


            // Elements
            const preparationType = document.querySelectorAll('input[name="PreparationType"]');
            const essayDuration = document.getElementById('EssayDuration');
            const essayPayment = document.getElementById('EssayPayment');
            const mcqCount = document.getElementById('MCQcount');
            const mcqPayment = document.getElementById('MCQpayment');
            const pageCount = document.getElementById('PageCount');
            const typingPayment = document.getElementById('TypingPayment');
            const supervisionAmount = document.getElementById('supervisionAmount');
            const totalAmount = document.getElementById('TotalAmount');

            // Function to calculate Essay Payment
            function calculateEssayPayment() {
                let duration = parseInt(essayDuration.value) || 0;
                let payment = 0;

                preparationType.forEach(type => {
                    if (type.checked) {
                        payment = type.value === "Setting" 
                            ? duration * ratePerHourSetting 
                            : duration * ratePerHourModerating;
                    }
                });

                essayPayment.value = `Rs. ${payment.toFixed(2)}`;
                calculateTotal();
            }

            // Function to calculate MCQ Payment
            function calculateMCQPayment() {
                let count = parseInt(mcqCount.value) || 0;
                let payment = 0;

                preparationType.forEach(type => {
                    if (type.checked) {
                        payment = type.value === "Setting" 
                            ? count * ratePerQsSetting 
                            : count * ratePerQsModerating;
                    }
                });

                mcqPayment.value = `Rs. ${payment.toFixed(2)}`;
                calculateTotal();
            }

            // Function to calculate Typing Payment
            function calculateTypingPayment() {
                let pages = parseInt(pageCount.value) || 0;
                let payment = 0;

                preparationType.forEach(type => {
                    if (type.checked) {
                        payment = type.value === "Setting" 
                            ? pages * ratePerPageSetting 
                            : pages * ratePerPageModerating;
                    }
                })

                typingPayment.value = `Rs. ${payment.toFixed(2)}`;
                calculateTotal();
            }


            // Function to calculate Packeting Supervision amount
            function calculateSupervisionPayment() {
                
                let payment = 0;

                preparationType.forEach(type => {
                    if (type.checked) {
                        payment = type.value === "Setting" 
                            ? 1*supervisionAmountSetting 
                            : 1*supervisionAmountModerating;
                    }
                })

                supervisionAmount.value = `Rs. ${payment.toFixed(2)}`;
                calculateTotal();
            }

            // Function to calculate Total Amount
            function calculateTotal() {
                let essay = parseFloat(essayPayment.value.replace('Rs. ', '')) || 0;
                let mcq = parseFloat(mcqPayment.value.replace('Rs. ', '')) || 0;
                let typing = parseFloat(typingPayment.value.replace('Rs. ', '')) || 0;
                let supervision = parseFloat(supervisionAmount.value.replace('Rs. ', '')) || 0;
                totalAmount.value = `Rs. ${(essay + mcq + typing + supervision).toFixed(2)}`;
            }

            // Event Listeners
            essayDuration.addEventListener('change', calculateEssayPayment);
            preparationType.forEach(type => type.addEventListener('change', calculateEssayPayment));
            mcqCount.addEventListener('input', calculateMCQPayment);
            pageCount.addEventListener('change', calculateTypingPayment);
            preparationType.forEach(type => type.addEventListener("change", calculateSupervisionPayment));

            
            //JavaScript to handle scroll to top on reset
            function scrollToTop() {
                document.getElementById('formContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }



            </script>


    </form>
    </div>

    <!-- Bootstrap JS Bundle (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
