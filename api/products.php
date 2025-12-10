<?php
// api/products.php

// Get all products
function getAllProducts(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, name, price, image, description FROM merch");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get one product by ID
function getProductById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare(
        "SELECT id, name, price, image, description FROM merch WHERE id = ?"
    );
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    return $product ?: null;
}
