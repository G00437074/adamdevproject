<?php
// Start the session so we can access the cart stored in $_SESSION
session_start();

// Include database connection (creates $pdo)
include_once 'includes/db_connect.php';

// Include product helper functions
require_once 'api/products.php';

// Safety check to ensure database connection exists
if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

// Ensure the cart exists in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ----------------------------
// Build cart display data
// ----------------------------

// Array to hold processed cart items
$cartItems = [];

// Variable to store the total cart price
$total = 0.0;

// Loop through each item stored in the session cart
foreach ($_SESSION['cart'] as $key => $item) {

    // Extract cart item data
    $productId = (int)($item['id'] ?? 0);
    $qty       = (int)($item['quantity'] ?? 0);
    $size      = (string)($item['size'] ?? '');

    // Skip invalid items
    if ($productId <= 0 || $qty <= 0) continue;

    // Fetch product details from the database
    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    // Calculate total price for this item
    $lineTotal = ((float)$product['price']) * $qty;

    // Add to overall cart total
    $total += $lineTotal;

    // Store processed item for display
    $cartItems[] = [
        'key'       => $key,                 // Unique cart key (used by JS)
        'name'      => $product['name'],
        'price'     => (float)$product['price'],
        'size'      => $size,
        'quantity'  => $qty,
        'lineTotal' => $lineTotal
    ];
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Page title shown in browser tab -->
    <title>Your Cart</title>

    <!-- Main site stylesheet -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=22">
</head>

<body>

    <!-- Include site header (navigation, login, etc.) -->
    <?php include 'includes/header.php'; ?>

    <main class="cart-page">

        <!-- Page heading -->
        <h1>Your Cart</h1>

        <!-- Link back to merch page -->
        <p><a href="merch.php">← Back to Merch</a></p>

        <!-- If cart is empty, show message -->
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>

            <!-- Cart items table -->
            <table border="1" cellpadding="8" id="cart-table">

                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>

                <!-- Table body -->
                <tbody>
                    <?php foreach ($cartItems as $item): ?>

                        <!-- Each row represents one cart item -->
                        <!-- data-key is used by JavaScript to identify the item -->
                        <tr data-key="<?= htmlspecialchars($item['key']) ?>">

                            <!-- Product name and optional size -->
                            <td>
                                <?= htmlspecialchars($item['name']) ?>
                                <?php if (!empty($item['size'])): ?>
                                    <br>
                                    <small>Size: <?= htmlspecialchars($item['size']) ?></small>
                                <?php endif; ?>
                            </td>

                            <!-- Product price -->
                            <!-- data-price is used by JavaScript for calculations -->
                            <td class="price-cell"
                                data-price="<?= htmlspecialchars((string)$item['price']) ?>">
                                €<?= number_format($item['price'], 2) ?>
                            </td>

                            <!-- Quantity input -->
                            <!-- data-qty allows JS to detect changes -->
                            <td>
                                <input
                                    type="number"
                                    min="0"
                                    value="<?= (int)$item['quantity'] ?>"
                                    class="qty-input"
                                    data-qty>
                            </td>

                            <!-- Line total for this item -->
                            <!-- data-line-total used for recalculating totals -->
                            <td class="line-total-cell" data-line-total>
                                €<?= number_format($item['lineTotal'], 2) ?>
                            </td>

                            <!-- Remove item button -->
                            <!-- data-remove is used by JavaScript -->
                            <td>
                                <button type="button" data-remove>✖</button>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>

                <!-- Cart total footer -->
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td colspan="2">
                            <strong id="cart-total">
                                €<?= number_format($total, 2) ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <br>

            <!-- Cart action buttons -->
            <div class="cart-actions">

                <!-- Proceed to checkout -->
                <a href="checkout.php" id="checkout-btn">Checkout</a>

                <!-- Empty entire cart (handled by JS) -->
                <button type="button" id="empty-cart" class="cart-btn">
                    Empty Cart
                </button>
            </div>

        <?php endif; ?>
    </main>

    <!-- Include site footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript that handles cart updates -->
    <script src="/adamdevproject/js/cart.js?v=1"></script>
</body>

</html>
