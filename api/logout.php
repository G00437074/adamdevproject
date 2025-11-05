<?php
// Start or resume the current session
// This lets PHP access and manage the session data that was previously created
session_start();

// Remove all session variables
// This clears any stored user data (like username, user ID, etc.)
session_unset();

// Completely destroy the session
// This deletes the session file on the server and invalidates the session ID
session_destroy();

// Tell the browser that the response will be in JSON format
header('Content-Type: application/json');

// Send back a simple JSON response indicating the logout was successful
echo json_encode(['success' => true]);
