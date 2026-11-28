<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $sql = "SELECT user_id, user_name, user_email, role, created_at FROM users ORDER BY user_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT user_id, user_name, user_email, role, created_at FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE user_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($name, $email, $password, $role = 'Customer') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (user_name, user_email, user_password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $email, $hashedPassword, $role]);
        return $this->db->lastInsertId();
    }

    public function verifyLogin($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['user_password'])) {
            unset($user['user_password']);
            return $user;
        }

        return false;
    }
}
