<?php
// Start session
session_start();

// Check if form data is being posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve email and password from the form
    $email = $_POST['email'];
    $passwordInput = $_POST['password'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("SELECT password FROM logintb WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Bind result
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($passwordInput, $hashed_password)) {
            // Password is correct, redirect to home page
            header("Location: hospital.html");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid password.";
        }
    } else {
        // Email not found
        echo "No user found with that email address.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}