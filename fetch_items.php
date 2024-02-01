<?php
// fetch_items.php
include 'connect_db.php';

if (isset($_POST['fromAnbar']) && isset($_POST['selectType'])) {
    $fromAnbar = $_POST['fromAnbar'];
    $selectType = $_POST['selectType'];

    if ($selectType == 'Reel' && $fromAnbar == 'Products') {
        $query = "SELECT ReelNumber FROM Products WHERE Status = 'In-stock' ORDER BY ReceivedDate";
    } else {
        $query = "SELECT ID, MaterialName FROM $fromAnbar WHERE Status = 'In-stock' ORDER BY ReceivedDate";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $value = $selectType == 'Reel' ? $row['ReelNumber'] : $row['ID'];
        $displayText = $selectType == 'Reel' ? $row['ReelNumber'] : $row['MaterialName'] . ' (' . $row['ID'] . ')';
        echo "<option value='" . $value . "'>" . $displayText . "</option>";
    }
}
?>
