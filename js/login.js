// ===============================
// LOGIN FUNCTION
// ===============================
// This function is called when the login form is submitted:
// <form id="loginForm" onsubmit="return loginUser();">
function loginUser() {
  // Get references to the form, message area, and login modal
  const form = document.getElementById('loginForm');
  const msg  = document.getElementById('loginMessage');
  const modal = document.getElementById('loginModal');

  // Base path where your PHP API files (like login.php) are stored
  const API_BASE = '/adamdevproject/api/';

  // Collect all form fields (username + password) into a FormData object
  const formData = new FormData(form);

  // Send the form data to the server using Fetch API (POST method)
  fetch(API_BASE + 'login.php', { method: 'POST', body: formData })
    // Convert the server response to JSON
    .then(r => r.json())
    .then(data => {
      // Show the message from the server (e.g. “Login successful!” or “Invalid password”)
      msg.textContent = data.message || '';

      // If login was successful, update the UI
      if (data.success) {
        // Hide the login modal
        modal.classList.remove('show');

        // Find the login link in the navigation bar
        const loginLink = document.getElementById('loginLink');
        if (loginLink) {
          // Get the parent <li> element that contains the login link
          const parentLi = loginLink.parentElement;

          // Create a new <span> to show a welcome message
          const welcome = document.createElement('span');
          welcome.textContent = `Welcome, ${data.username}! `;
          welcome.style.marginRight = '8px';

          // Insert the welcome message before the login link
          parentLi.prepend(welcome);

          // Change the link text from “Login” to “Logout”
          loginLink.textContent = 'Logout';

          // Remove the old ID so it’s no longer treated as a login link
          loginLink.removeAttribute('id');

          // Add a click event so this new “Logout” link logs the user out
          loginLink.addEventListener('click', e => {
            e.preventDefault();
            fetch(API_BASE + 'logout.php')       // Call the PHP logout script
              .then(r => r.json())               // Parse the response
              .then(() => location.reload());    // Reload the page to reflect logout
          });
        }
      }
    })
    // If something goes wrong (like server error or bad connection)
    .catch(err => {
      console.error(err); // Log the error for debugging
      msg.textContent = 'Error logging in. Check console.';
    });

  // Return false to prevent the browser from doing a normal form submission (page reload)
  return false;
}

// ===============================
// HELPER FUNCTIONS FOR MODALS
// ===============================

// Open the "Create Account" popup window
function openCreate(e) {
  if (e) e.preventDefault(); // Stop default link behavior
  const m = document.getElementById('createUserModal');
  if (m) { 
    m.style.display = 'flex'; // Show the modal (CSS 'flex' centers it)
  }
  return false;
}

// Close the "Create Account" popup window
function closeCreate() {
  const m = document.getElementById('createUserModal');
  if (m) {
    m.style.display = 'none'; // Hide the modal again
  }
  return false;
}

// Switch from the Login popup to the Create Account popup
function switchToCreate() { 
  const login = document.getElementById('loginModal');
  const create = document.getElementById('createUserModal');
  if (login) login.style.display = 'none';  // Hide login
  if (create) create.style.display = 'flex'; // Show create
  return false;
}

// Switch from the Create Account popup back to the Login popup
function switchToLogin() { 
  const login = document.getElementById('loginModal');
  const create = document.getElementById('createUserModal');
  if (create) create.style.display = 'none'; // Hide create
  if (login) login.style.display = 'flex';   // Show login
  return false;
}

// ===============================
// BACKDROP CLICK HANDLER
// ===============================
// This closes the Create Account popup when the user clicks outside the box
document.addEventListener('DOMContentLoaded', () => {
  const createModal = document.getElementById('createUserModal');
  const closeCreateBtn = document.getElementById('closeCreate');

  if (createModal) {
    window.addEventListener('click', (e) => {
      if (e.target === createModal) closeCreate(); // Close if clicked outside
    });
  }

  if (closeCreateBtn) {
    // Close button ("×") calls the closeCreate() function
    closeCreateBtn.addEventListener('click', closeCreate);
  }
});

// ===============================
// CREATE ACCOUNT FUNCTION
// ===============================
// Called when the create account form is submitted:
// <form id="createForm" onsubmit="return createUser();">
function createUser() {
  const form = document.getElementById('createForm');
  const msg  = document.getElementById('createMessage');
  const API_BASE = '/adamdevproject/api/'; // Adjust this if your folder name changes
  const formData = new FormData(form);

  // --- Basic input validation on the client side ---
  const u = (formData.get('username') || '').trim();
  const p = (formData.get('password') || '');
  if (!u || !p) {
    msg.textContent = 'Please enter a username and password.';
    return false;
  }

  // Send the form data to the server (create_user.php)
  fetch(API_BASE + 'create_user.php', { method: 'POST', body: formData })
    .then(async r => {
      // Server returns plain text (not JSON), so we read it as text
      const text = await r.text();
      msg.textContent = text; // Show the server’s response message

      // If the text includes “user created” or “created”, assume it succeeded
      if (/user created/i.test(text) || /created/i.test(text)) {
        // Automatically switch back to the Login modal after 1.2 seconds
        setTimeout(() => { switchToLogin(); }, 1200);
      }
    })
    .catch(() => {
      // Show an error message if something fails
      msg.textContent = 'Error creating account. Please try again.';
    });

  // Prevent the form from reloading the page
  return false;
}
