<?php
// Establish database connection (assuming MySQL)
$servername = "localhost";
$username = "root";
$password = "";
$database = "machine_test";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve input values from the form
$name = $_POST['name'];
$email = $_POST['email'];
$roll_number = $_POST['roll_number'];
$password = $_POST['password'];

// Generate password of 8 digits (You can customize this according to your requirements)
// $password = mt_rand(10000000, 99999999);

// Prepare and execute SQL query to insert student data
$sql = "INSERT INTO students (Name, Email, Roll_Number, Password) VALUES ('$name', '$email', '$roll_number', '$password')";

if ($conn->query($sql) === TRUE) {
    // Registration successful, redirect to login page
    header("Location: student_login.php");
    exit();
} else {
    // Registration failed, redirect back to registration page with error message
    header("Location: register.php?error=1");
    exit();
}

// Close database connection
$conn->close();
?>
