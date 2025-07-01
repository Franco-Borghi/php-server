<?php
if (file_exists(__DIR__ . '/env.php')) {
  $config = require __DIR__ . '/env.php';
} else {
  $config = [
    'host' => getenv('DB_HOST'),
    'port' => getenv('DB_PORT'),
    'dbname' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
  ];
}

try {
  $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
  $pdo = new PDO($dsn, $config['user'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error de conexión a la base de datos']);
  exit;
}
