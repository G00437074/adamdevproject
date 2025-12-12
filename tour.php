<!DOCTYPE html>
<html>

<head>
    <!-- Page title shown in the browser tab -->
    <title>Laufey Music - Tour</title>
    <link rel="icon" href="/adamdevproject/images/laufeyicon2.png?v=3" type="image/png">
    <link rel="apple-touch-icon" href="/adamdevproject/images/laufeyicon2.png?v=3">

<!-- Main site stylesheet -->
<link rel="stylesheet" href="/adamdevproject/css/style.css?v=23">

</head>

<body>

    <!-- Include the site header (navigation, login, etc.) -->
    <?php include 'includes/header.php'; ?>

    <!-- =========================
         Tour Section
         ========================= -->
    <section class="tour-preview">

        <!-- Section heading -->
        <h2>Tour</h2>

        <!-- List of tour names -->
        <!-- This is populated dynamically using JavaScript -->
        <div id="tourNameList" class="tour-name-list">
            <!-- JS will insert tour name buttons/links here -->
        </div>

        <!-- Tour filter controls -->
        <div class="tour-controls">

            <!-- Filter buttons for current and past tours -->
            <button class="tour-filter active" data-mode="current">
                Current Tour
            </button>

            <button class="tour-filter" data-mode="past">
                Past Tours
            </button>

            <!-- Search input for filtering by city or venue -->
            <input
                type="text"
                id="tourSearch"
                placeholder="Search city or venue">
        </div>

        <!-- Container where tour dates are displayed -->
        <!-- Content is loaded via AJAX in tour.js -->
        <div id="tour-list">
            Loading tour dates...
        </div>
    </section>

    <!-- JavaScript file that loads and filters tour dates -->
    <script src="js/tour.js"></script>

    <!-- Include the site footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>
