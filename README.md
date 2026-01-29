🎶 Laufey Music Website – DWDF Project
This project is a dynamic PHP/MySQL website created as part of the Dynamic Web Development module.
The site is themed around the artist Laufey and includes albums, songs, tour dates, merchandise, user accounts, a shopping cart, and a checkout system.

 👨‍💻 Author
- Adam Devlin
- Third-Year Computing & Digital Media Student
- Atlantic Technological University (ATU)


📌 Features
🎵 Albums page with cover images and embedded Spotify players
🎼 Dynamic tracklists loaded via AJAX
🛍 Merch store with cart functionality (PHP sessions)
🧾 Checkout system (demo payment, no real processing)
👤 User accounts (register, login, logout)
🗓 Tour dates with filtering and search
🍪 Cookies used for remembering user preferences
🗄 MySQL database with full schema and seed data
⚙️ One-click database setup


📂 Project Structure (Key Files)
🛠 Technologies Used
PHP (server-side logic)
MySQL (database)
PDO (secure database access)
JavaScript (Fetch API) for AJAX
HTML5 / CSS3
Spotify Embed Player


/includes
  ├── header.php
  ├── footer.php
  └── db_connect.php

/api
  ├── login.php
  ├── logout.php
  ├── create_user.php
  ├── get_album_songs.php
  ├── add_to_cart.php
  ├── remove_from_cart.php
  └── place_order.php

/css
  └── style.css

/js
  ├── login.js
  ├── merch.js
  ├── checkout.js
  └── tour.js

/images
  ├── albums/
  ├── merch/
  └── laufey_image.jpg

setup_db.php
index.php
albums.php
merch.php
cart.php
checkout.php


🚀 How to Run the Project (Important)
1️⃣ Requirements
- Local server environment (XAMPP / WAMP / MAMP)
- PHP 8+
- MySQL



2️⃣ Installation Steps
- Clone or download the repository
- Place the project folder into your local server directory

- Start Apache and MySQL
- Open the database setup file in your browser

✅ This will:
- Create the database adamdevproject
- Create all tables
- Insert albums, songs, merch, and tour data

- Fianlly, open the site in your browser


🗄 Database Notes
- Database name: adamdevproject
- The database schema matches the PHP code exactly
- setup_db.php is provided so no manual SQL import is required
- User and order data is created dynamically through the site


🔐 Authentication
- Users can create accounts and log in
- Passwords are securely hashed using password_hash()
- Sessions are handled safely using:
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


🖼 Images & Assets
- All images are stored locally inside the images/ folder
- Paths are relative, making the project portable
- No external image dependencies


🎧 Spotify Integration
- Album pages use official Spotify embed URLs
- Embed links are stored in the database (spotify_embed column)
- No Spotify API key required


⚠️ Important Notes
- This project is for educational purposes only
- No real payments are processed
- Card details in checkout are for demonstration only

⚠️ Known Issues / Limitations
💳 Checkout is for demonstration only
- The checkout form does not process real payments. Card details are not validated or stored and are used purely to demonstrate form handling and database inserts.
🔐 No password reset or email verification
- User accounts support registration and login, but features such as password reset, email verification, or account management are not implemented.
🧪 Minimal form validation
- Client-side and server-side validation is basic and focuses on required fields. More robust validation and error handling could be added in a production system.
📱 Limited mobile optimisation
- The site is responsive, but some layouts (such as large tables and album embeds) may not be fully optimised for very small screen sizes.
🎧 Spotify embeds rely on external service
- Album playback uses Spotify embed URLs. If Spotify is unavailable or blocks embeds, players may not load.
🗄 Local environment dependency
- The project is designed to run on a local PHP/MySQL environment (e.g. XAMPP). It is not configured for deployment to a live hosting platform.
🔒 No role-based access control
- All logged-in users have the same permissions. There is no admin dashboard or role separation.






