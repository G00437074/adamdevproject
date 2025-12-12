<?php
// api/empty_cart.php
// This file clears (empties) the shopping cart stored in the session

// Start the session so we can access and modify session data
session_start();

// Tell the browser that this API will return JSON data
header('Content-Type: application/json');

// Only allow POST requests for security reasons
// This prevents users from emptying the cart by visiting the URL directly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
    exit; // Stop the script if the request method is not POST
}

// Reset the cart to an empty array
// This removes all items from the shopping cart
$_SESSION['cart'] = [];

// Send a success response back to the frontend
echo json_encode([
    'status' => 'ok'
]);

exit; // End the script
