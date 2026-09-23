<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

// Stats
$totalUsers      = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM users"))[0] ?? 0;
$totalOrders     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders"))[0] ?? 0;
$totalRevenue    = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(total_price) FROM orders"))[0] ?? 0;
$totalRestaurants= mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM restaurants"))[0] ?? 0;
$totalComplaints = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complaints"))[0] ?? 0;
$totalFeedback   = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM feedback"))[0] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard | Railway Food Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #0f0e17;
        color: white;
        min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
        position: fixed; top: 0; left: 0;
        width: 240px; height: 100vh;
        background: rgba(255,255,255,0.04);
        border-right: 1px solid rgba(255,255,255,0.08);
        padding: 28px 16px;
        z-index: 100;
        display: flex; flex-direction: column;
    }
    .sidebar-logo {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 20px;
    }
    .sidebar-logo .logo-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg,#f7971e,#ffd200);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    .sidebar-logo span { font-weight: 800; font-size: 15px; }

    .nav-item-s {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px; border-radius: 12px;
        color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 500;
        text-decoration: none; margin-bottom: 4px;
        transition: all 0.2s;
    }
    .nav-item-s:hover, .nav-item-s.active {
        background: rgba(247,151,30,0.12);
        color: #f7971e;
    }
    .nav-item-s .ni { font-size: 18px; width: 22px; text-align: center; }

    .sidebar-footer { margin-top: auto; }
    .logout-btn {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border-radius: 12px;
        color: #ff6b6b; font-size: 14px; font-weight: 600;
        text-decoration: none;
        background: rgba(220,53,69,0.1);
        transition: all 0.2s;
    }
    .logout-btn:hover { background: rgba(220,53,69,0.2); color: #ff6b6b; }

    /* ── MAIN ── */
    .main-content {
        margin-left: 240px;
        padding: 32px;
        min-height: 100vh;
    }

    .top-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 32px;
    }
    .top-bar h1 { font-size: 24px; font-weight: 800; margin: 0; }
    .admin-badge {
        background: rgba(247,151,30,0.15);
        border: 1px solid rgba(247,151,30,0.3);
        color: #f7971e;
        border-radius: 50px; padding: 6px 18px; font-size: 13px; font-weight: 600;
    }

    /* ── STAT CARDS ── */
    .stat-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 24px;
        transition: all 0.3s; position: relative; overflow: hidden;
        height: 100%;
    }
    .stat-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
    }
    .stat-card.green::before  { background: linear-gradient(90deg,#22c55e,#16a34a); }
    .stat-card.blue::before   { background: linear-gradient(90deg,#3b82f6,#1d4ed8); }
    .stat-card.orange::before { background: linear-gradient(90deg,#f7971e,#ffd200); }
    .stat-card.purple::before { background: linear-gradient(90deg,#8b5cf6,#6d28d9); }
    .stat-card.red::before    { background: linear-gradient(90deg,#ef4444,#b91c1c); }
    .stat-card.teal::before   { background: linear-gradient(90deg,#14b8a6,#0f766e); }

    .stat-card:hover {
        transform: translateY(-4px);
        background: rgba(255,255,255,0.08);
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 16px;
    }
    .si-green  { background: rgba(34,197,94,0.15); }
    .si-blue   { background: rgba(59,130,246,0.15); }
    .si-orange { background: rgba(247,151,30,0.15); }
    .si-purple { background: rgba(139,92,246,0.15); }
    .si-red    { background: rgba(239,68,68,0.15); }
    .si-teal   { background: rgba(20,184,166,0.15); }

    .stat-value { font-size: 32px; font-weight: 800; margin-bottom: 4px; }
    .stat-label { font-size: 13px; color: rgba(255,255,255,0.5); font-weight: 500; }

    /* ── ACTION CARDS ── */
    .action-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 28px 24px;
        text-align: center; text-decoration: none; color: white;
        display: block; transition: all 0.3s; height: 100%;
    }
    .action-card:hover {
        color: white; transform: translateY(-5px);
        border-color: rgba(247,151,30,0.4);
        background: rgba(255,255,255,0.09);
        box-shadow: 0 15px 35px rgba(0,0,0,0.35);
    }
    .action-icon { font-size: 36px; margin-bottom: 14px; display: block; }
    .action-card h6 { font-weight: 700; margin-bottom: 6px; }
    .action-card p  { font-size: 12px; color: rgba(255,255,255,0.45); margin: 0; }
    .action-card .btn-go {
        display: inline-block; margin-top: 14px;
        background: linear-gradient(90deg,#f7971e,#ffd200);
        color: #0f0c29; font-weight: 700; font-size: 12px;
        border-radius: 50px; padding: 6px 20px;
    }

    .section-head { font-size: 17px; font-weight: 700; margin-bottom: 18px; color: rgba(255,255,255,0.8); }
</style>
</head>
<body>

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">🚆</div>
        <span>Railway Admin</span>
    </div>

    <a href="admin_dashboard.php" class="nav-item-s active">
        <span class="ni">📊</span> Dashboard
    </a>
    <a href="manage_users.php" class="nav-item-s">
        <span class="ni">👤</span> Users
    </a>
    <a href="manage_restaurants.php" class="nav-item-s">
        <span class="ni">🍽</span> Restaurants
    </a>
    <a href="manage_payments.php" class="nav-item-s">
        <span class="ni">💳</span> Payments
    </a>
    <a href="manage_feedback.php" class="nav-item-s">
        <span class="ni">💬</span> Feedback
    </a>
    <a href="manage_complaints.php" class="nav-item-s">
        <span class="ni">⚠</span> Complaints
    </a>

    <div class="sidebar-footer">
        <a href="admin_logout.php" class="logout-btn">
            <span>🚪</span> Logout
        </a>
    </div>
</aside>

<!-- ══════════ MAIN CONTENT ══════════ -->
<main class="main-content">

    <div class="top-bar">
        <div>
            <h1>Dashboard Overview</h1>
            <p style="color:rgba(255,255,255,0.4);font-size:13px;margin:0">
                Welcome back, <strong style="color:#f7971e"><?= htmlspecialchars($_SESSION['admin']) ?></strong>
            </p>
        </div>
        <span class="admin-badge">⚙ Super Admin</span>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card green">
                <div class="stat-icon si-green">👤</div>
                <div class="stat-value"><?= $totalUsers ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card blue">
                <div class="stat-icon si-blue">📦</div>
                <div class="stat-value"><?= $totalOrders ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card orange">
                <div class="stat-icon si-orange">💰</div>
                <div class="stat-value">₹<?= number_format($totalRevenue) ?></div>
                <div class="stat-label">Revenue</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card purple">
                <div class="stat-icon si-purple">🍽</div>
                <div class="stat-value"><?= $totalRestaurants ?></div>
                <div class="stat-label">Restaurants</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card red">
                <div class="stat-icon si-red">⚠</div>
                <div class="stat-value"><?= $totalComplaints ?></div>
                <div class="stat-label">Complaints</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card teal">
                <div class="stat-icon si-teal">💬</div>
                <div class="stat-value"><?= $totalFeedback ?></div>
                <div class="stat-label">Feedbacks</div>
            </div>
        </div>
    </div>

    <!-- ACTION CARDS -->
    <div class="section-head">Quick Actions</div>
    <div class="row g-3">
        <div class="col-6 col-md-4">
            <a href="manage_users.php" class="action-card">
                <span class="action-icon">👤</span>
                <h6>Manage Users</h6>
                <p>View and manage registered users</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="manage_restaurants.php" class="action-card">
                <span class="action-icon">🍽</span>
                <h6>Manage Restaurants</h6>
                <p>Edit & delete registered restaurants</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="manage_payments.php" class="action-card">
                <span class="action-icon">💳</span>
                <h6>Manage Payments</h6>
                <p>View all payment transactions</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="manage_feedback.php" class="action-card">
                <span class="action-icon">💬</span>
                <h6>View Feedback</h6>
                <p>Customer ratings & reviews</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="manage_complaints.php" class="action-card">
                <span class="action-icon">⚠</span>
                <h6>View Complaints</h6>
                <p>Customer complaint tickets</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="register_restaurant.php" class="action-card">
                <span class="action-icon">➕</span>
                <h6>Add Restaurant</h6>
                <p>Register a new restaurant partner</p>
                <span class="btn-go">Open →</span>
            </a>
        </div>
    </div>

</main>

</body>
</html>