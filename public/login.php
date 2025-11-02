<?php
session_start();
if (isset($_SESSION['role'])) {
    // Redirect already logged-in users to their dashboard
    $role = $_SESSION['role'];
    if ($role == 'admin') header("Location: ../src/views/admin/dashboard.php");
    elseif ($role == 'teacher') header("Location: ../src/views/teacher/dashboard.php");
    else header("Location: ../src/views/student/dashboard.php");
    exit(); // Always exit after a header redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Login - Student Management</title>
    
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <p class="subtitle">Welcome to the Student Management System</p>

        <form action="" method="post"> <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" name="login" value="Login">
        </form>

        <?php
        if (isset($_POST['login'])) {
            require_once('../config/db.php'); // Connect to database
            $username = $_POST['username'];
            $password = $_POST['password'];

            // IMPORTANT: Your original code is vulnerable to SQL Injection.
            // You should use prepared statements.
            $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use placeholders
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            
            // Execute the statement
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);


            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'admin') header("Location: ../src/views/admin/dashboard.php");
                elseif ($user['role'] == 'teacher') header("Location: ../src/views/teacher/dashboard.php");
                else header("Location: ../src/views/student/dashboard.php");
                exit();
            } else {
                // Use the new error class here
                echo "<p class='error-message'>Invalid username or password</p>";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
        ?>
    </div>

</body>
</html>