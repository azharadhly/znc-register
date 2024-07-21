<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: teacher_login.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $index = $conn->real_escape_string($_POST['index']);

    // Insert student into the database
    $sql = "INSERT INTO students (name, email, phone, address, index_no) VALUES ('$name', '$email', '$phone', '$address', '$index')";

    if ($conn->query($sql) === TRUE) {
        $student_id = $conn->insert_id; // Get the ID of the newly inserted student

        // Automatically assign the logged-in user to the new student
        $assign_sql = "INSERT INTO user_student_assignments (user_id, student_id) VALUES ($user_id, $student_id)";
        if ($conn->query($assign_sql) === TRUE) {
            echo "Student registered and user assigned successfully.";
        } else {
            echo "Error assigning user: " . $conn->error;
        }

        header("Location: teacher_dashboard.php");
        exit(); // Make sure to stop further execution
    } else {
        echo "Error: " . $conn->error;
    }
}

include 'layout/header.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Register a New Student</h5>

                        <!-- Form for Registering Student -->
                        <form class="row g-3" method="POST" action="">
                            <div class="form-group">
                                <label for="index">Index No:</label>
                                <input type="text" class="form-control" id="index" name="index" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                        <!-- Form for Registering Student -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
require_once 'layout/footer.php';
?>
