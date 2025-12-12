<?php
// api/remove_from_cart.php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$key = (string)($_POST['key'] ?? '');

if ($key === '') {
    echo json_encode(['status' => 'error', 'message' => 'Missing key']);
    exit;
}

if (isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}

echo json_encode(['status' => 'ok']);
exit;
