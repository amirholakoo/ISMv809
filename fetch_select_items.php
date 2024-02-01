

<?php
// fetch_select_items.php
include 'connect_db.php';

if (isset($_POST['from_anbar']) && isset($_POST['select_type'])) {
    $fromAnbar = $_POST['from_anbar'];
    list($type, $value) = explode('-', $_POST['select_type'], 2);

    if ($type == 'Width') {
        $query = "SELECT ReelNumber FROM Products WHERE Status = 'In-stock' AND Width = $value ORDER BY ReceivedDate";
    } else {
        $query = "SELECT MaterialName FROM $fromAnbar WHERE Status = 'In-stock' AND MaterialName = '$value' ORDER BY ReceivedDate";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='".$row['ReelNumber']."'>".$row['ReelNumber']."</option>";
    }
}
?>
