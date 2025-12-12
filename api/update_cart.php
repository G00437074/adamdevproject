<?php
// api/update_cart.php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$key = (string)($_POST['key'] ?? '');
$qty = (int)($_POST['quantity'] ?? -1);

if ($key === '' || $qty < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

if (!isset($_SESSION['cart'][$key])) {
    echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    exit;
}

if ($qty === 0) {
    unset($_SESSION['cart'][$key]);
} else {
    $_SESSION['cart'][$key]['quantity'] = $qty;
}

echo json_encode(['status' => 'ok']);
exit;
