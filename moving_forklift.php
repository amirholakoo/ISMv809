<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

echo "<link rel='stylesheet' href='style.css'>";

// List of Anbars including Products for Dropdown
$anbars = array_merge(['Products'], [
    'Anbar_Sangin', 'Anbar_Salon_Tolid', 'Anbar_Parvandeh', 
    'Anbar_Koochak', 'Anbar_Khamir_Ghadim', 'Anbar_Khamir_Kordan', 
    'Anbar_Muhvateh_Kardan', 'Anbar_Akhal'
]);

// Handle Move Operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_items'])) {
    // ... Code to handle the move operation
}

// HTML Form for Moving Items
echo "<div class='container'>";
echo "<form method='post' id='move_items_form'>";
echo "<h2>Move Items</h2>";

echo "From: <select name='from_anbar' id='from_anbar'>";
foreach ($anbars as $anbar) {
    echo "<option value='$anbar'>$anbar</option>";
}
echo "</select> <br>";

echo "Select Type: <select name='select_type' id='select_type'>";
echo "<option value=''>Select Type</option>"; // Options will be loaded dynamically
echo "</select> <br>";

echo "Select Item: <select name='selected_items[]' id='selected_items' multiple>";
// Options will be loaded dynamically
echo "</select> <br>";

echo "To: <select name='to_anbar' id='to_anbar'>";
// Options will be loaded dynamically based on 'From' selection
echo "</select> <br>";

echo "<input type='submit' name='move_items' value='Move'>";
echo "</form>";
echo "</div>";

// JavaScript for dynamic dropdowns
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script>
    // JavaScript for dynamic dropdowns and AJAX calls
    // Add AJAX code here
</script>";

echo "</body></html>";
$conn->close();
?>
