<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// Fetch Incoming Shipments for Dropdown
$shipmentsQuery = "SELECT ShipmentID, LicenseNumber FROM Shipments WHERE Status = 'Incoming' AND Location = 'LoadingUnloading'";
$shipmentsResult = $conn->query($shipmentsQuery);

// List of Anbars for Dropdown
$anbars = [
    'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
];



if (isset($_POST['unload_shipment'])) {
    if (isset($_POST['shipment_id'])) {
        $shipmentID = $_POST['shipment_id'];
        // Handle Unloading Shipment

    $licenseNumber = $_POST['license_number'];
    $unloadingLocation = $_POST['unloading_location'];
    $quantity = intval($_POST['quantity']);

    // Fetch shipment details using ShipmentID
    $shipmentDetailsQuery = "SELECT SupplierID, SupplierName, MaterialType, MaterialName FROM Shipments WHERE ShipmentID = ?";
    $shipmentDetailsStmt = $conn->prepare($shipmentDetailsQuery);
    $shipmentDetailsStmt->bind_param("i", $shipmentID);
    $shipmentDetailsStmt->execute();
    $result = $shipmentDetailsStmt->get_result();
    $shipmentDetails = $result->fetch_assoc();
    $shipmentDetailsStmt->close();

    // Update Shipments Table
    $updateShipments = $conn->prepare("UPDATE Shipments SET Location = 'LoadedUnloaded', UnloadLocation = ?, Quantity = ? WHERE ShipmentID = ?");
    $updateShipments->bind_param("sis", $unloadingLocation, $quantity, $shipmentID);
    $updateShipments->execute();
    $updateShipments->close();

    // Insert into Anbar Table
    for ($i = 0; $i < $quantity; $i++) {
        $insertAnbar = $conn->prepare("INSERT INTO $unloadingLocation (ReceiveDate, SupplierID, SupplierName, MaterialType, MaterialName, Description, Status, Location) VALUES (NOW(), ?, ?, ?, ?, '', 'In-stock', '$unloadingLocation')");
        $insertAnbar->bind_param("isss", $shipmentDetails['SupplierID'], $shipmentDetails['SupplierName'], $shipmentDetails['MaterialType'], $shipmentDetails['MaterialName']);
        $insertAnbar->execute();
        $insertAnbar->close();
    }

    echo "<p style='color:green;'>Successfully unloaded $quantity items to $unloadingLocation for shipment $licenseNumber.</p>";
}
    } else {
        echo "<p style='color:red;'>Shipment ID is missing.</p>";
    }



// HTML Form for Unloading Shipment
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Unload Shipment</h2>";

echo "Shipment (Shipment ID - License Number): <select name='shipment_id'>";
foreach ($shipmentsResult as $row) {
    echo "<option value='" . $row['ShipmentID'] . "'>" . $row['ShipmentID'] . " - " . $row['LicenseNumber'] . "</option>";
}
echo "</select> <br>";

echo "Unloading Location: <select name='unloading_location'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "Quantity: <input type='number' name='quantity' min='1' required> <br>";

echo "<input type='submit' name='unload_shipment' value='Unload Shipment'>";
echo "</form>";
echo "</div>";

echo "</body></html>";
$conn->close();
?>
