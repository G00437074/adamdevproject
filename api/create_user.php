<?php
// api/create_user.php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: text/plain');

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username === '' || $password === '') {
  echo 'Please provide a username and password.';
  exit;
}

try {
  // Check if username or email exists
  $check = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR (email IS NOT NULL AND email = ?) LIMIT 1');
  $check->execute([$username, $email]);
  if ($check->fetch()) {
    echo 'Username or email already exists.';
    exit;
  }

  // Hash password and insert
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $ins  = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
  $ins->execute([$username, $email ?: null, $hash]);

  echo 'User created. You can now log in.';
} catch (Throwable $e) {
  // Avoid leaking details in production
  echo 'Server error. Please try again.';
}
