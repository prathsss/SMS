<?php
session_start();
include('D:\xampp\xampp\htdocs\Student-management-system\config\db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$notice_id = intval($_GET['notice_id']);

// Only delete if this user posted the notice
$sql = "DELETE FROM notices WHERE notice_id = '$notice_id' AND posted_by = '$user_id'";
$result = $conn->query($sql);

if ($conn->affected_rows > 0) {
    echo "<script>alert('Notice deleted successfully!'); window.location='view_notice.php';</script>";
} else {
    echo "<script>alert('You cannot delete this notice.'); window.location='view_notice.php';</script>";
}
?>
