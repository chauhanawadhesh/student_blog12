<?php
// Start session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to the login page
    header("Location: student_login.php");
}

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

$sql = "SELECT * FROM blogs";
$result = $conn->query($sql);

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

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        h3 {
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px;
        }
        li {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .comment-form {
            display: none;
        }
        .reply-form {
            display: none;
        }
        .comments-container {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .comment {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .comment p {
            margin: 5px 0;
        }
        .temp{
            justify-content: space-between;
            display: flex;
            padding: 20px;
        }
        .blog-post-content{
            display: flex;
            margin: 10px;
        }
        .blog-post-details{
            margin-left: 35px;
        }
        .blog-image{
            height: 100px;
            width: 200px;
        }
    </style>
</head>
<body>
    <h2>Hi <strong><?php echo $student_name; ?></strong>, Welcome to the Student Dashboard!</h2>
    <div class="temp">
        <h3>Blog Posts</h3>
        <a href="logout.php">Logout</a>
    </div>
    <a href="create_blog_post.php" style="margin-left: 56px;">Create a New Blog Post</a>
    <ul class="blog-posts">
        <?php
        while ($row = $result->fetch_assoc()) {
            if (isset($row["Title"]) && isset($row["Description"]) && isset($row["Image"])) {
                ?>
                <li class="blog-post">
                    <div class="blog-post-content">
                        <div class="blog-post-image">
                            <img src="<?php echo $row["Image"]; ?>" alt="Blog Post Image" class="blog-image">
                        </div>
                        <div class="blog-post-details">
                            <h4 style="margin-top:0px;"><?php echo $row["Title"]; ?></h4>
                            <p><?php echo $row["Description"]; ?></p>
                            <button class="show-comments" data-blog-id="<?php echo $row["id"]; ?>">Show Comments</button>
                            <button class="show-comment-btn" data-blog-id="<?php echo $row["id"]; ?>">Post Comment</button>
                            <form class="comment-form" data-blog-id="<?php echo $row["id"]; ?>">
                                <textarea class="comment-textarea" name="comment" placeholder="Write your comment here..." rows="4" cols="50"></textarea><br>
                                <input type="hidden" class="blog-id" name="blog_id" value="<?php echo $row["id"]; ?>">
                                <input type="submit" class="submit-comment" value="Send">
                            </form>
                        </div>
                    </div>
                    <div class="comments-container"style="display:none"></div>
                </li>
                <?php
            } else {
                echo "<li>Invalid data for this blog post</li>";
            }
        }
        ?>
    </ul>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.comment-form', function() {
                $(this).siblings('.comments-container').toggle();
            });

            // Post Comment Form
            $(document).on('click', '.show-comment-btn', function() {
                $(this).siblings('.comment-form').toggle();
            });

        });
        $(document).ready(function() {
        $(document).on('click', '.post_reply', function() {
            $(this).siblings('.reply-form').toggle();
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $(document).on('submit', '.comment-form', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Get form data
            var formData = $(this).serialize();

            // AJAX post request to submit the comment
            $.ajax({
                url: 'save_comment.php', // Update with your PHP script URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    $(".comment-form").trigger("reset");
                    $(".show-comment-btn").trigger("click");
                    // Handle success response
                    console.log(response); // Log the response
                    // You can update the UI as needed here
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText); // Log the error response
                    // You can display an error message or take other actions as needed
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.show-comments', function() {
            var blogId = $(this).data('blog-id');
            var commentsContainer = $(this).closest('.blog-post').find('.comments-container');

            // AJAX request to fetch comments
            $.ajax({
                url: 'fetch_comments.php', // Update with your PHP script URL
                type: 'POST',
                data: { blog_id: blogId },
                success: function(response) {
                    // Update comments container with fetched comments
                    commentsContainer.html(response);
                    commentsContainer.slideToggle(); // Show/hide comments container
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error response
                    // Handle error if necessary
                }
            });
        });
    });
</script>

</html>
