<?php
// app/Models/Order.php

class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT o.*, c.customer_name, c.customer_phone 
            FROM orders o 
            JOIN customers c ON o.customer_id = c.customer_id 
            ORDER BY o.order_id DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT o.*, c.customer_name, c.customer_phone, c.customer_address 
            FROM orders o 
            JOIN customers c ON o.customer_id = c.customer_id 
            WHERE o.order_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $customerId, float $totalAmount, string $paymentMethod, string $paymentStatus, string $orderStatus): int {
        $stmt = $this->db->prepare("
            INSERT INTO orders (customer_id, total_amount, payment_method, payment_status, order_status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$customerId, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];

        if (!empty($data['payment_method'])) {
            $fields[] = "payment_method = ?";
            $params[] = $data['payment_method'];
        }
        if (!empty($data['payment_status'])) {
            $fields[] = "payment_status = ?";
            $params[] = $data['payment_status'];
        }
        if (!empty($data['order_status'])) {
            $fields[] = "order_status = ?";
            $params[] = $data['order_status'];
        }
        if (isset($data['total_amount'])) {
            $fields[] = "total_amount = ?";
            $params[] = (float)$data['total_amount'];
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE orders SET " . implode(', ', $fields) . " WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE order_id = ?");
        return $stmt->execute([$id]);
    }
}
