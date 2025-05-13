<?php
// Don't start session here as it should already be started in the including file
if (!isset($_SESSION['user_id'])) {
    // If somehow this file is accessed without a session
    header("Location: login.php");
    exit();
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <h5 class="mt-2">Hospital Management</h5>
            <p class="text-muted small"><br>
                <span class="badge bg-secondary"><?= ucfirst(htmlspecialchars($_SESSION['user_type'])) ?></span>
            </p>
        </div>
        
        <hr>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <!-- Patient Management -->
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'view_patients.php' ? 'active' : '' ?>" href="view_patients.php">
                    <i class="fas fa-users me-2"></i>
                    Patients
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'admin'): ?>
                <li class="nav-item ps-3">
                    <a class="nav-link <?= $current_page == 'add_patient.php' ? 'active' : '' ?>" href="add_patient.php">
                        <i class="fas fa-user-plus me-2"></i>
                        Add Patient
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Staff Management -->
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'view_staff.php' ? 'active' : '' ?>" href="view_staff.php">
                    <i class="fas fa-user-md me-2"></i>
                    Staff Directory
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'admin'): ?>
                <li class="nav-item ps-3">
                    <a class="nav-link <?= $current_page == 'add_staff.php' ? 'active' : '' ?>" href="add_staff.php">
                        <i class="fas fa-user-plus me-2"></i>
                        Add Staff
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Appointments -->
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'view_appointment.php' ? 'active' : '' ?>" href="view_appointment.php">
                    <i class="fas fa-calendar-check me-2"></i>
                    Appointments
                </a>
            </li>
            <li class="nav-item ps-3">
                <a class="nav-link <?= $current_page == 'add_appointment.php' ? 'active' : '' ?>" href="add_appointment.php">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Schedule Appointment
                </a>
            </li>
            
            <!-- Medical Records -->
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'view_records.php' ? 'active' : '' ?>" href="view_records.php">
                    <i class="fas fa-file-medical-alt me-2"></i>
                    Medical Records
                </a>
            </li>
            <?php if (in_array($_SESSION['user_type'], ['admin', 'doctor'])): ?>
                <li class="nav-item ps-3">
                    <a class="nav-link <?= $current_page == 'add_record.php' ? 'active' : '' ?>" href="add_record.php">
                        <i class="fas fa-file-medical me-2"></i>
                        New Record
                    </a>
                </li>
            <?php endif; ?>
            
            </ul>
        
        <hr>
        
        <!-- User Profile and Logout -->
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Sidebar-specific styles */
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .hospital-logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .nav-item {
            margin: 2px 0;
        }
        
        .nav-item.ps-3 {
            padding-left: 1rem;
        }
        
        .sidebar-heading {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .me-2 {
            margin-right: 0.5rem;
        }
        
        .mt-2 {
            margin-top: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .small {
            font-size: 0.875em;
        }
        
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        
        .bg-secondary {
            background-color: #6c757d;
        }
        
        .position-sticky {
            position: sticky;
            top: 0;
        }
        
        hr {
            margin: 1rem 0;
            color: inherit;
            border: 0;
            border-top: 1px solid;
            opacity: 0.25;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</nav>