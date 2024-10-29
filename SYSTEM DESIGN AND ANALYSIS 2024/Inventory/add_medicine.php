<?php
// add_medicine.php

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospitaldb";

// Connect to database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $quantity = intval($_POST['quantity']);
    $category = htmlspecialchars(trim($_POST['category']));
    $manufacturer = htmlspecialchars(trim($_POST['manufacturer']));
    $price = floatval($_POST['price']);

    // Verify all fields are filled
    if ($name && $quantity && $category && $manufacturer && $price) {
        // Check if the medicine already exists in the database
        $checkStmt = $conn->prepare("SELECT quantity FROM inventory WHERE medicine = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Medicine exists, update the quantity
            $checkStmt->bind_result($existingQuantity);
            $checkStmt->fetch();

            $newQuantity = $existingQuantity + $quantity;

            // Prepare the update statement
            $updateStmt = $conn->prepare("UPDATE inventory SET quantity = ?, price = ? WHERE medicine = ?");
            $updateStmt->bind_param("ids", $newQuantity, $price, $name);

            if ($updateStmt->execute()) {
                echo '
                   <div id="successModal" style="
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: #d4edda;
    border: 2px solid #003049;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    font-family: Arial, sans-serif;
">
    <h3 style="color: #155724; margin: 0;">Success!</h3>
    <p style="color: #155724;">Quantity updated successfully.</p>
    <button onclick="closeModal()" style="
        background-color: #003049;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    ">OK</button>
</div>

<script>
    function closeModal() {
        document.getElementById("successModal").style.display = "none";
        location.href = "./inventory.php";
    }
</script>

                ';
            } else {
                echo "Error updating quantity: " . $updateStmt->error;
            }

            $updateStmt->close();
        } else {
            // Medicine does not exist, insert a new record
            $stmt = $conn->prepare("INSERT INTO inventory (medicine, category, manufacturer, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $name, $category, $manufacturer, $quantity, $price);

            // Execute and check if insertion was successful
            if ($stmt->execute()) {
                echo '
                    <div id="successModal" style="
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: #d4edda;
    border: 2px solid #28a745;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    font-family: Arial, sans-serif;
">
    <h3 style="color: #155724; margin: 0;">Success!</h3>
    <p style="color: #155724;">Medicine successfully added.</p>
    <button onclick="closeModal()" style="
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    ">OK</button>
</div>

<script>
    function closeModal() {
        document.getElementById("successModal").style.display = "none"; // Hide the modal
        location.href = "./inventory.php"; 
    }
</script>

                ';
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        echo '
        <div id="successModal" style="
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #d4edda;
            border: 2px solid #571616;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            font-family: Arial, sans-serif;
        ">
            <h3 style="color: #ff0000; margin: 0;">Oops!</h3>
            <p style="color: #155724;">All fields are required!</p>
            <button onclick="closeModal(); location.href="./inventory.php;" style="
                background-color: #a75928;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 4px;
                cursor: pointer;
            " >OK</button>
        </div>

        <script>
            function closeModal() {
                document.getElementById("successModal").style.display = "none";
                
            }

            

        </script>
        ';
    }
}

$conn->close();
