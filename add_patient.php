<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if there's a message
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Patient | Hospital Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <h1>Add New Patient</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-section">
            <form action="process_patient.php" method="post">
                <div class="form-header">
                    <div class="form-icon">👤</div>
                    <h2>Patient Information</h2>
                    <p>Enter the details of the new patient</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter patient name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Age" min="0" max="120" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="admission_date">Admission Date</label>
                        <input type="date" id="admission_date" name="admission_date" required>
                    </div>
                    
                    <div class="form-group form-full">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Patient's full address" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Contact Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Phone number">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email (Optional)</label>
                        <input type="email" id="email" name="email" placeholder="Email address">
                    </div>
                </div>
                
                <div class="form-group form-full">
                    <label for="notes">Medical Notes (Optional)</label>
                    <textarea id="notes" name="notes" placeholder="Any important medical information"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn-secondary">Reset</button>
                    <button type="submit" class="btn-success">Add Patient</button>
                </div>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Patient form specific styles */
        h1 {
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.1s;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.2s;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.15);
            border-left: 4px solid #27ae60;
            color: #27ae60;
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.15);
            border-left: 4px solid #e74c3c;
            color: #e74c3c;
        }
        
        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.3s;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.4s;
        }
        
        .form-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary);
            background-color: rgba(52, 152, 219, 0.1);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
        
        .form-header h2 {
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .form-header p {
            color: #7f8c8d;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-full {
            grid-column: span 2;
        }
        
        .form-group {
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.5s; }
        .form-group:nth-child(2) { animation-delay: 0.55s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        .form-group:nth-child(4) { animation-delay: 0.65s; }
        .form-group:nth-child(5) { animation-delay: 0.7s; }
        .form-group:nth-child(6) { animation-delay: 0.75s; }
        .form-group:nth-child(7) { animation-delay: 0.8s; }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.25);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.85s;
        }
        
        .form-actions button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #2ecc71;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-full {
                grid-column: span 1;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>