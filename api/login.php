<?php
// Start the session so we can store login information
session_start();

// Include the database connection file (creates $pdo)
require_once '../includes/db_connect.php';

// Tell the browser that this script returns JSON data
header('Content-Type: application/json');

// ---------------------------------
// Only allow POST requests
// ---------------------------------

// Prevent users from accessing this file directly via the browser
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'success' => false,
    'message' => 'Invalid request.'
  ]);
  exit; // Stop the script
}

// ---------------------------------
// Read and validate form inputs
// ---------------------------------

// Get the username and remove extra spaces
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

// Get the password (do not trim passwords)
$password = $_POST['password'] ?? '';

// Check that both fields were filled in
if ($username === '' || $password === '') {
  echo json_encode([
    'success' => false,
    'message' => 'Please enter username and password.'
  ]);
  exit;
}

try {
  // ---------------------------------
  // Look up the user in the database
  // ---------------------------------

  // Prepare a SQL statement to safely fetch the user
  // LIMIT 1 ensures only one user is returned
  $stmt = $pdo->prepare(
    'SELECT id, username, password FROM users WHERE username = ? LIMIT 1'
  );

  // Execute the query with the username
  $stmt->execute([$username]);

  // Fetch the user record as an associative array
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // ---------------------------------
  // Verify password and log the user in
  // ---------------------------------

  // Check that the user exists AND the password matches the hashed password
  if ($user && password_verify($password, $user['password'])) {

    // Store user details in the session to keep them logged in
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id']  = (int)$user['id'];

    // Send a success response back to the frontend
    echo json_encode([
      'success'  => true,
      'message'  => 'Login successful!',
      'username' => $user['username'],
      'user_id'  => (int)$user['id']
    ]);
    exit;
  }

  // If the username or password is incorrect
  echo json_encode([
    'success' => false,
    'message' => 'Invalid username or password.'
  ]);
  exit;

} catch (Throwable $e) {
  // ---------------------------------
  // Handle any server or database errors
  // ---------------------------------

  // Return an error message if something goes wrong
  echo json_encode([
    'success' => false,
    'message' => 'Server error: ' . $e->getMessage()
  ]);
  exit;
}
