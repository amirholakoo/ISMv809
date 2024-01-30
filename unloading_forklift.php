<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// Fetch Incoming Trucks
$incomingTrucksQuery = "SELECT LicenseNumber FROM Shipments WHERE Status = 'Incoming' AND Location = 'LoadingUnloading'";
$incomingTrucksResult = $conn->query($incomingTrucksQuery);

// Unloading Forklift Operation
if (isset($_POST['unloading_forklift'])) {
    $licenseNumber = $_POST['license_number'];
    $unloadingZone = $_POST['unloading_zone'];
    $quantity = $_POST['quantity'];
    $receiveDate = date("Y-m-d H:i:s");

    // Select the appropriate Anbar table based on unloading zone
    $anbarTable = "";
    switch ($unloadingZone) {
        case "Anbar_Sangin":
            $anbarTable = "Anbar_Sangin";
            break;
        // ... other cases for different Anbars
    }

    // Insert into selected Anbar table
    if ($anbarTable) {
        $insertAnbarQuery = "INSERT INTO $anbarTable (ReceiveDate, LicenseNumber, Quantity, Status, Location) VALUES (?, ?, ?, 'In-stock', ?)";
        $insertAnbar = $conn->prepare($insertAnbarQuery);
        $insertAnbar->bind_param("sisi", $receiveDate, $licenseNumber, $quantity, $unloadingZone);

        if ($insertAnbar->execute()) {
            echo "<p style='color:green;'>Record successfully added to $anbarTable for $licenseNumber.</p>";
        } else {
            echo "<p style='color:red;'>Error adding record: " . $insertAnbar->error . "</p>";
        }
        $insertAnbar->close();
    } else {
        echo "<p style='color:red;'>Invalid unloading zone selected.</p>";
    }
}

// HTML Form for Unloading Forklift Operation
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Unloading Forklift Operation</h2>";
echo "Truck (License Number): <select name='license_number'>";
while ($row = $incomingTrucksResult->fetch_assoc()) {
    echo "<option value='" . $row['LicenseNumber'] . "'>" . $row['LicenseNumber'] . "</option>";
}
echo "</select> <br>";

// Dropdown for Unloading Zone
echo "Unloading Zone: <select name='unloading_zone'>";
// List of Anbar tables as options
echo "<option value='Anbar_Sangin'>Anbar_Sangin</option>";
// ... other Anbar options
echo "</select> <br>";

echo "Quantity: <input type='number' name='quantity' required> <br>";
echo "<input type='submit' name='unloading_forklift' value='Unload'>";
echo "</form>";
echo "</div>";

echo "</body></html>";
$conn->close();
?>
