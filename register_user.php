<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: teacher_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
            alert('User registered successfully.');
            window.location.href='admin.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>
