<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['train_id'])){
    echo "No Train Selected";
    exit();
}

$train_id = $_GET['train_id'];

/* Get Train Details */
$train_q = mysqli_query($conn,"SELECT * FROM trains WHERE id='$train_id'");
$train = mysqli_fetch_assoc($train_q);

/* Get Tracking Details */
$track_q = mysqli_query($conn,"SELECT * FROM train_tracking WHERE train_id='$train_id'");
$track = mysqli_fetch_assoc($track_q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Live Train Tracking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    font-family:'Poppins',sans-serif;
}

.track-card{
    background: rgba(255,255,255,0.1);
    border-radius:20px;
    padding:30px;
    backdrop-filter: blur(15px);
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.progress{
    height:25px;
    border-radius:50px;
}

.train-icon{
    font-size:40px;
    animation: moveTrain 3s infinite alternate ease-in-out;
}

@keyframes moveTrain{
    from{transform: translateX(0px);}
    to{transform: translateX(20px);}
}
</style>
</head>

<body>

<div class="container mt-5 col-md-8">

<div class="track-card text-center">

<h3>🚆 Live Train Tracking</h3>
<hr>

<h4><?= $train['train_number'] ?> - <?= $train['train_name'] ?></h4>

<div class="train-icon mt-3">🚆</div>

<p class="mt-4"><strong>Current Station:</strong> <?= $track['current_station'] ?></p>
<p><strong>Next Station:</strong> <?= $track['next_station'] ?></p>
<p><strong>Status:</strong> 
<span class="badge bg-warning text-dark"><?= $track['status'] ?></span>
</p>

<div class="mt-4">
<div class="progress">
  <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
       style="width: <?= $track['progress'] ?>%">
       <?= $track['progress'] ?>%
  </div>
</div>
</div>

<p class="mt-3 text-light">
Last Updated: <?= $track['updated_at'] ?>
</p>

<a href="dashboard.php" class="btn btn-light mt-3">Back to Dashboard</a>

</div>
</div>

</body>
</html>