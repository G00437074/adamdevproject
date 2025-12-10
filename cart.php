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

$action = $_POST['action'] ?? $_GET['action'] ?? 'view';

switch ($action) {
    case 'add':
        $id  = (int)($_POST['id'] ?? 0);
        $qty = (int)($_POST['quantity'] ?? 1);

        $product = getProductById($pdo, $id);
        if ($product && $qty > 0) {
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
        }
        header("Location: cart.php");
        exit;

    case 'update':
        foreach ($_POST['quantities'] ?? [] as $id => $qty) {
            $id  = (int)$id;
            $qty = (int)$qty;

            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id] = $qty;
            }
        }
        header("Location: cart.php");
        exit;

    case 'remove':
        $id = (int)($_GET['id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        header("Location: cart.php");
        exit;

    case 'empty':
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit;
}

// Build cart display
$cartItems = [];
$total = 0;

foreach ($_SESSION['cart'] as $productId => $qty) {
    $product = getProductById($pdo, (int)$productId);
    if (!$product) continue;

    $lineTotal = $product['price'] * $qty;
    $total += $lineTotal;

    $cartItems[] = [
        'id' => $productId,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $qty,
        'lineTotal' => $lineTotal
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=20">
</head>
<body>

<?php include 'includes/header.php'; ?>

<h1>Your Cart</h1>
<p><a href="merch.php">← Back to Merch</a></p>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
<form action="cart.php" method="post">
    <input type="hidden" name="action" value="update">

    <table border="1" cellpadding="8">
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>

        <?php foreach ($cartItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>€<?= number_format($item['price'], 2) ?></td>
            <td>
                <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="0">
            </td>
            <td>€<?= number_format($item['lineTotal'], 2) ?></td>
            <td><a href="cart.php?action=remove&id=<?= $item['id'] ?>">✖</a></td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td colspan="2"><strong>€<?= number_format($total, 2) ?></strong></td>
        </tr>
    </table>

    <br>
    <button type="submit">Update Cart</button>
    <a href="cart.php?action=empty">Empty Cart</a>
</form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

</body>
</html>
