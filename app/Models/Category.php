<?php
// app/Models/Category.php

class Category {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY category_id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, ?string $desc, string $status = 'Active'): int {
        $stmt = $this->db->prepare("INSERT INTO categories (category_name, category_description, category_status) VALUES (?, ?, ?)");
        $stmt->execute([$name, $desc, $status]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $name, ?string $desc, string $status = 'Active'): bool {
        $stmt = $this->db->prepare("UPDATE categories SET category_name = ?, category_description = ?, category_status = ? WHERE category_id = ?");
        return $stmt->execute([$name, $desc, $status, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = ?");
        return $stmt->execute([$id]);
    }
}
