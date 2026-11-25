<?php
// app/Controllers/ProductController.php

class ProductController {
    private Product $model;

    public function __construct() {
        $this->model = new Product();
    }

    public function getAll(): void {
        $products = $this->model->getAll();
        jsonResponse('success', 'Products fetched successfully', $products);
    }

    public function getById(int $id): void {
        $product = $this->model->getById($id);
        if (!$product) {
            jsonResponse('error', 'Product not found', null, 404);
        }
        jsonResponse('success', 'Product details', $product);
    }

    public function create(): void {
        $data = getRequestData();
        if (empty($data['product_name']) || !isset($data['price'])) {
            jsonResponse('error', 'Product name and price are required', null, 400);
        }

        $catId = !empty($data['category_id']) ? (int)$data['category_id'] : null;
        $price = (float)$data['price'];
        $qty = (int)($data['quantity'] ?? 0);
        $desc = $data['description'] ?? null;
        $avail = $data['availability'] ?? 'Available';

        $id = $this->model->create($data['product_name'], $catId, $price, $qty, $desc, $avail);
        jsonResponse('success', 'Product created successfully', ['product_id' => $id], 201);
    }

    public function update(int $id): void {
        $data = getRequestData();
        if (empty($data['product_name']) || !isset($data['price'])) {
            jsonResponse('error', 'Product name and price are required', null, 400);
        }

        $catId = !empty($data['category_id']) ? (int)$data['category_id'] : null;
        $price = (float)$data['price'];
        $qty = (int)($data['quantity'] ?? 0);
        $desc = $data['description'] ?? null;
        $avail = $data['availability'] ?? 'Available';

        $this->model->update($id, $data['product_name'], $catId, $price, $qty, $desc, $avail);
        jsonResponse('success', 'Product updated successfully');
    }

    public function delete(int $id): void {
        $this->model->delete($id);
        jsonResponse('success', 'Product deleted successfully');
    }
}
