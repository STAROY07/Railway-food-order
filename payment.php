<?php
session_start();

if(!isset($_POST['amount'])){
    header("Location: cart.php");
    exit();
}

$amount = $_POST['amount'];

/* SAVE TRAIN ID INTO SESSION */
if(isset($_POST['train_id'])){
    $_SESSION['selected_train'] = $_POST['train_id'];
}

/* OPTIONAL: Save other delivery details */
if(isset($_POST['state'])){
    $_SESSION['state'] = $_POST['state'];
}
if(isset($_POST['district'])){
    $_SESSION['district'] = $_POST['district'];
}
if(isset($_POST['coach'])){
    $_SESSION['coach'] = $_POST['coach'];
}
if(isset($_POST['seat'])){
    $_SESSION['seat'] = $_POST['seat'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Select Payment Method</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">
<div class="card p-4 shadow text-center">

<h4>💳 Choose Payment Method</h4>
<h5 class="text-success mt-3">Amount: ₹ <?= $amount ?></h5>

<!-- COD -->
<form action="payment_success.php" method="post" class="mt-4">
    <input type="hidden" name="amount" value="<?= $amount ?>">
    <input type="hidden" name="method" value="COD">
    <button type="submit" class="btn btn-warning w-100 mb-3">
        Cash On Delivery
    </button>
</form>

<!-- ONLINE -->
<button id="rzp-button" class="btn btn-success w-100">
    Online Payment
</button>

</div>
</div>

<script>
var options = {
    "key": "rzp_test_SCmqy0SgANdNA6",
    "amount": "<?= $amount * 100 ?>",
    "currency": "INR",
    "name": "Railway Food Order",
    "description": "Food Payment",
    "handler": function (response){
        window.location.href = "payment_success.php?payment_id=" 
        + response.razorpay_payment_id 
        + "&amount=<?= $amount ?>&method=Online";
    },
    "theme": {
        "color": "#28a745"
    }
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>

</body>
</html>