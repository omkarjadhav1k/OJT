<?php

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product();
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function create() {
        $name         = trim($_POST['product_name'] ?? '');
        $categoryId   = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $price        = $_POST['price'] ?? '';
        $quantity     = (int)($_POST['quantity'] ?? 0);
        $description  = trim($_POST['description'] ?? '');
        $availability = $_POST['availability'] ?? 'Available';

        if ($name === '' || $price === '') {
            return [
                'success' => false,
                'message' => 'Product name and price are required.'
            ];
        }

        $id = $this->model->create($name, $categoryId, (float)$price, $quantity, $description, $availability);

        return [
            'success'    => true,
            'message'    => 'Product created successfully.',
            'product_id' => $id
        ];
    }

    public function update($id) {
        $name         = trim($_POST['product_name'] ?? '');
        $categoryId   = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $price        = $_POST['price'] ?? '';
        $quantity     = (int)($_POST['quantity'] ?? 0);
        $description  = trim($_POST['description'] ?? '');
        $availability = $_POST['availability'] ?? 'Available';

        if ($name === '' || $price === '') {
            return [
                'success' => false,
                'message' => 'Product name and price are required.'
            ];
        }

        $this->model->update($id, $name, $categoryId, (float)$price, $quantity, $description, $availability);

        return [
            'success' => true,
            'message' => 'Product updated successfully.'
        ];
    }

    public function delete($id) {
        $this->model->delete($id);

        return [
            'success' => true,
            'message' => 'Product deleted successfully.'
        ];
    }
}
