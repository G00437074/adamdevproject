// ============================
// MODAL OPEN / CLOSE
// ============================

// Open the login modal
// Called when the user clicks the "Login" link
function openLogin(e) {
    // Prevent default link behaviour (page jump)
    if (e) e.preventDefault();
  
    // Get the login modal element
    const m = document.getElementById('loginModal');
  
    // Show the modal if it exists
    if (m) m.style.display = 'flex';
  
    // Prevent default behaviour
    return false;
  }
  
  // Close the login modal
  function closeLogin() {
    const m = document.getElementById('loginModal');
  
    // Hide the modal if it exists
    if (m) m.style.display = 'none';
  
    return false;
  }
  
  // Switch from Login modal to Create Account modal
  function switchToCreate() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
  
    // Hide login modal
    if (login)  login.style.display  = 'none';
  
    // Show create account modal
    if (create) create.style.display = 'flex';
  
    return false;
  }
  
  // Switch from Create Account modal back to Login modal
  function switchToLogin() {
    const login  = document.getElementById('loginModal');
    const create = document.getElementById('createUserModal');
  
    // Hide create account modal
    if (create) create.style.display = 'none';
  
    // Show login modal
    if (login)  login.style.display  = 'flex';
  
    return false;
  }
  
  // Close the Create Account modal
  function closeCreate() {
    const m = document.getElementById('createUserModal');
  
    // Hide the modal
    if (m) m.style.display = 'none';
  
    return false;
  }
  
  
  // ============================
  // LOGIN
  // ============================
  
  // Called when the login form is submitted
  async function loginUser() {
  
    // Get the login form and message element
    const form = document.getElementById('loginForm');
    const msg  = document.getElementById('loginMessage');
  
    // Safety check
    if (!form || !msg) return false;
  
    // Show loading message
    msg.textContent = 'Logging in...';
  
    // Collect form data (username + password)
    const formData = new FormData(form);
  
    try {
      // Send login request to the server
      const res = await fetch('/adamdevproject/api/login.php', {
        method: 'POST',
        body: formData,
        credentials: 'include',              // Include session cookies
        headers: { 'Accept': 'application/json' }
      });
  
      // Convert response to JSON
      const data = await res.json();
  
      // If login was successful
      if (data.success) {
        msg.textContent = 'Logged in!';
  
        // Reload page so PHP can re-render the navbar
        window.location.reload();
      } else {
        // Show error message from server
        msg.textContent = data.message || 'Login failed';
      }
    } catch (err) {
      // Handle network or server errors
      console.error(err);
      msg.textContent = 'Login error. Check console.';
    }
  
    // Stop normal form submission
    return false;
  }
  
  
  // ============================
  // LOGOUT
  // ============================
  
  // Attach logout behaviour after page loads
  document.addEventListener('DOMContentLoaded', () => {
  
    // Get the logout link (only exists if user is logged in)
    const logout = document.getElementById('logoutLink');
  
    // Stop if logout link does not exist
    if (!logout) return;
  
    // Handle logout click
    logout.addEventListener('click', async (e) => {
      e.preventDefault();
  
      try {
        // Call logout API to destroy session
        const res = await fetch('/adamdevproject/api/logout.php', {
          method: 'POST',
          credentials: 'include',
          headers: { 'Accept': 'application/json' }
        });
  
        const data = await res.json();
  
        // If logout was successful, reload page
        if (data.success) {
          window.location.reload();
        }
      } catch (err) {
        console.error(err);
      }
    });
  });
  
  
  // ============================
  // CREATE ACCOUNT
  // ============================
  
  // Called when the Create Account form is submitted
  function createUser() {
  
    // Get form and message display
    const form = document.getElementById('createForm');
    const msg  = document.getElementById('createMessage');
  
    // Safety check
    if (!form || !msg) return false;
  
    // Collect form data
    const formData = new FormData(form);
  
    // Extract username and password
    const u = (formData.get('username') || '').trim();
    const p = (formData.get('password') || '');
  
    // Basic validation
    if (!u || !p) {
      msg.textContent = 'Please enter a username and password.';
      return false;
    }
  
    // Password strength validation
    if (p.length < 8 || !/[^\w]/.test(p)) {
      msg.textContent =
        'Password must be at least 8 characters and contain a special character.';
      return false;
    }
  
    // Send create account request
    fetch('/adamdevproject/api/create_user.php', {
      method: 'POST',
      body: formData,
      credentials: 'include'
    })
    .then(r => r.text())
    .then(text => {
  
      // Show server response
      msg.textContent = text;
  
      // If account was created, switch back to login modal
      if (/created/i.test(text)) {
        setTimeout(() => switchToLogin(), 1200);
      }
    })
    .catch(() => {
      msg.textContent = 'Error creating account.';
    });
  
    // Stop normal form submission
    return false;
  }
  