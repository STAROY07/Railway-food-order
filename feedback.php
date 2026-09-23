<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user'];
$success = "";
$error = "";

if(isset($_POST['submit'])){

    $rating  = intval($_POST['rating']);
    $message = mysqli_real_escape_string($conn,$_POST['message']);

    if($rating >=1 && $rating <=5 && !empty($message)){

        $result = mysqli_query($conn,"INSERT INTO feedback 
        (user_email, email, rating, message, created_at)
        VALUES 
        ('$user_email','$user_email','$rating','$message',NOW())");

        if($result){
            $success = "Thank you! Your feedback has been submitted successfully.";
        } else {
            $error = mysqli_error($conn);
        }
    } else {
        $error = "Please provide valid rating and message.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Give Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#667eea,#764ba2);
    font-family: Arial;
}
.feedback-card{
    background:white;
    border-radius:15px;
    padding:30px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="feedback-card col-md-6">

<h4 class="text-center mb-4">💬 Share Your Feedback</h4>

<?php if($success){ ?>
<div class="alert alert-success text-center">
<?= $success ?>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="alert alert-danger text-center">
<?= $error ?>
</div>
<?php } ?>

<form method="post">

<div class="mb-3">
<label class="form-label">Rating</label>
<select name="rating" class="form-select" required>
<option value="">Select Rating</option>
<option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
<option value="4">⭐⭐⭐⭐ (Very Good)</option>
<option value="3">⭐⭐⭐ (Good)</option>
<option value="2">⭐⭐ (Average)</option>
<option value="1">⭐ (Poor)</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Message</label>
<textarea name="message" class="form-control" rows="4" placeholder="Write your feedback here..." required></textarea>
</div>

<button type="submit" name="submit" class="btn btn-primary w-100">
Submit Feedback
</button>

</form>

<div class="text-center mt-3">
<a href="dashboard.php" class="btn btn-dark btn-sm">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>