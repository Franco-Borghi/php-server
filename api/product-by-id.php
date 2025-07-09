<?php
require_once '../middlewares/cors.php';
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400);
  echo json_encode(['error' => 'ID inválido'], JSON_UNESCAPED_UNICODE);
  exit;
}

$id = (int) $_GET['id'];

try {
  $sql = "SELECT * FROM products WHERE id_items_showcase = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':id' => $id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'Producto no encontrado'], JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo json_encode($product, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener el producto'], JSON_UNESCAPED_UNICODE);
}
