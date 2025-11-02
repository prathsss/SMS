<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php");
    exit();
}

require_once('C:\xampp\htdocs\Student-management-system\config\db.php');

$sem_query = "SELECT * FROM semesters";
$sem_result = mysqli_query($conn, $sem_query);

if(isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $semester_id = $_POST['semester_id'];
    $address = $_POST['address'];
    $email= $_POST['email'];

    $user_sql = "INSERT INTO users (username, password, role,name,email) VALUES ('$username', '$password', 'student','$name','$email')";
    if (mysqli_query($conn, $user_sql)) 
        $user_id = mysqli_insert_id($conn); 

    $student_sql = "INSERT INTO students (user_id, name, roll_no, dob, gender, phone, semester_id, address) VALUES ('$user_id', '$name', '$roll_no', '$dob', '$gender', '$phone', '$semester_id', '$address')";
    mysqli_query($conn, $student_sql);
    header("Location: students.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="css\add_student.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Add New Student</h1>
        <form method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Roll No</label>
                <input type="text" name="roll_no" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Semester</label>
                <select name="semester_id">
                    <?php while($sem = mysqli_fetch_assoc($sem_result)) { ?>
                        <option value="<?php echo $sem['semester_id']; ?>"><?php echo $sem['semester_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <input type="submit" name="add" value="Add Student">
            <a href="students.php" class="back-link">← Back to Students</a>
        </form>
    </div>
</body>
</html>