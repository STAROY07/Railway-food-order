<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_POST['amount']) && !isset($_SESSION['amount'])){
    header("Location: cart.php");
    exit();
}

$amount = $_POST['amount'] ?? $_SESSION['amount'];
$_SESSION['amount'] = $amount;

// Hardcoded Indian railway stations (no DB table needed)
$stations = [
    "New Delhi","Mumbai Central","Chennai Central","Kolkata Howrah","Bengaluru City",
    "Hyderabad Secunderabad","Ahmedabad","Pune Junction","Jaipur","Lucknow",
    "Patna Junction","Bhopal Junction","Nagpur","Surat","Kanpur Central",
    "Vadodara","Coimbatore","Kochi Ernakulam","Visakhapatnam","Indore",
    "Amritsar","Chandigarh","Agra Cantt","Varanasi","Guwahati",
    "Ranchi","Bhubaneswar","Thiruvananthapuram","Jodhpur","Allahabad",
    "Haridwar","Dehradun","Shimla","Jammu Tawi","Mysuru",
    "Madurai","Tirupati","Vijayawada","Nashik Road","Aurangabad"
];

$success = "";
$error   = "";

if(isset($_POST['place_order'])){
    $station  = mysqli_real_escape_string($conn, $_POST['station']);
    $coach    = mysqli_real_escape_string($conn, $_POST['coach']);
    $seat     = mysqli_real_escape_string($conn, $_POST['seat']);
    $payment  = "Online";
    $user     = $_SESSION['user'];

    if(empty($station) || empty($coach) || empty($seat)){
        $error = "Please fill all fields.";
    } else {
        // Insert each cart item as an order
        $cart = mysqli_query($conn,"SELECT * FROM cart WHERE user_email='$user'");

        if(mysqli_num_rows($cart) == 0){
            header("Location: cart.php");
            exit();
        }

        $order_ids = [];
        $total = 0;

        while($row = mysqli_fetch_assoc($cart)){
            $food  = mysqli_real_escape_string($conn, $row['item_name']);
            $price = $row['price'];
            $qty   = $row['qty'];
            $sum   = $price * $qty;
            $total += $sum;

            mysqli_query($conn,"INSERT INTO orders
                (user_email, food_name, price, quantity, total_price, station, seat_no, payment_method, order_status)
                VALUES
                ('$user','$food','$price','$qty','$sum','$station $coach-$seat','$seat','$payment','Pending')");

            $order_ids[] = mysqli_insert_id($conn);
        }

        // Clear cart
        mysqli_query($conn,"DELETE FROM cart WHERE user_email='$user'");

        $_SESSION['order_ids']   = $order_ids;
        $_SESSION['pay_total']   = $total;
        $_SESSION['pay_station'] = $station;
        $_SESSION['pay_seat']    = "$coach - Seat $seat";

        header("Location: payment.php?amount=$total");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout | Railway Food Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
}
.checkout-card{
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
}
.section-title{
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c757d;
    margin-bottom: 12px;
}
.form-label{ font-weight: 600; font-size: 14px; }
.form-control, .form-select{
    border-radius: 10px;
    padding: 10px 14px;
    border: 2px solid #e0e0e0;
    transition: border 0.2s;
}
.form-control:focus, .form-select:focus{
    border-color: #2c5364;
    box-shadow: none;
}
.train-icon{
    font-size: 48px;
    display: block;
    text-align: center;
    margin-bottom: 8px;
}
.amount-badge{
    background: linear-gradient(90deg,#0f2027,#2c5364);
    color: white;
    border-radius: 50px;
    padding: 10px 28px;
    font-size: 18px;
    font-weight: 700;
    display: inline-block;
}
.btn-checkout{
    background: linear-gradient(90deg,#11998e,#38ef7d);
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    border: none;
    border-radius: 50px;
    padding: 14px 40px;
    transition: transform 0.2s, box-shadow 0.2s;
    width: 100%;
}
.btn-checkout:hover{
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(56,239,125,0.4);
    color: #fff;
}
</style>
</head>

<body class="d-flex align-items-center justify-content-center py-5">

<div class="col-md-7">
<div class="checkout-card">

<span class="train-icon">🚆</span>
<h3 class="text-center fw-bold mb-1">Delivery Details</h3>
<p class="text-center text-muted mb-4">Tell us where to deliver your food on the train</p>

<div class="text-center mb-4">
    <span class="amount-badge">💳 Amount: ₹<?= $amount ?></span>
</div>

<?php if($error){ ?>
<div class="alert alert-danger text-center"><?= $error ?></div>
<?php } ?>

<form method="post">
<input type="hidden" name="amount" value="<?= $amount ?>">

<div class="mb-3">
    <label class="form-label">📍 Delivery Station</label>
    <select name="station" class="form-select" required>
        <option value="">-- Select Your Station --</option>
        <?php foreach($stations as $s){ ?>
        <option value="<?= htmlspecialchars($s) ?>"><?= $s ?></option>
        <?php } ?>
    </select>
</div>

<div class="row">
<div class="col-md-6 mb-3">
    <label class="form-label">🚃 Coach / Compartment</label>
    <select name="coach" class="form-select" required>
        <option value="">-- Select Coach --</option>
        <option value="S1">S1 - Sleeper</option>
        <option value="S2">S2 - Sleeper</option>
        <option value="S3">S3 - Sleeper</option>
        <option value="S4">S4 - Sleeper</option>
        <option value="B1">B1 - 3 Tier AC</option>
        <option value="B2">B2 - 3 Tier AC</option>
        <option value="A1">A1 - 2 Tier AC</option>
        <option value="A2">A2 - 2 Tier AC</option>
        <option value="H1">H1 - 1st Class AC</option>
        <option value="GN">GN - General</option>
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">💺 Seat Number</label>
    <select name="seat" class="form-select" required>
        <option value="">-- Select Seat --</option>
        <?php for($i=1;$i<=72;$i++){ ?>
        <option value="<?= $i ?>"><?= $i ?></option>
        <?php } ?>
    </select>
</div>
</div>

<div class="d-grid mt-3">
    <button type="submit" name="place_order" class="btn-checkout">
        🛒 Confirm & Proceed to Payment
    </button>
</div>

</form>

<div class="text-center mt-3">
    <a href="cart.php" class="text-muted small">⬅ Back to Cart</a>
</div>

</div>
</div>

</body>
</html>