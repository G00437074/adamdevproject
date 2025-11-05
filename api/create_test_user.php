<?php
// Include the database connection file
// This file should create a $pdo object for connecting to your database
require_once '../includes/db_connect.php';

// Set the content type to plain text (so it doesn't show as HTML)
header('Content-Type: text/plain');

// Define some example user data to insert into the database
$username = 'testuser';
$email    = 'test@example.com';

// Hash the password before saving it to the database
// This makes the password secure by converting it into an irreversible format
$password = password_hash('secret123', PASSWORD_DEFAULT);

try {
    // Prepare an SQL statement with placeholders (?) to prevent SQL injection
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');

    // Execute the prepared statement, passing in the actual values for each placeholder
    $stmt->execute([$username, $email, $password]);

    // If everything works, display a success message
    echo "Created user:\nUsername: testuser\nPassword: secret123";
} catch (Throwable $e) {
    // If something goes wrong (like the user already exists or a database error),
    // catch the error and print a message
    echo "Maybe already exists or error: " . $e->getMessage();
}
