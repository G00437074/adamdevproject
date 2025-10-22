<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">

<main class="home-container">
    <!-- Hero Section -->
    <section class="hero">
        <img src="images/laufey_header.jpeg" alt="Laufey" class="hero-image">
        <div class="hero-text">
            <h1>Welcome to Laufey’s World</h1>
            <p>Discover her music, tour dates, and exclusive merch.</p>
            <a href="albums.php" class="btn">Explore Albums</a>
        </div>
    </section>

    <!-- Latest Section -->
    <section class="latest">
        <h2>Latest News</h2>
        <p>Discover Laufey’s newest music and experience her unique blend of jazz and soul.</p>

        <!-- Embedded YouTube video -->
        <div class="video-container">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/obLSGG-oEyw?si=xBsIT0MJm7Z2QzYP" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>

        <!-- Text with working links below video -->
        <div class="latest-links">
            <p>
                New song
                <strong>“Lover Girl”</strong> out now:
                <a href="https://laufey.ffm.to/lovergirl" target="_blank" rel="noopener noreferrer">
                    https://laufey.ffm.to/lovergirl
                </a>
            </p>

            <p>
                Stream/download Laufey’s new album
                <strong>‘A Matter of Time’</strong> out now:
                <a href="https://laufey.ffm.to/amatteroftime" target="_blank" rel="noopener noreferrer">
                    https://laufey.ffm.to/amatteroftime
                </a>
            </p>

            <!-- Silver Linings Video -->
            <div class="video-container">
                <iframe
                    src="https://www.youtube.com/embed/wbzJB36Z1F4?si=3LLLgoLJBhCV-PpF"
                    title="Laufey - Dreamer (Official Music Video)"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
            </div>

            <p>
                Stream or download <strong>“Silver Lining”</strong> from Laufey's new album,
                <strong>A Matter of Time</strong>, out now:
                <a href="https://laufey.ffm.to/amatteroftime" target="_blank" rel="noopener noreferrer">
                    https://laufey.ffm.to/amatteroftime
                </a>
            </p>
    </section>


    <!-- Tour Preview -->
    <section class="tour-preview">
        <h2>🎶 Upcoming Tour Dates</h2>
        <div id="tour-preview-list">Loading tour dates...</div>
    </section>
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>