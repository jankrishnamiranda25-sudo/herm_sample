<?php include 'food.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Food Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to the Online Food Store!</h1>
    <a href="cart.php" class="cart-link">🛒 View Cart</a>
    <div class="foods-container">
        <?php foreach ($foods as $food): ?>
            <div class="food-card">
                <img src="<?= $food['img'] ?>" alt="<?= $food['name'] ?>">
                <h2><?= $food['name'] ?></h2>
                <p><?= $food['desc'] ?></p>
                <p class="price">$<?= number_format($food['price'], 2) ?></p>
                <a href="product.php?id=<?= $food['id'] ?>" class="btn">View Details</a>
                <button class="btn add-to-cart" data-id="<?= $food['id'] ?>">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>