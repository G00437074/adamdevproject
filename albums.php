<!DOCTYPE html>
<html>

<head>
    <title>Laufey Music – Albums</title>

    <!-- Main stylesheet for your site -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=20">
</head>

<body>

    <!-- Include the site header (navigation, logo, etc.) -->
    <?php include 'includes/header.php'; ?>

    <div class="home-container">
        <section class="albums-section">
            <h2>Albums</h2>

            <p class="albums-intro">
                <!-- Small description above the albums grid -->
                Explore Laufey’s discography and view tracklists for each album.
            </p>

            <div class="albums-grid">
                <!-- This grid holds all album cards -->

                <?php
                // Connect to the database
                require_once 'includes/db_connect.php';

                // SQL query to fetch all albums
                // Ordered by most recent release year first
                $sql = "SELECT id, title, release_year, cover_img, spotify_embed
                        FROM albums
                        ORDER BY release_year DESC";

                // Run the query
                $stmt = $pdo->query($sql);

                // Fetch the results as an associative array
                $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through each album and build the HTML card
                foreach ($albums as $album): ?>
                    
                    <!-- One album card -->
                    <article class="album-card" data-album-id="<?= $album['id'] ?>">
                        
                        <!-- Album cover -->
                        <div class="album-cover-wrap">
                            <img src="<?= $album['cover_img'] ?>" class="album-cover"
                                 alt="<?= htmlspecialchars($album['title']) ?>">
                        </div>

                        <!-- Album title + release year + toggle button -->
                        <div class="album-meta">
                            <h3><?= htmlspecialchars($album['title']) ?></h3>
                            <p class="album-year"><?= $album['release_year'] ?></p>

                            <!-- Clicking this button loads/shows the tracklist -->
                            <button class="btn album-toggle">View Tracklist</button>
                        </div>

                        <!-- Hidden tracklist area (opened when user clicks button) -->
                        <div class="album-tracklist" hidden>

                            <?php if (!empty($album['spotify_embed'])): ?>
                                <!-- Optional Spotify player embed -->
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

                            <!-- Tracklist content gets loaded here by JS -->
                            <div class="album-tracklist-inner">
                                Loading tracks...
                            </div>
                        </div>
                    </article>

                <?php endforeach; ?>

            </div>
        </section>
    </div>

    <!-- JavaScript that handles show/hide + fetching songs -->
    <script src="/adamdevproject/js/albums.js?v=1"></script>

    <!-- Include the site footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>
