<?php

class OrderController {
    private $model;

    public function __construct() {
        $this->model = new Order();
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function create() {
        $customerId    = (int)($_POST['customer_id'] ?? 0);
        $totalAmount   = $_POST['total_amount'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'Cash';
        $paymentStatus = $_POST['payment_status'] ?? 'Pending';
        $orderStatus   = $_POST['order_status'] ?? 'Pending';

        if ($customerId <= 0 || $totalAmount === '') {
            return [
                'success' => false,
                'message' => 'Customer and total amount are required.'
            ];
        }

        $id = $this->model->create($customerId, (float)$totalAmount, $paymentMethod, $paymentStatus, $orderStatus);

        return [
            'success'  => true,
            'message'  => 'Order placed successfully.',
            'order_id' => $id
        ];
    }

    public function update($id) {
        $existing = $this->model->getById($id);
        if (!$existing) {
            return [
                'success' => false,
                'message' => 'Order not found.'
            ];
        }

        $totalAmount   = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : (float)$existing['total_amount'];
        $paymentMethod = $_POST['payment_method'] ?? $existing['payment_method'];
        $paymentStatus = $_POST['payment_status'] ?? $existing['payment_status'];
        $orderStatus   = $_POST['order_status'] ?? $existing['order_status'];

        $this->model->update($id, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus);

        return [
            'success' => true,
            'message' => 'Order updated successfully.'
        ];
    }

    public function delete($id) {
        $this->model->delete($id);

        return [
            'success' => true,
            'message' => 'Order deleted successfully.'
        ];
    }
}
