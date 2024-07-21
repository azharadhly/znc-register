<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: teacher_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = intval($_POST['student_id']);
    $user_id = intval($_POST['user_id']);

    // Check if the assignment already exists
    $check_sql = "SELECT * FROM user_student_assignments WHERE user_id = $user_id AND student_id = $student_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Assignment already exists.";
    } else {
        // Insert new assignment
        $sql = "INSERT INTO user_student_assignments (user_id, student_id) VALUES ($user_id, $student_id)";
        if ($conn->query($sql) === TRUE) {
            echo "User assigned to student successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
