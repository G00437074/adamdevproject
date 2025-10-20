<!DOCTYPE html>
<html>

<head>
    <title>Laufey Database Setup</title>
</head>

<body>

<?php
include 'includes/db_connect.php';
$conn = new mysqli($servername, $username, $password);

$dbname = "adamdevproject";

// Drop database if exists
$sql = "DROP DATABASE IF EXISTS $dbname;";
if ($conn->query($sql) === TRUE) {
  echo "Database dropped successfully<br>";
} else {
  echo "Error dropping database: " . $conn->error . "<br>";
}

// Create database
$sql = "CREATE DATABASE $dbname;";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully<br>";
} else {
  echo "Error creating database: " . $conn->error . "<br>";
}

$conn->close();

// Connect to the new database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create Albums table
$sql = "CREATE TABLE albums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    release_year INT,
    cover_img VARCHAR(255)
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Albums table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Songs table
$sql = "CREATE TABLE songs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT,
    title VARCHAR(100),
    duration VARCHAR(10),
    FOREIGN KEY (album_id) REFERENCES albums(id)
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Songs table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Merch table
$sql = "CREATE TABLE merch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(6,2),
    image VARCHAR(255),
    description TEXT
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Merch table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Tours table
$sql = "CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_name VARCHAR(100),
    city VARCHAR(100),
    venue VARCHAR(100),
    date DATE
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Tours table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Users table
$sql = "CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Users table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Orders table
$sql = "CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Orders table created<br>") : print("Error: " . $conn->error . "<br>");

// Create Order_Items table
$sql = "CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    merch_id INT NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (merch_id) REFERENCES merch(id) ON DELETE CASCADE
) ENGINE=InnoDB;";
$conn->query($sql) ? print("Order_Items table created<br>") : print("Error: " . $conn->error . "<br>");

// Insert Albums
$sql = "INSERT INTO albums (title, release_year, cover_img) VALUES
('Everything I Know About Love', 2022, 'images/everything_i_know_about_love.jpg'),
('Bewitched', 2023, 'images/bewitched.jpg'),
('A Matter of Time', 2025, 'images/a_matter_of_time.jpg');";
$conn->query($sql) ? print("Albums inserted<br>") : print("Error: " . $conn->error . "<br>");

// Insert Songs (tracklists for each album)
$sql = "INSERT INTO songs (album_id, title, duration) VALUES
-- Everything I Know About Love
(1, 'Someone New', '3:15'),
(1, 'Euphoria', '2:48'),
(1, 'Never Not', '3:05'),
(1, 'Autumn Leaves', '2:58'),
(1, 'Sunrise', '3:10'),

-- Bewitched
(2, 'Bewitched', '3:20'),
(2, 'Garden', '3:05'),
(2, 'Moonlight', '3:25'),
(2, 'Enchanted', '3:12'),
(2, 'Dreams', '2:55'),

-- A Matter of Time
(3, 'Time Flies', '3:30'),
(3, 'Hold Me', '3:15'),
(3, 'Letters', '3:05'),
(3, 'Falling Slowly', '3:20'),
(3, 'A Matter of Time', '3:10');";
$conn->query($sql) ? print("Songs inserted<br>") : print("Error: " . $conn->error . "<br>");

// Insert Merch
$sql = "INSERT INTO merch (name, price, image, description) VALUES
('Castle in Hollywood Tee', 37.00, 'images/castle_in_hollywood_tee.jpg', 'Limited edition T-shirt with the Castle in Hollywood graphic.'),
('Laufey x Oddli Crown Kit', 47.00, 'images/laufey_x_oddli_crown_kit.jpg', 'DIY crown-making kit collaboration with Oddli.'),
('Mr. Eclectic T-Shirt', 28.00, 'images/mr_eclectic_tshirt.jpg', 'Official T-shirt featuring the Mr. Eclectic album artwork.'),
('A Matter of Time Vinyl + Cassette Bundle', 44.00, 'images/a_matter_of_time_vinyl_bundle.jpg', 'Bundle including the A Matter of Time album on vinyl and cassette.'),
('A Matter of Time Hoodie', 56.00, 'images/a_matter_of_time_hoodie.jpg', 'Comfortable hoodie featuring the A Matter of Time album artwork.');";
$conn->query($sql) ? print("Merch inserted<br>") : print("Error: " . $conn->error . "<br>");

// Insert Tours (European dates only)
$sql = "INSERT INTO tours (tour_name, city, venue, date) VALUES
-- Everything I Know About Love Tour 2022
('Everything I Know About Love Tour', 'London', 'Brixton Academy', '2022-09-10'),
('Everything I Know About Love Tour', 'Paris', 'Le Trianon', '2022-09-12'),
('Everything I Know About Love Tour', 'Berlin', 'Velodrom', '2022-09-14'),

-- Bewitched Tour 2023-2024
('Bewitched Tour', 'Copenhagen', 'Royal Arena', '2023-11-05'),
('Bewitched Tour', 'Amsterdam', 'Ziggo Dome', '2023-11-07'),
('Bewitched Tour', 'Brussels', 'Ancienne Belgique', '2023-11-09'),
('Bewitched Tour', 'London', 'O2 Shepherds Bush', '2023-11-11'),

-- A Matter of Time Tour 2025 (Europe)
('A Matter of Time Tour', 'Zurich', 'Hallenstadion', '2025-02-18'),
('A Matter of Time Tour', 'Düsseldorf', 'Mitsubishi Electric Halle', '2025-02-19'),
('A Matter of Time Tour', 'Copenhagen', 'Royal Arena', '2025-02-22'),
('A Matter of Time Tour', 'Paris', 'Le Trianon', '2025-02-24'),
('A Matter of Time Tour', 'London', 'Brixton Academy', '2025-02-26'),
('A Matter of Time Tour', 'Manchester', 'Co-op Live', '2025-02-28'),
('A Matter of Time Tour', 'Berlin', 'Velodrom', '2025-03-01'),
('A Matter of Time Tour', 'Amsterdam', 'Ziggo Dome', '2025-03-03'),
('A Matter of Time Tour', 'Brussels', 'Ancienne Belgique', '2025-03-05'),
('A Matter of Time Tour', 'Oslo', 'Rockefeller Oslo', '2025-03-07'),
('A Matter of Time Tour', 'Stockholm', '3Arena', '2025-03-09'),
('A Matter of Time Tour', 'Helsinki', 'Olympic Stadium', '2025-03-11'),
('A Matter of Time Tour', 'Budapest', 'László Papp Budapest Sports Arena', '2025-03-13'),
('A Matter of Time Tour', 'Prague', 'Letňany', '2025-03-14');";
$conn->query($sql) ? print("Tours inserted<br>") : print("Error: " . $conn->error . "<br>");

$conn->close();
?>

</body>
</html>
