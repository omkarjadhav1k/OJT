<?php
// app/Controllers/UserController.php

class UserController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function getAll(): void {
        $users = $this->model->getAll();
        jsonResponse('success', 'Users fetched successfully', $users);
    }

    public function register(): void {
        $data = getRequestData();
        if (empty($data['user_name']) || empty($data['user_email']) || empty($data['user_password'])) {
            jsonResponse('error', 'Name, email, and password are required', null, 400);
        }

        if ($this->model->findByEmail($data['user_email'])) {
            jsonResponse('error', 'Email is already registered', null, 400);
        }

        $role = $data['role'] ?? 'Customer';
        $userId = $this->model->create($data['user_name'], $data['user_email'], $data['user_password'], $role);

        jsonResponse('success', 'User registered successfully', [
            'user_id'    => $userId,
            'user_name'  => $data['user_name'],
            'user_email' => $data['user_email'],
            'role'       => $role
        ], 201);
    }

    public function login(): void {
        $data = getRequestData();
        if (empty($data['user_email']) || empty($data['user_password'])) {
            jsonResponse('error', 'Email and password are required', null, 400);
        }

        $user = $this->model->findByEmail($data['user_email']);
        if (!$user || !password_verify($data['user_password'], $user['user_password'])) {
            jsonResponse('error', 'Invalid email or password', null, 401);
        }

        unset($user['user_password']);
        jsonResponse('success', 'Login successful', $user);
    }
}
