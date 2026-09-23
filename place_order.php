<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

if(!isset($_POST['station']) || !isset($_POST['seat']) || !isset($_POST['payment'])){
    header("Location: cart.php");
    exit();
}

$station = mysqli_real_escape_string($conn,$_POST['station']);
$seat    = mysqli_real_escape_string($conn,$_POST['seat']);
$payment = mysqli_real_escape_string($conn,$_POST['payment']);

$q = mysqli_query($conn,"SELECT * FROM cart WHERE user_email='$user'");

if(mysqli_num_rows($q) == 0){
    header("Location: cart.php");
    exit();
}

$total = 0;
$order_ids = [];

while($row = mysqli_fetch_assoc($q)){

    $food  = $row['item_name'];
    $price = $row['price'];
    $qty   = $row['qty'];
    $sum   = $price * $qty;

    $total += $sum;

    mysqli_query($conn,"INSERT INTO orders 
    (user_email, food_name, price, quantity, total_price, station, seat_no, payment_method, order_status) 
    VALUES 
    ('$user','$food','$price','$qty','$sum','$station','$seat','$payment','Pending')");

    $order_ids[] = mysqli_insert_id($conn);
}

// save all order IDs in session
$_SESSION['order_ids'] = $order_ids;
$_SESSION['pay_total'] = $total;

?>

<!DOCTYPE html>
<html>
<head>
<title>Order Summary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow p-4">
    <h3 class="text-success text-center">✅ Order Placed Successfully</h3>
    <hr>

    <h5>Order Summary</h5>

    <table class="table table-bordered">
        <tr class="table-dark">
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>

        <?php
        foreach($order_ids as $oid){
            $oq = mysqli_query($conn,"SELECT * FROM orders WHERE id='$oid'");
            $r = mysqli_fetch_assoc($oq);
        ?>
        <tr>
            <td><?= $r['food_name'] ?></td>
            <td>₹ <?= $r['price'] ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>₹ <?= $r['total_price'] ?></td>
        </tr>
        <?php } ?>

        <tr class="table-success">
            <th colspan="3" class="text-end">Grand Total</th>
            <th>₹ <?= $total ?></th>
        </tr>
    </table>

    <div class="text-center">
        <form action="payment.php" method="post">
            <input type="hidden" name="amount" value="<?= $total ?>">
            <button type="submit" class="btn btn-success btn-lg">
                Proceed To Payment 💳
            </button>
        </form>
    </div>
</div>

</div>

</body>
</html>
