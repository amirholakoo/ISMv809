<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// List of Anbars and Products for Dropdowns
$anbars = [
    'Products', 'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh',
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan',
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
];

// Handle Moving Material
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_material'])) {
    $fromAnbar = $_POST['from'];
    $toAnbar = $_POST['to'];
    $selectedItems = $_POST['selected_items']; // Assuming this is an array

    // Start transaction
    $conn->begin_transaction();

    try {
        foreach ($selectedItems as $item) {
            // Update status in origin Anbar/Products
            $updateOrigin = $conn->prepare("UPDATE $fromAnbar SET Status = 'Moved', Location = ? WHERE (ReelNumber = ? OR ID = ?)");
            $updateOrigin->bind_param("ssi", $toAnbar, $item, $item);
            $updateOrigin->execute();

            // Copy item to destination Anbar
            // Note: Ensure fields match between Anbars and Products table
            $copyToDestination = $conn->prepare("INSERT INTO $toAnbar (SELECT * FROM $fromAnbar WHERE (ReelNumber = ? OR ID = ?))");
            $copyToDestination->bind_param("si", $item, $item);
            $copyToDestination->execute();
        }

        $conn->commit();
        echo "<p style='color:green;'>Material moved successfully.</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// HTML Form for Moving Material
echo "<div class='container'>";
echo "<form method='post' id='move_material_form'>";
echo "<h2>Move Material</h2>";

echo "From: <select name='from' id='from'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";




echo "Select Type: <select name='select_type' id='select_type' onchange='loadItems()'>";
echo "<option value='Reel'>Reel</option>";
echo "<option value='Material'>Material</option>";
echo "</select> <br>";

echo "Select Item: <select name='selected_items[]' id='selected_items' multiple>";
// Options will be loaded dynamically using JavaScript
echo "</select> <br>";

echo "To: <select name='to' id='to'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "<input type='submit' name='move_material' value='Move'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns and AJAX
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script>
function loadItems() {
    var fromAnbar = $('#from').val();
    var selectType = $('#select_type').val();
    $.ajax({
        url: 'fetch_items.php',
        type: 'POST',
        data: {fromAnbar: fromAnbar, selectType: selectType},
        success: function(response) {
            $('#selected_items').html(response);
        }
    });
}

$(document).ready(function() {
    $('#from').change(loadItems);
});
</script>";

echo "</body></html>";
$conn->close();
?>
