<?php
// Start the session so we can access the shopping cart
session_start();

// Tell the browser that this script returns JSON data
header('Content-Type: application/json');

// Include the database connection (creates $pdo)
include_once __DIR__ . '/../includes/db_connect.php';

// Include product helper functions (e.g. getProductById)
require_once __DIR__ . '/products.php';

// --------------------
// Check database connection
// --------------------

if (!isset($pdo)) {
  echo json_encode([
    'status'  => 'error',
    'message' => 'DB connection not available'
  ]);
  exit;
}

// --------------------
// Check cart exists and is not empty
// --------------------

// Get the cart from the session, or an empty array if it does not exist
$cart = $_SESSION['cart'] ?? [];

// Stop if there are no items in the cart
if (empty($cart)) {
  echo json_encode([
    'status'  => 'error',
    'message' => 'Cart is empty'
  ]);
  exit;
}

// --------------------
// Only allow POST requests
// --------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'status'  => 'error',
    'message' => 'Invalid request'
  ]);
  exit;
}

// --------------------
// Read and validate customer details
// --------------------

// Get customer details from the form and remove extra spaces
$name    = trim($_POST['customer_name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$address = trim($_POST['address_line1'] ?? '');
$city    = trim($_POST['city'] ?? '');

// Ensure all required fields are filled in
if ($name === '' || $email === '' || $address === '' || $city === '') {
  echo json_encode([
    'status'  => 'error',
    'message' => 'Please fill in all fields'
  ]);
  exit;
}

try {
  // --------------------
  // Start database transaction
  // --------------------
  // This ensures that either the entire order is saved,
  // or nothing is saved if an error occurs
  $pdo->beginTransaction();

  // --------------------
  // Create the order record
  // --------------------

  // Insert customer details into the orders table
  $stmt = $pdo->prepare(
    "INSERT INTO orders (customer_name, email, address_line1, city)
     VALUES (?, ?, ?, ?)"
  );
  $stmt->execute([$name, $email, $address, $city]);

  // Get the ID of the newly created order
  $orderId = (int)$pdo->lastInsertId();

  // --------------------
  // Add items to the order_items table
  // --------------------

  // Prepare the statement once and reuse it for each cart item
  $itemStmt = $pdo->prepare(
    "INSERT INTO order_items (order_id, product_id, size, quantity, price)
     VALUES (?, ?, ?, ?, ?)"
  );

  // Loop through each item in the cart
  foreach ($cart as $key => $item) {

    // Read item details from the cart
    $productId = (int)($item['id'] ?? 0);
    $qty       = (int)($item['quantity'] ?? 0);
    $size      = (string)($item['size'] ?? '');

    // Skip invalid items
    if ($productId <= 0 || $qty <= 0) continue;

    // Fetch the product from the database to get the price
    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    // Get the product price
    $price = (float)$product['price'];

    // Insert the order item
    // If size is empty, store NULL in the database
    $itemStmt->execute([
      $orderId,
      $productId,
      $size !== '' ? $size : null,
      $qty,
      $price
    ]);
  }

  // --------------------
  // Commit the transaction
  // --------------------

  // Save all changes to the database
  $pdo->commit();

  // --------------------
  // Clear the cart after successful order
  // --------------------

  $_SESSION['cart'] = [];

  // Send success response back to the frontend
  echo json_encode([
    'status'  => 'ok',
    'message' => 'Order placed!',
    'orderId' => $orderId
  ]);
  exit;

} catch (Exception $e) {

  // --------------------
  // Roll back on error
  // --------------------

  // Undo all database changes if something goes wrong
  $pdo->rollBack();

  // Return an error response
  echo json_encode([
    'status'  => 'error',
    'message' => 'Could not place order'
  ]);
  exit;
}
