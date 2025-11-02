<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

// ✅ Only teacher or admin allowed
if (!isset($_SESSION['user_id']) || 
   ($_SESSION['role'] != 'teacher' && $_SESSION['role'] != 'admin')) {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ensure all fields are present
    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $posted_by = $_SESSION['user_id'];

        $sql = "INSERT INTO notices (title, content, posted_by, posted_on)
                VALUES ('$title', '$content', '$posted_by', NOW())";

        if ($conn->query($sql)) {
            echo "<script>alert('✅ Notice posted successfully!'); window.location='dashboard.php';</script>";
            exit;
        } else {
            echo "<p style='color:red;'>❌ SQL Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Please fill out all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Notice</title>
  <link rel="stylesheet" href="css/add_notice.css">
</head>
<body>
  <div class="container">
    <h2>Post a New Notice</h2>
    <form method="POST">
      <label>Notice Title:</label>
      <input type="text" name="title" required><br><br>

      <label>Content:</label>
      <textarea name="content" rows="6" required></textarea><br><br>

      <button type="submit">Post Notice</button>
    </form>
  </div>
  <a href="dashboard.php">Back to dashboard</a>
</body>
</html>
