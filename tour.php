<!DOCTYPE html>
<html>

<head>
    <title>Laufey Music</title>
    <!-- IMPORTANT: correct path + cache bust -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=10">
</head>


<body>




    <?php include 'includes/header.php'; ?>


    <section class="tour-preview">
        <h2>Tour</h2>

        <!-- NEW: list of all tour names -->
        <div id="tourNameList" class="tour-name-list">
            <!-- JS will populate this -->
        </div>

        <div class="tour-controls">
            <button class="tour-filter active" data-mode="current">Current Tour</button>
            <button class="tour-filter" data-mode="past">Past Tours</button>
            <input type="text" id="tourSearch" placeholder="Search city or venue">
        </div>

        <div id="tour-list">Loading tour dates...</div>
    </section>


    </main>

    <script src="js/tour.js"></script>
    <?php include 'includes/footer.php'; ?>

</body>

</html>