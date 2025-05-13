<?php
session_start();
include 'config.php';

// Security check - only admins can add staff
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $department = $_POST['department'] ?? null;
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $conn->begin_transaction();

        // Insert into users table
        $stmt_user = $conn->prepare("INSERT INTO users 
            (username, password, role) 
            VALUES (?, ?, ?)");
        $role = ($position == 'doctor') ? 'doctor' : 'staff';
        $stmt_user->bind_param("sss", $username, $password, $role);
        $stmt_user->execute();
        $user_id = $conn->insert_id;

        // Insert into staff table
        $stmt_staff = $conn->prepare("INSERT INTO staff 
            (user_id, name, position, department) 
            VALUES (?, ?, ?, ?)");
        $stmt_staff->bind_param("isss", $user_id, $name, $position, $department);
        $stmt_staff->execute();

        $conn->commit();
        $_SESSION['message'] = "Staff added successfully!";
        $_SESSION['message_type'] = 'success';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
    header("Location: view_staff.php");
    exit();
}

// Get existing departments
$departments = $conn->query("SELECT DISTINCT department FROM staff WHERE department IS NOT NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Staff</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --text-dark: #2c3e50;
            --background: #f8f9fa;
            --border: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            min-height: 100vh;
        }

        .form-container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--primary);
            margin-bottom: 25px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border);
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            outline: none;
        }

        .dynamic-field {
            display: none;
        }

        .show-field {
            display: block;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <main class="main-content">
        <div class="form-container">
            <h2><i class="fas fa-user-plus"></i> Add New Staff Member</h2>
            
            <form method="POST" id="staffForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label>Position</label>
                    <select name="position" id="position" required onchange="toggleDepartment()">
                        <option value="">Select Position</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                        <option value="administrator">Administrator</option>
                    </select>
                </div>

                <div class="form-group dynamic-field" id="departmentField">
                    <label>Department</label>
                    <select name="department">
                        <?php while ($dept = $departments->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($dept['department']) ?>">
                                <?= htmlspecialchars($dept['department']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="johndoe123">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Staff
                </button>
            </form>
        </div>
    </main>

    <script>
        function toggleDepartment() {
            const position = document.getElementById('position').value;
            const departmentField = document.getElementById('departmentField');
            if (position === 'doctor') {
                departmentField.classList.add('show-field');
            } else {
                departmentField.classList.remove('show-field');
            }
        }
        // Initial check on page load
        toggleDepartment();
    </script>
</body>
</html>