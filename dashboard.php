<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get statistics from database
$patients_count = $conn->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'];
$appointments_count = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];
$staff_count = $conn->query("SELECT COUNT(*) as count FROM staff")->fetch_assoc()['count'];
$records_count = $conn->query("SELECT COUNT(*) as count FROM medical_records")->fetch_assoc()['count'];

// Get today's appointments
$today = date('Y-m-d');
$today_appointments = $conn->query("SELECT a.*, p.name AS patient_name, s.name AS staff_name 
                                   FROM appointments a
                                   JOIN patients p ON a.patient_id = p.id
                                   JOIN staff s ON a.staff_id = s.id
                                   WHERE a.appointment_date = '$today'
                                   LIMIT 5");

// Get recent patients
$recent_patients = $conn->query("SELECT * FROM patients ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Hospital Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION['user_type']; ?>!</h1>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value"><?php echo $patients_count; ?></div>
                <div class="stat-label">Patients</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value"><?php echo $appointments_count; ?></div>
                <div class="stat-label">Appointments</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👨‍⚕️</div>
                <div class="stat-value"><?php echo $staff_count; ?></div>
                <div class="stat-label">Staff</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-value"><?php echo $records_count; ?></div>
                <div class="stat-label">Records</div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="dashboard-section">
                <h2>Today's Appointments</h2>
                <?php if ($today_appointments->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $today_appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo date('h:i A', strtotime($row['appointment_date'])); ?></td>
                            <td>
                                <span class="status <?php echo strtolower($row['status'] ?? 'pending'); ?>">
                                    <?php echo $row['status'] ?? 'Pending'; ?>
                                </span>
                            </td>
                            <td><?php echo $row['staff_name']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="alert alert-info">No appointments scheduled for today.</div>
                <?php endif; ?>
                <div class="text-center mt-20">
                    <a href="view_appointment.php" class="dashboard-link">View All Appointments</a>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Recent Patients</h2>
                <?php if ($recent_patients->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Admission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_patients->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['admission_date'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="alert alert-info">No patients in the database.</div>
                <?php endif; ?>
                <div class="text-center mt-20">
                    <a href="view_patients.php" class="dashboard-link">View All Patients</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Dashboard-specific styles */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            border-top: 4px solid var(--primary);
        }
        
        .stat-card:nth-child(2) {
            border-top-color: var(--secondary);
        }
        
        .stat-card:nth-child(3) {
            border-top-color: #f39c12;
        }
        
        .stat-card:nth-child(4) {
            border-top-color: #9b59b6;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-top: 30px;
        }
        
        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--shadow);
        }
        
        .dashboard-section h2 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--primary);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
        }
        
        .status.completed {
            background-color: rgba(46, 204, 113, 0.15);
            color: #27ae60;
        }
        
        .status.pending {
            background-color: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }
        
        .status.cancelled {
            background-color: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }
        
        .dashboard-link {
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .dashboard-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 4px solid var(--primary);
            color: var(--primary-dark);
            padding: 15px;
            border-radius: 5px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        /* Dashboard responsive styles */
        @media screen and (max-width: 1024px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .stats .stat-card,
        .dashboard-section {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</body>
</html>