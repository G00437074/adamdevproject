<?php
session_start();

include_once 'includes/db_connect.php';  // provides $pdo
require_once 'api/products.php';

if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'view';

switch ($action) {
    case 'add': {
        $id   = (int)($_POST['id'] ?? 0);
        $qty  = (int)($_POST['quantity'] ?? 1);
        $size = trim($_POST['size'] ?? '');

        if ($id > 0 && $qty > 0 && $size !== '') {
            // Unique key per product + size
            $key = $id . '_' . $size;

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$key] = [
                    'id'       => $id,
                    'size'     => $size,
                    'quantity' => $qty
                ];
            }
        }

        header("Location: cart.php");
        exit;
    }

    case 'update': {
        // quantities[] now uses the cart key, not just product id
        foreach (($_POST['quantities'] ?? []) as $key => $qty) {
            $qty = (int)$qty;

            if ($qty <= 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                if (isset($_SESSION['cart'][$key])) {
                    $_SESSION['cart'][$key]['quantity'] = $qty;
                }
            }
        }

        header("Location: cart.php");
        exit;
    }

    case 'remove': {
        $key = $_GET['key'] ?? '';
        if ($key !== '') {
            unset($_SESSION['cart'][$key]);
        }
        header("Location: cart.php");
        exit;
    }

    case 'empty': {
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit;
    }

    case 'view':
    default:
        break;
}

// Build cart display
$cartItems = [];
$total = 0.0;

foreach ($_SESSION['cart'] as $key => $item) {
    $productId = (int)($item['id'] ?? 0);
    $qty       = (int)($item['quantity'] ?? 0);
    $size      = (string)($item['size'] ?? '');

    if ($productId <= 0 || $qty <= 0) {
        continue;
    }

    $product = getProductById($pdo, $productId);
    if (!$product) {
        continue;
    }

    $lineTotal = ((float)$product['price']) * $qty;
    $total += $lineTotal;

    $cartItems[] = [
        'key'       => $key,                 // IMPORTANT
        'id'        => $productId,
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
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=21">
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
            <td>
                <?= htmlspecialchars($item['name']) ?>
                <?php if (!empty($item['size'])): ?>
                    <br><small>Size: <?= htmlspecialchars($item['size']) ?></small>
                <?php endif; ?>
            </td>
            <td>€<?= number_format($item['price'], 2) ?></td>
            <td>
                <input
                    type="number"
                    name="quantities[<?= htmlspecialchars($item['key']) ?>]"
                    value="<?= (int)$item['quantity'] ?>"
                    min="0"
                >
            </td>
            <td>€<?= number_format($item['lineTotal'], 2) ?></td>
            <td>
                <a href="cart.php?action=remove&key=<?= urlencode($item['key']) ?>">✖</a>
            </td>
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
