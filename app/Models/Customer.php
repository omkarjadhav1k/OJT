<?php

class Customer {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT c.*, u.user_email
                FROM customers c
                LEFT JOIN users u ON c.user_id = u.user_id
                ORDER BY c.customer_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT c.*, u.user_email
                FROM customers c
                LEFT JOIN users u ON c.user_id = u.user_id
                WHERE c.customer_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($userId, $name, $phone, $address) {
        $sql = "INSERT INTO customers (user_id, customer_name, customer_phone, customer_address)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $name, $phone, $address]);
        return $this->db->lastInsertId();
    }

    public function update($id, $userId, $name, $phone, $address) {
        $sql = "UPDATE customers
                SET user_id = ?, customer_name = ?, customer_phone = ?, customer_address = ?
                WHERE customer_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $name, $phone, $address, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM customers WHERE customer_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
