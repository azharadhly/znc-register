<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: teacher_login.html");
    exit();
}

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $sql = "DELETE FROM students WHERE id='$student_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: teacher_dashboard.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
