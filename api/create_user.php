<?php
// ===============================
// api/create_user.php
// ===============================

// Start or resume a session
session_start();

// Include the database connection file (creates $pdo)
require_once '../includes/db_connect.php';

// Return plain text (your JS expects text)
header('Content-Type: text/plain');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo 'Invalid request.';
  exit;
}

// ===============================
// GET AND VALIDATE FORM DATA
// ===============================
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : null; // optional
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Required fields validation
if ($username === '' || $password === '') {
  echo 'Please provide a username and password.';
  exit;
}

// Server-side password rules (match your JS)
if (strlen($password) < 8 || !preg_match('/[^\w]/', $password)) {
  echo 'Password must be at least 8 characters and contain a special character.';
  exit;
}

// Optional: if email provided, validate format
if ($email !== null && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo 'Please enter a valid email address.';
  exit;
}

try {
  // ===============================
  // CHECK IF USERNAME OR EMAIL ALREADY EXISTS
  // ===============================
  $check = $pdo->prepare(
    'SELECT 1 FROM users WHERE username = ? OR (email IS NOT NULL AND email = ?) LIMIT 1'
  );
  $check->execute([$username, $email ?: null]);

  if ($check->fetch()) {
    echo 'Username or email already exists.';
    exit;
  }

  // ===============================
  // CREATE NEW USER
  // ===============================

  // Hash the password securely before saving
  $hash = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user
  $ins = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
  $ins->execute([$username, $email ?: null, $hash]);

  echo 'User created. You can now log in.';
  exit;

} catch (Throwable $e) {
  // Generic error message (don’t leak DB details)
  echo 'Server error. Please try again.';
  exit;
}
