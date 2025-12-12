<!DOCTYPE html>
<html>

<head>
    <!-- Page title shown in the browser tab -->
    <title>Laufey Music</title>
</head>

<body>

<!-- Include the site header (navigation, login, logo, etc.) -->
<?php include 'includes/header.php'; ?>

<!-- Main site stylesheet -->
<link rel="stylesheet" href="css/style.css">

<main class="home-container">

    <!-- =========================
         Hero Section
         ========================= -->
    <section class="hero">

        <!-- Main hero image -->
        <img src="images/laufey_image.jpg" alt="Laufey" class="hero-image">

        <!-- Text overlay on hero image -->
        <div class="hero-text">
            <h1>Welcome to Laufey’s World</h1>

            <!-- Artist introduction text -->
            <p>
                Laufey (<i>pronounced lay-vay</i>) has captivated a generation with
                virtuosic songs of love and self-discovery by manifesting her
                vision of jazz-and classical-infused pop music. She has become a
                bridge for the older music she adores, from Chet Baker to Carole
                King to Ravel, by offering her bold interpretation to a younger
                crop of listeners who have become deeply connected over time.
                <br><br>
                Raised between Reykjavik and Washington, D.C.,
                she learned piano and cello as a child, later studying
                at Berklee College of Music. There, she wrote her debut EP,
                2021’s <em>Typical of Me</em>, whose striking single
                “Street by Street” debuted at No. 1 on Icelandic Radio.
                Her success has grown to include billions of streams,
                global recognition, sold-out venues, and collaborations
                with world-class artists.
                <br><br>
            </p>

            <!-- Call to action -->
            <p>Discover her music, tour dates, and exclusive merch.</p>
            <a href="albums.php" class="btn">Explore Laufey's Music</a>
        </div>
    </section>

    <!-- =========================
         Latest Section
         ========================= -->
    <section class="latest">

        <!-- Section heading -->
        <h2>Latest</h2>

        <!-- Section description -->
        <p>Discover Laufey’s newest music and experience her unique blend of jazz and soul.</p>

        <!-- Embedded YouTube video -->
        <div class="video-container">
            <iframe
                width="560"
                height="315"
                src="https://www.youtube.com/embed/obLSGG-oEyw?si=xBsIT0MJm7Z2QzYP"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Links related to the latest releases -->
        <div class="latest-links">

            <p>
                New song <strong>“Lover Girl”</strong> out now:
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

            <!-- Second embedded video -->
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
                <strong>'A Matter of Time'</strong>, out now:
                <a href="https://laufey.ffm.to/amatteroftime" target="_blank" rel="noopener noreferrer">
                    https://laufey.ffm.to/amatteroftime
                </a>
            </p>

            <br>

            <!-- Link to YouTube channel -->
            <p>
                Discover more music videos on Laufey's YouTube channel:
                <a href="https://youtube.com/@laufey?si=zVU9zTfKL7m-756R" target="_blank" rel="noopener noreferrer">
                    https://youtube.com/@laufey
                </a>
            </p>
        </div>
    </section>

    <!-- =========================
         Tour Preview (currently disabled)
         ========================= -->
    <!--
    <section class="tour-preview">
        <h2>🎶 Upcoming Tour Dates</h2>
        <div id="tour-preview-list">Loading tour dates...</div>
    </section>
    -->
</main>

<!-- Include the site footer -->
<?php include 'includes/footer.php'; ?>

<!-- Login and modal handling JavaScript -->
<script src="js/login.js"></script>

</body>
</html>
