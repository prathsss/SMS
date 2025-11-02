<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

// Fetch teachers with their subjects
$sql = "
SELECT t.teacher_id, u.username, u.name, u.email, t.phone, s.subject_name
FROM teachers t
JOIN users u ON t.user_id = u.user_id
JOIN subjects s ON t.subject_id = s.subject_id
";
$result = mysqli_query($conn, $sql);
?>
<html>
    <head>
        <link rel="stylesheet" href="css/teacher.css">
</head>
<body>
        <h1>Manage Teachers</h1>
<a href="add_teacher.php">Add New Teacher</a>
<table border="1">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Name</th>
    <th>Email</th>
    <th>Subject</th>
    <th>Phone</th>
    <th>Actions</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['teacher_id']; ?></td>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['subject_name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td>
        <a href="edit_teacher.php?id=<?php echo $row['teacher_id']; ?>">Edit</a> |
        <a href="delete_teacher.php?id=<?php echo $row['teacher_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php } ?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

