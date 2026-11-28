<?php

class CustomerController {
    private $model;

    public function __construct() {
        $this->model = new Customer();
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function create() {
        $userId  = !empty($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $name    = trim($_POST['customer_name'] ?? '');
        $phone   = trim($_POST['customer_phone'] ?? '');
        $address = trim($_POST['customer_address'] ?? '');

        if ($name === '' || $phone === '') {
            return [
                'success' => false,
                'message' => 'Customer name and phone are required.'
            ];
        }

        $id = $this->model->create($userId, $name, $phone, $address);

        return [
            'success'     => true,
            'message'     => 'Customer added successfully.',
            'customer_id' => $id
        ];
    }

    public function update($id) {
        $userId  = !empty($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $name    = trim($_POST['customer_name'] ?? '');
        $phone   = trim($_POST['customer_phone'] ?? '');
        $address = trim($_POST['customer_address'] ?? '');

        if ($name === '' || $phone === '') {
            return [
                'success' => false,
                'message' => 'Customer name and phone are required.'
            ];
        }

        $this->model->update($id, $userId, $name, $phone, $address);

        return [
            'success' => true,
            'message' => 'Customer updated successfully.'
        ];
    }

    public function delete($id) {
        $this->model->delete($id);

        return [
            'success' => true,
            'message' => 'Customer deleted successfully.'
        ];
    }
}
