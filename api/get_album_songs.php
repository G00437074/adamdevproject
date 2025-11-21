<?php
// api/get_album_songs.php

session_start(); // Start a session (used if your app needs session data)
require_once '../includes/db_connect.php'; // Load your database connection file

// Tell the browser that we're sending JSON data back
header('Content-Type: application/json');

// Get the album_id from the URL (e.g. ?album_id=3)
// intval() turns it into a number to avoid invalid input
$albumId = isset($_GET['album_id']) ? intval($_GET['album_id']) : 0;

// If no valid album ID was provided, return an error message as JSON
if ($albumId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid album ID']);
    exit; // Stop the script
}

// We select songs that belong to this album.
// NOTE: There is **no track_number column** in the DB,
// so we simply order by the song ID and count track numbers ourselves.
$sql = "SELECT id, title, duration
        FROM songs
        WHERE album_id = ?
        ORDER BY id ASC";

// Prepare the SQL statement to avoid SQL injection
$stmt = $pdo->prepare($sql);

// Execute the query with the album ID
$stmt->execute([$albumId]);

// Fetch all matching songs as an associative array
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no songs are found, return a JSON response containing simple HTML
if (!$songs) {
    echo json_encode([
        'status' => 'ok',
        'html'   => '<p>No tracks found for this album.</p>'
    ]);
    exit;
}

// Begin building the HTML for the tracklist
$html = '<ol class="tracklist">';

// We will manually count track numbers: 1, 2, 3...
$trackNo = 1;

// Loop through each song
foreach ($songs as $s) {

    // Escape text to prevent XSS (security)
    $name = htmlspecialchars($s['title']);
    $dur  = htmlspecialchars($s['duration']);

    // Add a list item for each track
    $html .= "
    <li class='tracklist-item'>
        <span class='track-number'>{$trackNo}.</span>
        <span class='track-title'>{$name}</span>
        <span class='track-duration'>{$dur}</span>
    </li>";

    // Increase track number for the next loop
    $trackNo++;
}

// Close the ordered list
$html .= '</ol>';

// Return the final JSON response with the generated HTML
echo json_encode([
    'status' => 'ok',
    'html'   => $html
]);
