<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

include 'connect_db.php';

// Fetch Suppliers for Dropdown
$suppliersQuery = "SELECT SupplierID, SupplierName FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

// Add Raw Material
if (isset($_POST['add_raw_material'])) {
    $supplierID = $_POST['supplier_id'];
    $materialType = $_POST['material_type'];
    $materialName = $_POST['material_name'];
    $userName = $conn->real_escape_string($_POST['user_name']);
    $comments = $userName . ' Created Date: ' . date("Y-m-d H:i:s");

    // Prepared statement to insert raw material
    $stmt = $conn->prepare("INSERT INTO RawMaterials (SupplierID, MaterialType, MaterialName, Comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $supplierID, $materialType, $materialName, $comments);
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>New raw material added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error adding raw material: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// CSS for styling
echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }
    .container {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 20px auto;
    }
    h2 {
        color: #333;
    }
    input[type='text'], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    input[type='submit'] {
        background-color: #5cb85c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type='submit']:hover {
        background-color: #4cae4c;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
</style>";

// HTML Form for Adding Raw Material
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Add New Raw Material</h2>";

echo "<div class='form-group'><label>Supplier:</label> <select name='supplier_id'>";
echo "<option value=''>Select Supplier</option>";
foreach ($suppliersResult as $row) {
    echo "<option value='" . $row['SupplierID'] . "'>" . $row['SupplierName'] . "</option>";
}
echo "</select></div>";

echo "<div class='form-group'><label>Material Type: </label> <select name='material_type'>;
echo "<option value=''>Select Material Type</option>"; 
echo "<option value='OCC'>OCC (آخال کاغذ و مقوا)</option>";
    echo "<option value='Offset'>Offset (پوشال سفید )</option>";
    echo "<option value='Office'>Office Forms (پرونده)</option>";
    echo "<option value='Chemical'>Chemical (مواد شیمیایی)</option>";
    echo "<option value='Parts'>Parts (قطعات)</option>";
    echo "<option value='Production'>Production (تولید)</option>";
    echo "<option value='Core'>Core (لوله کر)</option>";
    echo "<option value='NEW'>NEW (جدید)</option>";
echo "</select></div>";

echo "Material Name: <input type='text' name='material_name' required> <br>";
echo "User Name: <input type='text' name='user_name' required> <br>";
echo "<input type='submit' name='add_raw_material' value='Add Raw Material'>";

echo "</form>";
echo "</div>";

echo "</body></html>";

$conn->close();
?>
