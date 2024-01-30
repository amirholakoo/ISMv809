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

$shipmentID = $_POST['shipment_id'];
    $loadingLocation = $_POST['loading_location'];
    $selectedWidth = $_POST['width'];
    $selectedReels = $_POST['reel_numbers'];

    // Update Shipments Table
    $listOfReels = implode(',', $selectedReels);
    $updateShipments = $conn->prepare("UPDATE Shipments SET ListOfReels = ?, Location = 'LoadedUnloaded' WHERE ShipmentID = ?");
    $updateShipments->bind_param("si", $listOfReels, $shipmentID);
    $updateShipments->execute();
    $updateShipments->close();

    // Update Products or Anbar Table
    if ($loadingLocation == 'Products') {
        foreach ($selectedReels as $reelNumber) {
            $updateProduct = $conn->prepare("UPDATE Products SET Status = 'Sold', Location = ? WHERE ReelNumber = ?");
            $updateProduct->bind_param("ss", $shipmentID, $reelNumber);
            $updateProduct->execute();
            $updateProduct->close();
        }
    } else {
        foreach ($selectedReels as $reelNumber) {
            $updateAnbar = $conn->prepare("UPDATE $loadingLocation SET Status = 'Sold', Location = ? WHERE ReelNumber = ? AND Width = ?");
            $updateAnbar->bind_param("ssi", $shipmentID, $reelNumber, $selectedWidth);
            $updateAnbar->execute();
            $updateAnbar->close();
        }
    }

    echo "<p style='color:green;'>Truck loaded successfully for shipment ID $shipmentID.</p>";
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
$(document).ready(function() {
    $('#loading_location').change(function() {
        var location = $(this).val();
        $.ajax({
            url: 'fetch_widths.php',
            type: 'POST',
            data: {location: location},
            success: function(response) {
                $('#width').html(response);
            }
        });
    });

    $('#width').change(function() {
        var location = $('#loading_location').val();
        var width = $(this).val();
        $.ajax({
            url: 'fetch_reel_numbers.php',
            type: 'POST',
            data: {location: location, width: width},
            success: function(response) {
                $('#reel_numbers').html(response);
            }
        });
    });
});
</script>";

echo "</body></html>";
$conn->close();
?>
