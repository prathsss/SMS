<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

// Get student ID from URL
$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    header("Location: students.php");
    exit();
}

// Fetch student data
$sql = "SELECT * FROM students WHERE student_id = $student_id";
$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

// Fetch semesters for dropdown
$semester_query = "SELECT * FROM semesters";
$semester_result = mysqli_query($conn, $semester_query);

// Update student
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $semester_id = $_POST['semester_id'];
    $address = $_POST['address'];
    

    $update_sql = "UPDATE students 
                   SET name='$name', roll_no='$roll_no', dob='$dob', gender='$gender', phone='$phone', semester_id='$semester_id', address='$address' 
                   WHERE student_id=$student_id";
    mysqli_query($conn, $update_sql);
    header("Location: students.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/edit_student.css">
    </head>
<body>
    <div class="container">
<h1>Edit Student</h1>
<form method="post">
    Name: <input type="text" name="name" value="<?php echo $student['name']; ?>" required><br>
    Address: <input type="text" name="address" value="<?php echo $student['address']; ?>"><br>
    Date of Birth: <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required><br>
    
    Roll No: <input type="text" name="roll_no" value="<?php echo $student['roll_no']; ?>" required><br>
    Gender: 
    <select name="gender">
        <option value="Male" <?php if($student['gender']=='Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if($student['gender']=='Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if($student['gender']=='Other') echo 'selected'; ?>>Other</option>
    </select><br>
    Phone: <input type="text" name="phone" value="<?php echo $student['phone']; ?>"><br>
    Semester: 
    <select name="semester_id">
        <?php while($semester = mysqli_fetch_assoc($semester_result)) { ?>
            <option value="<?php echo $semester['semester_id']; ?>" 
                <?php if($semester['semester_id']==$student['semester_id']) echo 'selected'; ?>>
                <?php echo $semester['semester_name']; ?>
            </option>
        <?php } ?>
    </select><br><br>
    <input type="submit" name="update" value="Update Student">
</form>
<a href="students.php">Back</a>
 </div>
</body>
</html>
