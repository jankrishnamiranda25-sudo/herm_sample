<?php
include 'food.php';
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart logic via POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        if (isset($foods[$id])) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]++;
            } else {
                $_SESSION['cart'][$id] = 1;
            }
        }
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
        exit;
    }
    // Remove item via AJAX
    if (isset($_POST['remove'])) {
        $id = (int)$_POST['remove'];
        unset($_SESSION['cart'][$id]);
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
        exit;
    }
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Online Food Store</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-actions {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
    <button onclick="window.location='index.php'" class="btn" style="margin-bottom:16px;">← Continue Shopping</button>
    <h1>Your Shopping Cart</h1>
    <div id="cart-content">
    <?php if (empty($_SESSION['cart'])): ?>
    <div class="empty-cart-card">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="12" fill="#ffe0b2"/>
            <path d="M7 7h10l-1 9H8L7 7zm2 10a2 2 0 1 0 4 0" stroke="#ff9800" stroke-width="2" fill="none"/>
            <circle cx="9.5" cy="19" r="1.5" fill="#ff9800"/>
            <circle cx="14.5" cy="19" r="1.5" fill="#ff9800"/>
        </svg>
        <h2>Your cart is empty!</h2>
        <p>Looks like you haven't added any delicious food yet.</p>
        <button class="btn" onclick="window.location='index.php'">Browse Food Menu</button>
    </div>
    <?php else: ?>
        <table id="cart-table">
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Remove</th>
            </tr>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
                <tr data-id="<?= $id ?>">
                    <td><?= $foods[$id]['name'] ?></td>
                    <td><?= $qty ?></td>
                    <td>$<?= number_format($foods[$id]['price'] * $qty, 2) ?></td>
                    <td>
                        <button class="btn remove-btn" data-id="<?= $id ?>">Remove</button>
                    </td>
                </tr>
                <?php $total += $foods[$id]['price'] * $qty; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="2"><strong>Total:</strong></td>
                <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
        <!-- PAYMENT BUTTON START -->
        <div class="cart-actions">
            <form action="payment.php" method="post">
                <button type="submit" class="btn pay-btn" <?= $total == 0 ? 'disabled' : '' ?>>Proceed to Payment</button>
            </form>
        </div>
        <!-- PAYMENT BUTTON END -->
    <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>