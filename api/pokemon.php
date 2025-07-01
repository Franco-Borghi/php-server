<?php
require_once '../middlewares/cors.php';
require_once '../db.php';

header('Content-Type: application/json');

try {
  // Consulta para traer todos los Pokémon y sus tipos
  $sql = "
    SELECT 
      p.id,
      p.name,
      p.sprite,
      json_agg(t.name ORDER BY t.name) AS types
    FROM pokemon p
    LEFT JOIN pokemon_types pt ON p.id = pt.pokemon_id
    LEFT JOIN types t ON pt.type_id = t.id
    GROUP BY p.id
    ORDER BY p.id ASC
    LIMIT 150
  ";

  $stmt = $pdo->query($sql);
  $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($pokemons);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener los Pokémon'], JSON_UNESCAPED_UNICODE);
}
