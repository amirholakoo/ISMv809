<?php
// fetch_items.php
include 'connect_db.php';

if (isset($_POST['what']) && isset($_POST['from']) && isset($_POST['type'])) {
    $what = $_POST['what'];
    $from = $_POST['from'];
    $type = $_POST['type'];

    if ($what == 'Rolls') {
        $query = "SELECT ReelNumber FROM $from WHERE Width = '$type' AND Status = 'In-stock'";
    } else { // 'Raw'
        $query = "SELECT ID FROM $from WHERE MaterialName = '$type' AND Status = 'In-stock'";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $value = $what == 'Rolls' ? $row['ReelNumber'] : $row['ID'];
        echo "<option value='" . $value . "'>" . $value . "</option>";
    }
}
?>
