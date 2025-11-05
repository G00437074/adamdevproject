<?php 
// Start or resume the current session
// This allows PHP to keep track of user data (like login status) across different pages
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Laufey Music</title>

  <!-- Website favicon (small icon in the browser tab) -->
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

  <!-- Link to the external CSS file for page styling -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!-- =========================
       HEADER SECTION
       ========================= -->
  <!-- Full-width header image that appears at the top of the page -->
  <header class="header-banner">
    <img src="images/laufey_header.jpeg" alt="Laufey Header" class="header-image">
  </header>

  <!-- =========================
       NAVIGATION BAR
       ========================= -->
  <nav class="site-nav">
    <ul class="nav-links">
      <!-- Menu links to different pages on the website -->
      <li><a href="index.php">Home</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="albums.php">Albums</a></li>
      <li><a href="merch.php">Merch</a></li>

      <!-- The Login link doesn’t go to a new page.
           Instead, it opens a popup (handled by JavaScript). -->
      <li><a href="#" id="loginLink" onclick="return openLogin(event);">Login</a></li>
    </ul>
  </nav>

  <!-- =========================
       LOGIN POPUP WINDOW
       ========================= -->
  <!-- Hidden by default (display:none); will show when user clicks “Login” -->
  <div id="loginModal" class="login-modal" style="display:none;">
    <div class="login-box">
      <!-- “×” close button -->
      <span id="closeLogin" class="close-btn" onclick="return closeLogin();">&times;</span>

      <h2>Login</h2>

      <!-- Login form: calls loginUser() JavaScript function when submitted -->
      <form id="loginForm" onsubmit="return loginUser();">
        <input type="text" id="username" name="username" placeholder="Username" required><br>
        <input type="password" id="password" name="password" placeholder="Password" required><br>
        <button type="submit" class="btn">Login</button>

        <!-- Message area for success/error feedback -->
        <p id="loginMessage"></p>
      </form>

      <!-- Link to switch to the Create Account popup -->
      <p style="margin-top:10px;">
        Don’t have an account? 
        <a href="#" onclick="return switchToCreate();">Create one</a>
      </p>
    </div>
  </div>

  <!-- =========================
       CREATE ACCOUNT POPUP WINDOW
       ========================= -->
  <!-- Hidden by default; appears when user chooses to create a new account -->
  <div id="createUserModal" class="login-modal" style="display:none;">
    <div class="login-box">
      <!-- Close button -->
      <span id="closeCreate" class="close-btn" onclick="return closeCreate();">&times;</span>

      <h2>Create Account</h2>

      <!-- Create account form: calls createUser() JavaScript function -->
      <form id="createForm" onsubmit="return createUser();">
        <input type="text" id="newUsername" name="username" placeholder="Username" required><br>
        <input type="email" id="newEmail" name="email" placeholder="Email (optional)"><br>
        <input type="password" id="newPassword" name="password" placeholder="Password" required><br>
        <button type="submit" class="btn">Create Account</button>

        <!-- Message area for success/error feedback -->
        <p id="createMessage"></p>
      </form>

      <!-- Link to switch back to the Login popup -->
      <p style="margin-top:10px;">
        Already have an account? 
        <a href="#" onclick="return switchToLogin();">Log in</a>
      </p>
    </div>
  </div>

  <!-- =========================
       INLINE JAVASCRIPT FUNCTIONS
       ========================= -->
  <script>
    // Opens the Login popup when the user clicks “Login” in the navigation bar
    function openLogin(e) {
      if (e) e.preventDefault(); // Prevent the link from reloading the page
      var m = document.getElementById('loginModal');
      if (m) {
        m.style.display = 'flex'; // 'flex' centers the modal on screen (per your CSS)
      }
      return false; // Prevents default link behavior
    }

    // Closes the Login popup when the “×” is clicked
    function closeLogin() {
      var m = document.getElementById('loginModal');
      if (m) {
        m.style.display = 'none'; // Hides the modal again
      }
      return false;
    }
  </script>

  <!-- External JavaScript file for handling login and account creation logic -->
  <script src="js/login.js"></script>
</body>

</html>
