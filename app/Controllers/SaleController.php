<?php

class SaleController {
    private $saleModel;
    private $productModel;

    public function __construct() {
        $this->saleModel    = new Sale();
        $this->productModel = new Product();
    }

    public function getAll() {
        return $this->saleModel->getAll();
    }

    public function getById($id) {
        return $this->saleModel->getById($id);
    }

    public function create() {
        $customerId    = !empty($_POST['customer_id']) ? (int)$_POST['customer_id'] : null;
        $productId     = (int)($_POST['product_id'] ?? 0);
        $quantity      = (int)($_POST['quantity'] ?? 0);
        $price         = (float)($_POST['price'] ?? 0);
        $totalAmount   = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : ($quantity * $price);
        $paymentStatus = $_POST['payment_status'] ?? 'Paid';

        if ($productId <= 0 || $quantity <= 0 || $price <= 0) {
            return [
                'success' => false,
                'message' => 'Product, quantity, and price are required.'
            ];
        }

        $product = $this->productModel->getById($productId);
        if ($product && $product['quantity'] < $quantity) {
            return [
                'success' => false,
                'message' => "Insufficient stock. Only {$product['quantity']} available."
            ];
        }

        $saleId = $this->saleModel->create($customerId, $productId, $quantity, $price, $totalAmount, $paymentStatus);
        $this->productModel->reduceStock($productId, $quantity);

        return [
            'success'      => true,
            'message'      => 'Sale recorded and stock updated successfully.',
            'sale_id'      => $saleId,
            'total_amount' => $totalAmount
        ];
    }

    public function delete($id) {
        $this->saleModel->delete($id);

        return [
            'success' => true,
            'message' => 'Sale record deleted successfully.'
        ];
    }
}
