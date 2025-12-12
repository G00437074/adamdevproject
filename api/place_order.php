<?php
session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/products.php';

if (!isset($pdo)) {
  echo json_encode(['status'=>'error','message'=>'DB connection not available']);
  exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
  echo json_encode(['status'=>'error','message'=>'Cart is empty']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Invalid request']);
  exit;
}

$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address_line1'] ?? '');
$city = trim($_POST['city'] ?? '');

if ($name === '' || $email === '' || $address === '' || $city === '') {
  echo json_encode(['status'=>'error','message'=>'Please fill in all fields']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Create order
  $stmt = $pdo->prepare("INSERT INTO orders (customer_name, email, address_line1, city) VALUES (?, ?, ?, ?)");
  $stmt->execute([$name, $email, $address, $city]);
  $orderId = (int)$pdo->lastInsertId();

  // Add items
  $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");

  foreach ($cart as $key => $item) {
    $productId = (int)($item['id'] ?? 0);
    $qty = (int)($item['quantity'] ?? 0);
    $size = (string)($item['size'] ?? '');

    if ($productId <= 0 || $qty <= 0) continue;

    $product = getProductById($pdo, $productId);
    if (!$product) continue;

    $price = (float)$product['price'];
    $itemStmt->execute([$orderId, $productId, $size !== '' ? $size : null, $qty, $price]);
  }

  $pdo->commit();

  // Clear cart
  $_SESSION['cart'] = [];

  echo json_encode(['status'=>'ok','message'=>'Order placed!','orderId'=>$orderId]);
  exit;

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['status'=>'error','message'=>'Could not place order']);
  exit;
}
