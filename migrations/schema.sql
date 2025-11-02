-- --------------------------------------------------
-- Database: student_management
-- --------------------------------------------------
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- --------------------------------------------------
-- Table structure for `users` (role-based login)
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','teacher','student') NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample users
INSERT INTO users (username, password, role, name, email)
VALUES
('admin', '$2y$10$ZqT3k3fWnYkXbqf2yFQ4ueZr5bU7jWZfqDxB6bQeEw0bkD4A3yG7K', 'admin', 'Admin User', 'admin@example.com'),
('teacher1', '$2y$10$ZqT3k3fWnYkXbqf2yFQ4ueZr5bU7jWZfqDxB6bQeEw0bkD4A3yG7K', 'teacher', 'Teacher One', 'teacher1@example.com'),
('student1', '$2y$10$ZqT3k3fWnYkXbqf2yFQ4ueZr5bU7jWZfqDxB6bQeEw0bkD4A3yG7K', 'student', 'Student One', 'student1@example.com');

-- Password hash corresponds to: 123456

-- --------------------------------------------------
-- Table structure for `courses`
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    duration VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample courses
INSERT INTO courses (course_name, duration)
VALUES
('BCA 1st Year', '1 Year'),
('BCA 2nd Year', '1 Year');

-- --------------------------------------------------
-- Table structure for `students`
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    dob DATE,
    gender ENUM('Male','Female','Other'),
    course_id INT,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE SET NULL
);

-- --------------------------------------------------
-- Table structure for `attendance`
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS attendance (
    attend_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present','absent','leave') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- --------------------------------------------------
-- Table structure for `results`
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    marks INT NOT NULL,
    max_marks INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- --------------------------------------------------
-- Table structure for `notices` (optional)
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS notices (
    notice_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    posted_by INT,
    posted_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(user_id) ON DELETE SET NULL
);
