<?php
try {
    // Create a new PDO (PHP Data Object) connection to the MySQL database.
    // 'mysql:host=localhost;dbname=adamdevproject' means:
    //   - The database server is running on the same computer (localhost)
    //   - The database name is 'adamdevproject'
    // 'root' is the MySQL username (default for local servers like XAMPP or WAMP)
    // The empty string '' is the password (default is blank for local development)
    $pdo = new PDO('mysql:host=localhost;dbname=adamdevproject', 'root', '');

    // Enable exception mode so that PDO throws errors when something goes wrong.
    // This helps you catch and handle database issues more easily.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // If the connection fails, this block runs.
    // 'die()' stops the script and displays an error message.
    // In production, you might want to hide this message for security reasons.
    die("Database connection failed: " . $e->getMessage());
}
?>
