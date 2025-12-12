<?php
// api/empty_cart.php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$_SESSION['cart'] = [];

echo json_encode(['status' => 'ok']);
exit;
