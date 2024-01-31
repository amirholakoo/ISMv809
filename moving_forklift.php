<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// List of Anbars including Products
$anbars = array_merge(['Products'], [
    'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
]);

// Handle Material Movement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_material'])) {
    $materialType = $_POST['what'];
    $fromAnbar = $_POST['from'];
    $selectedType = $_POST['select_type'];
    $selectedItems = $_POST['select_item']; // Assuming this is an array
    $toAnbar = $_POST['to'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Move each item from the origin to the destination Anbar
        foreach ($selectedItems as $item) {
            // Update origin Anbar
            $updateOriginAnbar = $conn->prepare("UPDATE $fromAnbar SET Status = 'Moved', Location = ? WHERE (ReelNumber = ? OR MaterialName = ?) AND Status = 'In-stock'");
            $updateOriginAnbar->bind_param("sss", $toAnbar, $item, $item);
            $updateOriginAnbar->execute();

            // Insert into destination Anbar
            // Note: Add all necessary fields for INSERT query
            $insertDestAnbar = $conn->prepare("INSERT INTO $toAnbar (/* Fields */) SELECT /* Fields */ FROM $fromAnbar WHERE (ReelNumber = ? OR MaterialName = ?)");
            $insertDestAnbar->bind_param("ss", $item, $item);
            $insertDestAnbar->execute();
        }

        $conn->commit();
        echo "<p style='color:green;'>Materials moved successfully.</p>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error moving materials: " . $e->getMessage() . "</p>";
    }
}

// HTML Form for Moving Material
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Move Material</h2>";

echo "What: <select name='what' id='what'>";
echo "<option value='Rolls'>Rolls</option>";
echo "<option value='Raw'>Raw</option>";
echo "</select> <br>";

echo "From: <select name='from' id='from'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "Select Type: <select name='select_type' id='select_type'>";
// Dynamically populate based on 'What'
echo "</select> <br>";

echo "Select Item: <select name='select_item[]' id='select_item' multiple>";
// Dynamically populate based on 'Select Type'
echo "</select> <br>";

echo "To: <select name='to' id='to'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "<input type='submit' name='move_material' value='Move'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns and data fetching
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script>
$(document).ready(function() {
    $('#what, #from').change(function() {
        var what = $('#what').val();
        var from = $('#from').val();
        if (what && from) {
            // Fetch Types
            $.ajax({
                url: 'fetch_types.php',
                type: 'POST',
                data: { what: what, from: from },
                success: function(response) {
                    $('#select_type').html(response);
                }
            });
        }
    });

    $('#select_type').change(function() {
        var what = $('#what').val();
        var from = $('#from').val();
        var type = $(this).val();
        if (what && from && type) {
            // Fetch Items
            $.ajax({
                url: 'fetch_items.php',
                type: 'POST',
                data: { what: what, from: from, type: type },
                success: function(response) {
                    $('#select_item').html(response);
                }
            });
        }
    });
});
</script>
";

echo "</body></html>";
$conn->close();
?>
