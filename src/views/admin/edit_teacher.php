<?php
include('C:\xampp\htdocs\Student-management-system\config\db.php');

// Check if teacher ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch teacher + user data using a JOIN
    $query = "SELECT t.*, u.username, u.email 
              FROM teachers t
              JOIN users u ON t.user_id = u.user_id
              WHERE t.teacher_id = '$id'";
    $result = mysqli_query($conn, $query);
    $teacher = mysqli_fetch_assoc($result);
}

// Update teacher details
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject_id = $_POST['subject_id'];

    // Update users table
    $updateUser = "UPDATE users 
                   SET username='$username', email='$email'
                   WHERE user_id='{$teacher['user_id']}'";
    mysqli_query($conn, $updateUser);

    // Update teachers table
    $updateTeacher = "UPDATE teachers 
                      SET phone='$phone', subject_id='$subject_id' 
                      WHERE teacher_id='$id'";
    mysqli_query($conn, $updateTeacher);

    echo "<script>alert('Teacher updated successfully!'); window.location='teachers.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
    <link rel="stylesheet" href="css/edit_teacher.css">
</head>
<body>
    <div class="container">
        <h2>Edit Teacher</h2>
        <form method="POST">
            <label>Teacher Name:</label>
            <input type="text" name="username" value="<?php echo $teacher['username']; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $teacher['email']; ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $teacher['phone']; ?>" required>

            <label>Subject:</label>
            <select name="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php
                $subject_query = mysqli_query($conn, "SELECT * FROM subjects");
                while ($row = mysqli_fetch_assoc($subject_query)) {
                    $selected = ($row['subject_id'] == $teacher['subject_id']) ? 'selected' : '';
                    echo "<option value='{$row['subject_id']}' $selected>{$row['subject_name']}</option>";
                }
                ?>
            </select>

            <button type="submit" name="update">Update</button>
            <a href="teachers.php">Back</a>
        </form>
    </div>
</body>
</html>
