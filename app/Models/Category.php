<?php

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY category_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $description, $status = 'Active') {
        $sql = "INSERT INTO categories (category_name, category_description, category_status) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $description, $status]);
        return $this->db->lastInsertId();
    }

    public function update($id, $name, $description, $status = 'Active') {
        $sql = "UPDATE categories SET category_name = ?, category_description = ?, category_status = ? WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $status, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
