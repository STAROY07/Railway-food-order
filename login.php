<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login | Railway Food Order</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">
  <div class="card shadow p-4" style="width:380px">

    <h3 class="text-center text-primary mb-3">🔐 User Login</h3>
    <p class="text-center text-muted">Access your food orders</p>

    <form method="post">

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>

      <button name="login" class="btn btn-success w-100">Login</button>

    </form>

    <div class="text-center mt-3">
      <small>New user? <a href="register.php">Register here</a></small>
    </div>

<?php
if(isset($_POST['login'])){

  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$password'");

  if(mysqli_num_rows($q) == 1){

      $row = mysqli_fetch_assoc($q);

      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user'] = $row['email'];
      $_SESSION['name'] = $row['name'];

      header("Location: dashboard.php");
      exit;

  } else {
      echo "<div class='alert alert-danger mt-3'>Invalid Email or Password</div>";
  }
}
?>

  </div>
</div>

</body>
</html>
