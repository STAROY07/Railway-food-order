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

/* DELETE */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM restaurants WHERE id='$id'");
    if($del){
        $_SESSION['success_msg'] = "Restaurant #$id deleted successfully!";
    }
    header("Location: manage_restaurants.php");
    exit();
}

/* FETCH FOR EDIT */
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM restaurants WHERE id='$id'");
    $editData = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $upd = mysqli_query($conn, "UPDATE restaurants SET
        name='$name',
        owner_name='$owner',
        email='$email',
        phone='$phone',
        location='$location',
        description='$description'
        WHERE id='$id'");

    if($upd){
        $_SESSION['success_msg'] = "Restaurant '$name' updated successfully!";
    }

    header("Location: manage_restaurants.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM restaurants ORDER BY id DESC");
$totalCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Partner Restaurants | Railway Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.92);
    --border-color: rgba(255, 255, 255, 0.12);
    --accent-amber: #f59e0b;
    --accent-blue: #3b82f6;
    --accent-red: #ef4444;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.15) 0px, transparent 50%);
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

.form-control-dark {
    background: rgba(31, 41, 55, 0.8);
    border: 1px solid var(--border-color);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.95rem;
}

.form-control-dark:focus {
    background: rgba(31, 41, 55, 1);
    border-color: var(--accent-amber);
    color: #ffffff;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
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

.rest-img {
    width: 54px;
    height: 54px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

/* Action Buttons Flex Fix */
.btn-edit-action {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa !important;
    border: 1px solid rgba(59, 130, 246, 0.4);
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-edit-action:hover {
    background: #3b82f6;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.btn-delete-action {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.4);
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-delete-action:hover {
    background: #ef4444;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
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
            <h4 class="fw-bold mb-0 text-white">🍽️ Partner Restaurants</h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="register_restaurant.php" class="btn btn-success fw-bold px-3 py-2" style="border-radius: 10px;">+ Add Restaurant</a>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6"><?php echo $totalCount; ?> Registered</span>
        </div>
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
    
    <!-- EDIT FORM IF SELECTED -->
    <?php if($editData){ ?>
    <div class="glass-card mb-4">
        <h5 class="fw-bold text-white mb-3">✏️ Edit Restaurant Details (#<?php echo $editData['id']; ?>)</h5>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small">Restaurant Name</label>
                    <input type="text" name="name" class="form-control form-control-dark" value="<?php echo htmlspecialchars($editData['name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Owner Name</label>
                    <input type="text" name="owner" class="form-control form-control-dark" value="<?php echo htmlspecialchars($editData['owner_name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Email</label>
                    <input type="email" name="email" class="form-control form-control-dark" value="<?php echo htmlspecialchars($editData['email']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small">Phone</label>
                    <input type="text" name="phone" class="form-control form-control-dark" value="<?php echo htmlspecialchars($editData['phone']); ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label text-muted small">Station / Location</label>
                    <input type="text" name="location" class="form-control form-control-dark" value="<?php echo htmlspecialchars($editData['location']); ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label text-muted small">Description</label>
                    <textarea name="description" class="form-control form-control-dark" rows="2"><?php echo htmlspecialchars($editData['description']); ?></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button name="update" class="btn btn-warning px-4 fw-bold" style="border-radius: 10px;">Save Changes</button>
                <a href="manage_restaurants.php" class="btn btn-secondary px-4" style="border-radius: 10px;">Cancel</a>
            </div>
        </form>
    </div>
    <?php } ?>

    <!-- LIST OF RESTAURANTS -->
    <div class="glass-card">
        <?php if($totalCount == 0){ ?>
            <div class="text-center py-5">
                <h5 class="text-muted">No partner restaurants registered yet.</h5>
                <a href="register_restaurant.php" class="btn btn-warning fw-bold mt-2">Register First Restaurant</a>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 8%;">Logo</th>
                            <th style="width: 22%;">Restaurant Name</th>
                            <th style="width: 18%;">Owner</th>
                            <th style="width: 18%;">Location / Station</th>
                            <th style="width: 15%;">Contact Info</th>
                            <th style="width: 14%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr class="admin-row">
                            <td class="font-monospace fw-bold text-info">#<?php echo $row['id']; ?></td>
                            <td>
                                <?php if($row['image']){ ?>
                                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="rest-img" alt="logo">
                                <?php } else { ?>
                                    <div class="rest-img bg-dark d-flex align-items-center justify-content-center text-muted fs-5">🍽️</div>
                                <?php } ?>
                            </td>
                            <td class="fw-bold text-white fs-6"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="text-light fw-medium"><?php echo htmlspecialchars($row['owner_name']); ?></td>
                            <td><span class="badge bg-secondary px-3 py-2 rounded-pill"><?php echo htmlspecialchars($row['location']); ?></span></td>
                            <td class="small text-muted">
                                ✉️ <span class="text-light"><?php echo htmlspecialchars($row['email']); ?></span><br>
                                📞 <span class="text-light"><?php echo htmlspecialchars($row['phone']); ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit-action">✏️ Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($row['name'])); ?>?')" 
                                       class="btn-delete-action">🗑 Delete</a>
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