// js/tour.js
(function(){
    const listEl = document.getElementById('tour-list');
    const searchEl = document.getElementById('tourSearch');
  
    const API_BASE = '/adamdevproject/api/'; // adjust if your folder name differs
  
    function loadTours(q=''){
      const url = API_BASE + 'get_tours.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');
      fetch(url)
        .then(res => res.json())
        .then(data => {
          listEl.innerHTML = data.html || 'No results';
        })
        .catch(() => {
          listEl.innerHTML = 'Unable to load tour dates.';
        });
    }
  
    // initial load
    loadTours();
  
    // simple search (debounced)
    if (searchEl){
      let t;
      searchEl.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => loadTours(searchEl.value.trim()), 250);
      });
    }
  })();
  