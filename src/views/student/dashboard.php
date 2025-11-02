<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

// ✅ Redirect if user not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch student info safely
$studentQuery = $conn->query("
    SELECT s.*, d.dep_name, sem.semester_name
    FROM students s
    JOIN department d ON s.dep_id = d.dep_id
    JOIN semesters sem ON s.semester_id = sem.semester_id
    WHERE s.user_id = '$user_id'
");

if ($studentQuery && $studentQuery->num_rows > 0) {
    $student = $studentQuery->fetch_assoc();
} else {
    $student = null;
}

// ✅ Fetch subjects only if student found
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
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="css/student.css">
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?> 👋</h2>

<div class="dashboard">

  <section class="card">
    <h3>Profile Info</h3>
    <?php if ($student): ?>
      <p><b>Department:</b> <?php echo htmlspecialchars($student['dep_name']); ?></p>
      <p><b>Semester:</b> <?php echo htmlspecialchars($student['semester_name']); ?></p>
      <p><b>Roll No:</b> <?php echo htmlspecialchars($student['roll_no']); ?></p>
    <?php else: ?>
      <p style="color:red;">No student data found for your account.</p>
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
    </ul>
  </section>

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
        <a href="/Student-management-system/public/logout.php">Logout</a>
    </div>
  </section>

</div>
</body>
</html>
