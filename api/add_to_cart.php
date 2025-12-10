<?php
session_start();
include 'cart_functions.php';

$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
$value = isset($_POST['value']) ? (int)$_POST['value'] : 0;

// Ensure the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the item exists in the cart
if (isset($_SESSION['cart'][$id])) {
    // Update the quantity
    $_SESSION['cart'][$id]['quantity'] += $value;

    // Remove the item if the quantity drops to 0 or below
    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
}



$return_info = countItemsAndPrice();
$no_of_items = $return_info['no_of_items'];
$total_price = $return_info['total_price'];

  $total_price = number_format($total_price, 2);

// Display cart info
echo "<a href=\"#\" onclick='showShoppingBasket()'><img src=\"images/cart.jpg\" width=\"40\"></a>";
echo "<span style='display:inline-block; margin-left:10px;'>";
echo "<span style='background-color: #c71585; color: white; padding: 2px 6px; border-radius: 4px;'>";
echo $no_of_items;
echo "</span>";
echo "<br>";
echo "€" . $total_price;
echo "</span>";