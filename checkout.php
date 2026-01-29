<?php
// Start the session so we can access the shopping cart
session_start();

// Include the database connection (creates $pdo)
include_once 'includes/db_connect.php';

// Include product helper functions
require_once 'api/products.php';

// Stop the page if the database connection is missing
if (!isset($pdo)) die("❌ PDO connection not available.");

// Get the cart from the session (or an empty array if none exists)
$cart = $_SESSION['cart'] ?? [];

// ----------------------------
// Build order summary data
// ----------------------------

// Array to store processed cart items for display
$cartItems = [];

// Variable to store total order cost
$total = 0.0;

// Loop through each cart item stored in the session
foreach ($cart as $key => $item) {

    // Extract item data
    $productId = (int)($item['id'] ?? 0);
    $qty  = (int)($item['quantity'] ?? 0);
    $size = (string)($item['size'] ?? '');

    // Skip invalid items
    if ($productId <= 0 || $qty <= 0) continue;

    // Fetch product details from the database
    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    // Calculate line total for this item
    $lineTotal = ((float)$product['price']) * $qty;

    // Add to overall total
    $total += $lineTotal;

    // Store processed data for display
    $cartItems[] = [
        'name'      => $product['name'],
        'price'     => (float)$product['price'],
        'quantity'  => $qty,
        'size'      => $size,
        'lineTotal' => $lineTotal
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <!-- Main site stylesheet -->
  <link rel="stylesheet" href="css/style.css">
  <!-- Google Fonts used for styling -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Include site header (navigation, login, etc.) -->
<?php include 'includes/header.php'; ?>

<main class="cart-page">
  <h1>Checkout</h1>
  <p><a class="cart-back" href="cart.php">← Back to Cart</a></p>

  <!-- Check if cart is empty -->
  <?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>

    <!-- Order Summary Section -->
    <div class="checkout-box">
      <h2 class="checkout-heading">Order summary</h2>
      <ul class="checkout-summary-list">
        <?php foreach ($cartItems as $it): ?>
          <li class="checkout-summary-item">
            <div>
              <strong><?= htmlspecialchars($it['name']) ?></strong>
              <?php if (!empty($it['size'])): ?>
                <div><small>Size: <?= htmlspecialchars($it['size']) ?></small></div>
              <?php endif; ?>
              <div><small>Qty: <?= (int)$it['quantity'] ?></small></div>
            </div>
            <div>
              <strong>€<?= number_format($it['lineTotal'], 2) ?></strong>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="checkout-total">Total: €<?= number_format($total, 2) ?></p>
    </div>

    <!-- Checkout Form Section -->
    <div class="checkout-box">
      <h2 class="checkout-heading">Checkout</h2>
      <form id="checkout-form" class="checkout-form">
        <label>
          Name:
          <input name="customer_name" required>
        </label>

        <label>
          Email:
          <input name="email" type="email" required>
        </label>

        <label>
          Address:
          <input name="address_line1" required>
        </label>

        <label>
          City:
          <input name="city" required>
        </label>

        <label>
          Eircode:
          <input name="eircode" required>
        </label>

        <!-- Payment details (demo only – not real processing) -->
        <label>
          Card Name:
          <input name="cardname" required>
        </label>

        <label>
          Card Number:
          <input name="card" required>
        </label>

        <label>
          Expiry Date:
          <input name="expiry" required>
        </label>

        <button type="submit" id="place-order-btn" class="cart-btn">
          Place Order
        </button>
      </form>

      <p id="checkout-message" class="checkout-message"></p>
    </div>

  <?php endif; ?>
</main>

<!-- Include site footer -->
<?php include 'includes/footer.php'; ?>

<!-- JavaScript to handle form submission -->
<script src="/adamdevproject/js/checkout.js?v=1"></script>
</body>
</html>
