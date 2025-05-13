<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'doctor'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_appointments.php");
    exit();
}

// Get existing appointment data
$stmt = $conn->prepare("SELECT a.*, p.name AS patient_name, s.name AS doctor_name
                       FROM appointments a
                       JOIN patients p ON a.patient_id = p.id
                       JOIN staff s ON a.doctor_id = s.id
                       WHERE a.id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();

// Check ownership if doctor
if ($_SESSION['user_type'] == 'doctor' && $appointment['doctor_id'] != $_SESSION['user_id']) {
    header("Location: view_appointments.php");
    exit();
}

// Get lists
$patients = $conn->query("SELECT id, name FROM patients ORDER BY name");
$doctors = $conn->query("SELECT staff.id, staff.name 
                        FROM staff 
                        JOIN users ON staff.user_id = users.id 
                        WHERE users.role = 'doctor'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
    <style>
        .appointment-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2c3e50;
        }

        select, input, textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #e0e6eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        select:focus, input:focus, textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
            outline: none;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="appointment-container">
            <h2><i class="fas fa-calendar-edit"></i> Edit Appointment</h2>
            
            <form action="process_appointment.php" method="POST">
                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                
                <div class="form-group">
                    <label><i class="fas fa-user-injured"></i> Patient</label>
                    <select name="patient_id" required>
                        <?php while($patient = $patients->fetch_assoc()): ?>
                            <option value="<?= $patient['id'] ?>" 
                                <?= $patient['id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($patient['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-user-md"></i> Doctor</label>
                    <select name="doctor_id" required>
                        <?php while($doctor = $doctors->fetch_assoc()): ?>
                            <option value="<?= $doctor['id'] ?>" 
                                <?= $doctor['id'] == $appointment['doctor_id'] ? 'selected' : '' ?>>
                                Dr. <?= htmlspecialchars($doctor['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Date & Time</label>
                    <input type="datetime-local" name="appointment_datetime" 
                           value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_datetime'])) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-comment-medical"></i> Notes</label>
                    <textarea name="description"><?= htmlspecialchars($appointment['description']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Appointment
                </button>
            </form>
        </div>
    </div>
</body>
</html>