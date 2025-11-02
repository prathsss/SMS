<?php
session_start();
include('C:/xampp/htdocs/Student-management-system/config/db.php');

// ✅ Redirect if not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch student info
$studentQuery = $conn->query("SELECT student_id, name FROM students WHERE user_id = '$user_id'");
$student = $studentQuery && $studentQuery->num_rows > 0 ? $studentQuery->fetch_assoc() : null;

if (!$student) {
    die("<p style='color:red;'>No student record found.</p>");
}

// ✅ Fetch Marks Data
$marksData = [];
$marksQuery = $conn->query("
    SELECT sub.subject_name, m.exam_name, m.marks_obtained, m.total_marks, m.exam_date
    FROM marks m
    JOIN subjects sub ON m.subject_id = sub.subject_id
    WHERE m.student_id = '{$student['student_id']}'
    ORDER BY m.exam_date DESC
");
if ($marksQuery) {
    $marksData = $marksQuery->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Report</title>
    <link rel="stylesheet" href="css/marks.css">
</head>
<body>

<div class="container">
    <h2>Marks Report - <?php echo htmlspecialchars($student['name']); ?></h2>

    <?php if (!empty($marksData)): ?>
        <table>
            <tr>
                <th>Subject</th>
                <th>Exam Name</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Exam Date</th>
            </tr>
            <?php foreach ($marksData as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                <td><?php echo $row['marks_obtained']; ?></td>
                <td><?php echo $row['total_marks']; ?></td>
                <td><?php echo htmlspecialchars($row['exam_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No marks records found.</p>
    <?php endif; ?>

    <a href="/Student-management-system/src/views/student/dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
