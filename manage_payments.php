<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

/* Delete Payment Record */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM payments WHERE id='$id'");
    header("Location: manage_payments.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM payments ORDER BY id DESC");
$totalPayments = mysqli_num_rows($result);

// Calculate total revenue from completed payments
$revenueResult = mysqli_query($conn, "SELECT SUM(amount) as total FROM payments WHERE status='Success' OR status='Completed' OR status='paid'");
$totalRevenue = mysqli_fetch_assoc($revenueResult)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Payments | Railway Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.85);
    --border-color: rgba(255, 255, 255, 0.08);
    --accent-emerald: #10b981;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.12) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.12) 0px, transparent 50%);
    background-attachment: fixed;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #e5e7eb;
    min-height: 100vh;
}

.admin-header {
    background: rgba(17, 24, 39, 0.9);
    border-bottom: 1px solid var(--border-color);
    backdrop-filter: blur(12px);
    padding: 16px 0;
}

.glass-card {
    background: var(--card-bg);
    backdrop-filter: blur(16px);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

.admin-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.admin-table th {
    color: #9ca3af;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    padding: 12px 16px;
    border: none;
}

.admin-row {
    background: rgba(31, 41, 55, 0.6);
    transition: all 0.2s ease;
}

.admin-row:hover {
    background: rgba(31, 41, 55, 0.95);
    transform: translateY(-2px);
}

.admin-row td {
    padding: 16px;
    vertical-align: middle;
    border: none;
}

.admin-row td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
.admin-row td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

.btn-delete {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-delete:hover { background: #ef4444; color: #fff; }

.btn-back {
    background: rgba(255, 255, 255, 0.08);
    color: #d1d5db;
    border: 1px solid var(--border-color);
    padding: 8px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-back:hover { background: rgba(255, 255, 255, 0.15); color: #fff; }

.status-badge {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}
</style>
</head>

<body>

<header class="admin-header mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="admin_dashboard.php" class="btn-back">← Dashboard</a>
            <h4 class="fw-bold mb-0 text-white">💳 Payment Transactions</h4>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-success px-3 py-2 rounded-pill fs-6">Revenue: ₹ <?php echo number_format($totalRevenue, 2); ?></span>
            <span class="badge bg-secondary px-3 py-2 rounded-pill fs-6"><?php echo $totalPayments; ?> Logs</span>
        </div>
    </div>
</header>

<div class="container mb-5">
    <div class="glass-card">
        <?php if($totalPayments == 0){ ?>
            <div class="text-center py-5">
                <h5 class="text-muted">No payment records found.</h5>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Email</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date & Time</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr class="admin-row">
                            <td class="font-monospace text-muted">#<?php echo $row['id']; ?></td>
                            <td class="fw-semibold text-white"><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td class="font-monospace text-info"><?php echo htmlspecialchars($row['payment_id'] ?? 'PAY_'.rand(10000,99999)); ?></td>
                            <td class="fw-bold text-success">₹ <?php echo number_format($row['amount'], 2); ?></td>
                            <td><span class="badge bg-dark border border-secondary"><?php echo htmlspecialchars($row['method'] ?? 'Online UPI / Card'); ?></span></td>
                            <td><span class="status-badge">✓ <?php echo htmlspecialchars($row['status'] ?? 'Completed'); ?></span></td>
                            <td class="small text-muted"><?php echo htmlspecialchars($row['created_at'] ?? date('Y-m-d H:i')); ?></td>
                            <td class="text-end">
                                <a href="?delete=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Delete this payment transaction record?')" 
                                   class="btn-delete">🗑 Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>