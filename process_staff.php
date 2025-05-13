<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'admin') {
    // Create user first
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $user_sql = "INSERT INTO users (username, password, user_type) VALUES (?, ?, 'staff')";
        $stmt = $conn->prepare($user_sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        
        $user_id = $conn->insert_id;

        // Insert into staff table
        $staff_sql = "INSERT INTO staff (name, position, department, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($staff_sql);
        $stmt->bind_param("sssi", $name, $position, $department, $user_id);
        $stmt->execute();

        $conn->commit();
        header("Location: dashboard.php");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: login.php");
}
?>