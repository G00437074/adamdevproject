<?php
// ===============================
// api/create_user.php
// ===============================

// Start or resume a session
// (Not strictly needed for creating an account, but helpful if you later log the user in automatically)
session_start();

// Include the database connection file
// This should create a $pdo variable that connects to your MySQL database
require_once '../includes/db_connect.php';

// Tell the browser that this script will return plain text instead of HTML or JSON
header('Content-Type: text/plain');

// ===============================
// GET AND VALIDATE FORM DATA
// ===============================

// Collect the form data sent via POST
// Use trim() to remove extra spaces and null coalescing to avoid errors if a field is missing
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : null; // Email is optional
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Check that the required fields are filled in
if ($username === '' || $password === '') {
  echo 'Please provide a username and password.';
  exit; // Stop the script here if required data is missing
}

try {
  // ===============================
  // CHECK IF USERNAME OR EMAIL ALREADY EXISTS
  // ===============================
  // Use a prepared statement with placeholders (?) to prevent SQL injection
  $check = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR (email IS NOT NULL AND email = ?) LIMIT 1');

  // Execute the query with the actual values
  $check->execute([$username, $email]);

  // If we get a result, that means the username or email is already taken
  if ($check->fetch()) {
    echo 'Username or email already exists.';
    exit; // Stop the script here
  }

  // ===============================
  // CREATE NEW USER
  // ===============================

  // Hash the password for security before saving it
  // password_hash() uses a strong one-way encryption method
  $hash = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the INSERT query to add the new user into the database
  $ins  = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');

  // If the email is empty, use NULL instead of an empty string
  $ins->execute([$username, $email ?: null, $hash]);

  // If everything worked, send a success message back to the user
  echo 'User created. You can now log in.';

} catch (Throwable $e) {
  // If something goes wrong (like a database error),
  // send a simple error message instead of showing technical details.
  // This helps protect sensitive server information.
  echo 'Server error. Please try again.';
}

