<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_POST['amount']) || !isset($_POST['pay_method'])){
    header("Location: cart.php");
    exit();
}

$user   = $_SESSION['user'];
$amount = $_POST['amount'];
$method = $_POST['pay_method'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card shadow text-center p-4">

<h3 class="text-success">✅ Payment Successful</h3>
<hr>

<p><b>Amount Paid:</b> ₹ <?= $amount ?></p>
<p><b>Payment Method:</b> <?= $method ?></p>

<div class="alert alert-success">
Your order has been confirmed successfully 🚆🍽️
</div>

<a href="my_orders.php" class="btn btn-primary">📦 My Orders</a>
<a href="menu.php" class="btn btn-success">🍴 Order More</a>

</div>
</div>

</body>
</html>
