<?php
include 'food.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$food = $foods[$id] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $food ? $food['name'] : 'Product Not Found' ?> - Online Food Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.php">← Back to Home</a>
    <?php if ($food): ?>
        <div class="food-detail">
            <img src="<?= $food['img'] ?>" alt="<?= $food['name'] ?>">
            <h1><?= $food['name'] ?></h1>
            <p><?= $food['desc'] ?></p>
            <p class="price">$<?= number_format($food['price'], 2) ?></p>
            <button class="btn add-to-cart" data-id="<?= $food['id'] ?>">Add to Cart</button>
        </div>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
    <script src="script.js"></script>
</body>
</html>