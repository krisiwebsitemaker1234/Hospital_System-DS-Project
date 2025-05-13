<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Pagination setup
$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = '';
if (!empty($search)) {
    $search_condition = "WHERE name LIKE '%$search%' OR gender LIKE '%$search%' OR address LIKE '%$search%'";
}

// Count total records for pagination
$total_records_result = $conn->query("SELECT COUNT(*) as total FROM patients $search_condition");
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get patients with pagination
$patients = $conn->query("SELECT * FROM patients $search_condition ORDER BY id DESC LIMIT $offset, $records_per_page");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Patients | Hospital Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <h1>Patient Records</h1>
        
        <div class="page-actions">
            <?php if ($_SESSION['user_type'] == 'admin'): ?>
            <a href="add_patient.php" class="btn-primary">
                <span>➕</span> Add New Patient
            </a>
            <?php endif; ?>
        </div>
        
        <div class="filter-container">
            <form action="" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Search patients..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">🔍</button>
                </div>
                <?php if (!empty($search)): ?>
                <a href="view_patients.php" class="clear-search">Clear</a>
                <?php endif; ?>
            </form>
            
            <div class="records-info">
                Showing <strong><?php echo min($total_records, $offset + 1); ?>-<?php echo min($total_records, $offset + $records_per_page); ?></strong> of <strong><?php echo $total_records; ?></strong> patients
            </div>
        </div>
        
        <?php if ($patients->num_rows > 0): ?>
        <div class="dashboard-section">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Admission Date</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $patients->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['admission_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo ($page - 1); echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="page-link">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++): ?>
            <a href="?page=<?php echo $i; echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo ($page + 1); echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="page-link">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="alert alert-info">
            <?php echo empty($search) ? 'No patients found in the database.' : 'No patients matching your search criteria.'; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Patient list specific styles */
        h1 {
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.1s;
        }
        
        .page-actions {
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.2s;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .btn-primary span {
            margin-right: 8px;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.3s;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-group {
            position: relative;
        }
        
        .search-group input {
            padding: 12px 40px 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            width: 250px;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .search-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.25);
        }
        
        .search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--primary);
        }
        
        .clear-search {
            color: var(--warning);
            text-decoration: none;
            font-weight: 500;
        }
        
        .records-info {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.4s;
            overflow: hidden;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .data-table th {
            background-color: rgba(52, 152, 219, 0.05);
            font-weight: 600;
            color: var(--primary-dark);
            border-bottom: 2px solid var(--primary);
        }
        
        .data-table tr:hover {
            background-color: rgba(52, 152, 219, 0.03);
        }
        
        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .btn-icon.view {
            background-color: rgba(52, 152, 219, 0.15);
            color: var(--primary);
        }
        
        .btn-icon.edit {
            background-color: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }
        
        .btn-icon.delete {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--warning);
        }
        
        .btn-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.5s;
        }
        
        .page-link {
            padding: 10px 15px;
            border-radius: 8px;
            background-color: white;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .page-link.active {
            background-color: var(--primary);
            color: white;
        }
        
        .page-link:hover:not(.active) {
            background-color: #f1f1f1;
            transform: translateY(-2px);
        }
        
        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 4px solid var(--primary);
            color: var(--primary-dark);
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 0.4s;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .filter-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .search-group input {
                width: 100%;
            }
            
            .records-info {
                margin-top: 10px;
            }
        }
    </style>
</body>
</html>