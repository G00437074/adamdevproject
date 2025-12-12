<?php
// api/add_to_cart.php
session_start();

header('Content-Type: application/json');

include_once __DIR__ . '/../includes/db_connect.php'; // $pdo
require_once __DIR__ . '/products.php';

if (!isset($pdo)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection not available'
    ]);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Read inputs
$id   = (int)($_POST['id'] ?? 0);
$qty  = (int)($_POST['quantity'] ?? 1);
$size = trim($_POST['size'] ?? '');

// Basic validation
if ($id <= 0 || $qty <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid product or quantity'
    ]);
    exit;
}

// Fetch product (ensures valid product ID)
$product = getProductById($pdo, $id);
if (!$product) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Product not found'
    ]);
    exit;
}

// Detect clothing by product name
$nameLower = strtolower($product['name'] ?? '');
$isClothing =
    strpos($nameLower, 'tee') !== false ||
    strpos($nameLower, 't-shirt') !== false ||
    strpos($nameLower, 'tshirt') !== false ||
    strpos($nameLower, 'hoodie') !== false;

// Validate size only for clothing
if ($isClothing && $size === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please select a size'
    ]);
    exit;
}

// Force empty size for non-clothing items
if (!$isClothing) {
    $size = '';
}

// Build unique cart key
$key = ($size !== '') ? ($id . '_' . $size) : (string)$id;

// Add or update cart item
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $qty;
} else {
    $_SESSION['cart'][$key] = [
        'id'       => $id,
        'size'     => $size,
        'quantity' => $qty
    ];
}

// Calculate cart count (sum of quantities)
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}

// Success response
echo json_encode([
    'status'    => 'ok',
    'message'   => 'Added to cart',
    'cartCount' => $cartCount
]);
exit;
