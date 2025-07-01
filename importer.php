<?php
require_once 'db.php';

// 1. Crear tablas si no existen
$pdo->exec("
  CREATE TABLE IF NOT EXISTS types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
  );

  CREATE TABLE IF NOT EXISTS pokemon (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sprite TEXT
  );

  CREATE TABLE IF NOT EXISTS pokemon_types (
    pokemon_id INT REFERENCES pokemon(id),
    type_id INT REFERENCES types(id),
    PRIMARY KEY (pokemon_id, type_id)
  );
");

// 2. Verificar si hay Pokémon ya cargados
$stmt = $pdo->query("SELECT COUNT(*) FROM pokemon");
$count = $stmt->fetchColumn();

if ($count > 0) {
  // Ya hay Pokémon en la base. No se importará nada.
  return;
}

// 3. Si no hay datos, importar desde la PokéAPI
function fetchPokemon($id) {
  $url = "https://pokeapi.co/api/v2/pokemon/$id";
  $json = file_get_contents($url);
  return json_decode($json, true);
}

for ($i = 1; $i <= 150; $i++) {
  echo "✅ Importando Pokémon #$i...\n";

  $data = fetchPokemon($i);
  $name = $data['name'];
  $sprite = $data['sprites']['front_default'] ?? null;

  // Insertar el Pokémon
  $stmt = $pdo->prepare("INSERT INTO pokemon (id, name, sprite) VALUES (:id, :name, :sprite)");
  $stmt->execute([
    ':id' => $data['id'],
    ':name' => $name,
    ':sprite' => $sprite
  ]);

  // Insertar tipos y relaciones
  foreach ($data['types'] as $typeEntry) {
    $typeName = $typeEntry['type']['name'];

    // Insertar tipo si no existe
    $pdo->prepare("INSERT INTO types (name) VALUES (:name) ON CONFLICT (name) DO NOTHING")
        ->execute([':name' => $typeName]);

    // Obtener ID del tipo
    $stmt = $pdo->prepare("SELECT id FROM types WHERE name = :name");
    $stmt->execute([':name' => $typeName]);
    $typeId = $stmt->fetchColumn();

    // Insertar relación
    $pdo->prepare("INSERT INTO pokemon_types (pokemon_id, type_id) VALUES (:pid, :tid) ON CONFLICT DO NOTHING")
        ->execute([':pid' => $data['id'], ':tid' => $typeId]);
  }
}

echo "🎉 Importación finalizada.\n";
