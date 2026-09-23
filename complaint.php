<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];

/* Get user name from users table */
$q = mysqli_query($conn,"SELECT name FROM users WHERE email='$email'");
$userData = mysqli_fetch_assoc($q);
$name = $userData['name'];

$success = "";

if(isset($_POST['submit'])){

    $message = mysqli_real_escape_string($conn,$_POST['message']);

    if(!empty($message)){

        mysqli_query($conn,"INSERT INTO complaints (name, email, message)
                            VALUES ('$name','$email','$message')");

        $success = "Your complaint has been submitted successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Complaint</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#ff4b2b,#ff416c);
}
.complaint-card{
    background:white;
    border-radius:15px;
    padding:30px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="complaint-card col-md-6">

<h4 class="text-center mb-4">⚠ Submit Complaint</h4>

<?php if($success){ ?>
<div class="alert alert-success text-center">
<?= $success ?>
</div>
<?php } ?>

<form method="post">

<div class="mb-3">
<label class="form-label">Complaint Details</label>
<textarea name="message" class="form-control" rows="5" 
placeholder="Describe your issue here..." required></textarea>
</div>

<button type="submit" name="submit" class="btn btn-danger w-100">
Submit Complaint
</button>

</form>

<div class="text-center mt-3">
<a href="dashboard.php" class="btn btn-dark btn-sm">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>