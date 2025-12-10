<?php
session_start();
include_once 'includes/db_connect.php';
require_once 'api/products.php';

// ensure cart array exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];   // [ productId => quantity ]
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'view';

switch ($action) {
    case 'add':
        $id  = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        $product = getProductById($conn, $id);
        if ($product && $qty > 0) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] += $qty;
            } else {
                $_SESSION['cart'][$id] = $qty;
            }
        }
        header('Location: cart.php');
        exit;

    case 'update':
        if (!empty($_POST['quantities']) && is_array($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $id => $qty) {
                $id  = (int)$id;
                $qty = (int)$qty;
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id] = $qty;
                }
            }
        }
        header('Location: cart.php');
        exit;

    case 'remove':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        unset($_SESSION['cart'][$id]);
        header('Location: cart.php');
        exit;

    case 'empty':
        $_SESSION['cart'] = [];
        header('Location: cart.php');
        exit;

    case 'view':
    default:
        break;
}

// Build cart info
$cartItems = [];
$total = 0.0;

foreach ($_SESSION['cart'] as $productId => $qty) {
    $product = getProductById($conn, (int)$productId);
    if (!$product) {
        continue;
    }

    $lineTotal = $product['price'] * $qty;
    $total += $lineTotal;

    $cartItems[] = [
        'id'        => $productId,
        'name'      => $product['name'],
        'price'     => $product['price'],
        'quantity'  => $qty,
        'lineTotal' => $lineTotal,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=20">
    <style>
        /* You can move this into style.css later */
        table {
            border-collapse: collapse;
            width: 70%;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
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

        <table>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Line Total</th>
                <th>Remove</th>
            </tr>

            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>€<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <input
                            type="number"
                            name="quantities[<?php echo (int)$item['id']; ?>]"
                            value="<?php echo (int)$item['quantity']; ?>"
                            min="0"
                        >
                    </td>
                    <td>€<?php echo number_format($item['lineTotal'], 2); ?></td>
                    <td>
                        <a href="cart.php?action=remove&id=<?php echo (int)$item['id']; ?>">✖</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr class="total-row">
                <td colspan="3">Total</td>
                <td colspan="2">€<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>

        <p>
            <button type="submit">Update Cart</button>
            <a href="cart.php?action=empty">Empty Cart</a>
        </p>
    </form>

    <p>
        <!-- placeholder for real checkout later -->
        <button disabled>Checkout (coming soon)</button>
    </p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

</body>
</html>
