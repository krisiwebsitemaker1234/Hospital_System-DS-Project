<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin', 'doctor'])) {
    header("Location: login.php");
    exit();
}

// Get list of doctors from the database
$doctors = $conn->query("
    SELECT staff.id, staff.name 
    FROM staff 
    INNER JOIN users ON staff.user_id = users.id 
    WHERE users.role = 'doctor'
    ORDER BY staff.name
");
$doctorsList = $doctors->fetch_all(MYSQLI_ASSOC);

// Handle errors if no doctors found
if (!$doctorsList) {
    $_SESSION['message'] = "No doctors found in the system!";
    $_SESSION['message_type'] = 'danger';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record | Hospital Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --text-dark: #2c3e50;
            --border-color: #ecf0f1;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .record-form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <main class="main-content">
        <div class="record-form-container">
            <h1><i class="fas fa-file-medical"></i> New Medical Record</h1>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                    <?= $_SESSION['message'] ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <form action="process_record.php" method="post">
                <div class="form-section">
                    <label class="form-label" for="patient_id">Patient</label>
                    <select class="form-control" name="patient_id" id="patient_id" required>
                        <?php
                        $patients = $conn->query("SELECT id, name FROM patients ORDER BY name");
                        while ($patient = $patients->fetch_assoc()): ?>
                            <option value="<?= $patient['id'] ?>"><?= htmlspecialchars($patient['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label class="form-label" for="doctor_id">Doctor</label>
                    <select class="form-control" name="doctor_id" id="doctor_id" required>
                        <?php foreach ($doctorsList as $doctor): ?>
                            <option value="<?= $doctor['id'] ?>" <?= ($_SESSION['user_id'] == $doctor['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($doctor['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label class="form-label" for="diagnosis">Diagnosis</label>
                    <textarea class="form-control" name="diagnosis" id="diagnosis" rows="4" required></textarea>
                </div>

                <div class="form-section">
                    <label class="form-label" for="treatment">Treatment Plan</label>
                    <textarea class="form-control" name="treatment" id="treatment" rows="4"></textarea>
                </div>

                <div class="form-section">
                    <label class="form-label" for="record_date">Date</label>
                    <input class="form-control" type="date" name="record_date" id="record_date" 
                           value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                    <a href="view_records.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>