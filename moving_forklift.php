<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// List of Anbars and Products for Dropdown
$anbars = [
    'Products', 'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
];

// Handle Material Movement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_material'])) {
    // ... [Code to handle material movement]
}

// HTML Form for Moving Material
echo "<div class='container'>";
echo "<form method='post' id='move_material_form'>";
echo "<h2>Move Material</h2>";

echo "From: <select name='from_location' id='from_location'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "Select Type: <select name='select_type' id='select_type'>";
// Dynamically populate based on selected 'From' location
echo "</select> <br>";

echo "Select Item: <div id='select_item'>";
// Dynamically populate based on selected 'Select Type'
echo "</div> <br>";

echo "To: <select name='to_location' id='to_location'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "<input type='submit' name='move_material' value='Move'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns and form processing
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script>
$(document).ready(function() {
    $('#from_location').change(function() {
        var fromLocation = $(this).val();
        $.ajax({
            url: 'fetch_select_type.php',
            type: 'POST',
            data: {from_location: fromLocation},
            success: function(response) {
                $('#select_type').html(response);
            }
        });
    });

    $('#select_type').change(function() {
        var fromLocation = $('#from_location').val();
        var selectType = $(this).val();
        $.ajax({
            url: 'fetch_select_items.php',
            type: 'POST',
            data: {from_location: fromLocation, select_type: selectType},
            success: function(response) {
                $('#select_item').html("<select multiple name='selected_items[]'>" + response + "</select>");
            }
        });
    });
});
</script>
";

echo "</body></html>";
$conn->close();
?>
