<?php
// Start the session (not strictly required here, but useful if session data is needed later)
session_start();

// Include the database connection file (creates $pdo)
require_once '../includes/db_connect.php';

// Tell the browser that this script returns JSON data
header('Content-Type: application/json');

// --------------------
// Read URL parameters
// --------------------

// Search term for filtering by city or venue (e.g. ?q=Dublin)
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Determines whether to show current or past tour dates
// Defaults to "current" if not provided
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'current';

// Optional filter for a specific tour name
$tourName = isset($_GET['tour_name']) ? trim($_GET['tour_name']) : '';

// --------------------
// Build base SQL query
// --------------------

// Start with a basic query that always evaluates to true (WHERE 1)
// This makes it easier to add conditions dynamically
$sql = "SELECT id, city, venue, date, tour_name
        FROM tours
        WHERE 1";

// Array to store values that will be safely bound to the SQL query
$params = [];

// --------------------
// Filter by date (current or past tours)
// --------------------

if ($mode === 'past') {
    // Past tours: dates before today
    $sql .= " AND date < CURDATE()";
    $order = " ORDER BY date DESC"; // Most recent first
} else {
    // Current/upcoming tours: today or later
    $sql .= " AND date >= CURDATE()";
    $order = " ORDER BY date ASC"; // Soonest first
}

// --------------------
// Filter by tour name (if provided)
// --------------------

if ($tourName !== '') {
    // Add condition and store value for prepared statement
    $sql .= " AND tour_name = ?";
    $params[] = $tourName;
}

// --------------------
// Search filter (city or venue)
// --------------------

if ($search !== '') {
    // Use LIKE for partial matches
    $sql .= " AND (city LIKE ? OR venue LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Add the ORDER BY clause to the final SQL query
$sql .= $order;

// --------------------
// Execute the query
// --------------------

// Prepare the SQL statement to prevent SQL injection
$stmt = $pdo->prepare($sql);

// Execute the query with the parameter values
$stmt->execute($params);

// Fetch all matching tour records as an associative array
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no tours are found, return a message instead of a table
if (!$tours) {
    echo json_encode([
        'html' => '<p>No tour dates found.</p>'
    ]);
    exit;
}

// --------------------
// Build HTML table
// --------------------

// Start the table HTML
$html = '<table class="tour-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>City</th>
                <th>Venue</th>
                <th>Tour</th>
            </tr>
        </thead>
        <tbody>';

// Loop through each tour and add a row to the table
foreach ($tours as $t) {

    // Format the date for display
    $date = date("F j, Y", strtotime($t['date']));

    // Escape output to prevent XSS attacks
    $city  = htmlspecialchars($t['city']);
    $venue = htmlspecialchars($t['venue']);
    $tour  = htmlspecialchars($t['tour_name']);

    // Add a table row for this tour
    $html .= "
    <tr>
        <td>$date</td>
        <td>$city</td>
        <td>$venue</td>
        <td>
            <a href='#' class='tour-link' data-tour=\"$tour\">$tour</a>
        </td>
    </tr>";
}

// Close the table HTML
$html .= "</tbody></table>";

// --------------------
// Return HTML inside JSON
// --------------------

// Send the generated HTML back to the frontend as JSON
echo json_encode([
    'html' => $html
]);
