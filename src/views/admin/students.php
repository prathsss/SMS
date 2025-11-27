<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

// Correct path
require_once('C:xampp/htdocs/Student-management-system/config/db.php');

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}


$sql = "SELECT s.student_id, s.roll_no, s.name, s.gender, s.dob, s.address, s.phone, d.semester_name
        FROM students s
        LEFT JOIN semesters d ON s.semester_id = d.semester_id
        WHERE s.name LIKE '%$search%'
        OR s.roll_no LIKE '%$search%'
        OR s.phone LIKE '%$search%'
        OR s.address LIKE '%$search%'
        OR d.semester_name LIKE '%$search%'";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/student.css">

<script>
function searchNow() {
    let keyword = document.getElementById("search").value;
    window.location.href = "?search=" + encodeURIComponent(keyword);
}
</script>
</head>

<body>

<main class="main-content">
    <h1>Manage Students</h1>

    <div class="actions-bar">
        <input type="text" id="search" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <input type="button" value="Search" class="btn btn-search" onclick="searchNow()">
    </div>

    <div class="actions-bar">
        <a href="add_student.php" class="btn btn-primary">Add New Student</a>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Semester</th>
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
                        <td><?php echo htmlspecialchars($row['semester_name']); ?></td>
                        <td class="action-links">
                            <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="action-edit">Edit</a>
                            <span class="separator">|</span>
                            <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure?')" class="action-delete">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>
