<?php
// fetch_select_items.php
include 'connect_db.php';

if (isset($_POST['from_location']) && isset($_POST['select_type'])) {
    $fromLocation = $_POST['from_location'];
    $selectType = $_POST['select_type'];

    if ($fromLocation == 'Products') {
        $query = "SELECT ReelNumber FROM Products WHERE Status = 'In-stock' AND Width = $selectType ORDER BY ReceivedDate";
    } else {
        $query = "SELECT ID FROM $fromLocation WHERE Status = 'In-stock' AND MaterialName = $selectType ORDER BY ReceivedDate";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $value = ($fromLocation == 'Products') ? $row['ReelNumber'] : $row['ID'];
        echo "<option value='" . $value . "'>" . $value . "</option>";
    }
}
?>
