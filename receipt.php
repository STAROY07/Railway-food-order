<?php
include 'db.php';

/* Fetch latest order (for demo project) */
$result = mysqli_query($conn,"SELECT * FROM orders ORDER BY id DESC LIMIT 1");
$order = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Receipt</title>

<style>
body{
  margin:0;
  font-family: Arial, sans-serif;
  background:#f2f6fc;
}

.receipt-container{
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
}

.receipt-box{
  width:420px;
  background:#fff;
  padding:25px;
  border-radius:10px;
  box-shadow:0 8px 20px rgba(0,0,0,0.2);
}

.receipt-box h2{
  text-align:center;
  color:#007bff;
  margin-bottom:5px;
}

.receipt-box p{
  text-align:center;
  color:#555;
  margin-bottom:20px;
}

table{
  width:100%;
  border-collapse:collapse;
}

td{
  padding:8px 0;
  font-size:14px;
}

td:last-child{
  text-align:right;
}

hr{
  border:none;
  border-top:1px dashed #ccc;
  margin:15px 0;
}

.total{
  font-weight:bold;
  font-size:16px;
}

.status{
  color:green;
  font-weight:bold;
}

.actions{
  text-align:center;
  margin-top:20px;
}

button{
  padding:10px 15px;
  border:none;
  border-radius:5px;
  cursor:pointer;
  font-size:14px;
}

.print-btn{
  background:#28a745;
  color:#fff;
}

.home-btn{
  background:#007bff;
  color:#fff;
  margin-left:10px;
}

@media print{
  .actions{ display:none; }
}
</style>

</head>
<body>

<div class="receipt-container">
<div class="receipt-box">

<h2>🚆 Railway Food Receipt</h2>
<p>Thank you for ordering</p>

<table>
<tr>
  <td>Order ID</td>
  <td>#<?php echo $order['id']; ?></td>
</tr>
<tr>
  <td>PNR Number</td>
  <td><?php echo $order['pnr']; ?></td>
</tr>
<tr>
  <td>Food Item</td>
  <td><?php echo $order['food']; ?></td>
</tr>
<tr>
  <td>Category</td>
  <td><?php echo $order['category']; ?></td>
</tr>
<tr>
  <td>Coach & Seat</td>
  <td><?php echo $order['coach']." - ".$order['seat']; ?></td>
</tr>
<tr>
  <td>Delivery Station</td>
  <td><?php echo $order['station']; ?></td>
</tr>
<tr>
  <td>Payment Method</td>
  <td><?php echo $order['payment']; ?></td>
</tr>
<tr>
  <td>Payment Status</td>
  <td class="status">PAID</td>
</tr>
</table>

<hr>

<table>
<tr class="total">
  <td>Total Amount</td>
  <td>₹<?php echo $order['price']; ?></td>
</tr>
</table>

<div class="actions">
  <button class="print-btn" onclick="window.print()">🖨 Print</button>
  <a href="index.php">
    <button class="home-btn">🏠 Home</button>
  </a>
</div>

</div>
</div>

</body>
</html>
