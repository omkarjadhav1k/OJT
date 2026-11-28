<?php

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function baseUrl($path = '') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $base = rtrim($scriptDir, '/');
    $path = ltrim($path, '/');

    if ($path !== '') {
        return $base . '/' . $path;
    }

    return $base !== '' ? $base : '/';
}

function loadView($viewName, $data = []) {
    extract($data);
    $viewFile = __DIR__ . '/../app/Views/' . $viewName . '.php';

    if (file_exists($viewFile)) {
        require $viewFile;
    } else {
        echo "View not found: " . clean($viewName);
    }
}

function redirect($path) {
    header("Location: " . baseUrl($path));
    exit;
}

function clean($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
