<?php

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT p.*, c.category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.product_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT p.*, c.category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $categoryId, $price, $quantity, $description, $availability = 'Available') {
        $sql = "INSERT INTO products (product_name, category_id, price, quantity, description, availability)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $categoryId, $price, $quantity, $description, $availability]);
        return $this->db->lastInsertId();
    }

    public function update($id, $name, $categoryId, $price, $quantity, $description, $availability = 'Available') {
        $sql = "UPDATE products
                SET product_name = ?, category_id = ?, price = ?, quantity = ?, description = ?, availability = ?
                WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $categoryId, $price, $quantity, $description, $availability, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function reduceStock($id, $quantity) {
        $sql = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantity, $id]);
    }
}
