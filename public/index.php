<?php
require_once '../middlewares/cors.php';
require_once '../importer.php';

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$uri = rtrim($uri, '/');

// GET /api/products
if ($uri === '/api/products' && $method === 'GET') {
  require_once '../api/products.php';
  exit;
}

// GET /api/products/:id
if (preg_match('#^/api/products/(\d+)$#', $uri, $matches) && $method === 'GET') {
  $_GET['id'] = $matches[1];
  require_once '../api/product-by-id.php';
  exit;
}

// POST /api/products
if ($uri === '/api/products' && $method === 'POST') {
  require_once '../api/create-product.php';
  exit;
}

// Fallback
echo json_encode(['message' => 'API Productos funcionando'], JSON_UNESCAPED_UNICODE);
