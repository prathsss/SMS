<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

// ✅ Redirect if user not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// ✅ Fetch recent notices
$noticesQuery = $conn->query("
    SELECT n.title, n.content, n.posted_on, u.username AS posted_by, u.role AS posted_by_role
    FROM notices n
    LEFT JOIN users u ON n.posted_by = u.user_id
    ORDER BY n.posted_on DESC
    LIMIT 5
");

$notices = $noticesQuery ? $noticesQuery->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/view_notice.css">

</head>
<body>
  <section class="card">
    <h3>Recent Notices</h3>
    <?php if (!empty($notices)): ?>
      <ul>
      <?php foreach ($notices as $n): ?>
  <div>
      <h4 >
      <b> <u><?php echo htmlspecialchars($n['title']); ?></u></b>
      </h4>
      <p >
        <?php echo nl2br(htmlspecialchars($n['content'])); ?>
      </p>
      <small>
         <?php echo htmlspecialchars($n['posted_on']); ?><br>
        Posted by: <?php echo htmlspecialchars($n['posted_by'] ?? 'Unknown'); ?> 
        (<?php echo htmlspecialchars($n['posted_by_role'] ?? 'N/A'); ?>)
      </small>
  </div>
<?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No recent notices.</p>
    <?php endif; ?>
    <div class="logout-link">
        <a href="dashboard.php">Dashboard</a>
    </div>
  </section>

</div>
</body>
</html>
