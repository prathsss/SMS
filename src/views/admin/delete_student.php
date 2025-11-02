<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

$student_id = $_GET['id'] ?? null;

if ($student_id) {
    // 1️⃣ Get the linked user_id from students table
    $res = mysqli_query($conn, "SELECT user_id FROM students WHERE student_id = $student_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $student = mysqli_fetch_assoc($res);
        $user_id = $student['user_id'];

        // 2️⃣ Delete student record
        $delete_student = mysqli_query($conn, "DELETE FROM students WHERE student_id = $student_id");

        // 3️⃣ Delete linked user record
        $delete_user = mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");

        // 4️⃣ Redirect
        if ($delete_student && $delete_user) {
            header("Location: students.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting: " . mysqli_error($conn);
        }
    } else {
        echo "No student found with ID: $student_id";
    }
} else {
    echo "Invalid request. No ID provided.";
}
?>
