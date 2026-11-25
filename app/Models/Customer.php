<?php
// app/Models/Customer.php

class Customer {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT c.*, u.user_email 
            FROM customers c 
            LEFT JOIN users u ON c.user_id = u.user_id 
            ORDER BY c.customer_id DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT c.*, u.user_email 
            FROM customers c 
            LEFT JOIN users u ON c.user_id = u.user_id 
            WHERE c.customer_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(?int $userId, string $name, string $phone, ?string $address): int {
        $stmt = $this->db->prepare("
            INSERT INTO customers (user_id, customer_name, customer_phone, customer_address) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $name, $phone, $address]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, ?int $userId, string $name, string $phone, ?string $address): bool {
        $stmt = $this->db->prepare("
            UPDATE customers 
            SET user_id = ?, customer_name = ?, customer_phone = ?, customer_address = ? 
            WHERE customer_id = ?
        ");
        return $stmt->execute([$userId, $name, $phone, $address, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE customer_id = ?");
        return $stmt->execute([$id]);
    }
}
