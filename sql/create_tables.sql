CREATE DATABASE adamdevproject;
USE adamdevproject;

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tours
CREATE TABLE tours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100),
  venue VARCHAR(100),
  date DATE
);

-- Albums
CREATE TABLE albums (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  release_year INT,
  cover_img VARCHAR(255)
);

-- Songs
CREATE TABLE songs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  album_id INT,
  title VARCHAR(100),
  duration VARCHAR(10),
  FOREIGN KEY (album_id) REFERENCES albums(id)
);

-- Merch
CREATE TABLE merch (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  price DECIMAL(6,2),
  image VARCHAR(255),
  description TEXT
);


