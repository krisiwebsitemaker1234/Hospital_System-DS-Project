<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $admission_date = $_POST['admission_date'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO patients (name, age, gender, address, admission_date, user_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssi", $name, $age, $gender, $address, $admission_date, $user_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>