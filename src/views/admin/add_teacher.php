<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

// ✅ Fetch all semesters for the dropdown
$sem_result = mysqli_query($conn, "SELECT * FROM semesters");

// ✅ Handle form submissions
if (isset($_POST['add'])) {
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $subject_id = $_POST['subject_id'] ?? null;

    if (!$subject_id) {
        die("Please select a subject!");
    }

    mysqli_query($conn, "INSERT INTO users (username, password, role, name, email) 
                         VALUES ('$username', '$password', 'teacher', '$name', '$email')");
    $user_id = mysqli_insert_id($conn);

    mysqli_query($conn, "INSERT INTO teachers (user_id, subject_id, phone) 
                         VALUES ($user_id, $subject_id, '$phone')");

    header("Location: teachers.php");
    exit();
}

// ✅ If semester is selected, get subjects for that semester
$subjects_result = null;
if (isset($_POST['semester_id']) && !empty($_POST['semester_id'])) {
    $semester_id = $_POST['semester_id'];
    $subjects_result = mysqli_query($conn, "SELECT * FROM subjects WHERE semester_id = $semester_id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="css/add_teacher.css">
</head>
<body>
    <h1>Add Teacher</h1>

    <form method="post">
        Username: <input type="text" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" required><br>
        Password: <input type="password" name="password" value="<?php echo $_POST['password'] ?? ''; ?>" required><br>
        Name: <input type="text" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required><br>
        Phone: <input type="text" name="phone" value="<?php echo $_POST['phone'] ?? ''; ?>" required><br><br>

        Semester: 
        <select name="semester_id" required onchange="this.form.submit()">
            <option value="">--Select Semester--</option>
            <?php 
            mysqli_data_seek($sem_result, 0);
            while ($sem = mysqli_fetch_assoc($sem_result)) { ?>
                <option value="<?php echo $sem['semester_id']; ?>"
                    <?php if (isset($_POST['semester_id']) && $_POST['semester_id'] == $sem['semester_id']) echo 'selected'; ?>>
                    <?php echo $sem['semester_name']; ?>
                </option>
            <?php } ?>
        </select><br><br>

        Subject: 
        <select name="subject_id" required>
            <option value="">--Select Subject--</option>
            <?php 
            if ($subjects_result) {
                while ($sub = mysqli_fetch_assoc($subjects_result)) { ?>
                    <option value="<?php echo $sub['subject_id']; ?>">
                        <?php echo $sub['subject_name']; ?>
                    </option>
            <?php } } ?>
        </select><br><br>

        <input type="submit" name="add" value="Add Teacher">
        <a href="teachers.php">Back</a>
    </form>
</body>
</html>