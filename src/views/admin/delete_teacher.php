<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

$teacher_id = $_GET['id'] ?? null;
if ($teacher_id) {
    // Get user_id to delete user account
    $res = mysqli_query($conn, "SELECT user_id FROM teachers WHERE teacher_id=$teacher_id");
    $teacher = mysqli_fetch_assoc($res);
    $user_id = $teacher['user_id'];

    // Delete teacher and user
    mysqli_query($conn, "DELETE FROM teachers WHERE teacher_id=$teacher_id");
    mysqli_query($conn, "DELETE FROM users WHERE user_id=$user_id");
}

header("Location: teachers.php");
exit();
