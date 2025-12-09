<?php
// Start or resume session BEFORE output
session_start();

$isLoggedIn = !empty($_SESSION['user_id']);
$username   = $isLoggedIn ? $_SESSION['username'] : '';
?>

<header class="header-banner">
  <img src="images/laufey_header.jpeg" alt="Laufey Header" class="header-image">
</header>

<nav class="site-nav">
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="tour.php">Tour</a></li>
    <li><a href="albums.php">Albums</a></li>
    <li><a href="merch.php">Merch</a></li>

    <?php if ($isLoggedIn): ?>
      <li class="nav-user">Hi, <?= htmlspecialchars($username) ?></li>
      <li><a href="#" id="logoutLink">Logout</a></li>
    <?php else: ?>
      <li><a href="#" id="loginLink" onclick="return openLogin(event);">Login</a></li>
    <?php endif; ?>
  </ul>
</nav>

<!-- Login modal -->
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
    <p style="margin-top:10px;">
      Don’t have an account?
      <a href="#" onclick="return switchToCreate();">Create one</a>
    </p>
  </div>
</div>

<!-- Create Account modal -->
<div id="createUserModal" class="login-modal" style="display:none;">
  <div class="login-box">
    <span id="closeCreate" class="close-btn" onclick="return closeCreate();">&times;</span>
    <h2>Create Account</h2>
    <form id="createForm" onsubmit="return createUser();">
      <input type="text" id="newUsername" name="username" placeholder="Username" required><br>
      <input type="email" id="newEmail" name="email" placeholder="Email (optional)"><br>
      <input type="password" id="newPassword" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn">Create Account</button>
      <p id="createMessage"></p>
    </form>
    <p style="margin-top:10px;">
      Already have an account?
      <a href="#" onclick="return switchToLogin();">Log in</a>
    </p>
  </div>
</div>

<script>
  // Open / close login modal
  function openLogin(e) {
    if (e) e.preventDefault();
    const m = document.getElementById('loginModal');
    if (m) m.style.display = 'flex';
    return false;
  }

  function closeLogin() {
    const m = document.getElementById('loginModal');
    if (m) m.style.display = 'none';
    return false;
  }

  // Switch from Login -> Create Account
  function switchToCreate() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
    if (login)  login.style.display  = 'none';
    if (create) create.style.display = 'flex';
    return false;
  }

  // Switch from Create Account -> Login
  function switchToLogin() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
    if (create) create.style.display = 'none';
    if (login)  login.style.display  = 'flex';
    return false;
  }

  // Close Create Account modal
  function closeCreate() {
    const m = document.getElementById('createUserModal');
    if (m) m.style.display = 'none';
    return false;
  }

  // Attach logout click (only exists when logged in)
  document.addEventListener('DOMContentLoaded', () => {
    const logout = document.getElementById('logoutLink');
    if (logout) {
      logout.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
          const res = await fetch('/adamdevproject/api/logout.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Accept': 'application/json' }
          });
          const data = await res.json();
          if (data.success) {
            // Reload so PHP re-renders nav as logged-out
            window.location.reload();
          }
        } catch (err) {
          console.error(err);
        }
      });
    }
  });
</script>

<!-- Main login/create JS (loginUser, createUser, etc.) -->
<script src="js/login.js"></script>
