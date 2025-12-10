<?php
// api/products.php

// Get all products from `merch` table
function getAllProducts(mysqli $conn): array {
    $products = [];

    $sql = "SELECT id, name, price, image, description FROM merch";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $products[$row['id']] = $row;
        }
        $result->free();
    }

    return $products;
}

// Get a single product by ID
function getProductById(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare(
        "SELECT id, name, price, image, description FROM merch WHERE id = ?"
    );
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result  = $stmt->get_result();
    $product = $result->fetch_assoc() ?: null;
    $stmt->close();

    return $product;
}
