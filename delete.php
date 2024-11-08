<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_sql = "DELETE FROM form1 WHERE claimID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: payment.php');
    } else {
        echo "<p>Error deleting record: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>
