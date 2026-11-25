<?php
// app/Models/Product.php

class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT p.*, c.category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            ORDER BY p.product_id DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            WHERE p.product_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, ?int $catId, float $price, int $qty, ?string $desc, string $avail = 'Available'): int {
        $stmt = $this->db->prepare("
            INSERT INTO products (product_name, category_id, price, quantity, description, availability) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $catId, $price, $qty, $desc, $avail]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $name, ?int $catId, float $price, int $qty, ?string $desc, string $avail = 'Available'): bool {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET product_name = ?, category_id = ?, price = ?, quantity = ?, description = ?, availability = ? 
            WHERE product_id = ?
        ");
        return $stmt->execute([$name, $catId, $price, $qty, $desc, $avail, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        return $stmt->execute([$id]);
    }

    public function reduceStock(int $id, int $quantity): bool {
        $stmt = $this->db->prepare("UPDATE products SET quantity = quantity - ? WHERE product_id = ?");
        return $stmt->execute([$quantity, $id]);
    }
}
