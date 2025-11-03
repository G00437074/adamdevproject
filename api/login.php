<?php
session_start();
require_once '../includes/db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request.']); 
  exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
  echo json_encode(['success' => false, 'message' => 'Please enter username and password.']);
  exit;
}

try {
  // Query the user
  $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id']  = (int)$user['id'];

    echo json_encode([
      'success'  => true,
      'message'  => 'Login successful!',
      'username' => $user['username'],
      'user_id'  => (int)$user['id']
    ]);
    exit;
  }

  echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
  exit;

} catch (Throwable $e) {
  echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
  exit;
}
