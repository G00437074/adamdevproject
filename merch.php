<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once 'includes/db_connect.php';   // creates $pdo
require_once 'api/products.php';

if (!isset($pdo)) {
    die("❌ PDO connection not found.");
}

$products = getAllProducts($pdo);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laufey Merch</title>
    <link rel="stylesheet" href="/adamdevproject/css/style.css?v=21">

</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="merch-page">

    <h1>Laufey Merch</h1>
    <a href="cart.php" class="view-cart-link">View Cart</a>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
           <div class="product">
  <div class="product-image">
    <img src="images/merch/<?php echo htmlspecialchars(basename($product['image'])); ?>"
         alt="<?php echo htmlspecialchars($product['name']); ?>">
  </div>

  <div class="product-content">
    <div class="product-title-row">
      <h3><?php echo htmlspecialchars($product['name']); ?></h3>
      <p class="price">€<?php echo number_format($product['price'], 2); ?></p>
    </div>

    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

    <form action="cart.php" method="post" class="product-form">
  <input type="hidden" name="action" value="add">
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

      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
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

</html>
