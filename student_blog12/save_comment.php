<?php
session_start();
// Include your database connection file if necessary
// include 'db_connection.php';

// Assuming you have established a database connection already
$servername = "localhost";
$username = "root";
$password = "";
$database = "machine_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$student_id = $_SESSION['student_id']; // Assuming this is how you stored the student ID in the session

$stu_sql = "SELECT name FROM students WHERE id = $student_id"; // Assuming 'students' is the table name

$stu_result = $conn->query($stu_sql);

if ($stu_result->num_rows > 0) {
    // Fetch student name
    $stu_row = $stu_result->fetch_assoc();
    $student_name = $stu_row['name'];
} else {
    $student_name = '';
}
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data to prevent SQL injection
    $comment = $conn->real_escape_string($_POST['comment']);
    $blogId = $conn->real_escape_string($_POST['blog_id']);

    // Insert the comment into the database
    $sql = "INSERT INTO comments (blog_id, author,comment) VALUES ('$blogId','$student_name', '$comment')";

    if ($conn->query($sql) === TRUE) {
        echo "Comment submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
