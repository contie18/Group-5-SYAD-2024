<?php
// Start session
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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the form and sanitize input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

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
        if (password_verify($password, $hashed_password)) {
            // Store the user's email in the session
            $_SESSION['email'] = $email;

            // Redirect to the inventory page
            header("Location: inventory.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid password.";
        }
    } else {
        // Email not found
        echo "No user found with that email address.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
