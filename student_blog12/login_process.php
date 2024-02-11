<?php
// Start session
session_start();

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
$roll_number = $_POST['roll_number'];
$password = $_POST['password'];

// Prepare SQL query to check if the student exists
$sql = "SELECT * FROM students WHERE Roll_Number = '$roll_number' AND Password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Student found, set session variables and redirect to dashboard or home page
    $row = $result->fetch_assoc();
    $_SESSION['logged_in'] = true;
    $_SESSION['roll_number'] = $roll_number;
    $_SESSION['student_id'] = $row['id']; // Assuming 'id' is the column name for the student's ID
    $_SESSION['student_name'] = $row['name']; // Assuming 'name' is the column name for the student's name
    header("Location: dashboard.php");
    exit();
} else {
    // Invalid credentials, redirect back to login page with error message
    echo "Something went wrong";
    exit();
}

// Close database connection
$conn->close();
?>
