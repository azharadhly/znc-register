<?php
function getAttendancePercentage($student_id, $conn) {
    $total_classes_sql = "SELECT COUNT(*) AS total_classes FROM attendance WHERE student_id='$student_id'";
    $present_classes_sql = "SELECT COUNT(*) AS present_classes FROM attendance WHERE student_id='$student_id' AND status='present'";
    
    $total_classes = $conn->query($total_classes_sql)->fetch_assoc()['total_classes'];
    $present_classes = $conn->query($present_classes_sql)->fetch_assoc()['present_classes'];

    if ($total_classes > 0) {
        return ($present_classes / $total_classes) * 100;
    } else {
        return 0; // No classes recorded
    }
}
?>

