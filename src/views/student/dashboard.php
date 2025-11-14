<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student info
$studentQuery = $conn->query("
    SELECT s.*, d.dep_name, sem.semester_name
    FROM students s
    JOIN department d ON s.dep_id = d.dep_id
    JOIN semesters sem ON s.semester_id = sem.semester_id
    WHERE s.user_id = '$user_id'
");

$student = ($studentQuery && $studentQuery->num_rows > 0) ? $studentQuery->fetch_assoc() : null;

// Fetch subjects
$subjects = [];
if ($student) {
    $subjectQuery = $conn->query("
        SELECT subject_name, subject_code 
        FROM subjects 
        WHERE semester_id = '{$student['semester_id']}'
    ");
    if ($subjectQuery) {
        $subjects = $subjectQuery->fetch_all(MYSQLI_ASSOC);
    }
}

// Fetch notices
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
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="css/student.css">
</head>
<body>

<div class="dashboard-layout">

  <!-- ✅ SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <h2>Student Panel</h2>
      <p>Welcome,<strong> <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?></strong></p>
    </div>
    <ul class="sidebar-nav">
      <li><a href="attendance.php"> Check Attendance</a></li>
      <li><a href="marks.php">Check Marks</a></li>
      <li><a href="view_notices.php"> View Notices</a></li>
      <li><a href="/Student-management-system/public/logout.php"> Logout</a></li>
    </ul>
  </aside>

  <!-- ✅ MAIN CONTENT -->
  <main class="main-content">
    <h1>Student Dashboard</h1>

    <div class="dashboard">

      <section class="card">
        <h3>Profile Info</h3>
        <?php if ($student): ?>
          <p><b>Department:</b> <?php echo htmlspecialchars($student['dep_name']); ?></p>
          <p><b>Semester:</b> <?php echo htmlspecialchars($student['semester_name']); ?></p>
          <p><b>Roll No:</b> <?php echo htmlspecialchars($student['roll_no']); ?></p>
        <?php else: ?>
          <p style="color:red;">No student data found.</p>
        <?php endif; ?>
      </section>

      <section class="card">
        <h3>Subjects</h3>
        <?php if (!empty($subjects)): ?>
          <ul>
            <?php foreach ($subjects as $sub): ?>
              <li><?php echo htmlspecialchars($sub['subject_code'] . ' - ' . $sub['subject_name']); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No subjects found.</p>
        <?php endif; ?>
      </section>

      <section class="card">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="attendance.php">View Attendance</a></li>
          <li><a href="marks.php">View Marks</a></li>
          <li><a href="view_notices.php">View Notices</a></li>
        </ul>
      </section>
          <!--
      <section class="card">
        <h3>Recent Notices</h3>
        <?php if (!empty($notices)): ?>
          <?php foreach ($notices as $n): ?>
            <div>
              <h4><b><u><?php echo htmlspecialchars($n['title']); ?></u></b></h4>
              <p><?php echo nl2br(htmlspecialchars($n['content'])); ?></p>
              <small>
                <?php echo htmlspecialchars($n['posted_on']); ?><br>
                Posted by: <?php echo htmlspecialchars($n['posted_by'] ?? 'Unknown'); ?> 
                (<?php echo htmlspecialchars($n['posted_by_role'] ?? 'N/A'); ?>)
              </small>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No recent notices.</p>
        <?php endif; ?>
      </section>
        -->
    </div>
  </main>
</div>

</body>
</html>