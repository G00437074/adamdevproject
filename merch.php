<?php
// Start the session so we can access cart data
session_start();

// Include database connection (creates $pdo)
include_once 'includes/db_connect.php';

// Include product helper functions
require_once 'api/products.php';

// Stop execution if the database connection is missing
if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

// ----------------------------
// Fetch all products
// ----------------------------

// Get all merch products from the database
$products = getAllProducts($pdo);

// ----------------------------
// Calculate cart count
// ----------------------------

// This count is shown in the "View Cart" link
$cartCount = 0;

// If a cart exists, add up the quantities
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += (int)($item['quantity'] ?? 0);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Page title -->
    <title>Laufey Merch</title>

    <!-- Main site stylesheet -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=23">

    <!-- Google Fonts used for styling -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Include site header (navigation, login, etc.) -->
<?php include 'includes/header.php'; ?>

<main class="merch-page">

    <!-- Page heading -->
    <h1>Laufey Merch</h1>

    <!-- Link to cart with live item count -->
    <a href="cart.php" class="view-cart-link">
        View Cart (<span data-cart-count><?= $cartCount ?></span>)
    </a>

    <!-- Grid layout for all products -->
    <div class="product-grid">

        <?php foreach ($products as $product): ?>

            <?php
            // ----------------------------
            // Detect clothing products
            // ----------------------------
            // This determines whether a size selector should be shown
            $nameLower  = strtolower($product['name'] ?? '');
            $isClothing =
                (strpos($nameLower, 'tee') !== false) ||
                (strpos($nameLower, 't-shirt') !== false) ||
                (strpos($nameLower, 'tshirt') !== false) ||
                (strpos($nameLower, 'hoodie') !== false);
            ?>

            <!-- Single product card -->
            <div class="product">

                <!-- Product image -->
                <div class="product-image">
                    <img
                        src="images/merch/<?php echo htmlspecialchars(basename($product['image'])); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                    >
                </div>

                <!-- Product details -->
                <div class="product-content">

                    <!-- Product name and price -->
                    <div class="product-title-row">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">
                            €<?php echo number_format((float)$product['price'], 2); ?>
                        </p>
                    </div>

                    <!-- Product description -->
                    <p class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>

                    <!-- ----------------------------
                         Add to Cart Form
                         ---------------------------- -->
                    <!-- Submitted using JavaScript fetch (AJAX) -->
                    <form action="api/add_to_cart.php" method="post" class="product-form">

                        <!-- Hidden product ID -->
                        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">

                        <?php if ($isClothing): ?>
                            <!-- Size selector for clothing items -->
                            <label>
                                Size:
                                <select name="size" required>
                                    <option value="">Select</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                            </label>
                        <?php else: ?>
                            <!-- Non-clothing items do not require a size -->
                            <input type="hidden" name="size" value="">
                        <?php endif; ?>

                        <!-- Quantity input -->
                        <label>
                            Qty:
                            <input type="number" name="quantity" value="1" min="1">
                        </label>

                        <!-- Submit button -->
                        <button type="submit">Add to cart</button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- Include site footer -->
<?php include 'includes/footer.php'; ?>

<!-- JavaScript that handles add-to-cart behaviour -->
<script src="/adamdevproject/js/merch.js?v=3"></script>

</body>
</html>
