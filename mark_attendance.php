<?php
include 'db.php';
include 'functions.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: teacher_login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch students assigned to the logged-in user
$sql = "SELECT s.* FROM students s
        JOIN user_student_assignments ua ON s.id = ua.student_id
        WHERE ua.user_id = $user_id";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    foreach ($_POST['attendance'] as $student_id => $status) {
        $sql = "INSERT INTO attendance (student_id, date, status) VALUES ('$student_id', '$date', '$status')
                ON DUPLICATE KEY UPDATE status='$status'";
        $conn->query($sql);
    }
    header("Location: teacher_dashboard.php");
    exit();
}
include 'layout/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>
<body>
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="container">
                    <h2>Mark Attendance</h2>
                    <form method="POST" action="mark_attendance.php">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="text" class="form-control" id="date" name="date" required>
                        </div>
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Index No</th>
                                    <th>Name</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo $row['index_no']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td>
                                            <select name="attendance[<?php echo $row['id']; ?>]" class="form-control">
                                                <option value="present">Present</option>
                                                <option value="absent">Absent</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Submit Attendance</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#date').datepicker({
                format: 'yyyy-mm-dd',
                endDate: '0d',
                autoclose: true
            });
        });
    </script>
</body>
</html>

<?php
include 'layout/footer.php';
?>
