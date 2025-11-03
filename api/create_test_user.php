<?php
require_once '../includes/db_connect.php';
header('Content-Type: text/plain');

$username = 'testuser';
$email    = 'test@example.com';
$password = password_hash('secret123', PASSWORD_DEFAULT);

try {
    $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)')
        ->execute([$username, $email, $password]);
    echo "Created user:\nUsername: testuser\nPassword: secret123";
} catch (Throwable $e) {
    echo "Maybe already exists or error: " . $e->getMessage();
}
