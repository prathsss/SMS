<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

// 1. IMPORTANT: FIX THE REQUIRE PATH
// Use a relative path, not an absolute path specific to your local machine.
// Assuming this file is in 'src/views/admin/', adjust the path to 'config/db.php' accordingly.
require_once('../../../config/db.php'); 

$sql = "SELECT s.student_id, s.roll_no, s.name, s.gender, s.dob, s.address, s.phone, d.dep_name
        FROM students s
        LEFT JOIN department d ON s.dep_id = d.dep_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/student.css"> </head>
<body>

    <main class="main-content">
        <h1>Manage Students</h1>
        
        <div class="actions-bar">
             <a href="add_student.php" class="btn btn-primary">Add New Student</a>
             <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
        
        <div class="content-card">
            <div class="table-responsive">
                <table class="data-table" border="1" solid>
                    <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            
                            <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['dob']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['dep_name']); ?></td>
                            <td class="action-links">
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="action-edit">Edit</a>
                                <span class="separator">|</span>
                                <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?')" class="action-delete">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> </div> </main>

</body>
</html>