<?php
include 'connect_db.php';

// Add Supplier
if (isset($_POST['add_supplier'])) {
    $supplierName = $conn->real_escape_string($_POST['supplier_name']);
    $address = $conn->real_escape_string($_POST['supplier_address']);
    $phone = $conn->real_escape_string($_POST['supplier_phone']);

    $insertSupplier = "INSERT INTO Suppliers (SupplierName, Address, Phone) VALUES ('$supplierName', '$address', '$phone')";
    
    if ($conn->query($insertSupplier) === TRUE) {
        echo "<p style='color:green;'>New supplier added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error adding supplier: " . $conn->error . "</p>";
    }
}

// Add Customer
if (isset($_POST['add_customer'])) {
    $customerName = $conn->real_escape_string($_POST['customer_name']);
    $address = $conn->real_escape_string($_POST['customer_address']);
    $phone = $conn->real_escape_string($_POST['customer_phone']);

    $insertCustomer = "INSERT INTO Customers (CustomerName, Address, Phone) VALUES ('$customerName', '$address', '$phone')";
    
    if ($conn->query($insertCustomer) === TRUE) {
        echo "<p style='color:green;'>New customer added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error adding customer: " . $conn->error . "</p>";
    }
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
    input[type='text'], textarea {
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

// HTML Forms for Adding Supplier and Customer
echo "<div class='container'>";

// Form for Adding Supplier
echo "<form method='post'>";
echo "<h2>Add New Supplier</h2>";
echo "<div class='form-group'><label>Name:</label> <input type='text' name='supplier_name' required></div>";
echo "<div class='form-group'><label>Address:</label> <textarea name='supplier_address' required></textarea></div>";
echo "<div class='form-group'><label>Phone:</label> <input type='text' name='supplier_phone' required></div>";
echo "<input type='submit' name='add_supplier' value='Add Supplier'>";
echo "</form>";

// Form for Adding Customer
echo "<form method='post'>";
echo "<h2>Add New Customer</h2>";
echo "<div class='form-group'><label>Name:</label> <input type='text' name='customer_name' required></div>";
echo "<div class='form-group'><label>Address:</label> <textarea name='customer_address' required></textarea></div>";
echo "<div class='form-group'><label>Phone:</label> <input type='text' name='customer_phone' required></div>";
echo "<input type='submit' name='add_customer' value='Add Customer'>";
echo "</form>";

echo "</div>";
echo "</body></html>";

$conn->close();
?>
