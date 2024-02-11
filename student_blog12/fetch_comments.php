<?php
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

// Check if blog_id parameter is set
if(isset($_POST['blog_id'])) {
    // Sanitize the input to prevent SQL injection
    $blogId = $conn->real_escape_string($_POST['blog_id']);

    // Fetch comments from the database for the specified blog post
    $sql = "SELECT * FROM comments WHERE blog_id = '$blogId'";
    $result = $conn->query($sql);

    // Check if there are comments
    if ($result->num_rows > 0) {
        // Output each comment as HTML content
        while($row = $result->fetch_assoc()) {
            echo '<div class="comment">';
            echo '<p><strong>' . $row['author'] . '</strong>: ' . $row['comment'] . '</p>';
            echo '</div>';
        }
    } else {
        echo 'No comments yet.';
    }
} else {
    echo 'Invalid request.';
}

// Close the database connection
$conn->close();
?>
