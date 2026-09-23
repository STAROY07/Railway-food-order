<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = mysqli_real_escape_string($conn, $_SESSION['user']);

$result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' LIMIT 1");

if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
}else{
    echo "User not found!";
    exit();
}

if(isset($_POST['update'])){

    $phone   = mysqli_real_escape_string($conn,$_POST['phone']);
    $gender  = mysqli_real_escape_string($conn,$_POST['gender']);
    $dob     = $_POST['dob'];
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    if(!empty($_FILES['photo']['name'])){
        $img = time().'_'.$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/".$img);

        mysqli_query($conn,"UPDATE users SET 
            phone='$phone',
            gender='$gender',
            dob='$dob',
            address='$address',
            profile_pic='$img'
            WHERE email='$email'");
    } else {
        mysqli_query($conn,"UPDATE users SET 
            phone='$phone',
            gender='$gender',
            dob='$dob',
            address='$address'
            WHERE email='$email'");
    }

    header("Location: profile.php");
    exit();
}

function val($v){
    return htmlspecialchars($v ?? '');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile | Railway Food Order</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    font-family: 'Poppins', sans-serif;
}

.profile-card{
    background: #ffffff;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.avatar img{
    width:140px;
    height:140px;
    object-fit:cover;
    border-radius:50%;
    border:5px solid #2a5298;
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

.profile-header{
    text-align:center;
    margin-bottom:30px;
}

.info-box{
    background:#f1f5f9;
    border-radius:12px;
    padding:12px 15px;
    margin-bottom:12px;
}

.info-title{
    font-size:13px;
    color:#6c757d;
}

.info-value{
    font-size:16px;
    font-weight:600;
}

.form-control, .form-select{
    border-radius:10px;
}

.btn-custom{
    border-radius:30px;
}
</style>

</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="profile-card col-md-7">

<div class="profile-header">
    <div class="avatar mb-3">
        <img src="uploads/<?= val($user['profile_pic']) ?>" 
             onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png';">
    </div>
    <h4><?= val($user['name']) ?></h4>
    <small class="text-muted">🚆 Railway Food Customer</small>
</div>

<div class="row">

<div class="col-md-6">
    <div class="info-box">
        <div class="info-title">Email</div>
        <div class="info-value"><?= val($user['email']) ?></div>
    </div>
</div>

<div class="col-md-6">
    <div class="info-box">
        <div class="info-title">Phone</div>
        <div class="info-value"><?= val($user['phone']) ?: 'Not Added' ?></div>
    </div>
</div>

<div class="col-md-6">
    <div class="info-box">
        <div class="info-title">Gender</div>
        <div class="info-value"><?= val($user['gender']) ?: 'Not Selected' ?></div>
    </div>
</div>

<div class="col-md-6">
    <div class="info-box">
        <div class="info-title">Date of Birth</div>
        <div class="info-value"><?= val($user['dob']) ?: 'Not Added' ?></div>
    </div>
</div>

<div class="col-md-12">
    <div class="info-box">
        <div class="info-title">Address</div>
        <div class="info-value"><?= val($user['address']) ?: 'Not Added' ?></div>
    </div>
</div>

</div>

<hr>

<h5 class="mb-3">✏ Edit Profile</h5>

<form method="post" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
    <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?= val($user['phone']) ?>">
</div>

<div class="col-md-6 mb-3">
    <select name="gender" class="form-select">
        <option value="">Select Gender</option>
        <option <?= ($user['gender']=='Male')?'selected':'' ?>>Male</option>
        <option <?= ($user['gender']=='Female')?'selected':'' ?>>Female</option>
        <option <?= ($user['gender']=='Other')?'selected':'' ?>>Other</option>
    </select>
</div>

<div class="col-md-6 mb-3">
    <input type="date" name="dob" class="form-control" value="<?= val($user['dob']) ?>">
</div>

<div class="col-md-6 mb-3">
    <input type="file" name="photo" class="form-control">
</div>

<div class="col-md-12 mb-3">
    <textarea name="address" class="form-control" placeholder="Address"><?= val($user['address']) ?></textarea>
</div>

</div>

<button name="update" class="btn btn-success btn-custom w-100">
    Update Profile
</button>

</form>

<div class="d-flex justify-content-between mt-4">
    <a href="dashboard.php" class="btn btn-primary btn-custom">Home</a>
    <a href="logout.php" class="btn btn-danger btn-custom">Logout</a>
</div>

</div>
</div>

</body>
</html>