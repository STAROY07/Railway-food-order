<?php
session_start();
include 'db.php';

$success = "";
$error = "";

if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $imageName = "";
    if(!empty($_FILES['image']['name'])){
        $imageName = time()."_".basename($_FILES['image']['name']);
        if(!file_exists("uploads/")){
            mkdir("uploads/", 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$imageName);
    }

    $query = mysqli_query($conn, "INSERT INTO restaurants 
    (name, owner_name, email, phone, location, description, image)
    VALUES 
    ('$name','$owner','$email','$phone','$location','$description','$imageName')");

    if($query){
        $success = "Restaurant '$name' Registered Successfully!";
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Restaurant | Railway Food Order</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.95);
    --border-color: rgba(255, 255, 255, 0.12);
    --accent-orange: #ff6b35;
    --accent-green: #10b981;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(255, 107, 53, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.15) 0px, transparent 50%);
    background-attachment: fixed;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #f3f4f6;
    min-height: 100vh;
}

.register-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    padding: 36px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
}

.form-label {
    color: #d1d5db !important;
    font-weight: 600;
    font-size: 0.88rem;
    margin-bottom: 6px;
}

.form-control-dark {
    background: rgba(31, 41, 55, 0.9);
    border: 1px solid var(--border-color);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 0.95rem;
}

.form-control-dark::placeholder {
    color: #9ca3af;
}

.form-control-dark:focus {
    background: rgba(31, 41, 55, 1);
    border-color: var(--accent-orange);
    color: #ffffff;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.25);
}

.btn-submit {
    background: linear-gradient(135deg, #ff6b35, #e05320);
    color: white;
    font-weight: 700;
    padding: 14px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.35);
    transition: all 0.25s ease;
    font-size: 1.05rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(255, 107, 53, 0.5);
    color: white;
}

.btn-back {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid var(--border-color);
    padding: 10px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

/* Modal Popup Overlay */
.modal-content-dark {
    background: #111827;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    color: #fff;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center py-5" style="min-height:100vh">

<div class="register-card col-md-8 col-lg-7">

    <div class="text-center mb-4">
        <div class="fs-1 mb-2">🍽️</div>
        <h3 class="fw-bold text-white mb-1">Partner Restaurant Registration</h3>
        <p class="text-secondary">Register your food outlet to deliver fresh meals to train passengers.</p>
    </div>

    <?php if($error){ ?>
    <div class="alert alert-danger border-0 text-center mb-4" style="border-radius:12px; background: rgba(239, 68, 68, 0.2); color: #f87171;">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Restaurant Name *</label>
                <input type="text" name="name" class="form-control form-control-dark" placeholder="e.g. Royal Punjabi Rasoi" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Owner Name *</label>
                <input type="text" name="owner" class="form-control form-control-dark" placeholder="e.g. Rajesh Kumar" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control form-control-dark" placeholder="e.g. contact@restaurant.com" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone Number *</label>
                <input type="text" name="phone" class="form-control form-control-dark" placeholder="e.g. +91 9876543210" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Railway Station / Location *</label>
                <input type="text" name="location" class="form-control form-control-dark" placeholder="e.g. New Delhi Railway Station (Platform 1)" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Short Description</label>
                <textarea name="description" class="form-control form-control-dark" rows="3" placeholder="Briefly describe food specialties..."></textarea>
            </div>

            <div class="col-md-12">
                <label class="form-label">Upload Restaurant Image / Logo</label>
                <input type="file" name="image" class="form-control form-control-dark">
            </div>

        </div>

        <button type="submit" name="register" class="btn btn-submit w-100 mt-4">
            🚀 Register Restaurant
        </button>

    </form>

    <div class="text-center mt-4">
        <a href="index.php" class="btn-back me-2">← Home</a>
        <a href="admin_dashboard.php" class="btn-back">Admin Dashboard →</a>
    </div>

</div>
</div>

<!-- SUCCESS POPUP MODAL OVERLAY -->
<?php if($success){ ?>
<div class="modal fade show d-block" id="successModal" tabindex="-1" style="background: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-dark text-center p-4">
      <div class="modal-body">
        <div style="font-size: 4rem;" class="mb-3">🎉</div>
        <h4 class="fw-bold text-white mb-2">Registration Successful!</h4>
        <p class="text-secondary mb-4"><?php echo htmlspecialchars($success); ?></p>
        <div class="d-flex justify-content-center gap-3">
            <a href="manage_restaurants.php" class="btn btn-success fw-bold px-4 py-2" style="border-radius:12px;">
                View in Admin Panel
            </a>
            <a href="register_restaurant.php" class="btn btn-outline-light px-4 py-2" style="border-radius:12px;">
                Add Another
            </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>