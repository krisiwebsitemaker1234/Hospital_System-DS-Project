<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'doctor'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    
    // Verify ownership if doctor
    if ($_SESSION['user_type'] == 'doctor') {
        $stmt = $conn->prepare("SELECT doctor_id FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result['doctor_id'] != $_SESSION['user_id']) {
            header("Location: view_appointment.php");
            exit();
        }
    }

    // Delete appointment
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Appointment deleted successfully";
    $_SESSION['message_type'] = 'success';
}

header("Location: view_appointment.php");
exit();