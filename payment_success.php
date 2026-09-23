<?php
session_start();
include 'db.php';

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_GET['payment_id'])){

    $payment_id = $_GET['payment_id'];
    $amount = $_GET['amount'];
    $user = $_SESSION['user'];
    $date = date("d M Y, h:i A");

    // Save payment in DB
    mysqli_query($conn,"INSERT INTO payments(user_email, payment_id, amount, status, method)
    VALUES('$user','$payment_id','$amount','Paid','Online')");

    $mail_sent = false;

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joglesahil908@gmail.com';
        $mail->Password   = 'osmo wkkl ljie gxds';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('joglesahil908@gmail.com', 'Railway Food Order');
        $mail->addAddress($user);

        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation - Railway Food Order';
        $mail->Body    = "
            <h2>Payment Successful ✅</h2>
            <p><b>Payment ID:</b> $payment_id</p>
            <p><b>Amount:</b> ₹ $amount</p>
            <p>Your order has been confirmed.</p>
            <p>Thank you for ordering with us 🚆</p>
        ";

        $mail->send();
        $mail_sent = true;

    } catch (Exception $e) {
        $mail_sent = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    font-family: Arial;
}
.success-card{
    background:white;
    border-radius:20px;
    padding:40px;
    box-shadow:0 20px 40px rgba(0,0,0,0.3);
    text-align:center;
}
.success-icon{
    font-size:70px;
    color:#28a745;
}
.detail-box{
    background:#f8f9fa;
    border-radius:10px;
    padding:15px;
    margin-top:20px;
    text-align:left;
}
</style>

</head>

<body>

<div class="container mt-5 col-md-6">
<div class="success-card">

<div class="success-icon">✅</div>

<h3 class="mt-3 text-success">Payment Successful!</h3>
<p>Your order has been placed successfully.</p>

<div class="detail-box">
    <p><b>Payment ID:</b> <?= $payment_id ?></p>
    <p><b>Amount Paid:</b> ₹ <?= $amount ?></p>
    <p><b>Email:</b> <?= $user ?></p>
    <p><b>Date:</b> <?= $date ?></p>
    <p><b>Coach:</b> <?= $_SESSION['coach'] ?? '' ?></p>
<p><b>Seat:</b> <?= $_SESSION['seat'] ?? '' ?></p>
</div>

<?php if($mail_sent){ ?>
    <div class="alert alert-success mt-4">
        📧 Confirmation email sent successfully.
    </div>
<?php } else { ?>
    <div class="alert alert-warning mt-4">
        ⚠ Payment successful but email sending failed.
    </div>
<?php } ?>

<div class="mt-4">

    <a href="dashboard.php" class="btn btn-primary me-2">
        Back to Dashboard
    </a>

    <?php if(isset($_SESSION['selected_train'])){ ?>
        <a href="train_tracking.php?train_id=<?= $_SESSION['selected_train']; ?>" 
           class="btn btn-success">
           🚆 Track Your Train
        </a>
    <?php } ?>

</div>

</div>
</div>

</body>
</html>