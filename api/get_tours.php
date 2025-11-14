<?php
session_start();

// api/get_tours.php
require_once '../includes/db_connect.php';
header('Content-Type: application/json');

// Optional search term
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT id, city, venue, date 
        FROM tours 
        WHERE date >= CURDATE()";
$params = [];

if ($search !== '') {
  $sql .= " AND (city LIKE ? OR venue LIKE ?)";
  $like = '%' . $search . '%';
  $params[] = $like;
  $params[] = $like;
}

$sql .= " ORDER BY date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$tours) {
  echo json_encode(['status' => 'ok', 'html' => '<p>No upcoming dates found.</p>']);
  exit;
}

$html = '';
foreach ($tours as $t) {
  $d = date('D, M j, Y', strtotime($t['date']));
  $city = htmlspecialchars($t['city']);
  $venue = htmlspecialchars($t['venue']);

  $html .= "
    <div class='tour-item'>
      <h3 style='margin:6px 0;'>{$city} — {$venue}</h3>
      <p style='margin:0 0 8px; color:#555;'>{$d}</p>
      <button class='btn' onclick='alert(\"Tickets coming soon!\")'>Get Tickets</button>
    </div>
  ";
}

echo json_encode(['status' => 'ok', 'html' => $html]);
