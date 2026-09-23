<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = mysqli_real_escape_string($conn, $_SESSION['user']);
$q = mysqli_query($conn, "SELECT * FROM cart WHERE user_email='$user'");
$count = mysqli_num_rows($q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cart | Railway Food Order</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-dark: #0b0f19;
    --card-bg: rgba(17, 24, 39, 0.85);
    --border-color: rgba(255, 255, 255, 0.08);
    --accent-orange: #ff6b35;
    --accent-green: #10b981;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        radial-gradient(at 0% 0%, rgba(255, 107, 53, 0.12) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.12) 0px, transparent 50%);
    background-attachment: fixed;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #e5e7eb;
    min-height: 100vh;
}

.cart-container {
    max-width: 900px;
    margin: 40px auto;
}

.glass-card {
    background: var(--card-bg);
    backdrop-filter: blur(16px);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

.cart-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.cart-table th {
    color: #9ca3af;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    padding: 12px 16px;
    border: none;
}

.cart-row {
    background: rgba(31, 41, 55, 0.6);
    border-radius: 14px;
    transition: all 0.2s ease;
}

.cart-row:hover {
    background: rgba(31, 41, 55, 0.9);
    transform: translateY(-2px);
}

.cart-row td {
    padding: 16px;
    vertical-align: middle;
    border: none;
}

.cart-row td:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.cart-row td:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

.item-name {
    font-weight: 600;
    font-size: 1.05rem;
    color: #ffffff;
}

.price-tag {
    font-weight: 600;
    color: #f3f4f6;
}

.qty-control {
    display: inline-flex;
    align-items: center;
    background: rgba(17, 24, 39, 0.9);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    padding: 3px 8px;
}

.qty-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-weight: bold;
    font-size: 14px;
    transition: background 0.2s;
}

.qty-btn:hover {
    background: var(--accent-orange);
    color: #fff;
}

.qty-val {
    padding: 0 12px;
    font-weight: 700;
    color: #fff;
}

.btn-remove {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 7px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-remove:hover {
    background: #ef4444;
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
}

.summary-card {
    background: rgba(31, 41, 55, 0.5);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px 24px;
    margin-top: 24px;
}

.grand-total {
    font-size: 1.3rem;
    font-weight: 700;
    color: #10b981;
}

.btn-checkout {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    font-weight: 700;
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
    color: white;
}

.btn-continue {
    background: rgba(255, 255, 255, 0.06);
    color: #9ca3af;
    border: 1px solid var(--border-color);
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-continue:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
}

.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 15px;
    opacity: 0.8;
}
</style>
</head>

<body>

<div class="container cart-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold mb-0 text-white">🛒 My Cart</h2>
        <a href="menu.php" class="btn-continue">← Browse Menu</a>
    </div>

    <div class="glass-card">
        <?php if($count == 0){ ?>
            <div class="empty-state">
                <div class="empty-icon">🍽️</div>
                <h4 class="fw-bold text-white mb-2">Your Cart is Empty</h4>
                <p class="text-muted mb-4">Looks like you haven't added any delicious train meals yet.</p>
                <a href="menu.php" class="btn btn-checkout">Explore Delicious Menu</a>
            </div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 40%;">Item Details</th>
                            <th style="width: 15%;">Price</th>
                            <th style="width: 20%; text-align: center;">Quantity</th>
                            <th style="width: 10%;">Subtotal</th>
                            <th style="width: 10%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1; 
                        $grand = 0;
                        while($row = mysqli_fetch_assoc($q)){
                            $sum = $row['price'] * $row['qty'];
                            $grand += $sum;
                        ?>
                        <tr class="cart-row">
                            <td class="text-muted font-monospace"><?php echo $i++; ?></td>
                            <td>
                                <div class="item-name"><?php echo htmlspecialchars($row['item_name']); ?></div>
                            </td>
                            <td class="price-tag">₹ <?php echo number_format($row['price'], 2); ?></td>
                            <td text-align="center">
                                <div class="d-flex justify-content-center">
                                    <div class="qty-control">
                                        <a href="update_cart.php?id=<?php echo $row['id']; ?>&action=dec" class="qty-btn" title="Decrease">—</a>
                                        <span class="qty-val"><?php echo $row['qty']; ?></span>
                                        <a href="update_cart.php?id=<?php echo $row['id']; ?>&action=inc" class="qty-btn" title="Increase">+</a>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-white">₹ <?php echo number_format($sum, 2); ?></td>
                            <td class="text-end">
                                <a href="remove_cart.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Remove <?php echo htmlspecialchars(addslashes($row['item_name'])); ?> from cart?')" 
                                   class="btn-remove">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary & Checkout Section -->
            <div class="summary-card d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <span class="text-muted me-2">Grand Total:</span>
                    <span class="grand-total">₹ <?php echo number_format($grand, 2); ?></span>
                </div>
                <div class="d-flex gap-3 w-100 w-md-auto justify-content-end">
                    <a href="menu.php" class="btn-continue">Continue Shopping</a>
                    <form action="checkout.php" method="post" class="m-0">
                        <input type="hidden" name="amount" value="<?php echo $grand; ?>">
                        <button type="submit" class="btn-checkout">
                            Proceed to Checkout →
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
