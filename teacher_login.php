<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Ensure password is hashed in the database (ideally)
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id']; // Retrieve user ID
        $role = $row['role'];

        // Store user ID and role in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role == 'teacher') {
            header("Location: teacher_dashboard.php");
        } elseif ($role == 'admin') {
            header("Location: admin.php");
        }
        exit(); // Make sure to stop further execution
    } else {
        echo "Invalid username or password.";
    }
}
?>
