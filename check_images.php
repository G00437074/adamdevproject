<?php
require_once 'includes/db_connect.php';

echo "<h1>Image Path Checker</h1>";

function checkFile($label, $path) {
  $full = __DIR__ . '/' . ltrim($path, '/');
  $ok = file_exists($full);
  echo ($ok ? "✅" : "❌") . " <strong>$label</strong>: " . htmlspecialchars($path) . "<br>";
  if (!$ok) {
    echo "<small>Missing at: " . htmlspecialchars($full) . "</small><br><br>";
  }
}

/* -------- Albums -------- */
echo "<h2>Albums</h2>";
$albums = $pdo->query("SELECT id, title, cover_img FROM albums")->fetchAll(PDO::FETCH_ASSOC);

foreach ($albums as $a) {
  checkFile("Album #{$a['id']} – {$a['title']}", $a['cover_img']);
}

/* -------- Merch -------- */
echo "<h2>Merch</h2>";
$merch = $pdo->query("SELECT id, name, image FROM merch")->fetchAll(PDO::FETCH_ASSOC);

foreach ($merch as $m) {
  // Your merch.php adds images/merch/ automatically, so DB should be filename only
  $path = 'images/merch/' . basename($m['image']);
  checkFile("Merch #{$m['id']} – {$m['name']}", $path);
}
