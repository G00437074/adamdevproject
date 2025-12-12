<?php
session_start();
include_once 'includes/db_connect.php';
require_once 'api/products.php';

if (!isset($pdo)) die("❌ PDO connection not available.");

$cart = $_SESSION['cart'] ?? [];

$cartItems = [];
$total = 0.0;

foreach ($cart as $key => $item) {
    $productId = (int)($item['id'] ?? 0);
    $qty = (int)($item['quantity'] ?? 0);
    $size = (string)($item['size'] ?? '');

    if ($productId <= 0 || $qty <= 0) continue;

    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    $lineTotal = ((float)$product['price']) * $qty;
    $total += $lineTotal;

    $cartItems[] = [
        'name' => $product['name'],
        'price' => (float)$product['price'],
        'quantity' => $qty,
        'size' => $size,
        'lineTotal' => $lineTotal
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <link rel="stylesheet" href="/adamdevproject/css/style.css?v=24">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="cart-page">
  <h1>Checkout</h1>
  <p><a class="cart-back" href="cart.php">← Back to Cart</a></p>

  <?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>

    <div style="background:#f7f1cf;border-radius:22px;padding:1.25rem 1.5rem;box-shadow:0 8px 18px rgba(0,0,0,0.12);">
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 0.75rem;">Order summary</h2>

      <ul style="list-style:none;padding:0;margin:0;">
        <?php foreach ($cartItems as $it): ?>
          <li style="display:flex;justify-content:space-between;gap:1rem;padding:0.5rem 0;border-bottom:1px solid rgba(0,0,0,0.08);">
            <div>
              <strong><?= htmlspecialchars($it['name']) ?></strong>
              <?php if (!empty($it['size'])): ?>
                <div><small>Size: <?= htmlspecialchars($it['size']) ?></small></div>
              <?php endif; ?>
              <div><small>Qty: <?= (int)$it['quantity'] ?></small></div>
            </div>
            <div><strong>€<?= number_format($it['lineTotal'], 2) ?></strong></div>
          </li>
        <?php endforeach; ?>
      </ul>

      <p style="text-align:right;font-weight:700;margin-top:0.75rem;">
        Total: €<?= number_format($total, 2) ?>
      </p>
    </div>

    <br>

    <div style="background:#f7f1cf;border-radius:22px;padding:1.25rem 1.5rem;box-shadow:0 8px 18px rgba(0,0,0,0.12);">
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 0.75rem;">Checkout</h2>

      <form id="checkout-form">
        <label>
          Name:
          <input name="customer_name" required>
        </label><br><br>

        <label>
          Email:
          <input name="email" type="email" required>
        </label><br><br>

        <label>
          Address:
          <input name="address_line1" required>
        </label><br><br>

        <label>
          City:
          <input name="city" required>
        </label><br><br>

        <label>
          Eircode:
          <input name="eircode" required>
        </label><br><br>

        <label>
          Card Name:
          <input name="cardname" required>
        </label><br><br>

        <label>
          Card Number:
          <input name="card" required>
        </label><br><br>

        <label>
          Expiry Date:
          <input name="expiry" required>
        </label><br><br>

        <button type="submit" id="place-order-btn" class="cart-btn">Place Order</button>
      </form>

      <p id="checkout-message" style="margin-top:0.75rem;"></p>
    </div>

  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>

<script src="/adamdevproject/js/checkout.js?v=1"></script>
</body>
</html>
