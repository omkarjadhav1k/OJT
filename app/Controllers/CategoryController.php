<?php

class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new Category();
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function create() {
        $name   = trim($_POST['category_name'] ?? '');
        $desc   = trim($_POST['category_description'] ?? '');
        $status = $_POST['category_status'] ?? 'Active';

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Category name is required.'
            ];
        }

        $id = $this->model->create($name, $desc, $status);

        return [
            'success'     => true,
            'message'     => 'Category created successfully.',
            'category_id' => $id
        ];
    }

    public function update($id) {
        $name   = trim($_POST['category_name'] ?? '');
        $desc   = trim($_POST['category_description'] ?? '');
        $status = $_POST['category_status'] ?? 'Active';

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Category name is required.'
            ];
        }

        $this->model->update($id, $name, $desc, $status);

        return [
            'success' => true,
            'message' => 'Category updated successfully.'
        ];
    }

    public function delete($id) {
        $this->model->delete($id);

        return [
            'success' => true,
            'message' => 'Category deleted successfully.'
        ];
    }
}
