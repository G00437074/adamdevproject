<?php
// api/remove_from_cart.php
// This file removes a single item from the shopping cart

// Start the session so we can access the cart stored in $_SESSION
session_start();

// Tell the browser that this script returns JSON data
header('Content-Type: application/json');

// --------------------
// Only allow POST requests
// --------------------

// Prevent users from removing items by visiting the URL directly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid request'
    ]);
    exit;
}

// --------------------
// Read the cart item key
// --------------------

// Each cart item has a unique key (e.g. "5_M" or "3")
$key = (string)($_POST['key'] ?? '');

// If no key was provided, return an error
if ($key === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Missing key'
    ]);
    exit;
}

// --------------------
// Remove item from cart
// --------------------

// Check if the item exists in the cart
if (isset($_SESSION['cart'][$key])) {
    // Remove the item from the session cart
    unset($_SESSION['cart'][$key]);
}

// --------------------
// Send success response
// --------------------

// Even if the item didn't exist, return success
echo json_encode([
    'status' => 'ok'
]);

exit; // End the script
