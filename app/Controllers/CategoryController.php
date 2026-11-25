<?php
// app/Controllers/CategoryController.php

class CategoryController {
    private Category $model;

    public function __construct() {
        $this->model = new Category();
    }

    public function getAll(): void {
        $categories = $this->model->getAll();
        jsonResponse('success', 'Categories fetched successfully', $categories);
    }

    public function getById(int $id): void {
        $category = $this->model->getById($id);
        if (!$category) {
            jsonResponse('error', 'Category not found', null, 404);
        }
        jsonResponse('success', 'Category details', $category);
    }

    public function create(): void {
        $data = getRequestData();
        if (empty($data['category_name'])) {
            jsonResponse('error', 'Category name is required', null, 400);
        }

        $desc = $data['category_description'] ?? null;
        $status = $data['category_status'] ?? 'Active';
        $id = $this->model->create($data['category_name'], $desc, $status);

        jsonResponse('success', 'Category created successfully', ['category_id' => $id], 201);
    }

    public function update(int $id): void {
        $data = getRequestData();
        if (empty($data['category_name'])) {
            jsonResponse('error', 'Category name is required', null, 400);
        }

        $desc = $data['category_description'] ?? null;
        $status = $data['category_status'] ?? 'Active';
        $this->model->update($id, $data['category_name'], $desc, $status);

        jsonResponse('success', 'Category updated successfully');
    }

    public function delete(int $id): void {
        $this->model->delete($id);
        jsonResponse('success', 'Category deleted successfully');
    }
}
