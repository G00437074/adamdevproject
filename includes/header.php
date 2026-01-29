<?php
// Start or resume the session BEFORE any HTML output
// This allows us to read login information stored in $_SESSION
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is logged in by seeing if a user_id exists
$isLoggedIn = !empty($_SESSION['user_id']);

// Store the username if logged in, otherwise use an empty string
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>


<!-- Header banner image at the top of the page -->
<header class="header-banner">
  <img src="images/laufey_header.jpeg" alt="Laufey Header" class="header-image">
</header>

<body>
<!-- Main site navigation -->
<nav class="site-nav">
  <ul class="nav-links">

    <!-- Always-visible navigation links -->
    <li><a href="index.php">Home</a></li>
    <li><a href="tour.php">Tour</a></li>
    <li><a href="albums.php">Albums</a></li>
    <li><a href="merch.php">Merch</a></li>

    <!-- Show different options depending on login status -->
    <?php if ($isLoggedIn): ?>

      <!-- Greet the logged-in user (escaped for security) -->
      <li class="nav-user">Hi, <?= htmlspecialchars($username) ?></li>

      <!-- Logout link (handled with JavaScript) -->
      <li><a href="#" id="logoutLink">Logout</a></li>

    <?php else: ?>

      <!-- Login link opens the login modal -->
      <li>
        <a href="#" id="loginLink" onclick="return openLogin(event);">Login</a>
      </li>

    <?php endif; ?>
  </ul>
</nav>



<!-- Login modal (hidden by default) -->
<div id="loginModal" class="login-modal" style="display:none;">
  <div class="login-box">

    <!-- Close button -->
    <span id="closeLogin" class="close-btn" onclick="return closeLogin();">&times;</span>

    <h2>Login</h2>

    <!-- Login form handled by JavaScript (AJAX) -->
    <form id="loginForm" onsubmit="return loginUser();">
      <input type="text" id="username" name="username" placeholder="Username" required><br>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn">Login</button>
      <p id="loginMessage"></p>
    </form>

    <!-- Link to switch to Create Account modal -->
    <p style="margin-top:10px;">
      Don’t have an account?
      <a href="#" onclick="return switchToCreate();">Create one</a>
    </p>
  </div>
</div>


<!-- Create Account modal (hidden by default) -->
<div id="createUserModal" class="login-modal" style="display:none;">
  <div class="login-box">

    <!-- Close button -->
    <span id="closeCreate" class="close-btn" onclick="return closeCreate();">&times;</span>

    <h2>Create Account</h2>

    <!-- Account creation form handled with JavaScript -->
    <form id="createForm" onsubmit="return createUser()">
      <input type="text" id="newUsername" name="username" placeholder="Username" required><br>
      <input type="email" id="newEmail" name="email" placeholder="Email (optional)"><br>
      <input type="password" id="newPassword" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn">Create Account</button>
      <p id="createMessage"></p>
    </form>

    <!-- Link to switch back to Login modal -->
    <p style="margin-top:10px;">
      Already have an account?
      <a href="#" onclick="return switchToLogin();">Log in</a>
    </p>
  </div>
</div>


<!-- MOVED THIS CODE TO AUTH.JS, JUST KEEPING FOR VERSION CONTROL-->
<!-- <script>
  // ----------------------------
  // Login modal open / close
  // ----------------------------

  // Open the login modal
  function openLogin(e) {
    if (e) e.preventDefault();
    const m = document.getElementById('loginModal');
    if (m) m.style.display = 'flex';
    return false;
  }

  // Close the login modal
  function closeLogin() {
    const m = document.getElementById('loginModal');
    if (m) m.style.display = 'none';
    return false;
  }

  // ----------------------------
  // Switch between modals
  // ----------------------------

  // Switch from Login modal to Create Account modal
  function switchToCreate() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
    if (login)  login.style.display  = 'none';
    if (create) create.style.display = 'flex';
    return false;
  }

  // Switch from Create Account modal back to Login modal
  function switchToLogin() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
    if (create) create.style.display = 'none';
    if (login)  login.style.display  = 'flex';
    return false;
  }

  // Close the Create Account modal
  function closeCreate() {
    const m = document.getElementById('createUserModal');
    if (m) m.style.display = 'none';
    return false;
  }

  // ----------------------------
  // Logout handling
  // ----------------------------

  // Attach logout click handler once the page has loaded
  document.addEventListener('DOMContentLoaded', () => {
    const logout = document.getElementById('logoutLink');

    // Logout link only exists when the user is logged in
    if (logout) {
      logout.addEventListener('click', async (e) => {
        e.preventDefault();

        try {
          // Call the logout API to destroy the session
          const res = await fetch('/adamdevproject/api/logout.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Accept': 'application/json' }
          });

          const data = await res.json();

          // Reload page so PHP re-renders the nav as logged out
          if (data.success) {
            window.location.reload();
          }
        } catch (err) {
          console.error(err);
        }
      });
    }
  });

  // ----------------------------
  // Create account logic
  // ----------------------------

  function createUser() {
    console.log('createUser called');

    // Get form and message elements
    const form = document.getElementById('createForm');
    const msg  = document.getElementById('createMessage');

    // Base API path for requests
    const API_BASE = '/adamdevproject/api/';

    // Collect form data
    const formData = new FormData(form);

    const u = (formData.get('username') || '').trim();
    const p = (formData.get('password') || '');

    // Client-side validation before sending to PHP
    if (!u || !p) {
      msg.textContent = 'Please enter a username and password.';
      return false;
    }

    // Password rules: minimum 8 characters and at least one special character
    if (p.length < 8 || !/[^\w]/.test(p)) {
      msg.textContent = 'Password must be at least 8 characters and contain a special character.';
      return false;
    }

    // Send account creation request to the server
    fetch(API_BASE + 'create_user.php', {
        method: 'POST',
        body: formData,
        credentials: 'include',
        headers: { 'Accept': 'text/plain' }
      })
      .then(async r => {
        const text = await r.text();

        // Display response from PHP
        console.log('create_user response:', text);
        msg.textContent = text;

        // If account was created, switch back to Login modal
        if (/user created/i.test(text) || /created/i.test(text)) {
          setTimeout(() => { switchToLogin(); }, 1200);
        }
      })
      .catch(err => {
        console.error(err);
        msg.textContent = 'Error creating account. Please try again.';
      });

    // Prevent normal form submission
    return false;
  }
</script> -->


<!-- Main login/create JS (loginUser, createUser, etc.) -->
<script src="/adamdevproject/js/auth.js"></script>


<!-- Close the body of the HTML document -->
</body>

<!-- Close the HTML document -->
</html>