<?php
require_once '../middlewares/cors.php';
require_once '../importer.php';

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$uri = rtrim($uri, '/');

// Ruta: GET /api/pokemon
if ($uri === '/api/pokemon' && $method === 'GET') {
  require_once '../api/pokemon.php';
  exit;
}

// Ruta: GET /api/pokemon/{id}
if (preg_match('#^/api/pokemon/(.+)$#', $uri, $matches) && $method === 'GET') {
  $_GET['id'] = $matches[1]; // podría ser válido o no
  require_once '../api/pokemon-by-id.php';
  exit;
}

// Ruta por defecto
echo json_encode(['message' => 'Pokedex API running'], JSON_UNESCAPED_UNICODE);
