<!DOCTYPE html>
<html>

<head>
    <title>Laufey Music</title>
</head>


<body>



<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">

<main class="home-container">
    <!-- Hero Section -->
    <section class="hero">
        <img src="images/laufey_image.jpg" alt="Laufey" class="hero-image">
        <div class="hero-text">
            <h1>Welcome to Laufey’s World</h1>
            <p> Laufey (<i>pronounced lay-vay</i>) has captivated a generation with
                virtuosic songs of love and self-discovery by manifesting her
                vision of jazz-and classical-infused pop music. She has become a
                bridge for the older music she adores, from Chet Baker to Carole
                King to Ravel, by offering her bold interpretation to a younger
                crop of listeners who have become deeply connected over time.
                <br>
                <br>
                Raised between Reykjavik and Washington, D.C.,
                she learned piano and cello as a child, later studying
                at Berklee College of Music. There, she wrote her debut EP,
                2021’s Typical of Me, whose striking single “Street by Street” debuted at No. 1
                on Icelandic Radio—the first of many achievements that have grown to include
                5 billion global streams, a social media audience of 25 million, the biggest
                jazz LP debut in Spotify history and an album in Billboard’s Top 20 (both for Bewitched),
                a growing pile of Platinum plaques, a Forbes 30 Under 30 designation, and being
                named one of TIME’s 2025 Women of the Year. She’s sold out the Hollywood Bowl,
                Radio City Music Hall, and London’s Royal Albert Hall; performed backed by the
                LA Phil, the National Symphony Orchestra, and the China Philharmonic Orchestra;
                shared the stage with the likes of Jon Batiste and Raye; and collaborated on
                records with artists ranging from Beabadoobee to Norah Jones.
                <br>
                <br>
            <p>Discover her music, tour dates, and exclusive merch.</p>
            <a href="albums.php" class="btn">Explore Laufey's Music</a>
        </div>
    </section>

    <!-- Latest Section -->
    <section class="latest">
        <h2>Latest</h2>
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
                <strong>'A Matter of Time'</strong>, out now:
                <a href="https://laufey.ffm.to/amatteroftime" target="_blank" rel="noopener noreferrer">
                    https://laufey.ffm.to/amatteroftime
                </a>
            </p>
            <br>
            <p>Disover more music videos on Laufey's Youtube Channel:
                <a href="https://youtube.com/@laufey?si=zVU9zTfKL7m-756R" target="_blank" rel="noopener noreferrer">
                https://youtube.com/@laufey?si=zVU9zTfKL7m-756R
                </a>
            </p>
    </section>


    <!-- Tour Preview 
    <section class="tour-preview">
        <h2>🎶 Upcoming Tour Dates</h2>
        <div id="tour-preview-list">Loading tour dates...</div>
    </section> -->
</main>

<script src="js/main.js"></script>
<?php include 'includes/footer.php'; ?>

</body>
</html>