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
  <!-- Page title -->
  <title>Checkout</title>

  <!-- Main site stylesheet -->
  <link rel="stylesheet" href="/adamdevproject/css/style.css?v=24">

  <!-- Google Fonts used for styling -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Include site header (navigation, login, etc.) -->
<?php include 'includes/header.php'; ?>

<main class="cart-page">

  <!-- Page heading -->
  <h1>Checkout</h1>

  <!-- Link back to cart page -->
  <p><a class="cart-back" href="cart.php">← Back to Cart</a></p>

  <!-- If cart is empty, show message -->
  <?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>

    <!-- ----------------------------
         Order Summary Section
         ---------------------------- -->
    <div style="background:#f7f1cf;border-radius:22px;padding:1.25rem 1.5rem;box-shadow:0 8px 18px rgba(0,0,0,0.12);">

      <!-- Section title -->
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 0.75rem;">
        Order summary
      </h2>

      <!-- List of items in the order -->
      <ul style="list-style:none;padding:0;margin:0;">

        <?php foreach ($cartItems as $it): ?>
          <li style="display:flex;justify-content:space-between;gap:1rem;padding:0.5rem 0;border-bottom:1px solid rgba(0,0,0,0.08);">

            <!-- Product details -->
            <div>
              <strong><?= htmlspecialchars($it['name']) ?></strong>

              <!-- Show size if applicable -->
              <?php if (!empty($it['size'])): ?>
                <div><small>Size: <?= htmlspecialchars($it['size']) ?></small></div>
              <?php endif; ?>

              <!-- Quantity -->
              <div><small>Qty: <?= (int)$it['quantity'] ?></small></div>
            </div>

            <!-- Line total -->
            <div>
              <strong>€<?= number_format($it['lineTotal'], 2) ?></strong>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Overall total -->
      <p style="text-align:right;font-weight:700;margin-top:0.75rem;">
        Total: €<?= number_format($total, 2) ?>
      </p>
    </div>

    <br>

    <!-- ----------------------------
         Checkout Form Section
         ---------------------------- -->
    <div style="background:#f7f1cf;border-radius:22px;padding:1.25rem 1.5rem;box-shadow:0 8px 18px rgba(0,0,0,0.12);">

      <!-- Section title -->
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 0.75rem;">
        Checkout
      </h2>

      <!-- Checkout form (submitted using JavaScript) -->
      <form id="checkout-form">

        <!-- Customer details -->
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

        <!-- Payment details (demo only – not real processing) -->
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

        <!-- Submit order -->
        <button type="submit" id="place-order-btn" class="cart-btn">
          Place Order
        </button>
      </form>

      <!-- Message shown after submitting order -->
      <p id="checkout-message" style="margin-top:0.75rem;"></p>
    </div>

  <?php endif; ?>
</main>

<!-- Include site footer -->
<?php include 'includes/footer.php'; ?>

<!-- JavaScript that submits the checkout form using fetch() -->
<script src="/adamdevproject/js/checkout.js?v=1"></script>
</body>
</html>
