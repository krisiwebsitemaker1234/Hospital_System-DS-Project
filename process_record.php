<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $record_date = $_POST['record_date'];
    $doctor_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, diagnosis, treatment, record_date, doctor_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $patient_id, $diagnosis, $treatment, $record_date, $doctor_id);
    $stmt->execute();
    header("Location: view_records.php");
}
?>