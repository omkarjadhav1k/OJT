<?php
// app/Models/User.php

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT user_id, user_name, user_email, role, created_at FROM users ORDER BY user_id DESC");
        return $stmt->fetchAll();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password, string $role = 'Customer'): int {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (user_name, user_email, user_password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed, $role]);
        return (int)$this->db->lastInsertId();
    }
}
