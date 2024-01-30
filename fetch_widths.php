<?php
// fetch_widths.php
include 'connect_db.php';

if (isset($_POST['location'])) {
    $location = $_POST['location'];

    if ($location == 'Products') {
        $query = "SELECT DISTINCT Width FROM Products WHERE Status = 'In-stock'";
    } else {
        $query = "SELECT DISTINCT Width FROM $location WHERE Status = 'In-stock'";
    }

    $result = $conn->query($query);
    echo "<option value=''>Select Width</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['Width'] . "'>" . $row['Width'] . "</option>";
    }
}
?>
