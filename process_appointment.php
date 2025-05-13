<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $patient_id = $_POST['patient_id'];
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];
    $staff_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, staff_id, appointment_date, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $patient_id, $staff_id, $appointment_date, $description);
    $stmt->execute();
    header("Location: view_appointment.php");
}
?>