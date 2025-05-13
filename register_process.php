<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $sql = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $user_type);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Please login.";
        header("Location: login.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>