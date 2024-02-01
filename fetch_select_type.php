<?php
// fetch_select_type.php
include 'connect_db.php';

if (isset($_POST['from_location'])) {
    $fromLocation = $_POST['from_location'];

    if ($fromLocation == 'Products') {
        $query = "SELECT DISTINCT Width FROM Products WHERE Status = 'In-stock' ORDER BY Width";
    } else {
        $query = "SELECT DISTINCT MaterialName FROM $fromLocation WHERE Status = 'In-stock' ORDER BY MaterialName";
    }

    $result = $conn->query($query);
    if ($fromLocation == 'Products') {
        echo "<option value='Width'>Select Width</option>";
    } else {
        echo "<option value='MaterialName'>Select Material Name</option>";
    }

    while ($row = $result->fetch_assoc()) {
        $value = ($fromLocation == 'Products') ? $row['Width'] : $row['MaterialName'];
        echo "<option value='" . $value . "'>" . $value . "</option>";
    }
}
?>
