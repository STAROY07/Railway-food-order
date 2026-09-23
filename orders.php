<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

$query = mysqli_query($conn, "SELECT * FROM orders WHERE user_email='$user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders | Railway Food Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">🚆 Railway Food Order</a>
    <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">

<h3 class="mb-3">📦 My Orders</h3>

<?php if (mysqli_num_rows($query) == 0) { ?>

    <div class="alert alert-warning">No orders found.</div>

<?php } else { ?>

<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Food Item</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Station</th>
    <th>Seat No</th>
    <th>Status</th>
    <th>Date</th>
</tr>
</thead>

<tbody>
<?php
$i=1;
while($row = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?= $i++ ?></td>
    <td><?= $row['food_name'] ?></td>
    <td>₹ <?= $row['price'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td>₹ <?= $row['total_price'] ?></td>
    <td><?= $row['station'] ?></td>
    <td><?= $row['seat_no'] ?></td>
    <td>
        <span class="badge bg-success"><?= $row['order_status'] ?></span>
    </td>
    <td><?= $row['order_date'] ?></td>
</tr>

<?php } ?>

</tbody>
</table>

<?php } ?>

<a href="dashboard.php" class="btn btn-primary">⬅ Back to Dashboard</a>

</div>

</body>
</html>
