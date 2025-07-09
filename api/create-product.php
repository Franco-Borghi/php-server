<?php
require_once '../middlewares/cors.php';
require_once '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Método no permitido']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  http_response_code(400);
  echo json_encode(['error' => 'JSON inválido']);
  exit;
}

// Campos requeridos que debe enviar el frontend (sin id_showcase)
$requiredFields = [ 'Name_items_showcase', 'Status_items_showcase', 'Price_items_showcase'];
foreach ($requiredFields as $field) {
  if (!isset($input[$field])) {
    http_response_code(400);
    echo json_encode(['error' => "Falta el campo requerido: $field"]);
    exit;
  }
}

// Validación de status
$validStatus = ['available', 'sold', 'donado', 'rented'];
if (!in_array($input['Status_items_showcase'], $validStatus)) {
  http_response_code(400);
  echo json_encode(['error' => 'Estado inválido']);
  exit;
}

function isNumericOrNull($v) {
  return $v === null || is_numeric($v);
}

function isStringOrNull($v) {
  return $v === null || is_string($v);
}

function isBoolOrNull($v) {
  return $v === null || is_bool($v);
}

function isJsonOrNull($v) {
  if ($v === null) return true;
  if (is_array($v)) return true;  // Aceptar arrays
  if (is_string($v)) {
    json_decode($v);
    return json_last_error() === JSON_ERROR_NONE;
  }
  return false;
}

// Validaciones tipos básicos

if (!is_string($input['Name_items_showcase'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Name_items_showcase y slug deben ser strings']);
  exit;
}

// Validar opcionales solo si vienen
$valid = true;
$valid &= isNumericOrNull($input['Original_price_items_showcase'] ?? null);
$valid &= isNumericOrNull($input['Discount_items_showcase'] ?? null);
$valid &= isNumericOrNull($input['Min_price_offeritems_showcase'] ?? null);
$valid &= isNumericOrNull($input['Height_items_showcase'] ?? null);
$valid &= isNumericOrNull($input['Width_items_showcase'] ?? null);
$valid &= isNumericOrNull($input['Length_items_showcase'] ?? null);
$valid &= isStringOrNull($input['Delivery_items_showcase'] ?? null);
$valid &= isStringOrNull($input['Id_origin'] ?? null);
$valid &= isStringOrNull($input['user_type_id'] ?? null);
$valid &= isStringOrNull($input['fullname'] ?? null);
$valid &= isJsonOrNull($input['Images_items_showcase'] ?? null);
$valid &= isBoolOrNull($input['fav'] ?? null);
$valid &= isJsonOrNull($input['Image_crop_items_showcase'] ?? null);
$valid &= isJsonOrNull($input['Additional_Images_crop_items_showcase'] ?? null);

if (!$valid) {
  http_response_code(400);
  echo json_encode(['error' => 'Uno o más campos tienen tipos incorrectos']);
  exit;
}

try {
  // Generar nuevo id_showcase automáticamente
  $stmtLastId = $pdo->query("SELECT COALESCE(MAX(id_showcase), 0) as max_id FROM products");
  $lastId = (int) $stmtLastId->fetchColumn();
  $newIdShowcase = $lastId + 1;

  $stmt = $pdo->prepare("
    INSERT INTO products (
      id_items_showcase, id_showcase, name_items_showcase, slug, status_items_showcase,
      price_items_showcase, original_price_items_showcase, discount_items_showcase, min_price_offeritems_showcase,
      delivery_items_showcase, height_items_showcase, width_items_showcase, length_items_showcase,
      id_origin, user_type_id, fullname, images_items_showcase, image_crop, additional_images_crop, fav
    ) VALUES (
      :id_items_showcase, :id_showcase, :name_items_showcase, :slug, :status_items_showcase,
      :price_items_showcase, :original_price_items_showcase, :discount_items_showcase, :min_price_offeritems_showcase,
      :delivery_items_showcase, :height_items_showcase, :width_items_showcase, :length_items_showcase,
      :id_origin, :user_type_id, :fullname, :images_items_showcase, :image_crop, :additional_images_crop, :fav
    )
    ON CONFLICT (id_items_showcase) DO NOTHING
  ");

  $slug = '/productos/' . $newIdShowcase;

  $stmt->execute([
    ':id_items_showcase' => $newIdShowcase,
    ':id_showcase' => $newIdShowcase,
    ':name_items_showcase' => $input['Name_items_showcase'],
    ':slug' => $slug,
    ':status_items_showcase' => $input['Status_items_showcase'],
    ':price_items_showcase' => $input['Price_items_showcase'],
    ':original_price_items_showcase' => $input['Original_price_items_showcase'] ?? null,
    ':discount_items_showcase' => $input['Discount_items_showcase'] ?? null,
    ':min_price_offeritems_showcase' => $input['Min_price_offeritems_showcase'] ?? null,
    ':delivery_items_showcase' => $input['Delivery_items_showcase'] ?? null,
    ':height_items_showcase' => $input['Height_items_showcase'] ?? null,
    ':width_items_showcase' => $input['Width_items_showcase'] ?? null,
    ':length_items_showcase' => $input['Length_items_showcase'] ?? null,
    ':id_origin' => $input['Id_origin'] ?? null,
    ':user_type_id' => $input['user_type_id'] ?? null,
    ':fullname' => $input['fullname'] ?? null,
    ':images_items_showcase' => isset($input['Images_items_showcase']) ? json_encode($input['Images_items_showcase']) : null,
    ':image_crop' => isset($input['Image_crop_items_showcase']) ? $input['Image_crop_items_showcase'] : null,
    ':additional_images_crop' => isset($input['Additional_Images_crop_items_showcase']) ? $input['Additional_Images_crop_items_showcase'] : null,
    ':fav' => isset($input['fav']) ? (bool)$input['fav'] : null,
  ]);

  http_response_code(201);
  echo json_encode(['message' => 'Producto creado correctamente', 'id_showcase' => $newIdShowcase]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al insertar en base de datos', 'details' => $e->getMessage()]);
}
