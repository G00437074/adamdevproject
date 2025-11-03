<?php
// api/create_user.php
session_start();
require_once '../includes/db_connect.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username === '' || $password === '') { echo 'Please fill in all required fields.'; exit; }

$hash = password_hash($password, PASSWORD_DEFAULT);

$check = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1');
$check->execute([$username, $email]);
if ($check->fetch()) { echo 'Username or email already exists.'; exit; }

$ins = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
$ins->execute([$username, $email, $hash]);

echo 'User created. You can now log in.';
