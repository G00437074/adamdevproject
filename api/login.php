<?php
// Start a new session or resume an existing one
// This allows you to store user information (like username or ID) across pages
session_start();

// Include the database connection file
// This file should set up a $pdo variable for interacting with your database
require_once '../includes/db_connect.php';

// Tell the browser that the server will send data in JSON format
header('Content-Type: application/json');

// Check if the request method is POST (meaning data was sent from a form)
// If it's not POST, return an error message as JSON and stop the script
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request.']); 
  exit;
}

// Get the username and password from the form data (POST request)
// Use the null coalescing operator (??) to avoid errors if a field is missing
// trim() removes any extra spaces from the username
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = $_POST['password'] ?? '';

// Check if both username and password were entered
// If either field is empty, send a JSON error message and stop
if ($username === '' || $password === '') {
  echo json_encode(['success' => false, 'message' => 'Please enter username and password.']);
  exit;
}

try {
  // Prepare an SQL query to find a user by username
  // The ? is a placeholder to prevent SQL injection attacks
  $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');

  // Execute the SQL query, inserting the actual username value
  $stmt->execute([$username]);

  // Fetch one result as an associative array (e.g., ['id' => 1, 'username' => 'bob', ...])
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // If a user is found AND the entered password matches the stored hashed password
  if ($user && password_verify($password, $user['password'])) {
    // Save user information in the session so the site remembers they're logged in
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id']  = (int)$user['id'];

    // Return a success message and some user data in JSON format
    echo json_encode([
      'success'  => true,
      'message'  => 'Login successful!',
      'username' => $user['username'],
      'user_id'  => (int)$user['id']
    ]);
    exit;
  }

  // If the username doesn't exist or the password is wrong, show an error
  echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
  exit;

} catch (Throwable $e) {
  // If something goes wrong with the database or script,
  // catch the error and return a JSON message with the error details
  echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
  exit;
}

