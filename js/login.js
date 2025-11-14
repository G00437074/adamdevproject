document.addEventListener('DOMContentLoaded', () => {
  const loginLink  = document.getElementById('loginLink');
  const loginModal = document.getElementById('loginModal');
  const closeLogin = document.getElementById('closeLogin');

  const API_BASE = '/adamdevproject/api/'; // adjust if needed

  // Show modal
  if (loginLink && loginModal) {
    loginLink.addEventListener('click', e => {
      e.preventDefault();
      loginModal.classList.add('show');
    });
  }

  // Close modal
  if (closeLogin && loginModal) {
    closeLogin.addEventListener('click', () => {
      loginModal.classList.remove('show');
    });
  }

  window.addEventListener('click', e => {
    if (e.target === loginModal) loginModal.classList.remove('show');
  });

  // Logout link handler (server-side session)
  const logoutLink = document.getElementById('logoutLink');
  if (logoutLink) {
    logoutLink.addEventListener('click', e => {
      e.preventDefault();
      fetch(API_BASE + 'logout.php')
        .then(() => location.reload())
        .catch(() => alert('Logout failed.'));
    });
  }
});

// Called by form: <form id="loginForm" onsubmit="return loginUser();">
function loginUser() {
  const form = document.getElementById('loginForm');
  const msg  = document.getElementById('loginMessage');
  const modal = document.getElementById('loginModal');
  const API_BASE = '/adamdevproject/api/';

  const formData = new FormData(form);

  fetch(API_BASE + 'login.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
      msg.textContent = data.message || '';
      if (data.success) {
        modal.classList.remove('show');
        const loginLink = document.getElementById('loginLink');
        if (loginLink) {
          const parentLi = loginLink.parentElement;
          const welcome = document.createElement('span');
          welcome.textContent = `Welcome, ${data.username}! `;
          welcome.style.marginRight = '8px';
          parentLi.prepend(welcome);
          loginLink.textContent = 'Logout';
          loginLink.removeAttribute('id');
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
      console.error(err);
      msg.textContent = 'Error logging in. Check console.';
    });

  return false;
}
