<?php
// setup_db.php
// One-click database setup for Laufey project (matches your CURRENT schema)

include 'includes/db_connect.php';

// Change this if your project uses a different DB name
$dbname = "adamdevproject";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Drop + recreate database
$conn->query("DROP DATABASE IF EXISTS `$dbname`;");
$conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
$conn->close();

// Connect to the new database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Create tables (matching your PHP)
$sql = [];

$sql[] = "CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE albums (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  release_year INT,
  cover_img VARCHAR(255),
  spotify_embed TEXT
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE songs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  album_id INT NOT NULL,
  track_no INT NULL,
  title VARCHAR(255) NOT NULL,
  duration_seconds INT NULL,
  FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE merch (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  image VARCHAR(255),
  description TEXT
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE tours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  venue VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  tour_name VARCHAR(255) NOT NULL
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address_line1 VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

$sql[] = "CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  size VARCHAR(10) NULL,
  quantity INT NOT NULL,
  price DECIMAL(8,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES merch(id) ON DELETE RESTRICT
) ENGINE=InnoDB;";

// Execute schema
foreach ($sql as $q) {
  if (!$conn->query($q)) {
    die("Schema error: " . $conn->error);
  }
}

// Insert Albums (filenames match your repo)
$conn->query("INSERT INTO albums (id, title, release_year, cover_img, spotify_embed) VALUES
(1, 'Typical of Me', 2021, 'images/albums/typical.jpeg', 'https://open.spotify.com/embed/album/7pooeoqY4uJkTaW70qxm3z?utm_source=generator'),
(2, 'Everything I Know About Love', 2022, 'images/albums/everything.png', 'https://open.spotify.com/embed/album/777K2ytcKbDsX0AZ2y8CBS?utm_source=generator'),
(3, 'Bewitched', 2023, 'images/albums/bewitched.png', 'https://open.spotify.com/embed/album/1rpCHilZQkw84A3Y9czvMO?utm_source=generator'),
(4, 'Bewitched: The Goddess Edition', 2024, 'images/albums/goddess.jpeg', 'https://open.spotify.com/embed/album/1hmlhl74JfLyUqmqtCwvFb?utm_source=generator'),
(5, 'A Matter of Time', 2025, 'images/albums/amatteroftime.png', 'https://open.spotify.com/embed/album/5rMOCuiWWbEBcHaKM69Hmv?utm_source=generator'),
(6, 'A Very Laufey Holiday', 2025, 'images/albums/laufeyholiday.jpeg', 'https://open.spotify.com/embed/album/32gqZfrZ51UMunez3CZDJZ?utm_source=generator')
");

// Insert Merch (filenames match your repo)
$conn->query("INSERT INTO merch (name, price, image, description) VALUES
('A Matter of Time Hoodie', 65.00, 'a_matter_of_time_hoodie.png', 'Cozy hoodie inspired by A Matter of Time.'),
('A Matter of Time Vinyl Bundle', 75.00, 'a_matter_of_time_vinyl_bundle.png', 'Vinyl bundle for the new album.'),
('Castle in Hollywood Tee', 37.00, 'castle_in_hollywood_tee.png', 'Classic tee featuring the Castle in Hollywood design.'),
('Laufey x Oddli Crown Kit', 28.00, 'laufey_x_oddli_crown_kit.png', 'Accessories kit collaboration.'),
('Mr Eclectic Tshirt', 35.00, 'mr_eclectic_tshirt.png', 'Graphic tee with Mr Eclectic theme.')
");

// Insert Tours (sample)
$conn->query("INSERT INTO tours (city, venue, date, tour_name) VALUES
('Dublin', '3Arena', '2026-03-10', 'A Matter of Time Tour'),
('London', 'Royal Albert Hall', '2026-03-15', 'A Matter of Time Tour'),
('Paris', 'Olympia', '2026-03-20', 'A Matter of Time Tour'),
('Reykjavík', 'Harpa', '2024-10-05', 'Bewitched Tour'),
('New York', 'Radio City Music Hall', '2024-10-20', 'Bewitched Tour')
");

echo "<h2>✅ Database '$dbname' created and seeded successfully.</h2>";
echo "<p>Next: edit setup_db.php and replace the Spotify embed placeholders with real URLs.</p>";

$conn->close();
