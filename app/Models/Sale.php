<?php
// app/Models/Sale.php

class Sale {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT s.*, c.customer_name, p.product_name 
            FROM sales s 
            LEFT JOIN customers c ON s.customer_id = c.customer_id 
            LEFT JOIN products p ON s.product_id = p.product_id 
            ORDER BY s.sale_id DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT s.*, c.customer_name, p.product_name 
            FROM sales s 
            LEFT JOIN customers c ON s.customer_id = c.customer_id 
            LEFT JOIN products p ON s.product_id = p.product_id 
            WHERE s.sale_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(?int $customerId, int $productId, int $qty, float $price, float $totalAmount, string $paymentStatus = 'Paid'): int {
        $stmt = $this->db->prepare("
            INSERT INTO sales (customer_id, product_id, quantity, price, total_amount, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$customerId, $productId, $qty, $price, $totalAmount, $paymentStatus]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM sales WHERE sale_id = ?");
        return $stmt->execute([$id]);
    }
}
