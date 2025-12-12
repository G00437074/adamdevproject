// Run this code only after the HTML document has fully loaded
document.addEventListener('DOMContentLoaded', () => {

  // Get references to login-related elements in the navigation
  const loginLink  = document.getElementById('loginLink');   // "Login" link
  const loginModal = document.getElementById('loginModal');  // Login modal container
  const closeLogin = document.getElementById('closeLogin');  // Close (×) button

  // Base path for API requests (adjust if project folder changes)
  const API_BASE = '/adamdevproject/api/';

  // ----------------------------
  // Show login modal
  // ----------------------------

  // Open the login modal when the Login link is clicked
  if (loginLink && loginModal) {
    loginLink.addEventListener('click', e => {
      e.preventDefault();               // Stop normal link behaviour
      loginModal.classList.add('show'); // Show modal via CSS class
    });
  }

  // ----------------------------
  // Close login modal
  // ----------------------------

  // Close the modal when the close (×) button is clicked
  if (closeLogin && loginModal) {
    closeLogin.addEventListener('click', () => {
      loginModal.classList.remove('show');
    });
  }

  // Close the modal when clicking outside the modal content
  window.addEventListener('click', e => {
    if (e.target === loginModal) {
      loginModal.classList.remove('show');
    }
  });

  // ----------------------------
  // Logout handling
  // ----------------------------

  // Get the Logout link (only exists if user is logged in)
  const logoutLink = document.getElementById('logoutLink');

  if (logoutLink) {
    logoutLink.addEventListener('click', e => {
      e.preventDefault(); // Prevent default link behaviour

      // Call the logout API to destroy the PHP session
      fetch(API_BASE + 'logout.php')
        .then(() => {
          // Reload the page so PHP updates the navigation
          location.reload();
        })
        .catch(() => {
          // Display error if logout fails
          alert('Logout failed.');
        });
    });
  }
});

// ======================================================
// Login form handler
// Called by: <form id="loginForm" onsubmit="return loginUser();">
// ======================================================

function loginUser() {

  // Get references to form elements
  const form  = document.getElementById('loginForm');      // Login form
  const msg   = document.getElementById('loginMessage');   // Message display area
  const modal = document.getElementById('loginModal');     // Login modal

  // Base path for API requests
  const API_BASE = '/adamdevproject/api/';

  // Collect form data (username + password)
  const formData = new FormData(form);

  // Send login request to the server
  fetch(API_BASE + 'login.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json()) // Convert response to JSON
    .then(data => {

      // Display message returned by the server
      msg.textContent = data.message || '';

      // If login was successful
      if (data.success) {

        // Hide the login modal
        modal.classList.remove('show');

        // Find the Login link in the navigation
        const loginLink = document.getElementById('loginLink');

        if (loginLink) {
          // Replace Login link with welcome message + Logout behaviour
          const parentLi = loginLink.parentElement;

          // Create a welcome message
          const welcome = document.createElement('span');
          welcome.textContent = `Welcome, ${data.username}! `;
          welcome.style.marginRight = '8px';

          // Insert welcome message before the login link
          parentLi.prepend(welcome);

          // Change Login link to Logout
          loginLink.textContent = 'Logout';
          loginLink.removeAttribute('id');

          // Attach logout behaviour to the updated link
          loginLink.addEventListener('click', e => {
            e.preventDefault();
            fetch(API_BASE + 'logout.php')
              .then(r => r.json())
              .then(() => location.reload());
          });
        }
      }
    })
    .catch(err => {
      // Handle errors (network or server issues)
      console.error(err);
      msg.textContent = 'Error logging in. Check console.';
    });

  // Prevent normal form submission
  return false;
}
