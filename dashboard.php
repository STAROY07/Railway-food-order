<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Railway Food Order</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
        }

        .navbar {
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .hero {
            text-align: center;
            padding: 80px 20px 40px;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 42px;
        }

        .hero span {
            color: #ffd700;
        }

        .hero p {
            max-width: 600px;
            margin: 20px auto;
            opacity: 0.85;
        }

        .card-box {
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 20px;
            transition: 0.4s;
            color: white;
        }

        .card-box:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.2);
        }

        .btn-custom {
            border-radius: 50px;
            font-weight: 600;
        }

        .footer-text {
            text-align: center;
            margin-top: 60px;
            opacity: 0.8;
            font-size: 14px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">🚆 Railway Food Order</a>

    <div class="d-flex align-items-center">
        <span class="me-3">
            Welcome, <strong><?php echo $_SESSION['user']; ?></strong>
        </span>
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <h1>Delicious Food, <span>Right On Track!</span></h1>

    <p>
        Enjoy fresh, hygienic and tasty meals delivered directly to your train seat.
        Order anytime, anywhere and make your journey even more delightful.
    </p>
</section>

<!-- DASHBOARD CARDS -->
<div class="container pb-5">
    <div class="row g-4">

    

        <div class="col-md-3">
            <div class="card card-box text-center p-4 shadow">
                <h4>🍽 Order Food</h4>
                <p>Explore our delicious menu and satisfy your hunger.</p>
                <a href="menu.php" class="btn btn-warning btn-custom mt-2">View Menu</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box text-center p-4 shadow">
                <h4>🛒 My Cart</h4>
                <p>Check items added and update your order easily.</p>
                <a href="cart.php" class="btn btn-success btn-custom mt-2">Open Cart</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box text-center p-4 shadow">
                <h4>📦 My Orders</h4>
                <p>Track your previous and current food orders.</p>
                <a href="orders.php" class="btn btn-info btn-custom mt-2">View Orders</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box text-center p-4 shadow">
                <h4>👤 Profile</h4>
                <p>Manage your personal details and preferences.</p>
                <a href="profile.php" class="btn btn-dark btn-custom mt-2">View Profile</a>
            </div>
        </div>

        <!-- Feedback -->
<div class="col-md-3">
    <div class="card card-box text-center p-4">
        <h5>💬 Feedback</h5>
        <p>Share your experience and help us improve.</p>
        <a href="feedback.php" class="btn btn-info btn-custom mt-3">
            Give Feedback
        </a>
    </div>
</div>

<!-- Complaint -->
<div class="col-md-3">
    <div class="card card-box text-center p-4">
        <h5>⚠ Complaint</h5>
        <p>Facing any issue? Let us know immediately.</p>
        <a href="complaint.php" class="btn btn-danger btn-custom mt-3">
            Submit Complaint
        </a>
    </div>
</div>

    </div>

    <div class="footer-text">
        🚄 Fast Delivery | 🍽 Hygienic Food | 📍 Seat Service | 🕒 24/7 Available
    </div>
</div>

</body>
</html>