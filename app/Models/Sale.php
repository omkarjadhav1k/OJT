<?php

class Sale {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT s.*, c.customer_name, p.product_name
                FROM sales s
                LEFT JOIN customers c ON s.customer_id = c.customer_id
                LEFT JOIN products p ON s.product_id = p.product_id
                ORDER BY s.sale_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT s.*, c.customer_name, p.product_name
                FROM sales s
                LEFT JOIN customers c ON s.customer_id = c.customer_id
                LEFT JOIN products p ON s.product_id = p.product_id
                WHERE s.sale_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($customerId, $productId, $quantity, $price, $totalAmount, $paymentStatus = 'Paid') {
        $sql = "INSERT INTO sales (customer_id, product_id, quantity, price, total_amount, payment_status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId, $productId, $quantity, $price, $totalAmount, $paymentStatus]);
        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $sql = "DELETE FROM sales WHERE sale_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
