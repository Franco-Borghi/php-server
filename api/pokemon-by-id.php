<?php
require_once '../middlewares/cors.php';
require_once '../db.php';

header('Content-Type: application/json');

// Verificar si se pasó un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400);
  echo json_encode(['error' => 'ID inválido']);
  exit;
}

$id = (int) $_GET['id'];

try {
  $sql = "
    SELECT 
      p.id,
      p.name,
      p.sprite,
      json_agg(t.name ORDER BY t.name) AS types
    FROM pokemon p
    LEFT JOIN pokemon_types pt ON p.id = pt.pokemon_id
    LEFT JOIN types t ON pt.type_id = t.id
    WHERE p.id = :id
    GROUP BY p.id
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([':id' => $id]);
  $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$pokemon) {
    http_response_code(404);
    echo json_encode(['error' => 'Pokémon no encontrado']);
    exit;
  }

  echo json_encode($pokemon);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener el Pokémon']);
}
