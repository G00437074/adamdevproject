<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Laufey Music</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- Full-width header image -->
  <header class="header-banner">
    <img src="images/laufey_header.jpeg" alt="Laufey Header" class="header-image">
  </header>

  <!-- Navigation Bar -->
  <nav class="site-nav">
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="albums.php">Albums</a></li>
      <li><a href="merch.php">Merch</a></li>
      <!-- This will open the login popup instead of going to another page -->
      <!--<li><a href="#" id="loginLink">Login</a></li>-->
      <li><a href="#" id="loginLink" onclick="return openLogin(event);">Login</a></li>
    </ul>
  </nav>

  <div id="loginModal" class="login-modal" style="display:none;">
  <div class="login-box">
    <span id="closeLogin" class="close-btn" onclick="return closeLogin();">&times;</span>
    <h2>Login</h2>
    <form id="loginForm" onsubmit="return loginUser();">
      <input type="text" id="username" name="username" placeholder="Username" required><br>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn">Login</button>
      <p id="loginMessage"></p>
    </form>
  </div>
</div>


<script>
  function openLogin(e){
    if(e) e.preventDefault();
    var m = document.getElementById('loginModal');
    if (m){ m.style.display = 'flex'; } // flex centers it (per your CSS)
    return false;
  }
  function closeLogin(){
    var m = document.getElementById('loginModal');
    if (m){ m.style.display = 'none'; }
    return false;
  }
</script>
<script src="js/login.js"></script>
</body>
</html>

</body>
</html>

