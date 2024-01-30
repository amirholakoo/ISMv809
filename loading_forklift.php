<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// Fetch Outgoing Shipments for Dropdown
$shipmentsQuery = "SELECT ShipmentID, LicenseNumber FROM Shipments WHERE Status = 'Outgoing' AND Location = 'LoadingUnloading'";
$shipmentsResult = $conn->query($shipmentsQuery);

// List of Anbars and Products for Dropdown
$loadingLocations = array_merge(['Products'], [
    'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
]);

// Handle Load Truck
if (isset($_POST['load_truck'])) {
    // ... Code to handle truck loading, update Shipments, Products, and Anbar tables
}

// HTML Form for Loading Shipment
echo "<div class='container'>";
echo "<form method='post' id='load_truck_form'>";
echo "<h2>Load Truck</h2>";

echo "Shipment (Shipment ID - License Number): <select name='shipment_id'>";
foreach ($shipmentsResult as $row) {
    echo "<option value='" . $row['ShipmentID'] . "'>" . $row['ShipmentID'] . " - " . $row['LicenseNumber'] . "</option>";
}
echo "</select> <br>";

echo "Loading Location: <select name='loading_location' id='loading_location'>";
foreach ($loadingLocations as $location) {
    echo "<option value='$location'>$location</option>";
}
echo "</select> <br>";

echo "Width: <select name='width' id='width'>";
// Populate width options based on selected location
echo "</select> <br>";

echo "Reel Numbers: <select name='reel_numbers[]' id='reel_numbers' multiple>";
// Populate reel number options based on selected location and width
echo "</select> <br>";

echo "<input type='submit' name='load_truck' value='Load Truck'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script>
    // JavaScript to dynamically load widths and reel numbers based on selected location
    // Add AJAX code here
</script>";

echo "</body></html>";
$conn->close();
?>
