<?php
session_start();

include_once 'includes/db_connect.php';  // $pdo
require_once 'api/products.php';

if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Build cart display
$cartItems = [];
$total = 0.0;

foreach ($_SESSION['cart'] as $key => $item) {
    $productId = (int)($item['id'] ?? 0);
    $qty       = (int)($item['quantity'] ?? 0);
    $size      = (string)($item['size'] ?? '');

    if ($productId <= 0 || $qty <= 0) continue;

    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    $lineTotal = ((float)$product['price']) * $qty;
    $total += $lineTotal;

    $cartItems[] = [
        'key'       => $key,
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=22">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <h1>Your Cart</h1>
    <p><a href="merch.php">← Back to Merch</a></p>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <table border="1" cellpadding="8" id="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr data-key="<?= htmlspecialchars($item['key']) ?>">
                        <td>
                            <?= htmlspecialchars($item['name']) ?>
                            <?php if (!empty($item['size'])): ?>
                                <br><small>Size: <?= htmlspecialchars($item['size']) ?></small>
                            <?php endif; ?>
                        </td>


                        <td class="price-cell" data-price="<?= htmlspecialchars((string)$item['price']) ?>">
                            €<?= number_format($item['price'], 2) ?>
                        </td>

                        <td>
                            <input
                                type="number"
                                min="0"
                                value="<?= (int)$item['quantity'] ?>"
                                class="qty-input"
                                data-qty>
                        </td>

                        <td class="line-total-cell" data-line-total>
                            €<?= number_format($item['lineTotal'], 2) ?>
                        </td>

                        <td>
                            <button type="button" data-remove>✖</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><strong id="cart-total">€<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <br>
        <button type="button" id="empty-cart">Empty Cart</button>

    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>

    <script src="/adamdevproject/js/cart.js?v=1"></script>
</body>

</html>