<?php
session_start();
include_once 'includes/db_connect.php';
require_once 'api/products.php';

$products = getAllProducts($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laufey Merch</title>

    <!-- Main stylesheet for your site -->
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=20">
</head>

<body>

    <!-- Include the site header (navigation, logo, etc.) -->
    <?php include 'includes/header.php'; ?>

    <h1>Laufey Merch</h1>
    <p><a href="cart.php">View Cart</a></p>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="price">€<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo htmlspecialchars($product['description']); ?></p>

                <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
                    <label>
                        Qty:
                        <input type="number" name="quantity" value="1" min="1">
                    </label>
                    <button type="submit">Add to cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Include the site footer -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>
