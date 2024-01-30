<?php
// fetch_reel_numbers.php
include 'connect_db.php';

if (isset($_POST['location']) && isset($_POST['width'])) {
    $location = $_POST['location'];
    $width = $_POST['width'];

    if ($location == 'Products') {
        $query = "SELECT ReelNumber FROM Products WHERE Status = 'In-stock' AND Width = $width";
    } else {
        $query = "SELECT ReelNumber FROM $location WHERE Status = 'In-stock' AND Width = $width";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['ReelNumber'] . "'>" . $row['ReelNumber'] . "</option>";
    }
}
?>
