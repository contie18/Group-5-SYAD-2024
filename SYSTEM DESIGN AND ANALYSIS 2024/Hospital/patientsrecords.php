<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospitaldb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is being posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $name = $_POST['name']; // Name of the person
    $medicine = $_POST['medicine']; // Name of the medicine
    $disease = $_POST['disease']; // Disease description
    $price = $_POST['price']; // Price
    $quantity = $_POST['quantity']; // Quantity
    $phone = $_POST['phone']; // Phone number
    $age = $_POST['age']; // Age
    $location = $_POST['location']; // Location
    $prescription = $_POST['prescription']; // Prescription
    $visitDate = $_POST['idate']; // Visit date


    // Prepare and execute the check for inventory quantity
    $check = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM inventory WHERE medicine = ?");
    $check->bind_param("s", $medicine);
    $check->execute();
    $result = $check->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $total_quantity = $row['total_quantity'] ?? 0;

        // Check if enough quantity is available
        if ($total_quantity >= $quantity && $quantity > 0) {
            // Prepare and bind SQL statement for inserting patient
            $stmt = $conn->prepare("INSERT INTO patients (name, disease, price, medicine, quantity, phone, age, location, prescription, visit_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsiissss", $name, $disease, $price, $medicine, $quantity, $phone, $age, $location, $prescription, $visitDate);

            // Execute the statement
            if ($stmt->execute()) {
                // Update the inventory to subtract the quantity
                $update = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE medicine = ?");
                $update->bind_param("is", $quantity, $medicine);
                $update->execute();

                if ($update->affected_rows > 0) {

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
            <p style="color: #155724;">Medicine requested: ' . $medicine . '.</p>
            <p style="color: #155724;">Total quantity available ' . $total_quantity . '.</p>
            <p style="color: #155724;">Requested quantity: ' . $quantity . '.</p>
            <p style="color: #155724;">New record created successfully and inventory updated.</p>
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
                document.getElementById("successModal").style.display = "none";
            }
        </script>
        ';
                } else {
                    echo "Patient added but inventory update failed.";
                }

                // Close the update statement
                $update->close();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the insert statement
            $stmt->close();
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
            <p style="color: #155724;">Not enough medicine in stock.</p>
            <button onclick="closeModal()" style="
                background-color: #a75928;
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
            }
        </script>
        ';
        }
    } else {
        echo "Error checking inventory: " . $check->error;
    }

    // Close the inventory check statement and connection
    $check->close();
    $conn->close();
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
            <p style="color: #155724;">You are out of stock!.</p>
            <button onclick="closeModal()" style="
                background-color: #a75928;
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
            }
        </script>
        ';
}
