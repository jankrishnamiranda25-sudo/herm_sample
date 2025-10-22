<?php
session_start();
include 'food.php';
include 'db_connect.php'; // Database connection

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total and prepare order items
$total = 0;
$orderItems = [];
foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($foods[$id])) {
        $itemTotal = $foods[$id]['price'] * $qty;
        $total += $itemTotal;
        $orderItems[] = [
            'name' => $foods[$id]['name'],
            'qty' => $qty,
            'price' => $foods[$id]['price'],
            'subtotal' => $itemTotal
        ];
    }
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $payment_method = $_POST['payment_method'] ?? 'unknown';

    // Simulate successful payment
    $paid = true;

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (total, payment_method, payment_status) VALUES (?, ?, 'Paid')");
    $stmt->bind_param("ds", $total, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, food_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $item_stmt->bind_param("isidd", $order_id, $item['name'], $item['qty'], $item['price'], $item['subtotal']);
        $item_stmt->execute();
    }

    // Clear cart
    $_SESSION['cart'] = [];
}

// Calculate total
$total = 0;
$orderItems = [];
foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($foods[$id])) {
        $itemTotal = $foods[$id]['price'] * $qty;
        $total += $itemTotal;
        $orderItems[] = [
            'name' => $foods[$id]['name'],
            'qty' => $qty,
            'price' => $foods[$id]['price'],
            'subtotal' => $itemTotal
        ];
    }
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    // Here you would integrate with a payment gateway (e.g., Stripe, PayPal)
    // For demo: Simulate successful payment
    $paid = true;
    // Clear cart
    $_SESSION['cart'] = [];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment - Online Food Store</title>
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f7cac9 0%, #92a8d1 100%);
        }
        .main-content-center {
    flex: 1;
    display: flex;
    width: 100vw;
    justify-content: center;
    align-items: center;
}

.checkout-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100vw;
    height: 100%;
}

.summary-card {
    width: 600px;
    max-width: 90vw;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 28px 0 rgba(0,0,0,0.17);
    padding: 48px 48px 32px 48px;
    animation: pop-in 0.7s cubic-bezier(.42,0,.58,1.0);
    transition: box-shadow 0.22s, transform 0.22s;
    position: relative;
}
.summary-card:hover {
    box-shadow: 0 8px 40px 0 rgba(229,46,113,0.19), 0 2px 20px 0 rgba(0,0,0,0.08);
    transform: scale(1.025);
}

@keyframes pop-in {
    from { transform: scale(0.92) translateY(30px); opacity: 0; }
    to { transform: scale(1) translateY(0); opacity: 1; }
}

