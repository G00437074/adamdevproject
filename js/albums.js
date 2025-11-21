// js/albums.js

(function () {
    // Base URL for your API endpoints
    const API_BASE = "/adamdevproject/api/";

    // Select all album cards on the page
    const albumCards = document.querySelectorAll(".album-card");

    // If no album cards exist, stop the script
    if (!albumCards.length) return;

    // Loop through each album card
    albumCards.forEach((card) => {
        // Get important elements inside the card
        const btn            = card.querySelector(".album-toggle");          // Button to show/hide tracklist
        const tracklistWrap  = card.querySelector(".album-tracklist");       // The wrapper that is hidden/shown
        const tracklistInner = card.querySelector(".album-tracklist-inner"); // Where HTML of tracks will go
        const albumId        = card.dataset.albumId;                         // Read album ID from data-album-id

        // If any required part is missing, skip this card
        if (!btn || !tracklistWrap || !tracklistInner || !albumId) return;

        // Flags to remember state
        let isLoaded = false; // Have we already loaded tracks from the server?
        let isOpen   = false; // Is the tracklist currently open?

        // When the toggle button is clicked:
        btn.addEventListener("click", () => {
            // Flip the open/closed state
            isOpen = !isOpen;

            // If opening the tracklist
            if (isOpen) {
                tracklistWrap.hidden = false;  // Show the tracklist area
                btn.textContent = "Hide Tracklist"; // Change button text

                // If tracks haven't been loaded before, fetch them now
                if (!isLoaded) {
                    tracklistInner.textContent = "Loading tracks...";

                    // Send request to the PHP API to get album songs
                    fetch(API_BASE + "get_album_songs.php?album_id=" + encodeURIComponent(albumId))
                        .then((res) => res.json()) // Convert response to JSON
                        .then((data) => {
                            // If API says success, insert returned HTML
                            if (data.status === "ok") {
                                tracklistInner.innerHTML = data.html || "<p>No tracks found.</p>";
                            } else {
                                // If status is "error"
                                tracklistInner.innerHTML = "<p>Could not load tracks.</p>";
                            }
                        })
                        .catch((err) => {
                            // If something went wrong (network error, etc.)
                            console.error(err);
                            tracklistInner.innerHTML = "<p>Error loading songs.</p>";
                        });

                    isLoaded = true; // Mark tracks as loaded so we don't fetch again
                }

            } else {
                // If closing the tracklist
                tracklistWrap.hidden = true;   // Hide the tracklist
                btn.textContent = "View Tracklist"; // Reset button text
            }
        });
    });
})();
