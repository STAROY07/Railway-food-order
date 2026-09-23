<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }

$msg = "";
if(isset($_SESSION['success_msg'])){
    $msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

/* Handle User Deletion */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    if($del){
        $_SESSION['success_msg'] = "User account #$id deleted successfully!";
    }
    header("Location: manage_users.php");
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
if($search != ''){
    $result = mysqli_query($conn, "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' ORDER BY id DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
}

$totalUsers = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Railway Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.95);
    --border-color: rgba(255, 255, 255, 0.12);
    --accent-blue: #3b82f6;
    --accent-indigo: #6366f1;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%);
    background-attachment: fixed;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #f3f4f6;
    min-height: 100vh;
}

.admin-header {
    background: rgba(17, 24, 39, 0.95);
    border-bottom: 1px solid var(--border-color);
    backdrop-filter: blur(12px);
    padding: 18px 0;
}

.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}

.search-input {
    background: rgba(31, 41, 55, 0.9);
    border: 1px solid var(--border-color);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 0.95rem;
}

.search-input::placeholder { color: #9ca3af; }

.search-input:focus {
    background: rgba(31, 41, 55, 1);
    border-color: var(--accent-blue);
    color: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.admin-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.admin-table th {
    color: #d1d5db;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    padding: 14px 18px;
    border: none;
}

.admin-row {
    background: rgba(31, 41, 55, 0.8);
    transition: all 0.2s ease;
}

.admin-row:hover {
    background: rgba(31, 41, 55, 1);
    transform: translateY(-2px);
}

.admin-row td {
    padding: 18px;
    vertical-align: middle;
    border: none;
    color: #f3f4f6;
}

.admin-row td:first-child { border-top-left-radius: 14px; border-bottom-left-radius: 14px; }
.admin-row td:last-child { border-top-right-radius: 14px; border-bottom-right-radius: 14px; }

.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-blue), var(--accent-indigo));
    color: #ffffff;
    font-weight: 700;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.4);
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.btn-delete:hover {
    background: #ef4444;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5);
}

.btn-back {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid var(--border-color);
    padding: 10px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.custom-alert {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid #10b981;
    color: #34d399;
    border-radius: 14px;
    padding: 16px 24px;
    font-weight: 600;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>
</head>

<body>

<header class="admin-header mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="admin_dashboard.php" class="btn-back">← Admin Dashboard</a>
            <h4 class="fw-bold mb-0 text-white">👥 Manage Registered Users</h4>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill fs-6"><?php echo $totalUsers; ?> Users Registered</span>
    </div>
</header>

<div class="container mb-5">
    
    <?php if($msg){ ?>
    <div class="custom-alert alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center gap-2">
            <span style="font-size: 1.4rem;">🎉</span>
            <span><?php echo htmlspecialchars($msg); ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php } ?>

    <div class="glass-card">
        <form method="get" class="mb-4">
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control search-input" 
                           placeholder="Search users by name, email or phone..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px;">
                        🔍 Search User
                    </button>
                </div>
            </div>
        </form>

        <?php if($totalUsers == 0){ ?>
            <div class="text-center py-5">
                <h5 class="text-muted">No users found matching criteria.</h5>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 32%;">User Info</th>
                            <th style="width: 32%;">Email Address</th>
                            <th style="width: 16%;">Phone Number</th>
                            <th style="width: 12%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr class="admin-row">
                            <td class="font-monospace fw-bold text-info">#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($row['name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white fs-6"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <small class="text-secondary">Registered Account</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-light fw-medium"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="text-light"><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars(addslashes($row['name'])); ?>?')"
                                       class="btn-delete">
                                       🗑 Delete User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>