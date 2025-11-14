<?php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

// Parameters
$search    = isset($_GET['q']) ? trim($_GET['q']) : '';
$mode      = isset($_GET['mode']) ? $_GET['mode'] : 'current';
$tourName  = isset($_GET['tour_name']) ? trim($_GET['tour_name']) : '';

// SQL base
$sql = "SELECT id, city, venue, date, tour_name
        FROM tours
        WHERE 1";

$params = [];

// Current or Past filter
if ($mode === 'past') {
    $sql .= " AND date < CURDATE()";
    $order = " ORDER BY date DESC";
} else {
    $sql .= " AND date >= CURDATE()";
    $order = " ORDER BY date ASC";
}

// Filter by specific tour name
if ($tourName !== '') {
    $sql .= " AND tour_name = ?";
    $params[] = $tourName;
}

// Search filter
if ($search !== '') {
    $sql .= " AND (city LIKE ? OR venue LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= $order;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$tours) {
    echo json_encode(['html' => '<p>No tour dates found.</p>']);
    exit;
}

// Build a table of dates
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

foreach ($tours as $t) {
    $date = date("F j, Y", strtotime($t['date']));
    $city = htmlspecialchars($t['city']);
    $venue = htmlspecialchars($t['venue']);
    $tour = htmlspecialchars($t['tour_name']);

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

$html .= "</tbody></table>";

echo json_encode(['html' => $html]);
