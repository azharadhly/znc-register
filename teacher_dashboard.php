<?php
include 'db.php';
include 'functions.php'; // Ensure you have the function included
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: teacher_login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch students assigned to the logged-in user
$sql = "SELECT s.id, s.name, s.index_no, s.email, s.phone, s.address FROM students s
        JOIN user_student_assignments ua ON s.id = ua.student_id
        WHERE ua.user_id = $user_id";
$result = $conn->query($sql);
require_once 'layout/header.php';
?>
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="container">
                <h2>Welcome, Teacher</h2>
                <h3>Student List</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Index No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Attendance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['index_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo round(getAttendancePercentage($row['id'], $conn), 2); ?>%</td>
                                <td>
                                    <a href="delete_student.php?student_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<?php
require_once 'layout/footer.php';
?>
