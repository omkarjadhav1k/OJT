<?php
// app/Controllers/OrderController.php

class OrderController {
    private Order $model;

    public function __construct() {
        $this->model = new Order();
    }

    public function getAll(): void {
        $orders = $this->model->getAll();
        jsonResponse('success', 'Orders fetched successfully', $orders);
    }

    public function getById(int $id): void {
        $order = $this->model->getById($id);
        if (!$order) {
            jsonResponse('error', 'Order not found', null, 404);
        }
        jsonResponse('success', 'Order details', $order);
    }

    public function create(): void {
        $data = getRequestData();
        if (empty($data['customer_id']) || !isset($data['total_amount'])) {
            jsonResponse('error', 'Customer ID and total amount are required', null, 400);
        }

        $customerId    = (int)$data['customer_id'];
        $totalAmount   = (float)$data['total_amount'];
        $paymentMethod = $data['payment_method'] ?? 'Cash';
        $paymentStatus = $data['payment_status'] ?? 'Pending';
        $orderStatus   = $data['order_status'] ?? 'Pending';

        $id = $this->model->create($customerId, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus);
        jsonResponse('success', 'Order placed successfully', ['order_id' => $id], 201);
    }

    public function update(int $id): void {
        $data = getRequestData();
        $updated = $this->model->update($id, $data);
        if (!$updated) {
            jsonResponse('error', 'No fields provided to update', null, 400);
        }
        jsonResponse('success', 'Order updated successfully');
    }

    public function delete(int $id): void {
        $this->model->delete($id);
        jsonResponse('success', 'Order deleted successfully');
    }
}