/* Example close button for extra interactivity */
.summary-close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    background: #f6f6f6;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    cursor: pointer;
    font-size: 1.2em;
    color: #e52e71;
    transition: background .18s, color .18s;
}
.summary-close-btn:hover {
    background: #ffe0ef;
    color: #ff8a00;
}
        #summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 1.08em;
        }
        #summary-table th, #summary-table td {
            padding: 11px 10px;
            text-align: left;
            transition: background 0.18s;
        }
        #summary-table th {
            background: #f6f6f6;
        }
        #summary-table tr.data-row:hover {
            background: #f2faff;
            cursor: pointer;
            transition: background 0.18s;
        }
        #summary-table tr:last-child td {
            border-top: 2px solid #eee;
        }
        .pay-btn {
            width: 100%;
            padding: 17px 0;
            font-size: 1.2em;
            margin-top: 18px;
            border-radius: 8px;
            background: linear-gradient(90deg, #ff8a00 0%, #e52e71 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            box-shadow: 0 2px 8px 0 rgba(229,46,113,0.10);
            cursor: pointer;
            transition: background 0.25s, transform 0.15s;
        }
        .pay-btn:hover, .pay-btn:focus {
            background: linear-gradient(90deg, #e52e71 0%, #ff8a00 100%);
            transform: translateY(-2px) scale(1.02);
            outline: none;
        }
        .btn {
            background: #ffc107;
            color: #222;
            border: none;
            padding: 11px 18px;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }
        .btn:hover {
            background: #ff9800;
            color: #fff;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 36px 32px;
            box-shadow: 0 2px 20px 0 rgba(0,0,0,0.10);
            margin: 30px auto;
            width: 380px;
        }
        @media (max-width: 600px) {
            .checkout-container, .main-content-center {
                align-items: flex-start;
            }
            .summary-card, .card {
                width: 98vw;
                padding: 12px 2vw 22px 2vw;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.location='cart.php'" class="btn" style="margin-bottom:16px;position:absolute;left:16px;top:20px;">← Back to Cart</button>
    <div class="main-content-center">
    <?php if (isset($paid) && $paid): ?>
        <div class="card" style="text-align:center;">
            <h2>Thank you for your payment!</h2>
            <p>Your order has been placed successfully.</p>
            <button class="btn" onclick="window.location='index.php'">Order Again</button>
        </div>
    <?php else: ?>
        <div class="checkout-container">
            <div class="summary-card">
                <h2 style="margin-top:0;">Order Summary</h2>
                <table id="summary-table">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($orderItems as $item): ?>
                    <tr class="data-row" title="Click for fun!">
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    </tr>
                </table>
                <form method="post">
                    <!-- You can add payment fields here (name, card, etc.) -->
                    <button type="submit" name="pay" class="btn pay-btn">Pay Now</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    </div>
    <script>
    // Add click animation for rows
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#summary-table tr.data-row').forEach(row => {
        row.addEventListener('mousedown', () => {
            row.style.transform = 'scale(0.98)';
            row.style.background = '#ffe0b2';
        });
        row.addEventListener('mouseup', () => {
            row.style.transform = '';
            row.style.background = '';
        });
        row.addEventListener('mouseleave', () => {
            row.style.transform = '';
            row.style.background = '';
        });
    });

    // Payment method logic
    const paymentSelect = document.getElementById('payment-method');
    const creditCardFields = document.getElementById('credit-card-fields');

    paymentSelect.addEventListener('change', function() {
        if (this.value === 'credit_card') {
            creditCardFields.style.display = 'block';
        } else {
            creditCardFields.style.display = 'none';
        }
    });
});

document.getElementById("payment-method").addEventListener("change", function() {
  const cardFields = document.getElementById("credit-card-fields");
  cardFields.style.display = (this.value === "credit_card") ? "block" : "none";
});



    <form method="post" id="payment-form">
    <label for="payment-method" style="font-weight:bold;">Select Payment Method:</label><br>
    <select id="payment-method" name="payment_method" required style="width:100%;padding:10px;border-radius:8px;margin:10px 0 20px 0;border:1px solid #ccc;font-size:1em;">
        <option value="" disabled selected>-- Choose Payment Method --</option>
        <option value="credit_card">Credit / Debit Card</option>
        <option value="gcash">GCash</option>
        <option value="cod">Cash on Delivery</option>
    </select>

    <!-- Credit Card Fields -->
    <div id="credit-card-fields" style="display:none;animation:fadeIn 0.4s ease;">
        <input type="text" name="card_name" placeholder="Cardholder Name" style="width:100%;padding:10px;margin-bottom:10px;border-radius:6px;border:1px solid #ccc;">
        <input type="text" name="card_number" placeholder="Card Number" maxlength="16" style="width:100%;padding:10px;margin-bottom:10px;border-radius:6px;border:1px solid #ccc;">
        <div style="display:flex;gap:10px;">
            <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" style="flex:1;padding:10px;border-radius:6px;border:1px solid #ccc;">
            <input type="text" name="cvv" placeholder="CVV" maxlength="3" style="flex:1;padding:10px;border-radius:6px;border:1px solid #ccc;">
        </div>
    </div>

    <button type="submit" name="pay" class="btn pay-btn fancy-pay">💳 Pay Now</button>
</form>


    </script>
</body>
</html>