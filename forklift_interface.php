<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
include 'connect_db.php';

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// HTML for Forklift Interface Page
echo "<div class='container'>";
echo "<h2>Forklift Operations</h2>";

echo "<div class='forklift-tabs'>";
echo "<a href='unloading_forklift.php'><button class='btn'>Unloading</button></a><br><br><br>";
echo "<a href='loading_forklift.php'><button class='btn'>Loading</button></a><br><br><br>";
echo "<a href='using_forklift.php'><button class='btn'>Using</button></a><br><br><br>";
echo "<a href='moving_forklift.php'><button class='btn'>Moving</button></a><br><br><br>";
echo "</div>";
echo "</div>";

echo "</body></html>";
$conn->close();
?>
