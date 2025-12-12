<?php
// api/update_cart.php
// This file updates the quantity of an item in the shopping cart

// Start the session so we can access and modify the cart
session_start();

// Tell the browser that this API returns JSON data
header('Content-Type: application/json');

// --------------------
// Only allow POST requests
// --------------------

// Prevent users from updating the cart by visiting the URL directly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid request'
    ]);
    exit;
}

// --------------------
// Ensure the cart exists
// --------------------

// If the cart is not already in the session, create an empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --------------------
// Read and validate input data
// --------------------

// Unique key for the cart item (e.g. "5_M" or "3")
$key = (string)($_POST['key'] ?? '');

// New quantity for the cart item
$qty = (int)($_POST['quantity'] ?? -1);

// Check for missing or invalid data
if ($key === '' || $qty < 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid data'
    ]);
    exit;
}

// --------------------
// Check item exists in cart
// --------------------

// If the item is not in the cart, return an error
if (!isset($_SESSION['cart'][$key])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Item not found'
    ]);
    exit;
}

// --------------------
// Update or remove the item
// --------------------

// If quantity is set to 0, remo
