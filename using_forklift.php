<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// List of Anbars for Dropdown
$anbars = [
    'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
];

// Handle Material Usage
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['use_material'])) {
    $selectedAnbar = $_POST['anbar'];
    $materialName = $_POST['material_name'];
    $quantity = intval($_POST['quantity']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Fetch IDs of materials to be used
        $fetchIDsQuery = "SELECT ID, SupplierID, SupplierName, MaterialType FROM $selectedAnbar WHERE MaterialName = ? AND Status = 'In-stock' LIMIT ?";
        $fetchIDsStmt = $conn->prepare($fetchIDsQuery);
        $fetchIDsStmt->bind_param("si", $materialName, $quantity);
        $fetchIDsStmt->execute();
        $idResult = $fetchIDsStmt->get_result();

        while ($idRow = $idResult->fetch_assoc()) {
            $materialID = $idRow['ID'];

            // Update Anbar Table for each material
            $updateAnbarQuery = $conn->prepare("UPDATE $selectedAnbar SET Status = 'Used', Location = 'Used', LastDate = NOW() WHERE ID = ?");
            $updateAnbarQuery->bind_param("i", $materialID);
            $updateAnbarQuery->execute();

            // Insert into Consumption Table
            // Insert into Consumption Table
        $insertConsumptionQuery = $conn->prepare("INSERT INTO Consumption (Date, SupplierID, SupplierName, MaterialType, MaterialName, Description, Status) VALUES (NOW(), ?, ?, ?, ?, ?, 'Used')");
        $insertConsumptionQuery->bind_param("issss", $row['SupplierID'], $row['SupplierName'], $row['MaterialType'], $materialName, $row['Description']);
        $insertConsumptionQuery->execute();
        $insertConsumptionQuery->close();
        }

        $conn->commit();
        echo "<p style='color:green;'>Material used successfully.</p>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}



// HTML Form for Using Material
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Use Material</h2>";

echo "Anbar: <select name='anbar' id='anbar'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "Material Name: <select name='material_name' id='material_name'>";
// Dynamically populate based on selected Anbar
echo "</select> <br>";

echo "Quantity: <input type='number' name='quantity' min='1' required> <br>";

echo "<input type='submit' name='use_material' value='Use'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "
<script>
$(document).ready(function() {
    $('#anbar').change(function() {
        var anbar = $(this).val();
        $.ajax({
            url: 'fetch_material_names.php',
            type: 'POST',
            data: {anbar: anbar},
            success: function(response) {
                $('#material_name').html(response);
            }
        });
    });
});
</script>";

echo "</body></html>";
$conn->close();
?>
