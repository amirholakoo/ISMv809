
<?php
// fetch_select_type.php
include 'connect_db.php';

if (isset($_POST['from_anbar'])) {
    $fromAnbar = $_POST['from_anbar'];

    if ($fromAnbar == 'Products') {
        $query = "SELECT DISTINCT Width FROM Products WHERE Status = 'In-stock'";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='Width-".$row['Width']."'>Width: ".$row['Width']."</option>";
        }
    } else {
        $query = "SELECT DISTINCT MaterialName FROM $fromAnbar WHERE Status = 'In-stock'";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='Material-".$row['MaterialName']."'>".$row['MaterialName']."</option>";
        }
    }
}
?>
