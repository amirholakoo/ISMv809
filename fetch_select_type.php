<?php
include 'connect_db.php';

if (isset($_POST['from_location'])) {
    $from_location = $_POST['from_location'];

    if ($from_location == 'Products') {
        // Fetch distinct widths from Products
        $query = "SELECT DISTINCT Width FROM Products WHERE Status = 'In-stock'";
        echo "<option value=''>Select Width</option>";
    } else {
        // Fetch distinct material names from the selected Anbar
        $query = "SELECT DISTINCT MaterialName FROM $from_location WHERE Status = 'In-stock'";
        echo "<option value=''>Select Material Name</option>";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $value = $from_location == 'Products' ? $row['Width'] : $row['MaterialName'];
        echo "<option value='" . $value . "'>" . $value . "</option>";
    }
}
?>
