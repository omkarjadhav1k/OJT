<?php

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT o.*, c.customer_name, c.customer_phone
                FROM orders o
                JOIN customers c ON o.customer_id = c.customer_id
                ORDER BY o.order_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT o.*, c.customer_name, c.customer_phone, c.customer_address
                FROM orders o
                JOIN customers c ON o.customer_id = c.customer_id
                WHERE o.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($customerId, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus) {
        $sql = "INSERT INTO orders (customer_id, total_amount, payment_method, payment_status, order_status)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus]);
        return $this->db->lastInsertId();
    }

    public function update($id, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus) {
        $sql = "UPDATE orders
                SET total_amount = ?, payment_method = ?, payment_status = ?, order_status = ?
                WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$totalAmount, $paymentMethod, $paymentStatus, $orderStatus, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
