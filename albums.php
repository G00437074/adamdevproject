<!DOCTYPE html>
<html>

<head>
    <title>Laufey Music – Albums</title>
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=20">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="home-container">
        <section class="albums-section">
            <h2>🎼 Albums</h2>
            <p class="albums-intro">
                Explore Laufey’s discography and view tracklists for each album.
            </p>

            <div class="albums-grid">

                <?php
                // Fetch albums directly for the grid
                require_once 'includes/db_connect.php';

                $sql = "SELECT id, title, release_year, cover_img, spotify_embed
        FROM albums
        ORDER BY release_year DESC";
                $stmt = $pdo->query($sql);
                $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($albums as $album): ?>
                    <article class="album-card" data-album-id="<?= $album['id'] ?>">
                        <div class="album-cover-wrap">
                            <img src="<?= $album['cover_img'] ?>" class="album-cover"
                                alt="<?= htmlspecialchars($album['title']) ?>">
                        </div>

                        <div class="album-meta">
                            <h3><?= htmlspecialchars($album['title']) ?></h3>
                            <p class="album-year"><?= $album['release_year'] ?></p>
                            <button class="btn album-toggle">View Tracklist</button>
                        </div>

                        <div class="album-tracklist" hidden>

                            <?php if (!empty($album['spotify_embed'])): ?>
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

                            <div class="album-tracklist-inner">
                                Loading tracks...
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>


            </div>
        </section>
    </div>

    <script src="/adamdevproject/js/albums.js?v=1"></script>
    <?php include 'includes/footer.php'; ?>

</body>

</html>