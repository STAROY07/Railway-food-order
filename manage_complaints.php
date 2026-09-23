<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$msg = "";
if(isset($_SESSION['success_msg'])){
    $msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

/* Resolve / Delete Complaint */
if(isset($_GET['resolve']) || isset($_GET['delete'])){
    $id = intval($_GET['resolve'] ?? $_GET['delete']);
    $query = mysqli_query($conn, "DELETE FROM complaints WHERE id='$id'");
    if($query){
        $_SESSION['success_msg'] = "Complaint #$id has been resolved and closed successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to resolve complaint: " . mysqli_error($conn);
    }
    header("Location: manage_complaints.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM complaints ORDER BY id DESC");
$totalComplaints = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Complaints | Railway Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.92);
    --border-color: rgba(255, 255, 255, 0.12);
    --accent-red: #ef4444;
    --accent-green: #10b981;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.15) 0px, transparent 50%);
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

.complaint-box {
    background: rgba(17, 24, 39, 0.9);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: #e5e7eb;
    line-height: 1.5;
}

/* Button Styling Fix */
.btn-resolve {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff !important;
    border: none;
    padding: 9px 20px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    transition: all 0.25s ease;
    white-space: nowrap;
}

.btn-resolve:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.6);
    color: #ffffff !important;
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

/* Success Popup Toast Banner */
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
    animation: fadeInDown 0.4s ease-out;
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body>

<header class="admin-header mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="admin_dashboard.php" class="btn-back">← Admin Dashboard</a>
            <h4 class="fw-bold mb-0 text-white">⚠️ Customer Complaints & Feedback</h4>
        </div>
        <span class="badge bg-danger px-3 py-2 rounded-pill fs-6"><?php echo $totalComplaints; ?> Pending Complaints</span>
    </div>
</header>

<div class="container mb-5">
    
    <!-- Success Message Popup Banner -->
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
        <?php if($totalComplaints == 0){ ?>
            <div class="text-center py-5">
                <div class="fs-1 mb-2">🎉</div>
                <h5 class="text-white fw-bold">All Complaints Resolved!</h5>
                <p class="text-muted">There are currently no open customer complaints.</p>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">ID</th>
                            <th style="width: 22%;">Customer Name</th>
                            <th style="width: 24%;">Email Address</th>
                            <th style="width: 34%;">Complaint Message</th>
                            <th style="width: 14%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr class="admin-row">
                            <td class="font-monospace fw-bold text-info">#<?php echo $row['id']; ?></td>
                            <td class="fw-bold text-white fs-6"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="text-info fw-medium"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <div class="complaint-box">
                                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <a href="?resolve=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to resolve and close complaint #<?php echo $row['id']; ?>?')" 
                                       class="btn-resolve">
                                       ✓ Resolve Issue
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>