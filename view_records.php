<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Build base query
$where = "1=1";
$params = [];
$types = "";

// Filters
if (isset($_GET['patient_id']) && !empty($_GET['patient_id'])) {
    $where .= " AND m.patient_id = ?";
    $params[] = $_GET['patient_id'];
    $types .= "i";
}

if (isset($_GET['doctor_id']) && !empty($_GET['doctor_id'])) {
    $where .= " AND m.doctor_id = ?";
    $params[] = $_GET['doctor_id'];
    $types .= "i";
}

if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $where .= " AND m.record_date >= ?";
    $params[] = $_GET['date_from'];
    $types .= "s";
}

if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
    $where .= " AND m.record_date <= ?";
    $params[] = $_GET['date_to'];
    $types .= "s";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $where .= " AND (m.diagnosis LIKE ? OR m.treatment LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

// User-specific restrictions
if ($_SESSION['user_type'] == 'doctor') {
    $where .= " AND m.doctor_id = ?";
    $params[] = $_SESSION['user_id'];
    $types .= "i";
}

if ($_SESSION['user_type'] == 'patient') {
    $where .= " AND m.patient_id = ?";
    $params[] = $_SESSION['user_id'];
    $types .= "i";
}

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get records
$sql = "SELECT m.*, 
        p.name AS patient_name,
        s.name AS doctor_name,
        s.department AS doctor_dept
        FROM medical_records m
        JOIN patients p ON m.patient_id = p.id
        JOIN staff s ON m.doctor_id = s.id
        WHERE $where
        ORDER BY m.record_date DESC
        LIMIT ?, ?";

$params[] = $offset;
$params[] = $per_page;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get total count for pagination
$count_sql = "SELECT COUNT(*) AS total FROM medical_records m WHERE $where";
$count_stmt = $conn->prepare($count_sql);

// Prepare count parameters (exclude LIMIT params)
$count_params = array_slice($params, 0, -2);
$count_types = substr($types, 0, -2);

// Only bind parameters if they exist
if (!empty($count_params)) {
    $count_stmt->bind_param($count_types, ...$count_params);
}

$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records | Hospital System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
    <style>
        <style>
    :root {
        --primary: #3498db;
        --primary-light: #e8f4fc;
        --secondary: #2ecc71;
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --border: #e0e6eb;
        --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        --radius: 12px;
    }

    .records-container {
        margin-left: 280px;
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .header h1 {
        margin: 0;
        font-size: 1.8rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filters-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        position: relative;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .records-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .record-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .record-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .record-header {
        padding: 1.5rem;
        background: var(--primary-light);
        border-bottom: 1px solid var(--border);
    }

    .record-header h3 {
        margin: 0 0 0.5rem;
        color: var(--text-dark);
        font-size: 1.2rem;
    }

    .record-meta {
        font-size: 0.9rem;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .record-body {
        padding: 1.5rem;
    }

    .record-content {
        margin: 1rem 0;
    }

    .record-content h4 {
        margin: 0 0 0.5rem;
        color: var(--primary);
        font-size: 1rem;
    }

    .record-content p {
        margin: 0;
        color: var(--text-dark);
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .record-actions {
        border-top: 1px solid var(--border);
        padding: 1.5rem;
        display: flex;
        gap: 0.75rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination a {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: white;
        color: var(--text-dark);
        text-decoration: none;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background: var(--primary-light);
        border-color: var(--primary);
    }

    .pagination a.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .records-container {
            margin-left: 0;
            padding: 1rem;
        }
        
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .filter-form {
            grid-template-columns: 1fr;
        }
        
        .records-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="records-container">
        <div class="header">
            <h1><i class="fas fa-file-medical"></i> Medical Records</h1>
            <?php if (in_array($_SESSION['user_type'], ['admin', 'doctor'])): ?>
                <a href="add_record.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Record
                </a>
            <?php endif; ?>
        </div>

        <!-- Filters Form -->
        <div class="filters-card">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <input type="text" name="search" placeholder="Search records..."
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <!-- Add other filter fields as needed -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </form>
        </div>

        <!-- Records Grid -->
        <div class="records-grid">
            <?php while ($record = $result->fetch_assoc()): ?>
                <div class="record-card">
                    <div class="record-header">
                        <h3><?= htmlspecialchars($record['patient_name']) ?></h3>
                        <div class="record-meta">
                            <?= date('M d, Y', strtotime($record['record_date'])) ?>
                            <span>•</span>
                            Dr. <?= htmlspecialchars($record['doctor_name']) ?>
                        </div>
                    </div>
                    
                    <div class="record-body">
                        <div class="record-content">
                            <h4>Diagnosis</h4>
                            <p><?= nl2br(htmlspecialchars($record['diagnosis'])) ?></p>
                            
                            <?php if (!empty($record['treatment'])): ?>
                                <h4>Treatment</h4>
                                <p><?= nl2br(htmlspecialchars($record['treatment'])) ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if (in_array($_SESSION['user_type'], ['admin', 'doctor'])): ?>
                            <div class="record-actions">
                                <a href="edit_record.php?id=<?= $record['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?= $record['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>" 
                       class="<?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Delete Modal -->
    <div class="modal" id="deleteModal">
        <!-- Add modal content here -->
    </div>

    <script>
    // Delete functionality
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const recordId = this.dataset.id;
            if (confirm('Are you sure you want to delete this record?')) {
                window.location = `delete_record.php?id=${recordId}`;
            }
        });
    });
    </script>
</body>
</html>