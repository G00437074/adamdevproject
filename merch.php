<?php
session_start();

include_once 'includes/db_connect.php';   // $pdo
require_once 'api/products.php';

if (!isset($pdo)) {
    die("❌ PDO connection not available.");
}

$products = getAllProducts($pdo);

// cart count for header link
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += (int)($item['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laufey Merch</title>

    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=23">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="merch-page">

    <h1>Laufey Merch</h1>

    <a href="cart.php" class="view-cart-link">
        View Cart (<span data-cart-count><?= $cartCount ?></span>)
    </a>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php
            // Clothing detection (no DB changes needed)
            $nameLower  = strtolower($product['name'] ?? '');
            $isClothing =
                (strpos($nameLower, 'tee') !== false) ||
                (strpos($nameLower, 't-shirt') !== false) ||
                (strpos($nameLower, 'tshirt') !== false) ||
                (strpos($nameLower, 'hoodie') !== false);
            ?>
            <div class="product">

                <div class="product-image">
                    <img
                        src="images/merch/<?php echo htmlspecialchars(basename($product['image'])); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                    >
                </div>

                <div class="product-content">

                    <div class="product-title-row">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">€<?php echo number_format((float)$product['price'], 2); ?></p>
                    </div>

                    <p class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>

                    <!-- Add to cart (fetch handles submit) -->
                    <form action="api/add_to_cart.php" method="post" class="product-form">
                        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">

                        <?php if ($isClothing): ?>
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
                            <!-- Non-clothing items don't need size -->
                            <input type="hidden" name="size" value="">
                        <?php endif; ?>

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

<script src="/adamdevproject/js/merch.js?v=3"></script>

</body>
</html>
