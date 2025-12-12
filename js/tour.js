// js/tour.js
// This script handles loading, searching, and filtering tour dates

(function () {

  // ----------------------------
  // Get required page elements
  // ----------------------------

  // Container where tour dates will be displayed
  const listEl = document.getElementById('tour-list');

  // Safety check: if this page does not contain a tour list, stop the script
  if (!listEl) return;

  // Search input for filtering by city or venue
  const searchEl = document.getElementById('tourSearch');

  // Buttons for switching between "Current" and "Past" tours
  const filterButtons = document.querySelectorAll('.tour-filter');

  // Base API path (adjust if project folder changes)
  const API_BASE = '/adamdevproject/api/';

  // ----------------------------
  // State variables
  // ----------------------------

  // Determines whether to show current or past tours
  let currentMode = 'current'; // 'current' or 'past'

  // Stores the selected tour name (empty string means "all tours")
  let currentTour = '';

  // ----------------------------
  // Load tours from the server
  // ----------------------------

  function loadTours(q = '') {

    // Build URL parameters for the API request
    const params = new URLSearchParams();
    params.set('mode', currentMode);

    // Optional search term
    if (q) params.set('q', q);

    // Optional filter by tour name
    if (currentTour) params.set('tour_name', currentTour);

    // Final API URL
    const url = API_BASE + 'get_tours.php?' + params.toString();

    // Show loading message while fetching data
    listEl.innerHTML = 'Loading tour dates...';

    // Fetch tour data from the server
    fetch(url)
      .then(res => {
        // Check for network/server errors
        if (!res.ok) throw new Error('Network error');
        return res.json();
      })
      .then(data => {
        // Insert the returned HTML into the page
        listEl.innerHTML = data.html || 'No results.';
      })
      .catch(err => {
        // Handle errors
        console.error(err);
        listEl.innerHTML = 'Unable to load tour dates.';
      });
  }

  // ----------------------------
  // Initial page load
  // ----------------------------

  // Load current tour dates when the page first opens
  loadTours();

  // ----------------------------
  // Current / Past filter buttons
  // ----------------------------

  if (filterButtons.length) {
    filterButtons.forEach(btn => {

      btn.addEventListener('click', () => {

        // Read the selected mode from the button
        const mode = btn.getAttribute('data-mode') || 'current';

        // Update current mode (current or past)
        currentMode = mode;

        // Reset tour filter when switching modes
        currentTour = '';

        // Update active button styling
        filterButtons.forEach(b =>
          b.classList.toggle('active', b === btn)
        );

        // Reload tours using the current search term
        const q = searchEl ? searchEl.value.trim() : '';
        loadTours(q);
      });
    });
  }

  // ----------------------------
  // Search input (debounced)
  // ----------------------------

  if (searchEl) {
    let t; // timer reference

    searchEl.addEventListener('input', () => {

      // Clear previous timer to avoid too many requests
      clearTimeout(t);

      // Wait 250ms after typing stops before searching
      t = setTimeout(() => {
        const q = searchEl.value.trim();
        loadTours(q);
      }, 250);
    });
  }

  // ----------------------------
  // Click a tour name to filter
  // ----------------------------

  listEl.addEventListener('click', (e) => {

    // Detect clicks on tour name links
    const link = e.target.closest('.tour-link');
    if (!link) return;

    // Prevent default link behaviour
    e.preventDefault();

    // Store the selected tour name
    currentTour = link.dataset.tour || '';

    // Reload tours using the current search term
    const q = searchEl ? searchEl.value.trim() : '';
    loadTours(q);
  });

})();
