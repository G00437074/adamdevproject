<?php
session_start();

include_once 'includes/db_connect.php';   // $pdo
require_once 'api/products.php';

if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

$products = getAllProducts($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laufey Merch</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=22">

    <!-- Fonts (match albums page) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="merch-page">

    <h1>Laufey Merch</h1>
    <a href="cart.php" class="view-cart-link">
        View Cart
        <?php
        $cartCount = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $cartCount += (int)($item['quantity'] ?? 0);
            }
        }
        ?>
        (<span data-cart-count><?= $cartCount ?></span>)
    </a>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product">

                <!-- Image -->
                <div class="product-image">
                    <img
                        src="images/merch/<?php echo htmlspecialchars(basename($product['image'])); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                    >
                </div>

                <!-- Content -->
                <div class="product-content">

                    <div class="product-title-row">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">€<?php echo number_format($product['price'], 2); ?></p>
                    </div>

                    <p class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>

                    <!-- Add to cart form (fetch handles submit) -->
                    <form action="api/add_to_cart.php" method="post" class="product-form">
                        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">

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

                        <label>
                            Qty:
                            <input type="number" name="quantity" value="1" min="1">
                        </label>

                        <button type="submit">Add to cart</button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<?php include 'includes/footer.php'; ?>

<!-- Fetch logic for add to cart -->
<script src="/adamdevproject/js/merch.js?v=2"></script>

</body>
</html>
