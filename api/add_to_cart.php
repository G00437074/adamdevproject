<?php
// api/add_to_cart.php
// This file handles adding products to the shopping cart using PHP sessions

// Start the session so we can store and access cart data
session_start();

// Tell the browser that this API will return JSON data
header('Content-Type: application/json');

// Include the database connection file (creates $pdo)
include_once __DIR__ . '/../includes/db_connect.php';

// Include product helper functions (e.g. getProductById)
require_once __DIR__ . '/products.php';

// Check that the database connection exists
if (!isset($pdo)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection not available'
    ]);
    exit; // Stop script execution if DB is missing
}

// Only allow POST requests (prevents accessing this file directly via browser)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// If the cart does not exist in the session, create an empty cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Read data sent from the form or AJAX request
// Use default values if data is missing
$id   = (int)($_POST['id'] ?? 0);        // Product ID
$qty  = (int)($_POST['quantity'] ?? 1);  // Quantity (default is 1)
$size = trim($_POST['size'] ?? '');      // Clothing size (if applicable)

// If a size was selected, remember it for future visits
if (!empty($size)) {
    setcookie(
        'preferred_size',
        $size,
        time() + (60 * 60 * 24 * 30), // 30 days
        '/'
    );
}

// Basic validation to ensure valid input
if ($id <= 0 || $qty <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid product or quantity'
    ]);
    exit;
}

// Retrieve the product from the database
// This also confirms the product ID actually exists
$product = getProductById($pdo, $id);
if (!$product) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Product not found'
    ]);
    exit;
}

// Convert product name to lowercase for easier checking
$nameLower = strtolower($product['name'] ?? '');

// Check if the product is clothing based on keywords in its name
$isClothing =
    strpos($nameLower, 'tee') !== false ||
    strpos($nameLower, 't-shirt') !== false ||
    strpos($nameLower, 'tshirt') !== false ||
    strpos($nameLower, 'hoodie') !== false;

// If the item is clothing, a size must be selected
if ($isClothing && $size === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please select a size'
    ]);
    exit;
}

// If the item is NOT clothing, ignore any size value
if (!$isClothing) {
    $size = '';
}

// Create a unique key for the cart item
// Example: "5_M" for product ID 5 with size M
// Example: "3" for a non-clothing product
$key = ($size !== '') ? ($id . '_' . $size) : (string)$id;

// If the item already exists in the cart, increase its quantity
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $qty;
}
// Otherwise, add the item as a new cart entry
else {
    $_SESSION['cart'][$key] = [
        'id'       => $id,
        'size'     => $size,
        'quantity' => $qty
    ];
}

// Calculate the total number of items in the cart
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}

// Send a successful response back to the frontend
echo json_encode([
    'status'    => 'ok',
    'message'   => 'Added to cart',
    'cartCount' => $cartCount
]);

exit; // End the script
