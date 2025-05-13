<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current month and year (default to current month/year)
$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

// Validate month and year
if ($month < 1 || $month > 12) {
    $month = date('m');
}

// Calculate the previous and next month/year
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Get all appointments for the month
$start_date = "$year-$month-01";
$end_date = date('Y-m-t', strtotime($start_date));

$appointments = $conn->query("
    SELECT a.*, p.name AS patient_name, s.name AS staff_name 
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN staff s ON a.staff_id = s.id
    WHERE a.appointment_date BETWEEN '$start_date' AND '$end_date'
    ORDER BY a.appointment_date ASC
");

// Create an array to store appointments by date
$appointments_by_date = [];
while ($row = $appointments->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['appointment_date']));
    if (!isset($appointments_by_date[$date])) {
        $appointments_by_date[$date] = [];
    }
    $appointments_by_date[$date][] = $row;
}

// Get all days in the month
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$number_of_days = date('t', $first_day_of_month);
$first_day_of_week = date('N', $first_day_of_month);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Appointments | Hospital Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Appointments Calendar</h1>
            <a href="add_appointment.php" class="add-appointment-btn">
                <i class="fas fa-calendar-plus"></i>
                New Appointment
            </a>
        </div><br>
        
        <div class="calendar-container">
            <div class="calendar-header">
                <a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="calendar-nav">&laquo; Prev</a>
                <h2><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
                <a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="calendar-nav">Next &raquo;</a>
            </div>
            
            <div class="calendar">
                <div class="calendar-weekdays">
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                    <div>Sun</div>
                </div>
                
                <div class="calendar-days">
                    <?php
                    // Add empty cells for days before the first day of the month
                    for ($i = 1; $i < $first_day_of_week; $i++) {
                        echo '<div class="calendar-day empty"></div>';
                    }
                    
                    // Add cells for each day of the month
                    for ($day = 1; $day <= $number_of_days; $day++) {
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $is_today = ($date == date('Y-m-d'));
                        $day_class = $is_today ? 'today' : '';
                        $has_appointments = isset($appointments_by_date[$date]) && count($appointments_by_date[$date]) > 0;
                        
                        echo '<div class="calendar-day ' . $day_class . '">';
                        echo '<div class="day-number">' . $day . '</div>';
                        
                        if ($has_appointments) {
                            echo '<div class="appointment-count">' . count($appointments_by_date[$date]) . ' appt</div>';
                            echo '<div class="appointment-list">';
                            foreach ($appointments_by_date[$date] as $appointment) {
                                $status_class = strtolower($appointment['status'] ?? 'pending');
                                echo '<div class="appointment-item ' . $status_class . '">';
                                echo '<div class="appointment-time">' . date('h:i A', strtotime($appointment['appointment_date'])) . '</div>';
                                echo '<div class="appointment-patient">' . htmlspecialchars($appointment['patient_name']) . '</div>';
                                echo '<div class="appointment-doctor">Dr. ' . htmlspecialchars($appointment['staff_name']) . '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    
                    // Add empty cells for days after the last day of the month
                    $last_day_of_week = date('N', mktime(0, 0, 0, $month, $number_of_days, $year));
                    for ($i = $last_day_of_week + 1; $i <= 7; $i++) {
                        echo '<div class="calendar-day empty"></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="appointment-status-legend">
            <div class="legend-item">
                <span class="status-indicator pending"></span> Pending
            </div>
            <div class="legend-item">
                <span class="status-indicator completed"></span> Completed
            </div>
            <div class="legend-item">
                <span class="status-indicator cancelled"></span> Cancelled
            </div>
        </div>
        
        <!-- List view of upcoming appointments -->
        <div class="upcoming-appointments">
            <h2>Upcoming Appointments</h2>
            <?php
            $today = date('Y-m-d');
            $upcoming = $conn->query("
                SELECT a.*, p.name AS patient_name, s.name AS staff_name 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN staff s ON a.staff_id = s.id
                WHERE a.appointment_date >= '$today'
                ORDER BY a.appointment_date ASC
                LIMIT 10
            ");
            
            if ($upcoming->num_rows > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="data-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Patient</th>';
                echo '<th>Date & Time</th>';
                echo '<th>Doctor</th>';
                echo '<th>Status</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                while ($row = $upcoming->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['patient_name']) . '</td>';
                    echo '<td>' . date('M d, Y h:i A', strtotime($row['appointment_date'])) . '</td>';
                    echo '<td>Dr. ' . htmlspecialchars($row['staff_name']) . '</td>';
                    echo '<td><span class="status ' . strtolower($row['status'] ?? 'pending') . '">' . ($row['status'] ?? 'Pending') . '</span></td>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info">No upcoming appointments found.</div>';
            }
            ?>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Calendar styles */
        .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .calendar-nav {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 5px;
            transition: var(--transition);
        }
        
        .calendar-nav:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .calendar {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .calendar-weekdays div {
            padding: 12px;
            text-align: center;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            min-height: 60vh;
        }
        
        .calendar-day {
            border-right: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 10px;
            min-height: 100px;
            position: relative;
        }
        
        .calendar-day:nth-child(7n) {
            border-right: none;
        }
        
        .calendar-day.empty {
            background: #f9f9f9;
        }
        
        .calendar-day.today {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .day-number {
            font-weight: 500;
            position: absolute;
            top: 5px;
            right: 8px;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .today .day-number {
            background-color: var(--primary);
            color: white;
        }

    .add-appointment-btn {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white !important;
        padding: 12px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none !important;
        position: relative;
        overflow: hidden;
    }

    .add-appointment-btn span {
        font-size: 1.2em;
        transition: transform 0.3s ease;
    }

    .add-appointment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(52, 152, 219, 0.3);
        background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);
    }

    .add-appointment-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(52, 152, 219, 0.2);
    }

    .add-appointment-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent,
            rgba(255, 255, 255, 0.3),
            transparent
        );
        transition: left 0.6s;
    }

    .add-appointment-btn:hover::before {
        left: 100%;
    }

    @media (max-width: 768px) {
        .add-appointment-btn {
            padding: 10px 20px;
            font-size: 0.9em;
        }
        
        .add-appointment-btn span {
            font-size: 1em;
        }
    }
        
        .appointment-count {
            margin-top: 25px;
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        .appointment-list {
            margin-top: 5px;
            max-height: 150px;
            overflow-y: auto;
        }
        
        .appointment-item {
            background: white;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 5px;
            font-size: 0.8rem;
            border-left: 3px solid #95a5a6;
        }
        
        .appointment-item.completed {
            border-left-color: var(--secondary);
        }
        
        .appointment-item.pending {
            border-left-color: #f39c12;
        }
        
        .appointment-item.cancelled {
            border-left-color: var(--warning);
        }
        
        .appointment-time {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .appointment-patient {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .appointment-doctor {
            color: #7f8c8d;
            font-size: 0.75rem;
        }
        
        .appointment-status-legend {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-indicator.pending {
            background-color: #f39c12;
        }
        
        .status-indicator.completed {
            background-color: var(--secondary);
        }
        
        .status-indicator.cancelled {
            background-color: var(--warning);
        }
        
        .upcoming-appointments h2 {
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        @media screen and (max-width: 768px) {
            .calendar-days {
                min-height: auto;
            }
            
            .calendar-day {
                min-height: 80px;
            }
            
            .appointment-list {
                max-height: 100px;
            }
        }
    </style>
</body>
</html>