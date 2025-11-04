<?php
session_start();
require_once('D:\xampp\xampp\htdocs\Student-management-system\config\db.php');

// ✅ Allow only admin to delete notices
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /Student-management-system/public/login.php");
    exit();
}

// ✅ Check if notice_id is provided
if (!isset($_GET['notice_id']) || empty($_GET['notice_id'])) {
    echo "<script>alert('Invalid request!'); window.location.href='view_notice.php';</script>";
    exit();
}

$notice_id = intval($_GET['notice_id']); // Sanitizing input

// ✅ Prepare and execute deletion query
$stmt = $conn->prepare("DELETE FROM notices WHERE notice_id = ?");
$stmt->bind_param("i", $notice_id);

if ($stmt->execute()) {
    echo "<script>alert('Notice deleted successfully!'); window.location.href='view_notice.php';</script>";
} else {
    echo "<script>alert('Error deleting notice! Please try again.'); window.location.href='view_notice.php';</script>";
}

$stmt->close();
$conn->close();
?>
