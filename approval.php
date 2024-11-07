<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
include 'config.php';
// Retrieve user information from the session
$user = $_SESSION['user'];

$approval_sql = "SELECT name, dateTime, status, comment FROM approvalstatus ORDER BY dateTime DESC";
$result = $conn->query($approval_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <!-- Custom Styles -->
    <style>
          body {
            background-image: url('https://wallpaper-house.com/data/out/9/wallpaper2you_339572.jpg');
            background-size: cover; 
            background-repeat: no-repeat; 
            padding-top: 70px;
            background-color: #f8f9fa;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>

<div class="content">
    <div class="header">
        <h2>Claim Approval Status</h2>
    </div>
    
    
    <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead class="table-dark"><tr><th>Name</th><th>Date & Time</th><th>Status</th><th>Comment</th></tr></thead><tbody>';

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['dateTime']))) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<p>No approval records found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    echo "<br><br><br>"; 
    include 'footer.php'; 
?>
