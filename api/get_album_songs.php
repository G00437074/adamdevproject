<?php
// api/get_album_songs.php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$albumId = isset($_GET['album_id']) ? intval($_GET['album_id']) : 0;

if ($albumId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid album ID']);
    exit;
}

// Your songs table: id, album_id, title, duration
// No track_number column, so we order by id and count manually.
$sql = "SELECT id, title, duration
        FROM songs
        WHERE album_id = ?
        ORDER BY id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$albumId]);
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$songs) {
    echo json_encode([
        'status' => 'ok',
        'html'   => '<p>No tracks found for this album.</p>'
    ]);
    exit;
}

$html = '<ol class="tracklist">';

$trackNo = 1;
foreach ($songs as $s) {
    $name = htmlspecialchars($s['title']);
    $dur  = htmlspecialchars($s['duration']);

    $html .= "
    <li class='tracklist-item'>
        <span class='track-number'>{$trackNo}.</span>
        <span class='track-title'>{$name}</span>
        <span class='track-duration'>{$dur}</span>
    </li>";

    $trackNo++;
}

$html .= '</ol>';

echo json_encode([
    'status' => 'ok',
    'html'   => $html
]);
