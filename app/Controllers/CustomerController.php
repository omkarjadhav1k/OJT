<?php
// app/Controllers/CustomerController.php

class CustomerController {
    private Customer $model;

    public function __construct() {
        $this->model = new Customer();
    }

    public function getAll(): void {
        $customers = $this->model->getAll();
        jsonResponse('success', 'Customers fetched successfully', $customers);
    }

    public function getById(int $id): void {
        $customer = $this->model->getById($id);
        if (!$customer) {
            jsonResponse('error', 'Customer not found', null, 404);
        }
        jsonResponse('success', 'Customer details', $customer);
    }

    public function create(): void {
        $data = getRequestData();
        if (empty($data['customer_name']) || empty($data['customer_phone'])) {
            jsonResponse('error', 'Customer name and phone are required', null, 400);
        }

        $userId = !empty($data['user_id']) ? (int)$data['user_id'] : null;
        $address = $data['customer_address'] ?? null;

        $id = $this->model->create($userId, $data['customer_name'], $data['customer_phone'], $address);
        jsonResponse('success', 'Customer added successfully', ['customer_id' => $id], 201);
    }

    public function update(int $id): void {
        $data = getRequestData();
        if (empty($data['customer_name']) || empty($data['customer_phone'])) {
            jsonResponse('error', 'Customer name and phone are required', null, 400);
        }

        $userId = !empty($data['user_id']) ? (int)$data['user_id'] : null;
        $address = $data['customer_address'] ?? null;

        $this->model->update($id, $userId, $data['customer_name'], $data['customer_phone'], $address);
        jsonResponse('success', 'Customer updated successfully');
    }

    public function delete(int $id): void {
        $this->model->delete($id);
        jsonResponse('success', 'Customer deleted successfully');
    }
}
