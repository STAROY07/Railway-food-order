<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("location:login.php");
    exit;
}

$items = [
    ["Paneer Butter Masala",180,"https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"],
    ["Shahi Paneer",190,"https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300&q=80"],
    ["Kadai Paneer",170,"https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=300&q=80"],
    ["Palak Paneer",160,"https://images.unsplash.com/photo-1604579278613-87f2d22b9cb4?w=300&q=80"],
    ["Matar Paneer",150,"https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=300&q=80"],
    ["Veg Biryani",140,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Veg Pulao",130,"https://images.unsplash.com/photo-1596797038530-2c107229654b?w=300&q=80"],
    ["Jeera Rice",110,"https://images.unsplash.com/photo-1516684732162-798a0062be99?w=300&q=80"],
    ["Steam Rice",100,"https://images.unsplash.com/photo-1536304993881-ff86e0c9c01d?w=300&q=80"],
    ["Dal Fry",120,"https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=300&q=80"],
    ["Dal Tadka",130,"https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&q=80"],
    ["Dal Makhani",150,"https://images.unsplash.com/photo-1600803907087-f56d462fd26b?w=300&q=80"],
    ["Chole Masala",140,"https://images.unsplash.com/photo-1565363887715-aae7ec2e1f71?w=300&q=80"],
    ["Rajma Masala",140,"https://images.unsplash.com/photo-1542560534-0a414e74a4ec?w=300&q=80"],
    ["Aloo Gobi",120,"https://images.unsplash.com/photo-1606923231677-7b83f3e7c14b?w=300&q=80"],
    ["Aloo Matar",110,"https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=300&q=80"],
    ["Mix Veg",130,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Veg Kolhapuri",160,"https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=300&q=80"],
    ["Veg Handi",150,"https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"],
    ["Veg Korma",170,"https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300&q=80"],
    ["Malai Kofta",180,"https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&q=80"],
    ["Veg Manchurian",150,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Veg Hakka Noodles",140,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Veg Fried Rice",140,"https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Veg Schezwan Rice",150,"https://images.unsplash.com/photo-1596797038530-2c107229654b?w=300&q=80"],
    ["Veg Burger",80,"https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80"],
    ["Veg Pizza",150,"https://images.unsplash.com/photo-1513104890138-7c749659a591?w=300&q=80"],
    ["Cheese Sandwich",90,"https://images.unsplash.com/photo-1553909489-cd47e0907980?w=300&q=80"],
    ["Veg Grilled Sandwich",100,"https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=300&q=80"],
    ["Paneer Sandwich",110,"https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=300&q=80"],
    ["Masala Dosa",120,"https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80"],
    ["Plain Dosa",100,"https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80"],
    ["Idli Sambhar",80,"https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80"],
    ["Medu Vada",90,"https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80"],
    ["Uttapam",110,"https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=300&q=80"],
    ["Pav Bhaji",120,"https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=300&q=80"],
    ["Misal Pav",100,"https://images.unsplash.com/photo-1565363887715-aae7ec2e1f71?w=300&q=80"],
    ["Vada Pav",30,"https://images.unsplash.com/photo-1606923231677-7b83f3e7c14b?w=300&q=80"],
    ["Samosa",25,"https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?w=300&q=80"],
    ["Kachori",30,"https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?w=300&q=80"],
    ["Poha",50,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Upma",60,"https://images.unsplash.com/photo-1596797038530-2c107229654b?w=300&q=80"],
    ["Veg Cutlet",70,"https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?w=300&q=80"],
    ["French Fries",90,"https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=300&q=80"],
    ["Spring Roll",110,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Paneer Pakora",120,"https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?w=300&q=80"],
    ["Veg Pakora",80,"https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?w=300&q=80"],
    ["Bhindi Masala",120,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Tinda Masala",110,"https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=300&q=80"],
    ["Baingan Bharta",130,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Corn Palak",140,"https://images.unsplash.com/photo-1604579278613-87f2d22b9cb4?w=300&q=80"],
    ["Veg Makhanwala",160,"https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"],
    ["Veg Jaipuri",150,"https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300&q=80"],
    ["Veg Diwani Handi",170,"https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&q=80"],
    ["Veg Hydrabadi",180,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Veg Kofta",160,"https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=300&q=80"],
    ["Veg Angara",170,"https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=300&q=80"],
    ["Veg Mughlai",180,"https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"],
    ["Veg Tikka Masala",190,"https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"],
    ["Paneer Tikka",200,"https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Paneer Chilli",170,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Paneer Manchurian",180,"https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Veg Chowmein",130,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Veg Singapore Noodles",150,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Veg Thai Curry",200,"https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=300&q=80"],
    ["Veg Momos",100,"https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&q=80"],
    ["Paneer Momos",120,"https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&q=80"],
    ["Veg Frankie",90,"https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=300&q=80"],
    ["Paneer Frankie",110,"https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=300&q=80"],
    ["Veg Wrap",100,"https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"],
    ["Veg Thali",220,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Special Veg Thali",260,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Mini Veg Thali",180,"https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80"],
    ["Curd Rice",100,"https://images.unsplash.com/photo-1536304993881-ff86e0c9c01d?w=300&q=80"],
    ["Lemon Rice",90,"https://images.unsplash.com/photo-1516684732162-798a0062be99?w=300&q=80"]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Veg Items | Railway Food Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg,#e8f5e9,#f1f8e9);
            font-family: 'Poppins', sans-serif;
        }
        .navbar-veg {
            background: linear-gradient(90deg,#1b5e20,#2e7d32);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .food-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .food-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
        }
        .food-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .food-card:hover img {
            transform: scale(1.07);
        }
        .img-wrapper {
            overflow: hidden;
            position: relative;
        }
        .veg-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #fff;
            border: 2px solid #2e7d32;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 700;
            color: #2e7d32;
        }
        .card-body { padding: 14px 16px; }
        .item-name {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        .item-price {
            font-size: 16px;
            font-weight: 700;
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .btn-cart {
            background: linear-gradient(90deg,#2e7d32,#43a047);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 7px 0;
            font-size: 13px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-cart:hover {
            background: linear-gradient(90deg,#1b5e20,#2e7d32);
            color: #fff;
            transform: scale(1.03);
        }
        .btn-cart svg {
            width: 16px;
            height: 16px;
            fill: white;
        }
        .page-title {
            font-weight: 700;
            color: #1b5e20;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-veg navbar-dark px-4 py-3">
    <div class="d-flex align-items-center gap-3">
        <span class="navbar-brand fw-bold fs-5 mb-0">🥗 Veg Food Menu</span>
    </div>
    <div class="d-flex gap-2">
        <a href="menu.php" class="btn btn-outline-light btn-sm">⬅ Menu</a>
        <a href="cart.php" class="btn btn-warning btn-sm fw-bold">🛒 Cart</a>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <h2 class="text-center page-title mb-1">🌿 Veg Food Menu</h2>
    <p class="text-center text-muted mb-4">Fresh & delicious vegetarian meals delivered to your seat</p>

    <div class="row g-3">
        <?php foreach($items as $item){ ?>
        <div class="col-6 col-md-3">
            <div class="card food-card h-100">
                <div class="img-wrapper">
                    <img src="<?= $item[2] ?>"
                         alt="<?= htmlspecialchars($item[0]) ?>"
                         loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'">
                    <span class="veg-badge">🟢 VEG</span>
                </div>
                <div class="card-body text-center">
                    <div class="item-name"><?= $item[0] ?></div>
                    <div class="item-price">₹<?= $item[1] ?></div>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="item_name" value="<?= $item[0] ?>">
                        <input type="hidden" name="price" value="<?= $item[1] ?>">
                        <button type="submit" class="btn-cart">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
