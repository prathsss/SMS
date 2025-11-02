<?php
session_start();

// ✅ Role check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php'); 

$teacher_user_id = (int)$_SESSION['user_id'];

// --- 1. Fetch Teacher Info
$profile_query = "
    SELECT u.name, u.email, t.phone, t.subject_id
    FROM users u
    JOIN teachers t ON u.user_id = t.user_id
    WHERE u.user_id = $teacher_user_id";
$profile_result = mysqli_query($conn, $profile_query);
$teacher_info = mysqli_fetch_assoc($profile_result);

$teacher_name = $teacher_info['name'] ?? 'Teacher';
$assigned_subject_id = $teacher_info['subject_id'] ?? null;

// --- 2. Fetch Subject Name
$assigned_subject_name = 'N/A';
if ($assigned_subject_id) {
    $subject_query = "SELECT subject_name FROM subjects WHERE subject_id = $assigned_subject_id";
    $subject_row = mysqli_fetch_assoc(mysqli_query($conn, $subject_query));
    $assigned_subject_name = $subject_row['subject_name'] ?? 'N/A';
}

// --- 3. Fetch Students (linked by semester)
$students_result = null;
if ($assigned_subject_id) {
    $students_query = "
        SELECT u.user_id, u.name, s.roll_no, s.student_id
        FROM students s
        JOIN users u ON s.user_id = u.user_id
        JOIN subjects sub ON s.semester_id = sub.semester_id
        WHERE sub.subject_id = $assigned_subject_id
        ORDER BY s.roll_no ASC";
    $students_result = mysqli_query($conn, $students_query);
}

// --- 4. Save Marks
if (isset($_POST['submit_marks']) && $assigned_subject_id) {
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $total_marks = (int)$_POST['total_marks'];
    $current_date = date('Y-m-d');

    foreach ($_POST['marks'] as $student_user_id => $marks_obtained) {
        $marks_obtained = (int)$marks_obtained;
        if ($marks_obtained > $total_marks || $marks_obtained < 0) continue;

        $find_student_id_query = "SELECT student_id FROM students WHERE user_id = $student_user_id";
        $student_row = mysqli_fetch_assoc(mysqli_query($conn, $find_student_id_query));
        $student_id = $student_row['student_id'];

        $marks_insert_sql = "
            INSERT INTO marks (student_id, subject_id, exam_name, marks_obtained, total_marks, exam_date)
            VALUES ($student_id, $assigned_subject_id, '$exam_name', $marks_obtained, $total_marks, '$current_date')";
        mysqli_query($conn, $marks_insert_sql);
    }

    header("Location: dashboard.php?status=marks_saved");
    exit();
}

// --- 5. Save Attendance (optional extension)
if (isset($_POST['submit_attendance'])) {
    $date = $_POST['date'];
    foreach ($_POST['attendance'] as $student_user_id => $status) {
        $find_student_id_query = "SELECT student_id FROM students WHERE user_id = $student_user_id";
        $student_row = mysqli_fetch_assoc(mysqli_query($conn, $find_student_id_query));
        $student_id = $student_row['student_id'];
        $status_value = '';
        if ($status == 'P') $status_value = 'present';
        elseif ($status == 'A') $status_value = 'absent';
        mysqli_query($conn, "
            INSERT INTO attendance (student_id, subject_id, date, status)
            VALUES ($student_id, $assigned_subject_id, '$date', '$status_value')");
    }
    header("Location:dashboard.php?status=attendance_saved");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <title>Teacher Dashboard</title>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?></h1>
<p>Subject: <b><?php echo htmlspecialchars($assigned_subject_name); ?></b></p>
<hr>

<!-- ✅ Profile Info -->
<h2>Profile Information</h2>
<ul>
    <li>Email: <?php echo $teacher_info['email'] ?? 'N/A'; ?></li>
    <li>Phone: <?php echo $teacher_info['phone'] ?? 'N/A'; ?></li>
</ul>

<hr>

<!-- ✅ Attendance Form -->
<h2>1. Mark Attendance</h2>
<form method="post">
    <label>Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required></label>
    <table border="1">
        <tr><th>Roll No</th><th>Name</th><th>Status</th></tr>
        <?php
        if ($students_result && mysqli_num_rows($students_result) > 0) {
            mysqli_data_seek($students_result, 0);
            while($student = mysqli_fetch_assoc($students_result)) {
                echo "<tr>
                        <td>{$student['roll_no']}</td>
                        <td>{$student['name']}</td>
                        <td>
                            <label><input type='radio' name='attendance[{$student['user_id']}]' value='P' checked> P</label>
                            <label><input type='radio' name='attendance[{$student['user_id']}]' value='A'> A</label>
                        </td>
                      </tr>";
            }
        } else echo "<tr><td colspan='3'>No students enrolled.</td></tr>";
        ?>
    </table>
    <input type="submit" name="submit_attendance" value="Save Attendance">
</form>

<hr>

<!-- ✅ Marks Form -->
<h2>2. Enter Marks</h2>
<form method="post">
    <label>Exam Name: <input type="text" name="exam_name" required></label><br>
    <label>Total Marks: <input type="number" name="total_marks" min="1" required></label>
    <table border="1">
        <tr><th>Roll No</th><th>Name</th><th>Marks</th></tr>
        <?php
        if ($students_result && mysqli_num_rows($students_result) > 0) {
            mysqli_data_seek($students_result, 0);
            while($student = mysqli_fetch_assoc($students_result)) {
                echo "<tr>
                        <td>{$student['roll_no']}</td>
                        <td>{$student['name']}</td>
                        <td><input type='number' name='marks[{$student['user_id']}]' min='0' required></td>
                      </tr>";
            }
        } else echo "<tr><td colspan='3'>No students enrolled.</td></tr>";
        ?>
    </table>
    <input type="submit" name="submit_marks" value="Save Marks">
</form>
<section class="card">
  <h3>3. Notices</h3>
  <a href="add_notice.php">Add Notice</a><br><br>
<a href="view_notice.php">View Notices</a>
</section>
<a href="/student-management-system/public/logout.php">Logout</a>
</body>
</html>
