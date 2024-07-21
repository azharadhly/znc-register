<?php
include 'db.php';
include 'functions.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: teacher_login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$index_no = isset($_GET['index_no']) ? $conn->real_escape_string($_GET['index_no']) : '';
$start_month = isset($_GET['start_month']) ? $_GET['start_month'] : date('Y-m');
$end_month = isset($_GET['end_month']) ? $_GET['end_month'] : date('Y-m');

if ($index_no && $start_month && $end_month) {
    // Fetch student details by index_no and check if assigned to logged-in teacher
    $sql = "SELECT s.* FROM students s
            JOIN user_student_assignments ua ON s.id = ua.student_id
            WHERE s.index_no = '$index_no' AND ua.user_id = $user_id";
    $student_result = $conn->query($sql);
    $student = $student_result->fetch_assoc();

    if ($student) {
        // Prepare an array to store attendance data
        $attendance_data = [];
        $current_month = $start_month;
        
        // Loop through each month in the range
        while (strtotime($current_month) <= strtotime($end_month)) {
            // Fetch attendance data for the current month
            $attendance_sql = "SELECT COUNT(*) AS present_days FROM attendance WHERE student_id = " . intval($student['id']) . " AND status = 'present' AND DATE_FORMAT(date, '%Y-%m') = '$current_month'";
            $attendance_result = $conn->query($attendance_sql);
            $attendance = $attendance_result->fetch_assoc();
            
            // Calculate total classes for the current month
            $total_classes_sql = "SELECT COUNT(*) AS total_classes FROM attendance WHERE student_id = " . intval($student['id']) . " AND DATE_FORMAT(date, '%Y-%m') = '$current_month'";
            $total_classes_result = $conn->query($total_classes_sql);
            $total_classes = $total_classes_result->fetch_assoc()['total_classes'];
            
            $attendance_percentage = $total_classes > 0 ? ($attendance['present_days'] / $total_classes) * 100 : 0;
            
            // Store the data
            $attendance_data[$current_month] = [
                'present_days' => $attendance['present_days'],
                'attendance_percentage' => round($attendance_percentage, 2)
            ];
            
            // Move to the next month
            $current_month = date('Y-m', strtotime("$current_month +1 month"));
        }
    }
}
include 'layout/header.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="container">
                <h2>Student Report</h2>
                <form method="GET" action="student_report.php">
                    <div class="form-group">
                        <label for="index_no">Enter Student Index Number:</label>
                        <input type="text" class="form-control" id="index_no" name="index_no" required>
                    </div>
                    <div class="form-group">
                        <label for="start_month">Select Start Month:</label>
                        <input type="month" class="form-control" id="start_month" name="start_month" value="<?php echo htmlspecialchars($start_month); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="end_month">Select End Month:</label>
                        <input type="month" class="form-control" id="end_month" name="end_month" value="<?php echo htmlspecialchars($end_month); ?>" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Get Report</button>
                    <br>
                    <hr>
                </form>
                
                <?php if (isset($student)): ?>
                    <h3>Details for <?php echo htmlspecialchars($student['name']); ?></h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Index No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($student['index_no']); ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td><?php echo htmlspecialchars($student['address']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Attendance Report</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Present Days</th>
                                <th>Attendance Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_data as $month => $data): ?>
                            <tr>
                                <td><?php echo date('F Y', strtotime($month)); ?></td>
                                <td><?php echo $data['present_days']; ?></td>
                                <td><?php echo $data['attendance_percentage']; ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div> The average percentage is <span style="color:red;"> <b> <?php echo round(getAttendancePercentage($student['id'], $conn), 2); ?>%</b></span></div>
                    <br><hr>
                    <canvas id="attendanceChart" width="400" height="200"></canvas>
<hr>
                    <a href="teacher_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                <?php elseif (isset($_GET['index_no'])): ?>
                    <p class="text-danger">Student not found or not assigned to you. Please check the index number.</p>
                <?php endif; ?>
                
            </div>
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('attendanceChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line', // Change to 'bar', 'pie', or 'bar' as needed
                data: {
                    labels: [<?php echo '"' . implode('","', array_keys($attendance_data)) . '"'; ?>], // Month labels
                    datasets: [{
                        label: 'Attendance Percentage',
                        data: [<?php echo implode(',', array_column($attendance_data, 'attendance_percentage')); ?>],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) { return value + '%'; } // Add '%' symbol to y-axis values
                            }
                        }
                    }
                }
            });
        });
    </script>
        </div>
    </section>
</main>

<?php include 'layout/footer.php';?>
