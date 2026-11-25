<?php
// app/Controllers/SaleController.php

class SaleController {
    private Sale $saleModel;
    private Product $productModel;

    public function __construct() {
        $this->saleModel = new Sale();
        $this->productModel = new Product();
    }

    public function getAll(): void {
        $sales = $this->saleModel->getAll();
        jsonResponse('success', 'Sales fetched successfully', $sales);
    }

    public function getById(int $id): void {
        $sale = $this->saleModel->getById($id);
        if (!$sale) {
            jsonResponse('error', 'Sale record not found', null, 404);
        }
        jsonResponse('success', 'Sale details', $sale);
    }

    public function create(): void {
        $data = getRequestData();
        if (empty($data['product_id']) || empty($data['quantity']) || !isset($data['price'])) {
            jsonResponse('error', 'Product ID, quantity, and price are required', null, 400);
        }

        $customerId    = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;
        $productId     = (int)$data['product_id'];
        $qty           = (int)$data['quantity'];
        $price         = (float)$data['price'];
        $totalAmount   = isset($data['total_amount']) ? (float)$data['total_amount'] : ($qty * $price);
        $paymentStatus = $data['payment_status'] ?? 'Paid';

        // Check stock
        $product = $this->productModel->getById($productId);
        if ($product && $product['quantity'] < $qty) {
            jsonResponse('error', "Insufficient stock! Only {$product['quantity']} available.", null, 400);
        }

        $saleId = $this->saleModel->create($customerId, $productId, $qty, $price, $totalAmount, $paymentStatus);
        $this->productModel->reduceStock($productId, $qty);

        jsonResponse('success', 'Sale recorded and stock updated successfully', [
            'sale_id'      => $saleId,
            'total_amount' => $totalAmount
        ], 201);
    }

    public function delete(int $id): void {
        $this->saleModel->delete($id);
        jsonResponse('success', 'Sale record deleted successfully');
    }
}
