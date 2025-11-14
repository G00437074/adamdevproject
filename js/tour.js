// js/tour.js
(function () {
    const listEl        = document.getElementById('tour-list');
    if (!listEl) return; // safety: if this page doesn't have the tour list, stop.
  
    const searchEl      = document.getElementById('tourSearch');
    const filterButtons = document.querySelectorAll('.tour-filter');
  
    // 🔧 Adjust this if your project folder is different
    const API_BASE = '/adamdevproject/api/';
  
    let currentMode = 'current'; // 'current' or 'past'
    let currentTour = '';        // specific tour_name, or '' for all tours
  
    function loadTours(q = '') {
      const params = new URLSearchParams();
      params.set('mode', currentMode);
      if (q) params.set('q', q);
      if (currentTour) params.set('tour_name', currentTour);
  
      const url = API_BASE + 'get_tours.php?' + params.toString();
  
      listEl.innerHTML = 'Loading tour dates...';
  
      fetch(url)
        .then(res => {
          if (!res.ok) throw new Error('Network error');
          return res.json();
        })
        .then(data => {
          listEl.innerHTML = data.html || 'No results.';
        })
        .catch(err => {
          console.error(err);
          listEl.innerHTML = 'Unable to load tour dates.';
        });
    }
  
    // Initial load
    loadTours();
  
    // 🔄 Current / Past buttons
    if (filterButtons.length) {
      filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const mode = btn.getAttribute('data-mode') || 'current';
          currentMode = mode;
          currentTour = ''; // reset tour filter when switching mode
  
          filterButtons.forEach(b =>
            b.classList.toggle('active', b === btn)
          );
  
          const q = searchEl ? searchEl.value.trim() : '';
          loadTours(q);
        });
      });
    }
  
    // 🔍 Search (debounced)
    if (searchEl) {
      let t;
      searchEl.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => {
          const q = searchEl.value.trim();
          loadTours(q);
        }, 250);
      });
    }
  
    // 🎫 Click on a tour name to filter by that tour
    listEl.addEventListener('click', (e) => {
      const link = e.target.closest('.tour-link');
      if (!link) return;
  
      e.preventDefault();
      currentTour = link.dataset.tour || '';
  
      const q = searchEl ? searchEl.value.trim() : '';
      loadTours(q);
    });
  })();
  