// js/albums.js
(function () {
    const API_BASE = "/adamdevproject/api/";
  
    const albumCards = document.querySelectorAll(".album-card");
    if (!albumCards.length) return;
  
    albumCards.forEach((card) => {
      const btn            = card.querySelector(".album-toggle");
      const tracklistWrap  = card.querySelector(".album-tracklist");
      const tracklistInner = card.querySelector(".album-tracklist-inner");
      const albumId        = card.dataset.albumId; // from data-album-id
  
      if (!btn || !tracklistWrap || !tracklistInner || !albumId) return;
  
      let isLoaded = false;
      let isOpen   = false;
  
      btn.addEventListener("click", () => {
        isOpen = !isOpen;
  
        if (isOpen) {
          tracklistWrap.hidden = false;
          btn.textContent = "Hide Tracklist";
  
          if (!isLoaded) {
            tracklistInner.textContent = "Loading tracks...";
  
            fetch(API_BASE + "get_album_songs.php?album_id=" + encodeURIComponent(albumId))
              .then((res) => res.json())
              .then((data) => {
                if (data.status === "ok") {
                  tracklistInner.innerHTML = data.html || "<p>No tracks found.</p>";
                } else {
                  tracklistInner.innerHTML = "<p>Could not load tracks.</p>";
                }
              })
              .catch((err) => {
                console.error(err);
                tracklistInner.innerHTML = "<p>Error loading songs.</p>";
              });
  
            isLoaded = true;
          }
        } else {
          tracklistWrap.hidden = true;
          btn.textContent = "View Tracklist";
        }
      });
    });
  })();
  