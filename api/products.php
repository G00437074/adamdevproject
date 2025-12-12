<?php
// api/products.php
// This file contains reusable functions for working with products
// It is included in other files that need product data

// ---------------------------------
// Get all products from the database
// ---------------------------------

function getAllProducts(PDO $pdo): array {

    // Run a SQL query to select all products from the merch table
    // We only select the columns we actually need
    $stmt = $pdo->query(
        "SELECT id, name, price, image, description FROM merch"
    );

    // Fetch all rows as an associative array and return them
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------------------------------
// Get a single product by its ID
// ---------------------------------

function getProductById(PDO $pdo, int $id): ?array {

    // Prepare a SQL statement with a placeholder (?)
    // This helps protect against SQL injection
    $stmt = $pdo->prepare(
        "SELECT id, name, price, image, description
         FROM merch
         WHERE id = ?"
    );

    // Execute the query, replacing the placeholder with the product ID
    $stmt->execute([$id]);

    // Fetch one product as an associative array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the product if found, otherwise return null
    return $product ?: null;
}
