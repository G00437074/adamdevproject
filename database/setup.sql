/* ==========================================
   Laufey Music Website - Database Setup
   Database name matches includes/db_connect.php:
   mysql:host=localhost;dbname=adamdevproject
   ========================================== */

CREATE DATABASE IF NOT EXISTS adamdevproject
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE adamdevproject;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS songs;
DROP TABLE IF EXISTS tours;
DROP TABLE IF EXISTS merch;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;


/* =========================
   USERS (matches login.php)
   ========================= */
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(255) NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   ALBUMS (includes spotify_embed)
   ========================= */
CREATE TABLE albums (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  release_year INT NULL,
  cover_img VARCHAR(255) NULL,
  spotify_embed TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   SONGS (tracklists)
   ========================= */
CREATE TABLE songs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  album_id INT UNSIGNED NOT NULL,
  track_no INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  duration_seconds INT UNSIGNED NULL,
  CONSTRAINT fk_songs_album
    FOREIGN KEY (album_id) REFERENCES albums(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   MERCH (matches products.php)
   ========================= */
CREATE TABLE merch (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  image VARCHAR(255) NOT NULL,
  description TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   TOURS
   ========================= */
CREATE TABLE tours (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  venue VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  tour_name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   ORDERS + ORDER_ITEMS
   (matches api/place_order.php)
   ========================= */
CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address_line1 VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  size VARCHAR(10) NULL,
  quantity INT UNSIGNED NOT NULL,
  price DECIMAL(8,2) NOT NULL,

  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_order_items_product
    FOREIGN KEY (product_id) REFERENCES merch(id)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* ==========================================
   SEED DATA (images match your repo)
   ========================================== */

INSERT INTO albums (id, title, release_year, cover_img, spotify_embed) VALUES
(1, 'Typical of Me', 2021, 'images/albums/typical.jpeg', 'https://open.spotify.com/embed/album/7pooeoqY4uJkTaW70qxm3z?utm_source=generator'),
(2, 'Everything I Know About Love', 2022, 'images/albums/everything.png', 'https://open.spotify.com/embed/album/777K2ytcKbDsX0AZ2y8CBS?utm_source=generator'),
(3, 'Bewitched', 2023, 'images/albums/bewitched.png', 'https://open.spotify.com/embed/album/1rpCHilZQkw84A3Y9czvMO?utm_source=generator'),
(4, 'Bewitched: The Goddess Edition', 2024, 'images/albums/goddess.jpeg', 'https://open.spotify.com/embed/album/1hmlhl74JfLyUqmqtCwvFb?utm_source=generator'),
(5, 'A Matter of Time', 2025, 'images/albums/amatteroftime.png', 'https://open.spotify.com/embed/album/5rMOCuiWWbEBcHaKM69Hmv?utm_source=generator'),
(6, 'A Very Laufey Holiday', 2025, 'images/albums/laufeyholiday.jpeg', 'https://open.spotify.com/embed/album/32gqZfrZ51UMunez3CZDJZ?utm_source=generator');

INSERT INTO songs (id, album_id, title, duration) VALUES
(39, 1, 'Street by Street', '3:44'),
(40, 1, 'Magnolia', '3:00'),
(41, 1, 'Like the Movies', '2:42'),
(42, 1, 'I Wish You Love', '2:35'),
(43, 1, 'James', '2:55'),
(44, 1, 'Someone New', '3:18'),
(45, 1, 'Best Friend', '2:45'),

(46, 2, 'Fragile', '3:52'),
(47, 2, 'Beautiful Stranger', '3:36'),
(48, 2, 'Valentine', '3:13'),
(49, 2, 'Above the Chinese Restaurant', '3:06'),
(50, 2, 'Dear Soulmate', '3:03'),
(51, 2, 'What Love Will Do to You', '3:39'),
(52, 2, 'I''ve Never Been in Love Before', '3:17'),
(53, 2, 'Just Like Chet', '3:01'),
(54, 2, 'Everything I Know About Love', '3:36'),
(55, 2, 'Falling Behind', '3:17'),
(56, 2, 'Hi', '3:20'),
(57, 2, 'Dance with You Tonight', '3:16'),
(58, 2, 'Night Light', '3:32'),

(59, 3, 'Dreamer', '3:30'),
(60, 3, 'Second Best', '3:24'),
(61, 3, 'Haunted', '3:20'),
(62, 3, 'Must Be Love', '3:04'),
(63, 3, 'While You Were Sleeping', '2:57'),
(64, 3, 'Lovesick', '3:45'),
(65, 3, 'California and Me', '3:36'),
(66, 3, 'Nocturne (Interlude)', '2:24'),
(67, 3, 'Promise', '3:38'),

(68, 4, 'Dreamer', '3:30'),
(69, 4, 'Second Best', '3:24'),
(70, 4, 'Haunted', '3:20'),
(71, 4, 'Must Be Love', '3:04'),
(72, 4, 'While You Were Sleeping', '2:57'),
(73, 4, 'Lovesick', '3:45'),
(74, 4, 'California and Me', '3:36'),
(75, 4, 'Nocturne (Interlude)', '2:24'),
(76, 4, 'Promise', '3:38'),
(77, 4, 'Goddess', '3:29'),
(78, 4, 'Bored', '3:24'),
(79, 4, 'Trouble', '3:21'),
(80, 4, 'It Could Happen to You', '3:12'),

(81, 5, 'Clockwork', '2:30'),
(82, 5, 'Lover Girl', '2:44'),
(83, 5, 'Snow White', '3:13'),
(84, 5, 'Castle in Hollywood', '2:33'),
(85, 5, 'Carousel', '3:19'),
(86, 5, 'Silver Lining', '3:45'),
(87, 5, 'Too Little, Too Late', '3:30'),
(88, 5, 'Cuckoo Ballet (Interlude)', '1:20'),
(89, 5, 'Forget-Me-Not', '3:10'),
(90, 5, 'Tough Luck', '3:25'),
(91, 5, 'A Cautionary Tale', '3:00'),
(92, 5, 'Mr. Eclectic', '3:50'),
(93, 5, 'Clean Air', '3:15'),
(94, 5, 'Sabotage', '3:40'),

(95, 6, 'Santa Claus Is Comin'' To Town', '2:39'),
(96, 6, 'Santa Baby', '3:02'),
(97, 6, 'Winter Wonderland', '2:12'),
(98, 6, 'Christmas Magic', '3:10'),
(99, 6, 'Christmas Dreaming', '2:33'),
(100, 6, 'The Christmas Waltz', '2:47'),
(101, 6, 'Love to Keep Me Warm', '2:38');


INSERT INTO merch (name, price, image, description) VALUES
('A Matter of Time Hoodie', 65.00, 'a_matter_of_time_hoodie.png', 'Cozy hoodie inspired by A Matter of Time.'),
('A Matter of Time Vinyl Bundle', 75.00, 'a_matter_of_time_vinyl_bundle.png', 'Vinyl bundle for the new album.'),
('Castle in Hollywood Tee', 37.00, 'castle_in_hollywood_tee.png', 'Classic tee featuring the Castle in Hollywood design.'),
('Laufey x Oddli Crown Kit', 28.00, 'laufey_x_oddli_crown_kit.png', 'Accessories kit collaboration.'),
('Mr Eclectic Tshirt', 35.00, 'mr_eclectic_tshirt.png', 'Graphic tee with Mr Eclectic theme.'); 


INSERT INTO tours (id, city, venue, date, tour_name) VALUES
(1, 'Phoenix', 'Valley Bar', '2022-09-13', 'Everything I Know About Love Tour'),
(2, 'Austin', '3TEN Austin City Limits Live', '2022-09-14', 'Everything I Know About Love Tour'),
(3, 'Houston', 'Upstairs at White Oak Music Hall', '2022-09-16', 'Everything I Know About Love Tour'),
(4, 'Dallas', 'Cambridge Room, House of Blues', '2022-09-17', 'Everything I Know About Love Tour'),
(5, 'Atlanta', 'Vinyl at Center Stage', '2022-09-18', 'Everything I Know About Love Tour'),
(6, 'Nashville', 'The End', '2022-09-20', 'Everything I Know About Love Tour'),
(7, 'Washington', 'Union Stage', '2022-09-21', 'Everything I Know About Love Tour'),
(8, 'Philadelphia', 'The Foundry, The Fillmore', '2022-09-23', 'Everything I Know About Love Tour'),
(9, 'Cambridge', 'The Sinclair', '2022-09-24', 'Everything I Know About Love Tour'),
(10, 'New York', 'Bowery Ballroom', '2022-09-27', 'Everything I Know About Love Tour'),
(11, 'New York', 'Bowery Ballroom', '2022-09-28', 'Everything I Know About Love Tour'),
(12, 'Toronto', 'Velvet Underground', '2022-09-30', 'Everything I Know About Love Tour'),

(13, 'New York', 'Madison Square Garden', '2023-04-14', 'Bewitched Tour'),
(14, 'Boston', 'TD Garden', '2023-04-16', 'Bewitched Tour'),
(15, 'Philadelphia', 'Xfinity Mobile Arena', '2023-04-18', 'Bewitched Tour'),
(16, 'Washington', 'Capital One Arena', '2023-04-20', 'Bewitched Tour'),
(17, 'Atlanta', 'State Farm Arena', '2023-04-22', 'Bewitched Tour'),
(18, 'Chicago', 'United Center', '2023-04-25', 'Bewitched Tour'),
(19, 'Toronto', 'Scotiabank Arena', '2023-04-27', 'Bewitched Tour'),
(20, 'Montreal', 'Bell Centre', '2023-04-29', 'Bewitched Tour'),
(21, 'Los Angeles', 'Crypto.com Arena', '2023-05-03', 'Bewitched Tour'),
(22, 'San Francisco', 'Chase Center', '2023-05-05', 'Bewitched Tour'),
(23, 'Seattle', 'Climate Pledge Arena', '2023-05-07', 'Bewitched Tour'),
(24, 'Denver', 'Ball Arena', '2023-05-10', 'Bewitched Tour'),
(25, 'Dallas', 'American Airlines Center', '2023-05-12', 'Bewitched Tour'),
(26, 'Houston', 'Toyota Center', '2023-05-14', 'Bewitched Tour'),

(27, 'Orlando', 'Kia Forum', '2025-09-15', 'A Matter of Time Tour'),
(28, 'Atlanta', 'State Farm Arena', '2025-09-16', 'A Matter of Time Tour'),
(29, 'Fort Worth', 'Dickies Arena', '2025-09-18', 'A Matter of Time Tour'),
(30, 'Houston', 'Toyota Center', '2025-09-20', 'A Matter of Time Tour'),
(31, 'Austin', 'Moody Center', '2025-09-21', 'A Matter of Time Tour'),
(32, 'San Diego', 'Pechanga Arena', '2025-09-24', 'A Matter of Time Tour'),
(33, 'Los Angeles', 'Crypto.com Arena', '2025-09-26', 'A Matter of Time Tour'),
(34, 'Oakland', 'Oakland Arena', '2025-09-29', 'A Matter of Time Tour'),
(35, 'Morrison', 'Red Rocks Amphitheatre', '2025-10-08', 'A Matter of Time Tour'),
(36, 'Toronto', 'Scotiabank Arena', '2025-10-12', 'A Matter of Time Tour'),
(37, 'Laval', 'Place Bell', '2025-10-13', 'A Matter of Time Tour'),
(38, 'New York', 'Madison Square Garden', '2025-10-15', 'A Matter of Time Tour'),
(39, 'Toronto', 'Scotiabank Arena', '2025-10-20', 'A Matter of Time Tour'),
(40, 'Laval', 'Place Bell', '2025-10-21', 'A Matter of Time Tour'),
(41, 'Washington', 'Capital One Arena', '2025-10-23', 'A Matter of Time Tour'),
(42, 'Philadelphia', 'Xfinity Mobile Arena', '2025-10-24', 'A Matter of Time Tour'),
(43, 'Boston', 'TD Garden', '2025-10-27', 'A Matter of Time Tour'),
(44, 'New York', 'Madison Square Garden', '2025-12-12', 'A Matter of Time Tour'),
(45, 'Boston', 'TD Garden', '2025-12-14', 'A Matter of Time Tour'),
(46, 'Philadelphia', 'Xfinity Mobile Arena', '2025-12-15', 'A Matter of Time Tour'),

(67, 'Zurich', 'Hallenstadion', '2026-02-18', 'A Matter of Time Tour'),
(68, 'Düsseldorf', 'Mitsubishi Electric Halle', '2026-02-19', 'A Matter of Time Tour'),
(69, 'Copenhagen', 'Royal Arena', '2026-02-22', 'A Matter of Time Tour'),
(70, 'Paris', 'Le Trianon', '2026-02-24', 'A Matter of Time Tour'),
(71, 'London', 'Brixton Academy', '2026-02-26', 'A Matter of Time Tour'),
(72, 'Manchester', 'Co-op Live', '2026-02-28', 'A Matter of Time Tour'),
(73, 'Berlin', 'Velodrom', '2026-03-01', 'A Matter of Time Tour'),
(74, 'Amsterdam', 'Ziggo Dome', '2026-03-03', 'A Matter of Time Tour'),
(75, 'Brussels', 'Ancienne Belgique', '2026-03-05', 'A Matter of Time Tour'),
(76, 'Oslo', 'Rockefeller Oslo', '2026-03-07', 'A Matter of Time Tour'),
(77, 'Stockholm', '3Arena', '2026-03-09', 'A Matter of Time Tour'),
(78, 'Helsinki', 'Olympic Stadium', '2026-03-11', 'A Matter of Time Tour'),
(79, 'Budapest', 'Laszlo Papp Budapest Sports Arena', '2026-03-13', 'A Matter of Time Tour'),
(80, 'Prague', 'Letnany', '2026-03-14', 'A Matter of Time Tour'),
(81, 'Dublin', '3Arena', '2026-03-20', 'A Matter of Time Tour'),
(82, 'Bergamo', 'Teatro Donizetti', '2026-03-22', 'A Matter of Time Tour'),
(83, 'Reykjavík', 'Laugardalshöll', '2026-03-24', 'A Matter of Time Tour'),
(84, 'Lisbon', 'Altice Arena', '2026-03-26', 'A Matter of Time Tour'),
(85, 'Barcelona', 'Palau Sant Jordi', '2026-03-28', 'A Matter of Time Tour');



$conn->close();
