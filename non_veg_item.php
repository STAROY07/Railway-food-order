<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$nonveg_items = [
    ["Chicken Biryani",   220, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Mutton Biryani",    280, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Egg Biryani",       160, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Chicken Fried Rice",180, "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Egg Fried Rice",    150, "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Butter Chicken",    240, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Chicken Tikka",     260, "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Chicken Kebab",     240, "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Tandoori Chicken",  280, "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Chicken Curry",     200, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Mutton Curry",      260, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Fish Curry",        220, "https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=300&q=80"],
    ["Fish Fry",          200, "https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=300&q=80"],
    ["Prawn Curry",       280, "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=300&q=80"],
    ["Prawn Fry",         260, "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=300&q=80"],
    ["Chicken Lollipop",  200, "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken 65",        190, "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken Manchurian",180, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Egg Curry",         140, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Egg Masala",        150, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Chicken Roll",      160, "https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Egg Roll",          120, "https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Chicken Burger",    180, "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80"],
    ["Chicken Pizza",     220, "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=300&q=80"],
    ["Chicken Sandwich",  160, "https://images.unsplash.com/photo-1553909489-cd47e0907980?w=300&q=80"],
    ["Mutton Korma",      270, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Fish Tikka",        230, "https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=300&q=80"],
    ["Prawn Masala",      280, "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=300&q=80"],
    ["Chicken Shawarma",  160, "https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Chicken Nuggets",   150, "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken Momos",     130, "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&q=80"],
    ["Steamed Chicken Momos",130,"https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&q=80"],
    ["Fried Chicken Momos",140,"https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&q=80"],
    ["Chicken Chowmein",  160, "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Egg Chowmein",      140, "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Chicken Pasta",     200, "https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=300&q=80"],
    ["Chicken Lasagna",   220, "https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=300&q=80"],
    ["Chicken Steak",     300, "https://images.unsplash.com/photo-1558030006-450675393462?w=300&q=80"],
    ["Grilled Chicken",   260, "https://images.unsplash.com/photo-1544025162-d76694265947?w=300&q=80"],
    ["Roast Chicken",     280, "https://images.unsplash.com/photo-1544025162-d76694265947?w=300&q=80"],
    ["Chicken Frankie",   140, "https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Egg Frankie",       120, "https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Chicken Wrap",      150, "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"],
    ["Egg Wrap",          130, "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"],
    ["Chicken Cutlet",    160, "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken Popcorn",   150, "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken Wings",     220, "https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=300&q=80"],
    ["Chicken Soup",      120, "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300&q=80"],
    ["Mutton Soup",       150, "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300&q=80"],
    ["Fish Soup",         140, "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300&q=80"],
    ["Chicken Schezwan Rice",190,"https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Egg Schezwan Rice", 160, "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Chicken Triple Rice",200,"https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Egg Triple Rice",   170, "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"],
    ["Chicken Hakka Noodles",170,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Egg Hakka Noodles", 150, "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Chicken Singapore Noodles",180,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Egg Singapore Noodles",160,"https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"],
    ["Chicken Thai Curry",230,"https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=300&q=80"],
    ["Chicken Malaysian Curry",240,"https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=300&q=80"],
    ["Chicken Kolhapuri", 220, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Mutton Rogan Josh", 290, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Chicken Hyderabadi",230, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Chicken Afghani",   250, "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Chicken Seekh Kebab",240,"https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Mutton Seekh Kebab",270,"https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&q=80"],
    ["Fish Fingers",      180, "https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=300&q=80"],
    ["Fish Burger",       190, "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80"],
    ["Chicken Cheese Balls",160,"https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&q=80"],
    ["Chicken Stuffed Paratha",180,"https://images.unsplash.com/photo-1628191081676-8f89f8eb8f34?w=300&q=80"],
    ["Egg Bhurji",        130, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Egg Omelette",      110, "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Boiled Eggs Plate", 80,  "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80"],
    ["Chicken Salad",     160, "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"],
    ["Chicken Caesar Salad",180,"https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"],
    ["Grilled Fish",      220, "https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=300&q=80"],
    ["Fish Biryani",      240, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Prawn Biryani",     260, "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"],
    ["Chicken Pulao",     200, "https://images.unsplash.com/photo-1596797038530-2c107229654b?w=300&q=80"],
    ["Egg Pulao",         160, "https://images.unsplash.com/photo-1596797038530-2c107229654b?w=300&q=80"],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Non-Veg Menu | Railway Food Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg,#ffecd2,#fcb69f);
            font-family: 'Poppins', sans-serif;
        }
        .navbar-nonveg {
            background: linear-gradient(90deg,#7b1a1a,#c0392b);
            box-shadow: 0 4px 15px rgba(0,0,0,0.25);
        }
        .food-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }
        .food-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.22);
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
        .nonveg-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #fff;
            border: 2px solid #c0392b;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 700;
            color: #c0392b;
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
            color: #c0392b;
            margin-bottom: 10px;
        }
        .btn-cart {
            background: linear-gradient(90deg,#c0392b,#e74c3c);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 7px 0;
            font-size: 13px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-cart:hover {
            background: linear-gradient(90deg,#7b1a1a,#c0392b);
            color: #fff;
            transform: scale(1.03);
        }
        .btn-cart svg {
            width: 16px;
            height: 16px;
            fill: white;
        }
        .page-title { font-weight: 700; color: #7b1a1a; }
    </style>
</head>

<body>

<nav class="navbar navbar-nonveg navbar-dark px-4 py-3 d-flex justify-content-between">
    <span class="navbar-brand fw-bold fs-5 mb-0">🍗 Non-Veg Food Menu</span>
    <div class="d-flex gap-2">
        <a href="menu.php" class="btn btn-outline-light btn-sm">⬅ Menu</a>
        <a href="cart.php" class="btn btn-warning btn-sm fw-bold">🛒 Cart</a>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <h2 class="text-center page-title mb-1">🍖 Non-Veg Food Menu</h2>
    <p class="text-center text-muted mb-4">Savory non-vegetarian delights delivered to your train seat</p>

    <div class="row g-3">
        <?php foreach($nonveg_items as $item){ ?>
        <div class="col-6 col-md-3">
            <div class="card food-card h-100">
                <div class="img-wrapper">
                    <img src="<?= $item[2] ?>"
                         alt="<?= htmlspecialchars($item[0]) ?>"
                         loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&q=80'">
                    <span class="nonveg-badge">🔴 NON-VEG</span>
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

    <div class="text-center mt-5">
        <a href="menu.php" class="btn btn-secondary me-2">⬅ Back to Menu</a>
        <a href="cart.php" class="btn btn-success">🛒 Go to Cart</a>
    </div>
</div>

</body>
</html>
