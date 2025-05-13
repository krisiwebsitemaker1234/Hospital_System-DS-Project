<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Staff Directory";

// Build query with JOIN
$where = "1=1";
$params = [];

// Filters
if (isset($_GET['position']) && !empty($_GET['position'])) {
    $where .= " AND staff.position = ?";
    $params[] = $_GET['position'];
}

if (isset($_GET['department']) && !empty($_GET['department'])) {
    $where .= " AND staff.department = ?";
    $params[] = $_GET['department'];
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $where .= " AND users.active = ?";
    $params[] = ($_GET['status'] == 'active') ? 1 : 0;
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $where .= " AND (staff.name LIKE ? OR users.email LIKE ? OR users.phone LIKE ?)";
    $searchParam = "%{$_GET['search']}%";
    array_push($params, $searchParam, $searchParam, $searchParam);
}

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Get total records
$count_sql = "SELECT COUNT(*) as total 
              FROM staff
              INNER JOIN users ON staff.user_id = users.id
              WHERE $where";
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Main query (CORRECTED)
// Main query
$sql = "SELECT staff.id, staff.name, staff.position, staff.department,
               users.email, users.phone, users.active, users.created_at
        FROM staff
        INNER JOIN users ON staff.user_id = users.id
        WHERE $where
        ORDER BY staff.name ASC
        LIMIT ?, ?";

// Prepare statement
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    // Split parameters into WHERE and LIMIT parts
    $where_params = $params; // Original filter parameters
    $limit_params = [$offset, $records_per_page]; // Integer values
    
    // Create type string: 's' for WHERE params, 'ii' for LIMIT
    $types = str_repeat('s', count($where_params)) . 'ii';
    
    // Merge parameters: WHERE params first, then LIMIT
    $merged_params = array_merge($where_params, $limit_params);
    
    // Bind parameters with correct types
    $stmt->bind_param($types, ...$merged_params);
} else {
    // Bind only LIMIT parameters as integers
    $stmt->bind_param("ii", $offset, $records_per_page);
}

$stmt->execute();
$result = $stmt->get_result();

// Get filter options
$departments = $conn->query("SELECT DISTINCT department FROM staff WHERE department IS NOT NULL ORDER BY department");
$departmentList = $departments->fetch_all(MYSQLI_ASSOC);

$positions = $conn->query("SELECT DISTINCT position FROM staff WHERE position IS NOT NULL ORDER BY position");
$positionList = $positions->fetch_all(MYSQLI_ASSOC);
?>


<style>
    /* Consistent dashboard styling */
    .dashboard-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
    }

    .section-header {
        font-size: 1.2rem;
        color: var(--primary);
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: var(--transition);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
    }

    .card-header {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .card-header.active {
        background: var(--primary);
        color: white;
    }

    .card-header.inactive {
        background: var(--secondary);
        color: white;
    }

    .card-body {
        padding: 15px;
    }

    .card-footer {
        background: var(--gray-light);
        padding: 15px;
        border-top: 1px solid var(--border-color);
    }

    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
    }

    .page-link {
        color: var(--primary);
        margin: 0 5px;
        border-radius: 5px;
    }
</style>
<style>
/* Staff Cards Styling */
.staff-cards-container {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.staff-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #e9ecef;
}

.staff-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.card-header {
    padding: 1.5rem;
    color: white;
}

.card-header.active {
    background: #2ecc71;
}

.card-header.inactive {
    background: #95a5a6;
}

.card-header h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
}

.card-header .position {
    margin: 0.5rem 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.card-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.info-item i {
    width: 25px;
    color: #7f8c8d;
}

.info-item a {
    color: #3498db;
    text-decoration: none;
}

.info-item a:hover {
    text-decoration: underline;
}

.joined-date {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
    font-size: 0.85rem;
    color: #7f8c8d;
    display: flex;
    align-items: center;
}

.joined-date i {
    margin-right: 8px;
}

.no-results {
    text-align: center;
    padding: 4rem;
    background: white;
    border-radius: 10px;
}

.no-results i {
    font-size: 3rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
}

.no-results p {
    color: #7f8c8d;
    margin: 0;
}
</style>

<link rel="stylesheet" href="style.css"> 
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const staffId = this.getAttribute('data-id');
            const staffName = this.getAttribute('data-name');
            
            document.getElementById('staffName').textContent = staffName;
            document.getElementById('confirmDelete').href = 'delete_staff.php?id=' + staffId;
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        
        <main class="main-content" style="margin-left: var(--sidebar-width); padding: 20px 25px;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-user-md"></i> Staff Directory</h1>
                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                    <a href="add_staff.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add Staff
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>
            
            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <i style="color:black;" class="fas fa-filter"></i> <p style="color: #333; font-weight: bold;">Filter Staff</p>
                </div>
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                                placeholder="Name, Email, or Phone">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="position" class="form-label">Position</label>
                            <select name="position" id="position" class="form-select">
                                <option value="">All Positions</option>
                                <?php foreach ($positionList as $pos): ?>
                                    <option value="<?= htmlspecialchars($pos['position']) ?>" 
                                        <?= (isset($_GET['position']) && $_GET['position'] == $pos['position']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pos['position']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="department" class="form-label">Department</label>
                            <select name="department" id="department" class="form-select">
                                <option value="">All Departments</option>
                                <?php foreach ($departmentList as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept['department']) ?>" 
                                        <?= (isset($_GET['department']) && $_GET['department'] == $dept['department']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['department']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div><br>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                            <a href="view_staff.php" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Replace the staff cards section with this styled version -->
<div class="staff-cards-container">
    <?php if ($result->num_rows > 0): ?>
        <div class="cards-grid">
            <?php while ($staff = $result->fetch_assoc()): ?>
                <div class="staff-card">
                    <div class="card-header <?= $staff['active'] ? 'active' : 'inactive' ?>">
                        <h3><?= htmlspecialchars($staff['name']) ?></h3>
                        <p class="position"><?= htmlspecialchars($staff['position']) ?></p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($staff['department'])): ?>
                            <div class="info-item">
                                <i class="fas fa-building"></i>
                                <span><?= htmlspecialchars($staff['department']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?= htmlspecialchars($staff['email']) ?>">
                                <?= htmlspecialchars($staff['email']) ?>
                            </a>
                        </div>

                        <?php if (!empty($staff['phone'])): ?>
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:<?= htmlspecialchars($staff['phone']) ?>">
                                    <?= htmlspecialchars($staff['phone']) ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($_SESSION['user_type'] == 'admin'): ?>
                            <div class="joined-date">
                                <i class="fas fa-calendar-alt"></i>
                                Joined: <?= date('M d, Y', strtotime($staff['created_at'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <i class="fas fa-user-slash"></i>
            <p>No staff members found matching your criteria</p>
        </div>
    <?php endif; ?>
</div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a>
                        </li>
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </main>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">