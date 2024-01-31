<?php
// fetch_types.php
include 'connect_db.php';

if (isset($_POST['what']) && isset($_POST['from'])) {
    $what = $_POST['what'];
    $from = $_POST['from'];

    if ($what == 'Rolls') {
        $query = "SELECT DISTINCT Width FROM $from WHERE Status = 'In-stock'";
    } else { // 'Raw'
        $query = "SELECT DISTINCT MaterialName FROM $from WHERE Status = 'In-stock'";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $value = $what == 'Rolls' ? $row['Width'] : $row['MaterialName'];
        echo "<option value='" . $value . "'>" . $value . "</option>";
    }
}
?>
