<?php
session_start();
include 'db.php';

if(isset($_SESSION['admin'])){
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if(isset($_POST['login'])){
    $email    = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $query = mysqli_query($conn,"SELECT * FROM admins
                                 WHERE email='$email' AND password='$password'");

    if(mysqli_num_rows($query) == 1){
        $_SESSION['admin'] = $email;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login | Railway Food Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; }
    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .orb {
        position: fixed; border-radius: 50%;
        filter: blur(80px); opacity: 0.2; pointer-events: none;
    }
    .orb-1 { width:400px; height:400px; background:#6c63ff; top:-100px; left:-100px; }
    .orb-2 { width:350px; height:350px; background:#f7971e; bottom:-80px; right:-80px; }

    .login-box {
        position: relative; z-index: 2;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 24px;
        padding: 48px 44px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        color: white;
    }
    .shield-icon {
        width: 72px; height: 72px;
        background: linear-gradient(135deg,#f7971e,#ffd200);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 34px;
        margin: 0 auto 24px;
        box-shadow: 0 8px 24px rgba(247,151,30,0.4);
    }
    .login-box h2 {
        text-align: center; font-weight: 800;
        font-size: 26px; margin-bottom: 4px;
    }
    .login-box .sub {
        text-align: center; color: rgba(255,255,255,0.5);
        font-size: 13px; margin-bottom: 30px;
    }
    .form-floating label { color: rgba(255,255,255,0.5); }
    .form-control {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        color: white;
        padding: 14px 16px;
        font-size: 15px;
        transition: border 0.2s;
    }
    .form-control:focus {
        background: rgba(255,255,255,0.12);
        border-color: #f7971e;
        box-shadow: 0 0 0 3px rgba(247,151,30,0.15);
        color: white;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.35); }
    .form-label { font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 6px; }

    .btn-login-submit {
        background: linear-gradient(90deg,#f7971e,#ffd200);
        color: #0f0c29;
        font-weight: 800;
        font-size: 15px;
        border: none;
        border-radius: 50px;
        padding: 14px;
        width: 100%;
        transition: all 0.3s;
        margin-top: 8px;
    }
    .btn-login-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(247,151,30,0.45);
    }
    .alert-error {
        background: rgba(220,53,69,0.18);
        border: 1px solid rgba(220,53,69,0.4);
        color: #ff8a92;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        margin-bottom: 18px;
        text-align: center;
    }
    .back-link { text-align: center; margin-top: 20px; }
    .back-link a { color: rgba(255,255,255,0.45); font-size: 13px; text-decoration: none; }
    .back-link a:hover { color: #f7971e; }
</style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="login-box">
    <div class="shield-icon">⚙</div>
    <h2>Admin Portal</h2>
    <p class="sub">Railway Food Order System</p>

    <?php if($error){ ?>
    <div class="alert-error">⚠ <?= $error ?></div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="admin@railway.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button name="login" class="btn-login-submit">🔐 Login to Admin Panel</button>
    </form>

    <div class="back-link">
        <a href="index.php">← Back to Main Site</a>
    </div>
</div>

</body>
</html>