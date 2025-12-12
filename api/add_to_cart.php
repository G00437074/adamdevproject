<?php
// api/add_to_cart.php
session_start();

header('Content-Type: application/json');

include_once __DIR__ . '/../includes/db_connect.php';   // $pdo
require_once __DIR__ . '/products.php';

if (!isset($pdo)) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection not available']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id   = (int)($_POST['id'] ?? 0);
$qty  = (int)($_POST['quantity'] ?? 1);
$size = trim($_POST['size'] ?? '');

if ($id <= 0 || $qty <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity']);
    exit;
}

if ($size === '') {
    echo json_encode(['status' => 'error', 'message' => 'Please select a size']);
    exit;
}

// Verify product exists
$product = getProductById($pdo, $id);
if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    exit;
}

// Unique key per product+size
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

// Total items (sum of quantities)
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}

echo json_encode([
    'status'    => 'ok',
    'message'   => 'Added to cart',
    'cartCount' => $cartCount
]);
exit;
