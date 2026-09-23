<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu | Railway Food Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            margin:0;
            font-family:'Poppins',sans-serif;
            background: linear-gradient(135deg, #1d4350, #a43931);
            color:white;
        }

        .navbar{
            background: rgba(0,0,0,0.3) !important;
        }

        .hero{
            text-align:center;
            padding:60px 20px 30px;
        }

        .hero h2{
            font-weight:700;
        }

        .hero span{
            color:#ffd700;
        }

        .menu-card{
            border-radius:20px;
            padding:40px;
            transition:0.4s;
            background:white;
            color:black;
        }

        .menu-card:hover{
            transform:translateY(-10px);
            box-shadow:0 15px 35px rgba(0,0,0,0.3);
        }

        .veg-title{
            color:#28a745;
            font-weight:600;
        }

        .nonveg-title{
            color:#dc3545;
            font-weight:600;
        }

        .btn-custom{
            border-radius:50px;
            padding:8px 25px;
            font-weight:600;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container">
        <span class="navbar-brand fw-bold">🚆 Railway Food Order</span>
        <div>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="cart.php" class="btn btn-warning btn-sm">Cart</a>
        </div>
    </div>
</nav>

<section class="hero">
    <h2>Choose Your <span>Meal Category</span></h2>
    <p>Fresh, hygienic and delicious meals delivered to your train seat.</p>
</section>

<div class="container pb-5">
    <div class="row g-4 mt-3 align-items-stretch">

        <!-- VEG -->
        <div class="col-md-6 d-flex">
            <div class="menu-card text-center shadow w-100">
                <h3 class="veg-title">🥗 Veg Menu</h3>
                <p class="mt-3">
                    Pure vegetarian dishes made with fresh ingredients
                    for a healthy and satisfying journey.
                </p>
                <a href="veg_item.php" class="btn btn-success btn-custom mt-auto">
                    View Veg Items
                </a>
            </div>
        </div>

        <!-- NON VEG -->
        <div class="col-md-6 d-flex">
            <div class="menu-card text-center shadow w-100">
                <h3 class="nonveg-title">🍗 Non-Veg Menu</h3>
                <p class="mt-3">
                    Enjoy flavorful and delicious non-veg dishes
                    served hot and fresh at your seat.
                </p>
                <a href="non_veg_item.php" class="btn btn-danger btn-custom mt-auto">
                    View Non-Veg Items
                </a>
            </div>
        </div>

    </div>
</div>

</body>
</html>