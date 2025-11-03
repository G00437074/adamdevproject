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

  // ----- Open/Close helpers -----
function openCreate(e){ if(e) e.preventDefault(); const m=document.getElementById('createUserModal'); if(m){ m.style.display='flex'; } return false; }
function closeCreate(){ const m=document.getElementById('createUserModal'); if(m){ m.style.display='none'; } return false; }

function switchToCreate(){ 
  const login = document.getElementById('loginModal');
  const create = document.getElementById('createUserModal');
  if (login) login.style.display = 'none';
  if (create) create.style.display = 'flex';
  return false;
}

function switchToLogin(){ 
  const login = document.getElementById('loginModal');
  const create = document.getElementById('createUserModal');
  if (create) create.style.display = 'none';
  if (login) login.style.display = 'flex';
  return false;
}

// Close create modal if clicking backdrop
document.addEventListener('DOMContentLoaded', () => {
  const createModal = document.getElementById('createUserModal');
  const closeCreateBtn = document.getElementById('closeCreate');
  if (createModal){
    window.addEventListener('click', (e) => { if (e.target === createModal) closeCreate(); });
  }
  if (closeCreateBtn){
    closeCreateBtn.addEventListener('click', closeCreate);
  }
});

// ----- Create Account submit via Fetch -----
function createUser() {
  const form = document.getElementById('createForm');
  const msg  = document.getElementById('createMessage');
  const API_BASE = '/adamdevproject/api/'; // adjust if your folder name is different
  const formData = new FormData(form);

  // basic client-side checks (optional)
  const u = (formData.get('username') || '').trim();
  const p = (formData.get('password') || '');
  if (!u || !p) {
    msg.textContent = 'Please enter a username and password.';
    return false;
  }

  fetch(API_BASE + 'create_user.php', { method: 'POST', body: formData })
    .then(async r => {
      const text = await r.text();     // API returns plain text message
      msg.textContent = text;
      // if success, bounce back to login after a moment
      if (/user created/i.test(text) || /created/i.test(text)) {
        setTimeout(() => { switchToLogin(); }, 1200);
      }
    })
    .catch(() => { msg.textContent = 'Error creating account. Please try again.'; });

  return false; // prevent normal submit
}

  