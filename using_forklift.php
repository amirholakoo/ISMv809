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

    // Fetch material details from the Anbar
    // Assuming Anbar tables have SupplierID, SupplierName, MaterialType, and Description
    $fetchDetailsQuery = $conn->prepare("SELECT SupplierID, SupplierName, MaterialType, Description FROM $selectedAnbar WHERE MaterialName = ? AND Status = 'In-stock' LIMIT ?");
    $fetchDetailsQuery->bind_param("si", $materialName, $quantity);
    $fetchDetailsQuery->execute();
    $result = $fetchDetailsQuery->get_result();

    while ($row = $result->fetch_assoc()) {
        // Update Anbar Table
        $updateAnbarQuery = $conn->prepare("UPDATE $selectedAnbar SET Status = 'Used', Location = 'Used', LastDate = NOW() WHERE MaterialName = ? AND SupplierID = ?");
        $updateAnbarQuery->bind_param("si", $materialName, $row['SupplierID']);
        $updateAnbarQuery->execute();
        $updateAnbarQuery->close();

        // Insert into Consumption Table
        $insertConsumptionQuery = $conn->prepare("INSERT INTO Consumption (Date, SupplierID, SupplierName, MaterialType, MaterialName, Description, Status) VALUES (NOW(), ?, ?, ?, ?, ?, 'Used')");
        $insertConsumptionQuery->bind_param("issss", $row['SupplierID'], $row['SupplierName'], $row['MaterialType'], $materialName, $row['Description']);
        $insertConsumptionQuery->execute();
        $insertConsumptionQuery->close();
    }

    echo "<p style='color:green;'>Material used successfully.</p>";
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
echo "<script>
    // JavaScript to dynamically load material names based on selected Anbar
    // Add AJAX code here
</script>";

echo "</body></html>";
$conn->close();
?>
