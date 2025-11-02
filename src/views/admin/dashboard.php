<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: /student-management-system/public/login.php"); 
    exit();
}

// 2. Database Connection
// !!! IMPORTANT: Adjust this path to your database configuration file
// It's relative to THIS file (dashboard.php)
require_once('../../../config/db.php'); // Example: If dashboard.php is in src/views/admin/

// 3. Fetch Data Counts
// Count Students (from the 'users' table where role is 'student')
$student_sql = "SELECT COUNT(user_id) AS student_count FROM users WHERE role = 'student'";
$student_result = mysqli_query($conn, $student_sql);
$student_data = mysqli_fetch_assoc($student_result);
$student_count = $student_data['student_count'];

// Count Teachers (from the 'users' table where role is 'teacher')
$teacher_sql = "SELECT COUNT(user_id) AS teacher_count FROM users WHERE role = 'teacher'";
$teacher_result = mysqli_query($conn, $teacher_sql);
$teacher_data = mysqli_fetch_assoc($teacher_result);
$teacher_count = $teacher_data['teacher_count'];

// Count Courses (assuming you have a 'courses' table)
// If your table is named differently, change 'courses'
$course_sql = "SELECT COUNT(*) AS dep_count FROM department"; 
$course_result = mysqli_query($conn, $course_sql);
$course_data = mysqli_fetch_assoc($course_result);
$course_count = $course_data['dep_count'];

// Close the connection as we are done with queries
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SMS</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">    
</head>
<body>

    <div class="dashboard-layout">
        
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SMS Admin</h2>
                <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?>!</strong></p>
            </div>
            
            <ul class="sidebar-nav">
                <li><a href="students.php">Manage Students</a></li>
                <li><a href="teachers.php">Manage Teachers</a></li>
                <li><a href="add_notice.php">Add Notices</a></li>
                <li> <a href="view_notice.php">Notices</a></li>
            </ul>

         
        </nav>

        <main class="main-content">
            <h1>Admin Dashboard</h1>
            
            <div class="summary-cards">
                
                <div class="summary-card">
                    <h3>Total Students</h3>
                    <p class="count"><?php echo $student_count; ?></p> 
                    <div class="card-link">
                        <a href="students.php">View Students &rarr;</a>
                    </div>
                </div>

                <div class="summary-card">
                    <h3>Total Teachers</h3>
                    <p class="count"><?php echo $teacher_count; ?></p>
                    <div class="card-link">
                        <a href="teachers.php">View Teachers &rarr;</a>
                    </div>
                </div>
                <div class="summary-card">
                    <h3>Add Notices</h3>
                    
                    <div class="card-link">
                        <a href="add_notice.php">Add Notice &rarr;</a>
                    </div>
                </div>
                <div class="summary-card">
                    <h3>View Notices</h3>
                    
                    <div class="card-link">
                        <a href="view_notice.php">View Notices &rarr;</a>
                    </div>
                </div>
                    
                </div>
            </div>
          <div class="summary-card">  

 <div class="logout-link">
                <a href="/student-management-system/public/logout.php">Logout</a>
            </div>
</div>
  
        </main>
        
    </div> </body>
</html>