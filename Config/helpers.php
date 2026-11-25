<?php
// Config/helpers.php

// Return uniform JSON response
function jsonResponse(string $status, string $message, mixed $data = null, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'  => $status,
        'message' => $message,
        'data'    => $data
    ]);
    exit;
}

// Get request data from JSON body or POST form data
function getRequestData(): array {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    return is_array($json) ? array_merge($_POST, $json) : $_POST;
}
