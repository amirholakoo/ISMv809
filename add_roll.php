<?php
include 'connect_db.php';
include 'phpqrcode/qrlib.php'; // Path to phpqrcode library

// Include the CSS file
echo "<link rel='stylesheet' href='style.css'>";

// Add Roll to Products Table
if (isset($_POST['add_roll'])) {
    $reelNumber = $conn->real_escape_string($_POST['reel_number']);
    $width = $_POST['width'];
    $gsm = $_POST['gsm'];
    $length = $_POST['length'];
    $grade = $conn->real_escape_string($_POST['grade']);
    $breaks = $conn->real_escape_string($_POST['breaks']);
    $comments = $conn->real_escape_string($_POST['comments']);
    $status = 'In-Stock';
    $location = 'Production';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Products (ReelNumber, Width, GSM, Length, Grade, Breaks, Comments, Status, Location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiissss", $reelNumber, $width, $gsm, $length, $grade, $breaks, $comments, $status, $location);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>New roll added successfully!</p>";

        // Generate QR Code
        $qrData = "Reel Number: $reelNumber, Width: $width, GSM: $gsm, Breaks: $breaks, Comments: $comments";
        $qrCodePath = 'qrcodes/'.$reelNumber.'.png';
        QRcode::png($qrData, $qrCodePath);

        // Display QR Code
        echo "<p>QR Code for Reel Number $reelNumber:</p>";
        echo "<img src='$qrCodePath' />";

        // Generate new reel number for the next submission
        $newReelNumber += $reelNumber; // logic to calculate next reel number

        
    } else {
        echo "<p style='color:red;'>Error adding roll: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// HTML Form for Adding Roll
echo "<div class='container'>";
echo "<form method='post'>";
echo "<h2>Add New Roll</h2>";
echo "<div class='form-group'><label>Reel Number:</label> <input type='text' name='reel_number' value='$newReelNumber' required></div>";

// Width Dropdown
echo "<div class='form-group'><label>Width:</label> <select name='width'>";
$widthOptions = [200, 210, 220, 230, 240, 250, 'NEW'];
foreach ($widthOptions as $option) {
    echo "<option value='$option'>$option</option>";
}
echo "</select></div>";

// Default values for GSM, Length, Grade, Breaks
echo "<div class='form-group'><label>GSM:</label> <input type='number' name='gsm' value='130'></div>";
echo "<div class='form-group'><label>Length:</label> <input type='number' name='length' value='7001'></div>";
echo "<div class='form-group'><label>Grade:</label> <input type='text' name='grade' value='A'></div>";
echo "<div class='form-group'><label>Breaks:</label> <input type='text' name='breaks' value='0'></div>";
echo "<div class='form-group'><label>Comments:</label> <textarea name='comments'></textarea></div>";

echo "<input type='submit' name='add_roll' value='Add Roll'>";
echo "</form>";
echo "</div>";

echo "</body></html>";


$conn->close();
?>
