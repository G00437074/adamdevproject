<!DOCTYPE html>
<html>

<head>
    <!-- Page title shown in the browser tab -->
    <title>Laufey Music – Albums</title>

    <!-- Favicon -->
    <link rel="icon" href="/adamdevproject/images/laufeyicon2.png?v=3" type="image/png">
    <link rel="apple-touch-icon" href="/adamdevproject/images/laufeyicon2.png?v=3">

    <!-- Main stylesheet for the website -->
    <!-- Version number (?v=20) helps prevent browser caching issues -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Include the site header (logo, navigation, login, etc.) -->
    <?php include 'includes/header.php'; ?>

    <!-- Main page container -->
    <div class="home-container">

        <!-- Albums section -->
        <section class="albums-section">

            <!-- Section heading -->
            <h2>Albums</h2>

            <!-- Intro text above the albums grid -->
            <p class="albums-intro">
                Explore Laufey’s discography and view tracklists for each album.
            </p>

            <!-- Grid layout that holds all album cards -->
            <div class="albums-grid">

                <?php
                // ---------------------------------
                // Database connection
                // ---------------------------------

                // Include database connection file (creates $pdo)
                require_once 'includes/db_connect.php';

                // ---------------------------------
                // Fetch album data
                // ---------------------------------

                // SQL query to retrieve all albums
                // Albums are ordered by newest release first
                $sql = "SELECT id, title, release_year, cover_img, spotify_embed
                        FROM albums
                        ORDER BY release_year DESC";

                // Execute the query
                $stmt = $pdo->query($sql);

                // Fetch all album records as an associative array
                $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ---------------------------------
                // Output each album card
                // ---------------------------------

                foreach ($albums as $album): ?>

                    <!-- Single album card -->
                    <!-- data-album-id is used by JavaScript to load tracklists -->
                    <article class="album-card" data-album-id="<?= $album['id'] ?>">

                        <!-- Album cover image -->
                        <div class="album-cover-wrap">
                            <img
                                src="<?= $album['cover_img'] ?>"
                                class="album-cover"
                                alt="<?= htmlspecialchars($album['title']) ?>">
                        </div>

                        <!-- Album metadata -->
                        <div class="album-meta">

                            <!-- Album title -->
                            <h3><?= htmlspecialchars($album['title']) ?></h3>

                            <!-- Release year -->
                            <p class="album-year"><?= $album['release_year'] ?></p>

                            <!-- Button to show/hide the tracklist -->
                            <button class="btn album-toggle">
                                View Tracklist
                            </button>
                        </div>

                        <!-- Tracklist container (hidden by default) -->
                        <div class="album-tracklist" hidden>

                            <?php if (!empty($album['spotify_embed'])): ?>
                                <!-- Optional Spotify embed player -->
                                <div class="album-embed-wrap">
                                    <iframe
                                        src="<?= htmlspecialchars($album['spotify_embed']) ?>"
                                        width="100%"
                                        height="152"
                                        frameborder="0"
                                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                        loading="lazy">
                                    </iframe>
                                </div>
                            <?php endif; ?>

                            <!-- Tracklist content loaded dynamically via JavaScript -->
                            <div class="album-tracklist-inner">
                                Loading tracks...
                            </div>
                        </div>
                    </article>

                <?php endforeach; ?>

            </div>
        </section>
    </div>

    <!-- JavaScript file that handles album toggling and track loading -->
    <script src="/adamdevproject/js/albums.js?v=1"></script>

    <!-- Include the site footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>