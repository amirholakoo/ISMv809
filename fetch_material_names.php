<?php
include 'connect_db.php';

if (isset($_POST['anbar'])) {
    $anbar = $_POST['anbar'];

    $query = "SELECT DISTINCT MaterialName FROM $anbar WHERE Status = 'In-stock'";
    $result = $conn->query($query);
    if (!$result) {
        echo "Error: " . $conn->error;  // Display error if query fails
    } else {
        echo "<option value=''>Select Material Name</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['MaterialName'] . "'>" . $row['MaterialName'] . "</option>";
        }
    }
}
?>
