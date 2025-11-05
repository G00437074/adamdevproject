<?php
// Start or resume the current session
session_start();

// Reset user-related session data
$_SESSION['username'] = "";
$_SESSION['user_id'] = 0;

// Tell the browser that the response will be in JSON format
header('Content-Type: application/json');

// Send back a simple JSON response indicating the logout was successful
echo json_encode(['success' => true]);
