<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

// ✅ Redirect if not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch student_id
$studentQuery = $conn->query("SELECT student_id, name FROM students WHERE user_id = '$user_id'");
$student = $studentQuery && $studentQuery->num_rows > 0 ? $studentQuery->fetch_assoc() : null;

if (!$student) {
    die("<p style='color:red;'>No student record found.</p>");
}

// ✅ Fetch Attendance Data
$attendanceData = [];
$attendanceQuery = $conn->query("
    SELECT sub.subject_name,
           COUNT(CASE WHEN a.status = 'Present' THEN 1 END) AS present_days,
           COUNT(*) AS total_days
    FROM attendance a
    JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE a.student_id = '{$student['student_id']}'
    GROUP BY a.subject_id
");
if ($attendanceQuery) {
    $attendanceData = $attendanceQuery->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
   <link rel="stylesheet" href="css/attendance.css">
</head>
<body>

<div class="container">
    <h2>Attendance Report - <?php echo htmlspecialchars($student['name']); ?></h2>

    <?php if (!empty($attendanceData)): ?>
        <table>
            <tr>
                <th>Subject</th>
                <th>Present Days</th>
                <th>Total Days</th>
                <th>Percentage</th>
            </tr>
            <?php foreach ($attendanceData as $row): 
                $percentage = ($row['total_days'] > 0) ? round(($row['present_days'] / $row['total_days']) * 100, 2) : 0;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo $row['present_days']; ?></td>
                <td><?php echo $row['total_days']; ?></td>
                <td><?php echo $percentage . '%'; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No attendance records found.</p>
    <?php endif; ?>

    <a href="/Student-management-system/src/views/student/dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
