<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>User Registration | Railway Food Order</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Roboto',sans-serif; }
    body { background: linear-gradient(to right,#f7971e,#ffd200); min-height:100vh; display:flex; justify-content:center; align-items:center; }
    .register-container { background:#fff; padding:40px; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.2); width:100%; max-width:400px; }
    .register-box h2 { text-align:center; color:#333; margin-bottom:10px; font-size:28px; }
    .register-box p { text-align:center; color:#777; margin-bottom:30px; font-size:14px; }
    .register-box input { width:100%; padding:12px 15px; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; font-size:14px; transition:0.3s; }
    .register-box input:focus { border-color:#f7971e; outline:none; box-shadow:0 0 5px rgba(247,151,30,0.5); }
    .register-box button { width:100%; padding:12px; background:#f7971e; border:none; border-radius:8px; color:#fff; font-size:16px; font-weight:700; cursor:pointer; transition:0.3s; }
    .register-box button:hover { background:#ffd200; color:#333; }
    .success { text-align:center; color:green; margin-top:20px; font-weight:bold; }
    .error { text-align:center; color:red; margin-top:20px; font-weight:bold; }
    .login-link { text-align:center; margin-top:20px; font-size:14px; color:#555; }
    .login-link a { color:#f7971e; text-decoration:none; font-weight:700; }
    .login-link a:hover { text-decoration:underline; }
  </style>
</head>
<body>

<div class="register-container">
  <div class="register-box">

    <h2>? Register</h2>
    <p>Create your account to order food</p>

    <?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($name != "" && $email != "" && $password != "") {

        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $message = "<div class='error'>Email already registered. Please login.</div>";
        } else {
            // Plain password (NO HASHING)
            $sql = "INSERT INTO users (name, email, password) 
                    VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                $message = "<div class='success'>Registration successful!</div>";
            } else {
                $message = "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    } else {
        $message = "<div class='error'>Please fill in all fields.</div>";
    }
}
?>

    <form method="post">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="submit">Register</button>
    </form>

    <?php
      if($message != "") echo $message; // Show message below form
    ?>

    <div class="login-link">
      Already have an account? <a href="login.php">Login</a>
    </div>

  </div>
</div>

</body>
</html>